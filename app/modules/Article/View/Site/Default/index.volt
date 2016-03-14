{% extends "../../Core/View/Layout/Default/site-main.volt" %}

{% block css %}
    <link href="{{ static_url('min/index.php?g=cssDefaultHomeSite&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
{% endblock %}
{% block content %}
<section>
    <div class="hometitle"><h3>Hoạt động tại CEC</h3></div>
    <ul class="whatwedo">
        <li>
            <a href="#">
                <img width="380" height="253" src="images/stock-small-2.jpg" alt="" >
                <h3>Tư vấn thiết kế xây dựng môi trường, dân dụng, công nghiệp</h3>
            </a>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.</p>
        </li>
        <li>
            <a href="#">
                <img width="380" height="253" src="images/stock-small-1.jpg" alt="" >
                <h3>Tư vấn xử lý nước cấp, nước thải</h3>
            </a>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.</p>
        </li>
        <li>
            <a href="#">
                <img width="380" height="253" src="images/stock-small-3.jpg" alt="" >
                <h3>Kinh doanh vật tư thiết bị môi trường</h3>
            </a>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.</p>
        </li>
    </ul>

    <div class="hometitle"><h3>Dự án tại CEC</h3></div>
    <ul class="projects">
        <li>
            <a href="#">
                <img width="380" height="253" src="images/stock-3.jpg" alt="" >
                <h3>Thiết kế thi công Nhà Máy xử lý nước Rạch Giá, Kiên Giang</h3>
            </a>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.</p>
        </li>
        <li>
            <a href="#">
                <img width="380" height="253" src="images/stock-4.jpg" alt="" >
                <h3>Thiết kế thi công hệ thống xử lý nước Khu Du lịch Hồ Tràm</h3>
            </a>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.</p>
        </li>
        <li>
            <a href="#">
                <img width="380" height="253" src="images/stock-5.jpg" alt="" >
                <h3>Thiết kế thi công hệ thống Cấp thoát nước An Giang</h3>
            </a>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque.</p>
        </li>
    </ul>
</section>
{% endblock %}
