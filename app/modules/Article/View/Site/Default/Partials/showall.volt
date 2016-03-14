
<ul class="projects">
    {% if myArticles.total_items > 0 %}
        {% for item in myArticles.items %}
            <li>
                <a href="#">
                    <img width="380" height="253" src="{{ static_url(item.getMediumImage()) }}" alt="" >
                    <h3>{{ item.title }}</h3>
                </a>
                <p>{{ item.seodescription }}</p>
            </li>
        {% endfor %}
    {% else %}
        No data found
    {% endif %}
</ul>
{{ dump(myArticles) }}
<a href="javascript:void(0)" class="viewmore" id="article" data-page="{{ myArticles.next }}">Xem thêm còn <span class="rest">{{ myArticles.total_items - (myArticles.current * myArticles.limit) }}</span> công trình</a>
