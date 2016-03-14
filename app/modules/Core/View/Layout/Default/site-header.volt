<header>
    <div class="head">
        <a href="{{ url('/') }}" class="logo"><img width="300" height="60" src="{{ static_url('assets/default/images/logo-cec.png') }}" alt="logo-cec" ></a>

        <a href="lien-he.html" class="contact">Liên hệ</a>
        <a href="bai-viet.html" class="aboutus">Giới thiệu CEC</a>

        <!--<a href="tel:0918496939" class="hotline">Công ty TNHH Xây Dựng & Môi Trường CEC</a>-->
    </div>
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
