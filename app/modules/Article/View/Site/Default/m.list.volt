{% extends "../../Core/View/Layout/Default/m.site-main.volt" %}

{% block css %}
    <link href="{{ static_url('min/index.php?g=cssDefaultProductSiteMobile&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
{% endblock %}

{% block title %}
    {#Article list#}
    {% if myArticles is defined %}
        {{ myCategory.name }}
    {% endif %}

    {#Product list#}
    {% if myProducts is defined %}
        {{ myProductCategory.name }}
    {% endif %}

    {% if myArticle is defined %}
        {{ myArticle.title }}
    {% endif %}

    Liên hệ
{% endblock %}

{% block content %}
    <section>
        {% include '../../Core/View/Layout/Default/site-breadcrumb.volt' %}

        {#Article list#}
        {% if myArticles is defined %}
            {% include '../../Article/View/Site/Default/Partials/m.showall.volt' %}
        {% endif %}

        {#Product list#}
        {% if myProducts is defined %}
            {% include '../../Product/View/Site/Default/Partials/m.showall.volt' %}
        {% endif %}

        {#Article Detail#}
        {% if myArticle is defined %}
            {% include '../../Article/View/Site/Default/Partials/m.detail.volt' %}
        {% endif %}

        {% if slug == 'lien-he' %}
            {% include '../../Article/View/Site/Default/Partials/m.contact.volt' %}
        {% endif %}
    </section>
{% endblock %}
