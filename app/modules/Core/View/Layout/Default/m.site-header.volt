<header>
    <a href="{{ url("") }}" class="logo"><img width="300" height="60" src="{{ static_url('assets/default/images/logo-cec.png') }}" alt="logo-cec" ></a>
    <a class="menu"><span></span>Menu</a>
    <nav>
        {% set level = 1 %}
        {% for n, cat in myCategories %}
            {% if cat.level == level %}
                </li>
            {% elseif cat.level > level %}
                <ul class="dd-list">
            {% else %}
                </li>
                {% set x = level - cat.level %}
                {% for i in x..1 if i > 0 %}
                    </ul>
                    </li>
                {% endfor %}
            {% endif %}
            <li class="dd-item dd3-item">
                <a>{{ cat.name }}</a>
            {% set level = cat.level %}
        {% endfor %}
        {% for i in level..0 if i > 1 %}
            </li>
            </ul>
        {% endfor %}
    </nav>
</header>
