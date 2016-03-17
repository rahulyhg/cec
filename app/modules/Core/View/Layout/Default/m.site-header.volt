<header>
    <a href="{{ url("") }}" class="logo"><img width="300" height="60" src="{{ static_url('assets/default/images/logo-cec.png') }}" alt="logo-cec" ></a>
    <a class="menu"><span></span>Menu</a>
    <nav>
        <ul>
            <li><a href="{{ url("") }}">Trang chủ</a></li>
            {% for cat in myPcategories %}
                <li>
                    <a href="{{ url(cat.getSeo().slug) }}">{{ cat.name }}</a>
                    {% if cat.child|length > 0%}
                        <div class="submenu">
                        {% for child in cat.child %}
                            <a href="{{ url(child.getSeo().slug) }}">{{ child.name }}</a>
                        {% endfor %}
                        </div>
                    {% endif %}
                </li>
            {% endfor %}
            {% for cat in myCategories %}
                <li>
                    <a href="{{ url(cat.getSeo().slug) }}">{{ cat.name }}</a>
                    {% if cat.child|length > 0%}
                        <div class="submenu">
                        {% for child in cat.child %}
                            <a href="{{ url(child.getSeo().slug) }}">{{ child.name }}</a>
                        {% endfor %}
                        </div>
                    {% endif %}
                </li>
            {% endfor %}
        </ul>
    </nav>
</header>
