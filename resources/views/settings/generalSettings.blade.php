@extends('shopify-app::layouts.default')

@section('content')
    <section></section>

    <section class="full-width">
        <article>
            <div class="columns six">
                <div class="align-left">
                    <a class="button secondary user-guide" href="#">
                        <i class="icon-question append-spacing"></i>
                        <span>User Guide</span>
                    </a>
                    <a class="link" target="_blank" style="padding-left: 1rem;" href="https://support.extendons.com/">
                        <span>Get Support</span>
                    </a>
                </div>
            </div>
            <div class="columns six">
                <div class="align-right">
                    <a class="button home" href="{{ route('customers.index') }}">
                        <span>Customers</span>
                    </a>
                    <a class="button home" href="{{ route('rules.index') }}">
                        <span>Rules</span>
                    </a>
                </div>
            </div>
        </article>
    </section>

    <section class="full-width error-message default-hidden">
        <article>
            <div class="columns twelve">
                <div class="alert error">
                    <dl>
                        <dt>Error Alert</dt>
                        <dd class="exception-error">All fields is Required</dd>
                    </dl>
                </div>
            </div>
        </article>
    </section>

    <section class="full-width success-message-1 default-hidden">
        <article>
            <div class="columns twelve">
                <div class="column twelve success-message"></div>
            </div>
        </article>
    </section>

    <section class="full-width">
        <article>
            <div class="column twelve">
                <div class="card has-sections" style="padding:0px;">
                    <ul class="tabs">
                        <li class="active" id="generalSettingsLi">
                            <a href="#" id="generalSettings"><i class="icon-edit append-spacing"></i>General
                                Settings</a>
                        </li>
                        <li id="messagesLi">
                            <a href="#" id="messages"><i class="icon-pages append-spacing"></i>Messages</a>
                        </li>
                    </ul>

                    <div id="generalSettingDiv">
                        {!! Form::open(['id' => 'form_settings']) !!}
                        <section class="full-width" style="padding-top:35px; ">
                            <div class="row row-flex column twelve">
                                <aside class="ml-2">
                                    <h2>General</h2>
                                    <p>App Enable/Disable</p>
                                    <p>App functioanlity will only work if it is checked</p>
                                </aside>
                                <article>
                                    <div class="card">
                                        <div class="row">
                                            <div class="columns four">
                                                {{ Form::label('enable_app', 'Enable App') }}
                                            </div>
                                            <div class="columns eight">
                                                @if ($generalSettings['enable_app'])
                                                    {{ Form::checkbox('enable_app_cb', '1', true, ['id' => 'enable_app_cb']) }}
                                                    {{ Form::hidden('enable_app', 1, ['id' => 'enable_app']) }}
                                                @else
                                                    {{ Form::checkbox('enable_app_cb', '0', false, ['id' => 'enable_app_cb']) }}
                                                    {{ Form::hidden('enable_app', 0, ['id' => 'enable_app']) }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </section>



                        <section class="full-width">
                            <div class="row row-flex column twelve">
                                <aside class="ml-2">
                                    <h2>Admin Email</h2>
                                    <p>Admin email for notification to the customers
                                    </p>
                                </aside>
                                <article>
                                    <div class="card">
                                        <div class="row">
                                            <div class="columns twelve">
                                                {{ Form::label('admin', 'Admin Email', [
                                                    'style' => 'font-size: medium;                               padding-bottom: 7px;',
                                                ]) }}

                                                {{ Form::text('admin_email', $generalSettings['admin_email'], ['placeholder' => 'Admin Email']) }}
                                                <label class="error_admin_email generalSettingErrors"
                                                    style="display: none;color:red;"></label>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </section>



                        <section class="full-width">
                            <div class="row row-flex column twelve">
                                <aside class="ml-2">
                                    <h2>Waiting List Button Settings</h2>
                                    <p>This button will displayed at the Product Page.</p>
                                </aside>
                                <article>
                                    <div class="card">
                                        <div class="row">
                                            <div class="columns twelve">
                                                {{ Form::label('waiting_list_button', 'Text', ['style' => 'font-size: medium;padding-bottom: 7px;']) }}

                                                {{ Form::text('waiting_list_button_text', $generalSettings['waiting_list_button_text'], ['placeholder' => 'Waiting List Button Text']) }}
                                                <label class="error_waiting_list_button_text generalSettingErrors"
                                                    style="display: none;color:red;"></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="columns twelve">
                                                {{ Form::label('waiting_list_button_text_color', 'Text Colour', ['style' => 'font-size: medium;padding-bottom: 7px;']) }}
                                                {{ Form::text('waiting_list_button_text_color', $generalSettings['waiting_list_button_text_color'], ['data-jscolor' => '']) }}
                                                <label class="error_waiting_list_button_text_color  generalSettingErrors"
                                                    style="display: none;color:red;"></label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="columns twelve">
                                                {{ Form::label('waiting_list_button_bg_color', 'Background Colour', ['style' => 'font-size: medium;                               padding-bottom: 7px;']) }}
                                                {{ Form::text('waiting_list_button_bg_color', $generalSettings['waiting_list_button_bg_color'], ['data-jscolor' => '']) }}
                                                <label class="error_waiting_list_button_bg_color  generalSettingErrors"
                                                    style="display: none;color:red;"></label>
                                            </div>
                                        </div>

                                    </div>
                                </article>
                            </div>
                        </section>
                        {!! Form::close() !!}
                        <section class="full-width" style="padding-bottom:25px; ">
                            <article>
                                <div class="columns twelve">
                                    <div class="align-right">
                                        <a class="button btn-save">
                                            <span>Save</span>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </section>
                    </div>



                    <div id="messageDiv" style="display: none;">
                        {!! Form::open(['id' => 'form_messages']) !!}
                        <section class="full-width" style="padding-top:35px; ">
                            <article>
                                <div class="column twelve">
                                    <div class="row">
                                        <div class="column twelve">
                                            {{ Form::label('Success Message', 'Success Message', [
                                                'style' => 'font-size: medium;                               padding-bottom: 7px;',
                                            ]) }}

                                            {{ Form::text('success_message', $messages['success_message'], ['placeholder' => 'Enter the Success Message']) }}
                                            <label class="error_success_message"></label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="column twelve">
                                            {{ Form::label('Email ALready Exist Message', 'Email ALready Exist Message', [
                                                'style' => 'font-size: medium;                               padding-bottom: 7px;',
                                            ]) }}

                                            {{ Form::text('email_already_exist_message', $messages['email_already_exist_message'], ['placeholder' => 'Enter Email ALready Exist Message']) }}
                                            <label class="error_email_already_exist"></label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="column twelve">
                                            {{ Form::label('Does not have account Message', 'Does Not Have Account Message', [
                                                'style' => 'font-size: medium;                               padding-bottom: 7px;',
                                            ]) }}

                                            {{ Form::text('does_not_have_account_message', $messages['does_not_have_account_message'], ['placeholder' => 'Enter does not have account Message']) }}
                                            <label class="error_success_message"></label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="column twelve">
                                            {{ Form::label('Product already in the waiting list Message', 'Product already in the waiting list Message', [
                                                'style' => 'font-size: medium;                               padding-bottom: 7px;',
                                            ]) }}

                                            {{ Form::text('product_already_in_the_waiting_message', $messages['product_already_in_the_waiting_message'], ['placeholder' => 'Enter Product already in the waiting list Message']) }}
                                            <label class="error_success_message"></label>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="column twelve">
                                            {{ Form::label('Product in the waiting list button message', 'Product in the waiting list button message', [
                                                'style' => 'font-size: medium;                               padding-bottom: 7px;',
                                            ]) }}

                                            {{ Form::text('product_in_the_waiting_list_button_message', $messages['product_in_the_waiting_list_button_message'], ['placeholder' => 'Enter Product In The Waiting list Button Message']) }}
                                            <label class="error_success_message"></label>
                                        </div>
                                    </div>

                                </div>
                            </article>
                        </section>
                        {!! Form::close() !!}
                        <section class="full-width" style="margin-top:25px; margin-bottom:15px; ">
                            <article>
                                <div class="columns twelve">
                                    <div class="align-right">
                                        <a class="button message-btn-save">
                                            <span>Save</span>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </section>
                    </div>
                </div>
            </div>
        </article>
    </section>
@endsection
