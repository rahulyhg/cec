<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>403 Forbidden</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta content="" name="description" />
        <meta content="" name="author" />
        <link rel="shortcut icon" type="image/x-icon" href="{{ static_url('favicon.ico') }}">
        <link rel="icon" type="image/x-icon" href="{{ static_url('favicon.ico') }}">
        <!-- BEGIN Pages CSS-->
        <link href="{{ static_url('plugins/boostrapv3/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ static_url('min/index.php?g=cssDefaultCoreAdmin&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
        {% block css %}{% endblock %}
        <!--[if lte IE 9]>
            <link href="{{ static_url('plugins/admin-fix/ie9.css') }}" rel="stylesheet" type="text/css" />
        <![endif]-->
        <script type="text/javascript" src="{{ static_url('plugins/jquery/jquery-1.11.1.min.js') }}"></script>
        <script type="text/javascript">
            window.onload = function() {
                // fix for windows 8
                if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
                    document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="{{ static_url('plugins/admin-fix/windows.chrome.fix.css')}}" />'
            }
        </script>
    </head>
    <body class="fixed-header error-page  ">
        <div class="container-xs-height full-height">
          <div class="row-xs-height">
            <div class="col-xs-height col-middle">
              <div class="error-container text-center">
                <h1 class="error-number">403</h1>
                <h2 class="semi-bold">Sorry but you couldn't access this page</h2>
              </div>
            </div>
          </div>
        </div>
    </body>
</html>
