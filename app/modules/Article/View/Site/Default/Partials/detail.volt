<article>
    <h3>{{ myArticle.title }}</h3>
    {{ myArticle.content }}
</article>
<aside>
    Hoạt động tại:

    <img src="{{ static_url('assets/default/images/logo-cec.png') }}" alt="logo-cec">

    {% if myArticleActivity %}
        {% for activity in myArticleActivity %}
            <h3><a href="{{ url(activity.getSeo().slug) }}">{{ activity.title }}</a></h3>
        {% endfor %}
    {% endif %}

</aside>
