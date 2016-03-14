{% extends "../../Core/View/Layout/Default/site-main.volt" %}

{% block css %}
    <link href="{{ static_url('min/index.php?g=cssDefaultProductSite&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
{% endblock %}

{% block content %}
    <section>
        {% include '../../Core/View/Layout/Default/site-breadcrumb.volt' %}

    {% if myArticles is defined %}
        {% include '../../Article/View/Site/Default/Partials/showall.volt' %}
    {% endif %}

    {% if myProducts is defined %}
        {% include '../../Product/View/Site/Default/Partials/showall.volt' %}
    {% endif %}
    </section>
{% endblock %}
