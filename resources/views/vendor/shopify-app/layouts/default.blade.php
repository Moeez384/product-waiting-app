<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('shopify-app.app_name') }}</title>

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/uptown.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/select2.min.css') }}">

    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">


    @yield('styles')

    @if (\Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_enabled'))
        <script
                src="https://unpkg.com/@shopify/app-bridge{{ \Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_version') ? '@' . config('shopify-app.appbridge_version') : '' }}">
        </script>
        <script
                src="https://unpkg.com/@shopify/app-bridge-utils{{ \Osiset\ShopifyApp\Util::getShopifyConfig('appbridge_version') ? '@' . config('shopify-app.appbridge_version') : '' }}">
        </script>
        <script @if (\Osiset\ShopifyApp\Util::getShopifyConfig('turbo_enabled')) data-turbolinks-eval="false" @endif>
            var AppBridge = window['app-bridge'];
            var actions = AppBridge.actions;
            var utils = window['app-bridge-utils'];
            var createApp = AppBridge.default;
            window.app = createApp({
                apiKey: "{{ \Osiset\ShopifyApp\Util::getShopifyConfig('api_key', $shopDomain ?? Auth::user()->name) }}",
                shopOrigin: "{{ $shopDomain ?? Auth::user()->name }}",
                host: "{{ \Request::get('host') }}",
                forceRedirect: true,
            });
        </script>

        @include('shopify-app::partials.token_handler')
        @include('shopify-app::partials.flash_messages')
    @endif

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('js/loader.js') }}"></script>

    @if (Route::current()->getName() === 'general.settings' || Route::current()->getName() === 'home')
        <style>
            .fa {
                font-size: 1.2em !important;
            }

        </style>
        <script src="{{ asset('js/jscolor.js') }}" defer></script>
        <script src="{{ asset('js/generalsettings.js') }}" defer></script>
    @elseif (Route::currentRouteName() === 'rules.create')
        <script src="{{ asset('js/ruleadd.js') }}"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script src="{{ asset('js/select2.min.js') }}" defer></script>
    @elseif (Route::currentRouteName() === 'rules.edit')
        <script src="{{ asset('js/ruleEdit.js') }}"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script src="{{ asset('js/select2.min.js') }}" defer></script>
    @elseif (Route::current()->getName() === 'rules.index')
        <script src="{{ asset('js/ruleview.js') }}"></script>
    @elseif (Route::current()->getName() === 'customers.index')
        <script src="{{ asset('js/customerview.js') }}" defer></script>
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @endif

    @yield('scripts')
</head>

<body>
    <div class="app-wrapper">
        <div class="app-content">
            <main role="main">
                <div class="lds-ring app-loader">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                @yield('content')
                <footer>
                    <article class="help">
                        <span></span>
                        <p>For support email at <a target="_blank"
                                href="mailto:shopify@extendons.com">shopify@extendons.com</a></p>
                    </article>
                </footer>
            </main>
        </div>
    </div>
</body>

</html>
