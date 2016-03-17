{% extends "../../Core/View/Layout/Default/m.site-main.volt" %}

{% block css %}
    <link href="{{ static_url('min/index.php?g=cssDefaultHomeSiteMobile&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
{% endblock %}

{% block title %}
    Trang chủ
{% endblock %}

{% block content %}
<section>
    <div class="hometitle"><h3>Hoạt động tại CEC</h3></div>
    <ul class="whatwedo">
        {% if myArticleActivity|length > 0 %}
            {% for item in myArticleActivity %}
                <li>
                    <a href="{{ url(item.getSeo().slug) }}">
                        <img width="380" height="253" src="{{ static_url(item.getThumbnailImage()) }}" alt="" >
                        <h3>{{ item.title }}</h3>
                    </a>
                    <p>{{ item.seodescription }}</p>
                </li>
            {% endfor %}
        {% endif %}
    </ul>

    <div class="hometitle"><h3>Dự án tại CEC</h3></div>
    <ul class="projects">
        {% if myArticleProject|length > 0 %}
            {% for item in myArticleProject %}
                <li>
                    <a href="{{ url(item.getSeo().slug) }}">
                        <img width="380" height="253" src="{{ static_url(item.getThumbnailImage()) }}" alt="" >
                        <h3>{{ item.title }}</h3>
                    </a>
                    <p>{{ item.seodescription }}</p>
                </li>
            {% endfor %}
        {% endif %}
    </ul>
</section>
{% endblock %}
