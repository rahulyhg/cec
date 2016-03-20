{% extends "../../Core/View/Layout/Default/site-main.volt" %}

{% block css %}
    <link href="{{ static_url('min/index.php?g=cssDefaultProductSite&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
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

    {% if slug == 'lien-he' %}
        Liên hệ
    {% endif %}

    {% if slug == 'notfound' %}
        404 Trang không tìm thấy
    {% endif %}
{% endblock %}

{% block content %}
    <section>
        {% include '../../Core/View/Layout/Default/site-breadcrumb.volt' %}

        {#Article list#}
        {% if myArticles is defined %}
            {% include '../../Article/View/Site/Default/Partials/showall.volt' %}
        {% endif %}

        {#Product list#}
        {% if myProducts is defined %}
            {% include '../../Product/View/Site/Default/Partials/showall.volt' %}
        {% endif %}

        {#Article Detail#}
        {% if myArticle is defined %}
            {% include '../../Article/View/Site/Default/Partials/detail.volt' %}
        {% endif %}

        {% if slug == 'lien-he' %}
            {% include '../../Article/View/Site/Default/Partials/contact.volt' %}
        {% endif %}
    </section>
{% endblock %}
