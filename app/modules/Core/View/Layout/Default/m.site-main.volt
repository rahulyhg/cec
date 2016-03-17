<!doctype html>
<html>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="{{ static_url('plugins/detectie/html5shiv.js') }}"></script>
  <script src="{{ static_url('plugins/detectie/respond.min.js') }}"></script>
<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>
            {% block title %}{% endblock %} - {{ config.global.title }}
        </title>
        <link href="{{ static_url('min/index.php?g=cssDefaultCoreSiteMobile&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
        {% block css %}{% endblock %}
        <script type="text/javascript" src="{{ static_url('plugins/jquery/jquery-1.11.1.min.js') }}"></script>
    </head>

    <body>
        {% block content %}{% endblock %}
        {% include '../../Core/View/Layout/Default/m.site-header.volt' %}
        {% include '../../Core/View/Layout/Default/m.site-footer.volt' %}
    </body>
    <script type="text/javascript" src="{{ static_url('min/index.php?g=jsDefaultCoreSite&rev=' ~ config.global.version.js) }}"></script>
</html>