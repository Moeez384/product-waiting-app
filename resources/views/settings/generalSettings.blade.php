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
                    <a class="link" target="_blank" style="padding-left: 1rem;"
                        href="https://support.extendons.com/">
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

    <section></section>

    {!! Form::open(['id' => 'form_settings']) !!}
    <section class="full-width">
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
                        <div class="columns four">
                            {{ Form::label('admin_email', 'Admin Email') }}
                        </div>
                        <div class="columns eight">
                            {{ Form::text('admin_email', $generalSettings['admin_email'], ['placeholder' => 'Admin Email']) }}
                            <label class="error_user_email"></label>
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
                        <div class="columns four">
                            {{ Form::label('waiting_list_button_text', 'Text') }}
                        </div>
                        <div class="columns eight">
                            {{ Form::text('waiting_list_button_text', $generalSettings['waiting_list_button_text'], ['placeholder' => 'Waiting List Button Text']) }}
                            <label class="error_waiting_list_button_text"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns four">
                            {{ Form::label('waiting_list_button_text_color', 'Text Colour') }}
                        </div>
                        <div class="columns eight">
                            {{ Form::text('waiting_list_button_text_color', $generalSettings['waiting_list_button_text_color'], ['data-jscolor' => '']) }}
                            <label class="error_waiting_list_button_text_color"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="columns four">
                            {{ Form::label('waiting_list_button_bg_color', 'Background Colour') }}
                        </div>
                        <div class="columns eight">
                            {{ Form::text('waiting_list_button_bg_color', $generalSettings['waiting_list_button_bg_color'], ['data-jscolor' => '']) }}
                            <label class="error_waiting_list_button_bg_color"></label>
                        </div>
                    </div>

                </div>
            </article>
        </div>
    </section>
    {!! Form::close() !!}
    <section class="full-width">
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
@endsection
