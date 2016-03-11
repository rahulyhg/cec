<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>{% block title %}{% endblock %}</title>
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
        <link href="{{ static_url('min/index.php?g=cssDefaultCoreSite&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
        {% block css %}{% endblock %}
        <!--[if lte IE 9]>
            <link href="{{ static_url('plugins/admin-fix/ie9.css') }}" rel="stylesheet" type="text/css" />
        <![endif]-->
        <script type="text/javascript" src="{{ static_url('plugins/jquery/jquery-1.11.1.min.js') }}"></script>
        {#<script type="text/javascript" src="{{ static_url('plugins/jquery-ui/jquery-ui.min.js') }}"></script>#}
        <script type="text/javascript">
            var root_url = "{{ url.getBaseUri() }}admin";
            window.onload = function() {
                // fix for windows 8
                if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
                    document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="{{ static_url('plugins/admin-fix/windows.chrome.fix.css')}}" />'
            }
        </script>
    </head>
    <body>
        <!-- START PAGE-CONTAINER -->
        <div class="page-container">
            {% include '../../Core/View/Layout/Default/site-header.volt' %}
            <!-- START PAGE CONTENT WRAPPER -->
            <div class="page-content-wrapper">
                <!-- START PAGE CONTENT -->
                <div class="content">
                {% block content %}{% endblock %}
                </div>
                <!-- END PAGE CONTENT -->
                {% include '../../Core/View/Layout/Default/site-footer.volt' %}
        </div>
        <!-- END PAGE CONTAINER -->
        {#{% include '../../Core/View/Layout/Default/admin-quickview.volt' %}#}
        {% include '../../Core/View/Layout/Default/site-search.volt' %}
        <!-- BEGIN PAGE LEVEL JS -->
        <script type="text/javascript" src="{{ static_url('plugins/boostrapv3/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ static_url('min/index.php?g=jsDefaultCoreSite&rev=' ~ config.global.version.js) }}"></script>
        {% block js %}{% endblock %}
    </body>
</html>

{% if config.global.profiler === true %}
{{ helper('profiler', 'core').render() }}
{% endif %}
