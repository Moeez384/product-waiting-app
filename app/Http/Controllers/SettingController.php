<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Message;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{

    public function index()
    {
        $generalSettings = Setting::where('user_id', Auth::user()->id)
            ->first();

        $messages = Message::where('user_id', Auth::user()->id)
            ->first();

        if (!$generalSettings && !$messages) {
            return view('settings.generalSettings')
                ->with('generalSettings', $this->getGeneralSettings())
                ->with('messages', $this->getMessages());
        } elseif ($generalSettings && !$messages) {
            return view('settings.generalSettings')
                ->with('generalSettings', $generalSettings)
                ->with('messages', $this->getMessages());
        } elseif (!$generalSettings && $messages) {
            return view('settings.generalSettings')
                ->with('generalSettings', $this->getGeneralSettings())
                ->with('messages', $messages);
        } else {
            return view('settings.generalSettings')
                ->with('generalSettings', $generalSettings)
                ->with('messages', $messages);
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

    private function getMessages()
    {
        $messages['success_message'] = '';
        $messages['email_already_exist_message'] = '';
        $messages['does_not_have_account_message'] = '';
        $messages['product_already_in_the_waiting_message'] = '';
        $messages['product_in_the_waiting_list_button_message'] = '';

        return $messages;
    }

    public function save(SettingRequest $request)
    {
        $request->validate([
            'waiting_list_button_text' => 'required',
            'waiting_list_button_text_color' => 'required',
            'waiting_list_button_bg_color' => 'required',
            'admin_email' => 'required|email',
        ]);

        $setting = Setting::where('user_id', Auth::user()->id)
            ->first();

        try {
            if ($setting) {
                $this->update($request, $setting->id);

                return response()
                    ->json('Settings Updated Successfully');
            } else {
                $this->store($request);

                return response()
                    ->json('Settings Saved Successfully');
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    private function store($generalSettings)
    {
        Setting::create([
            'user_id' => Auth::user()->id,
            'enable_app' => $generalSettings['enable_app'],
            'waiting_list_button_text' => $generalSettings['waiting_list_button_text'],
            'waiting_list_button_text_color' => $generalSettings['waiting_list_button_text_color'],
            'waiting_list_button_bg_color' => $generalSettings['waiting_list_button_bg_color'],
            'admin_email' => $generalSettings['admin_email'],
        ]);

        return;
    }

    private function update($generalSettings, $setting_id)
    {
        Setting::where('id', $setting_id)->update([
            'enable_app' => $generalSettings['enable_app'],
            'waiting_list_button_text' => $generalSettings['waiting_list_button_text'],
            'waiting_list_button_text_color' => $generalSettings['waiting_list_button_text_color'],
            'waiting_list_button_bg_color' => $generalSettings['waiting_list_button_bg_color'],
            'admin_email' => $generalSettings['admin_email'],
        ]);

        return;
    }

    public function saveMessages(Request $request)
    {
        $messages = Message::where('user_id', Auth::user()->id)
            ->first();

        try {
            if ($messages) {
                $this->updateMessages($request, $messages->id);

                return response()
                    ->json('Messages Updated Successfully');
            } else {
                $this->storeMessages($request);

                return response()
                    ->json('Messages Saved Successfully');
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    private function storeMessages($messages)
    {
        Message::create([
            'user_id' => Auth::user()->id,
            'success_message' => $messages['success_message'],
            'email_already_exist_message' => $messages['email_already_exist_message'],
            'does_not_have_account_message' => $messages['does_not_have_account_message'],
            'product_already_in_the_waiting_message' => $messages['product_already_in_the_waiting_message'],
            'product_in_the_waiting_list_button_message' => $messages['product_in_the_waiting_list_button_message'],
        ]);

        return;
    }

    private function updateMessages($messages, $message_id)
    {
        Message::where('id', $message_id)->update([
            'success_message' => $messages['success_message'],
            'email_already_exist_message' => $messages['email_already_exist_message'],
            'does_not_have_account_message' => $messages['does_not_have_account_message'],
            'product_already_in_the_waiting_message' => $messages['product_already_in_the_waiting_message'],
            'product_in_the_waiting_list_button_message' => $messages['product_in_the_waiting_list_button_message'],
        ]);

        return;
    }
}
