<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-T54QVZX');</script>
    <!-- End Google Tag Manager -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta id="token" name="token" value="{{csrf_token()}}">

    <link href="{{mix('css/app.css')}}" rel="stylesheet" type="text/css">



    <title>@yield('title')</title>
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T54QVZX"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <div id="vue-root" class="site__wrapper">
        @include('shared.header')

        <div class="site__content container">
            <div class="main">
                @yield('content')
            </div>
        </div>

        @include('shared.footer')
    </div>
    <script src="{{mix('js/app.js')}}"></script>
</body>
</html>
