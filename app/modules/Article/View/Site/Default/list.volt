{% extends "../../Core/View/Layout/Default/site-main.volt" %}


{% if myArticles is defined %}
    {% include '../../Article/View/Site/Default/Partials/showall.volt' %}
{% endif %}

{% if myProducts is defined %}
    {% include '../../Product/View/Site/Default/Partials/showall.volt' %}
{% endif %}
