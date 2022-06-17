<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Shopify\Rest\Admin2022_04\Customer as Cus;
use Shopify\Utils;
use App\Models\User;
use App\Models\CustomerCategory;
use App\Jobs\ProductWaitingListJob;
use App\Jobs\SendEmailJob;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Js;
use App\Jobs\ChangeStatusJob;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::where('user_id', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->with('categories')
                ->simplePaginate(5);

            return view('customers.pagination', compact('customers'))->render();
        } else {
            $customers = Customer::where('user_id', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->with('categories')
                ->simplePaginate(5);

            $categories = Category::all()
                ->unique('product_or_collection_id');

            return view('customers.index', compact('customers', 'categories'));
        }
    }


    public function changeStatus($id)
    {
        try {
            $customer = Customer::find($id);
            $statusId = $customer->status;

            $status = '';

            if ($statusId == 0) {
                $status = '1';
            } else {
                $status = '0';
            }

            Customer::where('id', $id)
                ->update([
                    'status' => $status,
                ]);

            dispatch(new ChangeStatusJob($customer->id));

            $result['message'] = "Customer Status Updated Successfully";
            $result['status'] = $status;

            return response()->json($result);
        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function search(Request $request)
    {
        if ($request->search && $request->productId) {

            $customers = Customer::where(function ($query) use ($request) {
                return $query->where('email', 'like', '%' . $request->search . '%')
                    ->where('user_id', Auth::user()->id)
                    ->orWhere('status', 'like', '%' . $request->search . '%');
            })->orderBy('id', 'DESC')
                ->whereHas('categories', function ($query) use ($request) {
                    return $query->where('product_or_collection_id', $request->productId);
                })->simplePaginate(5);

            return view('customers.pagination', compact('customers'))->render();
        } elseif ($request->search) {

            $customers = Customer::where('user_id', Auth::user()->id)
                ->Where('email', 'like', '%' . $request->search . '%')
                ->orWhere('status', 'like', '%' . $request->search . '%')
                ->orderBy('id', 'DESC')
                ->with('categories')
                ->simplePaginate(5);

            return view('customers.pagination', compact('customers'))->render();
        } elseif ($request->productId) {
            $customers = Customer::where('user_id', Auth::user()->id)
                ->orderBY('id', 'DESC')
                ->whereHas('categories', function ($query) use ($request) {
                    return $query->where('product_or_collection_id', $request->productId);
                })
                ->simplePaginate(5);

            return view('customers.pagination', compact('customers'))->render();
        } else {
            $customers = Customer::where('user_id', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->with('categories')
                ->simplePaginate(5);

            return view('customers.pagination', compact('customers'))->render();
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        $user = User::where('name', $request->domain_name)->first();

        $customer = Customer::where('email', $request->email)->first();
        if ($customer) {
            return response()->json('This email is already registered. Please login to add product to the waiting list!', 404);
        } else {
            $result = $user->api()->rest('POST', '/admin/customers.json', ['customer' => [
                'first_name' => "",
                'last_name' => "",
                'email' => $request->email,
                'phone' => $request->phoneNumber,
                'verified_email' => true,
                'send_email_welcome' => false,
                'password' => $request->password,
                'password_confirmation' => $request->password,
                'tags' => 'Login with from Product waiting list app',
            ]]);

            if ($result['status'] == 422) {
                return response()->json('This email is already registered. Please login to add product to the waiting list!', 444);
            } else {
                $customer = Customer::create([
                    'email' => $request->email,
                    'status' => '0',
                    'cid' => $result['body']['customer']->id,
                    'user_id' => $user->id,
                ]);
                $category = Category::where('handle', $request->handle)->first();
                CustomerCategory::create([
                    'customer_id' => $customer->id,
                    'category_id' => $category->id,
                ]);
            }
        }
        $details = [
            'product' => $category->title,
            'email' => $customer->email,
        ];

        dispatch(new ProductWaitingListJob($details));

        return response()->json("Product added to the waiting list");
    }

    public function login(Request $request)
    {
        $user = User::where('name', $request->domain_name)->first();
        $result = $user->api()->rest('GET', '/admin/api/2022-04/customers/' . $request->cid . '.json');

        if (empty($result)) {
            return response()->json('Does not have an account', 404);
        }

        $email = $result['body']['customer']->email;
        $customer = Customer::where('user_id', $user->id)
            ->where('email', $email)
            ->first();
        $category = Category::where('handle', $request->handle)->first();

        if (empty($customer)) {
            $customer = Customer::create([
                'email' => $email,
                'status' => '0',
                'cid' => $result['body']['customer']->id,
                'user_id' => $user->id
            ]);

            CustomerCategory::create([
                'customer_id' => $customer->id,
                'category_id' => $category->id,
            ]);

            $details = [
                'product' => $category->title,
                'email' => $customer->email,
            ];

            dispatch(new ProductWaitingListJob($details));

            return response()->json('Product added to the waiting list');
        } else {

            $customerCategory = CustomerCategory::where('customer_id', $customer->id)
                ->where('category_id', $category->id)
                ->first();

            if ($customerCategory) {
                return response()->json('You have already added the product in the waiting list', 404);
            } else {

                CustomerCategory::create([
                    'customer_id' => $customer->id,
                    'category_id' => $category->id,
                ]);

                $details = [
                    'product' => $category->title,
                    'email' => $customer->email,
                ];
                dispatch(new ProductWaitingListJob($details));
                return response()->json('Product added to the waiting list');
            }
        }
    }

    public function exportCsv()
    {
        dispatch(new SendEmailJob(Auth::user()->id));

        return response()->json('Customer Csv has been sent to your mail.');
    }
}
