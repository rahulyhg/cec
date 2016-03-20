<header>
    <div class="head">
        <a href="{{ url('') }}" class="logo"><img width="300" height="60" src="{{ static_url('assets/default/images/logo-cec.png') }}" alt="logo-cec" ></a>

        <a href="{{ url('lien-he') }}" class="contact">Liên hệ</a>
        <a href="{{ url('gioi-thieu') }}" class="aboutus">Giới thiệu CEC</a>

        <!--<a href="tel:0918496939" class="hotline">Công ty TNHH Xây Dựng & Môi Trường CEC</a>-->
    </div>
    <nav>
        <ul>
            <li><a href="{{ url("") }}">Trang chủ</a></li>
            {% for pcat in myPcategories %}
                <li>
                    <a href="{% if pcat.count > 0 %}{{ url(pcat.getSeo().slug) }}{% else %}javascript:;{% endif %}">{{ pcat.name }}</a>
                    {% if pcat.child|length > 0%}
                        <div class="submenu">
                        {% for pchild in pcat.child %}
                            <a href="{% if pchild.count > 0 %}{{ url(pchild.getSeo().slug) }}{% else %}javascript:;{% endif %}">{{ pchild.name }}</a>
                        {% endfor %}
                        </div>
                    {% endif %}
                </li>
            {% endfor %}
            {% for cat in myCategories %}
                <li>
                    <a href="{% if cat.count > 0 %}{{ url(cat.getSeo().slug) }}{% else %}javascript:;{% endif %}">{{ cat.name }}</a>
                    {% if cat.child|length > 0%}
                        <div class="submenu">
                        {% for child in cat.child %}
                            <a href="{% if child.count > 0 %}{{ url(child.getSeo().slug) }}{% else %}javascript:;{% endif %}">{{ child.name }}</a>
                        {% endfor %}
                        </div>
                    {% endif %}
                </li>
            {% endfor %}
        </ul>

        {#{% set level = 1 %}
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
        {% endfor %}#}
    </nav>
</header>
