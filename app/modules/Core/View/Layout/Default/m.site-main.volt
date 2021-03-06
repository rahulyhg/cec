<!doctype html>
<html>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="{{ static_url('plugins/detectie/html5shiv.js') }}"></script>
      <script src="{{ static_url('plugins/detectie/respond.min.js') }}"></script>
    <![endif]-->
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8" />
        <title>
            {% block title %}{% endblock %} - {{ config.global.title }}
        </title>
        <link rel="shortcut icon" type="image/x-icon" href="{{ static_url('favicon.ico') }}">
        <link rel="icon" type="image/x-icon" href="{{ static_url('favicon.ico') }}">
        <link href="{{ static_url('min/index.php?g=cssDefaultCoreSiteMobile&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
        {% block css %}{% endblock %}
        <script type="text/javascript" src="{{ static_url('plugins/jquery/jquery-1.11.1.min.js') }}"></script>
        <script type="text/javascript">
            var root_url = "{{ url.getBaseUri() }}";
            var static_url = "{{ url.getStaticBaseUri() }}";
        </script>
    </head>

    <body>
        {% block content %}{% endblock %}
        {% include '../../Core/View/Layout/Default/m.site-header.volt' %}
        {% include '../../Core/View/Layout/Default/m.site-footer.volt' %}
    </body>
    <script type="text/javascript" src="{{ static_url('min/index.php?g=jsDefaultCoreSite&rev=' ~ config.global.version.js) }}"></script>
</html>
