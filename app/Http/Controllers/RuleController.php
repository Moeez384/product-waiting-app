<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Rule;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Shopify\Rest\Admin2022_04\Customer;
use Shopify\Utils;
use App\Jobs\SendMailForRuleJob;
use App\Models\Customer as Cus;
use App\Models\CustomerCategory;

class RuleController extends Controller
{
    public function index()
    {
        $ruleSettings = Rule::where('user_id', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->with('categories')
            ->simplePaginate(5);

        $generalSettings = Setting::where('user_id', Auth::user()->id)->first();

        if (count($ruleSettings) > 0 && $generalSettings) {
            return view("rules.index", compact(['ruleSettings']));
        } else if ($generalSettings) {
            return redirect()->route('rules.create');
        } else {
            return redirect()->route('general.settings');
        }
    }

    public function create()
    {
        return view('rules.create')
            ->with('ruleSettings', $this->getRuleSettings());
    }

    private function getRuleSettings()
    {
        $ruleSettings;
        $ruleSettings['title'] = null;
        $ruleSettings['status'] = 0;
        $ruleSettings['no_of_customers'] = 0;
        $ruleSettings['start_date'] = Carbon::now()->format('m/d/Y');
        $ruleSettings['end_date'] = Carbon::now()->addDays(20)->format('m/d/Y');
        $ruleSettings['id'] = 0;

        return $ruleSettings;
    }

    public function getProducts(Request $request)
    {
        $searchTitle = $request->get('term');
        if ($searchTitle == '') {
            $searchTitle = 'a';
        }
        $fields = [
            'fields' => 'id,title',
            'limit' => 249
        ];

        $shop = Auth::user();
        $result = $shop->api()->rest('GET', '/admin/products.json', $fields);

        foreach ($result['body']['products'] as $product) {
            if (stripos($product['title'], $searchTitle) !== false) {
                $dataArray[] = (object)[
                    'text' => $product['title'],
                    'id' => $product['id']
                ];
            }
        }
        return response()->json($dataArray);
    }


    public function save(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'no_of_customers' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'products' => 'required',
        ]);

        if (carbon::parse($request->start_date) > Carbon::parse($request->end_date)) {
            return response()->json('End Date must be greater than Start Date', 406);
        }

        $rule = Rule::where('user_id', Auth::user()->id)
            ->where('id', $request->id)
            ->first();

        if (empty($rule)) {
            $check = $this->store($request);
            if ($check == 1) {
                return response()->json('Rule Saved Successfully');
            } else {
                return response()->json("The Item " . $check . " already Exits", 406);
            }
        } else {
            $check = $this->update($request, $rule->id);
            if ($check == 1) {
                return response()->json('Rule Updated Successfully');
            } else {
                return response()->json("The Item " . $check . " already Exits in another Rule", 406);
            }
        }
    }

    private function store($data)
    {
        $checkCategory = $this->checkCategoryForSave($data);

        if ($checkCategory) {
            return $checkCategory;
        } else {
            $rule = new Rule();
            $rule->user_id = Auth::user()->id;
            $rule->status = $data->status;
            $rule->title = $data->title;
            $rule->no_of_customers = $data->no_of_customers;
            $rule->start_date = carbon::parse($data->start_date);
            $rule->end_date = carbon::parse($data->end_date);

            $rule->save();

            $this->saveCategory($data, $rule->id);

            return 1;
        }
    }

    private function checkCategoryForSave($data)
    {
        $product = '';
        for ($i = 0; $i < count($data['products']); $i++) {
            $category = Category::where('product_or_collection_id', $data['products'][$i])->first();
            if ($category) {
                $product = $category->title;
                break;
            }
        }
        if ($product) {
            return $product;
        } else {
            return false;
        }
    }

    private function checkCategoryForUpdate($data)
    {
        $product = '';
        for ($i = 0; $i < count($data['products']); $i++) {

            $category = Category::where('product_or_collection_id', $data['products'][$i])
                ->whereHas('rule', function ($query) use ($data) {
                    return $query->where('id', '!=', $data['id']);
                })->first();

            if ($category) {
                $product = $category->title;
                break;
            }
        }
        if ($product) {
            return $product;
        } else {
            return false;
        }
    }

    private function update($data, $id)
    {
        $checkCategory = $this->checkCategoryForUpdate($data);
        if ($checkCategory) {
            return $checkCategory;
        } else {
            try {
                $rule = Rule::where('id', $data['id'])
                    ->where('user_id', Auth::user()->id)
                    ->update([
                        'status' => $data['status'],
                        'title' => $data['title'],
                        'no_of_customers' => $data['no_of_customers'],
                        'start_date' => Carbon::parse($data['start_date']),
                        'end_date' => Carbon::parse($data['end_date']),
                    ]);

                Category::where('rule_id', $id)->delete();

                $this->saveCategory($data, $id);

                return 1;
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage(),
                ], 404);
            }
        }
    }

    private function saveCategory($data, $id)
    {
        for ($i = 0; $i < count($data['products']); $i++) {

            $shop = Auth::user();
            $result = $shop->api()->rest('GET', '/admin/products/' . $data['products'][$i] . '.json');

            $category = new Category();
            $category->rule_id = $id;
            $category->product_or_collection_id = $data['products'][$i];
            $category->title = $result['body']['product']->title;
            $category->handle = $result['body']['product']->handle;
            $category->save();
        }

        return;
    }

    public function destroy(Request $request)
    {
        try {
            Rule::where('id', $request->id)->delete();

            $ruleSettings = Rule::where('user_id', Auth::user()->id)
                ->orderBy('id', 'desc')
                ->with('categories')
                ->simplePaginate(5);

            return view('rules.pagination_data', compact('ruleSettings'))->render();
        } catch (\Exception $e) {
            return response()->json("Something Went Wrong!", 406);
        }
    }

    public function ruleSearch(Request $request)
    {
        $search = $request->search;

        $ruleSettings = Rule::where('user_id', Auth::user()->id)
            ->where(
                function ($query) use ($search) {
                    return $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('status', 'like', '%' . $search . '%')
                        ->orWhere('no_of_customers', 'like', '%' . $search . '%')
                        ->orWhere('start_date', 'like', '%' . $search . '%')
                        ->orWhere('end_date', 'like', '%' . $search . '%')
                        ->orWhere(
                            function ($query) use ($search) {
                                $query->whereHas(
                                    'categories',
                                    function ($query) use ($search) {
                                        return $query->where('title', 'like', '%' . $search . '%');
                                    }
                                );
                            }
                        );
                }
            )
            ->orderBy('id', 'desc')
            ->simplePaginate(5);

        return view('rules.pagination_data', compact('ruleSettings'))->render();
    }

    public function pagination(Request $request)
    {
        $ruleSettings = Rule::where('user_id', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->with('categories')
            ->simplePaginate(5);

        return view('rules.pagination_data', compact('ruleSettings'))->render();
    }

    public function edit(Rule $rule)
    {
        return view('rules.create')
            ->with('ruleSettings', $rule);
    }


    public function checkProductHandle(Request $request)
    {
        $user = User::where('name', $request->domain_name)->first();

        if ($request->cid) {
            $customer = Cus::where('cid', $request->cid)->first();
            $product = Category::where('handle', $request->handle)->first();
            if ($customer) {
                $customerCategory = CustomerCategory::where('customer_id', $customer->id)
                    ->where('category_id', $product->id)
                    ->first();

                if ($customerCategory) {
                    $result['tocken'] = 2;
                    $result['setting']
                        = Setting::where('user_id', $user->id)->first();
                    $result['customer'] = $customer;
                    return response()->json($result);
                }
            } else {
                return $this->checkForProductHandle($request, $user->id);
            }
        } else {
            return $this->checkForProductHandle($request, $user->id);
        }
    }

    private function checkForProductHandle($request, $id)
    {
        $customerCount = Cus::where('user_id', $id)
            ->whereHas('categories', function ($query) use ($request) {
                return $query->where('handle', $request->handle);
            })
            ->count();

        $rules = Rule::where('status', '1')
            ->where('user_id', $id)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->where('no_of_customers', '>', $customerCount)
            ->get();

        if ($rules->count() > 0) {

            foreach ($rules as $rule) {
                $product = Category::where('rule_id', $rule->id)
                    ->where('handle', $request->handle)
                    ->first();

                if ($product) {
                    $result['token'] = 1;
                    $result['product'] = $product;
                    $result['setting'] =
                        Setting::where('user_id', $id)->first();
                    return response()->json($result);
                }
            }
        } else if ($rules->count() == 0) {
            $rules = Rule::where('user_id', $id)->get();

            foreach ($rules as $rule) {
                $product = Category::where('rule_id', $rule->id)
                    ->where('handle', $request->handle)
                    ->first();

                if ($product) {
                    $result['token'] = 0;
                    $result['product'] = $product;
                    $result['setting'] =
                        Setting::where('user_id', $id)->first();
                    return response()->json($result);
                }
            }
        }
    }

    public function exportCsv()
    {
        dispatch(new SendMailForRuleJob(Auth::user()->id));

        return response()->json('Rules Csv has been sent to your mail.');
    }
}
