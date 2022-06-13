<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{

    public function index()
    {
        $generalSettings = Setting::where('user_id', Auth::user()->id)->first();

        if (!$generalSettings) {
            return view('settings.generalSettings')
                ->with('generalSettings', $this->getGeneralSettings());
        } else {
            return view('settings.generalSettings', compact('generalSettings'));
        }
    }

    private function getGeneralSettings()
    {
        $generalSettings['enable_app'] = 0;
        $generalSettings['waiting_list_button_text'] = null;
        $generalSettings['waiting_list_button_text_color'] = "#FFFFFF";
        $generalSettings['waiting_list_button_bg_color'] = "#5563c1";
        $generalSettings['admin_email'] = null;

        return $generalSettings;
    }

    public function save(SettingRequest $request)
    {
        $setting = Setting::where('user_id', Auth::user()->id)->first();

        try {
            if ($setting) {
                $this->update($request, $setting->id);
                return response()->json('Settings Updated Successfully');
            } else {
                $this->store($request);
                return response()->json('Settings Saved Successfully');
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    private function store($data)
    {
        Setting::create([
            'user_id' => Auth::user()->id,
            'enable_app' => $data['enable_app'],
            'waiting_list_button_text' => $data['waiting_list_button_text'],
            'waiting_list_button_text_color' => $data['waiting_list_button_text_color'],
            'waiting_list_button_bg_color' => $data['waiting_list_button_bg_color'],
            'admin_email' => $data['admin_email'],
        ]);

        return;
    }

    private function update($data, $id)
    {
        Setting::where('id', $id)->update([
            'enable_app' => $data['enable_app'],
            'waiting_list_button_text' => $data['waiting_list_button_text'],
            'waiting_list_button_text_color' => $data['waiting_list_button_text_color'],
            'waiting_list_button_bg_color' => $data['waiting_list_button_bg_color'],
            'admin_email' => $data['admin_email'],
        ]);

        return;
    }
}
