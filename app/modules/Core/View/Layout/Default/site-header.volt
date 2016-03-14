<header>
    <div class="head">
        <a href="{{ url('/') }}" class="logo"><img width="300" height="60" src="{{ static_url('assets/default/images/logo-cec.png') }}" alt="logo-cec" ></a>

        <a href="lien-he.html" class="contact">Liên hệ</a>
        <a href="bai-viet.html" class="aboutus">Giới thiệu CEC</a>

        <!--<a href="tel:0918496939" class="hotline">Công ty TNHH Xây Dựng & Môi Trường CEC</a>-->
    </div>
    <nav>
        {#<ul>
            <li>
                <a href="index.html" class="actived">Trang chủ</a>
            </li>
            <li>
                <a href="product.html">Sản phẩm thiết bị</a>
                <div class="submenu">
                    <a href="#">Thiết bị ngành nước</a>
                    <a href="#">Thiết bị điện</a>
                    <a href="#">Thiết bị xây dựng</a>
                    <a href="#">Hóa chất xử lý nước</a>
                </div>
            </li>
            <li>
                <a href="bai-viet.html">Tư vấn thiết kế</a>
                <div class="submenu">
                    <a href="#">Cấp thoát nước & môi trường</a>
                    <a href="#">Xây dựng dân dụng & công nghiệp</a>
                </div>
            </li>
            <li>
                <a href="bai-viet.html">Xử lý nước</a>
                <div class="submenu">
                    <a href="#">Xử lý nước cấp</a>
                    <a href="#">Xử lý nước thải</a>
                </div>
            </li>
            <li>
                <a href="cong-trinh-tieu-bieu.html">Công trình tiêu biểu</a>
            </li>
            <li>
                <a href="mau-nha-dep.html">Các mẫu nhà đẹp</a>
            </li>
        </ul>#}

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
                <p>{{ cat.name }}</p>
            {% set level = cat.level %}
        {% endfor %}
        {% for i in level..0 if i > 1 %}
            </li>
            </ul>
        {% endfor %}
    </nav>
</header>
