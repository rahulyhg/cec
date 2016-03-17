<ul class="breadcrumb" xmlns:v="http://rdf.data-vocabulary.org/#">
    {% for b in bc %}
        {% if (b['active']) %}
            <li typeof="v:Breadcrumb"><h1><a href="javascript:;">{{ b['text'] }}</a></h1></li>
        {% else %}
            <li typeof="v:Breadcrumb">
                <p><a href="{{ b['link'] }}">{{ b['text'] }}</a> <span>›</span></p>
            </li>
        {% endif %}
    {% endfor %}
</ul>
