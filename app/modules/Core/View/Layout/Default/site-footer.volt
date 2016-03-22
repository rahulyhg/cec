                <footer>
    <div class="rowfoot">
        <ul class="footermenu">
            <li>
                <a href="{{ url('tu-van-thiet-ke') }}">Tư vấn thiết kế</a>
                <a href="{{ url('xu-ly-nuoc') }}">Xử lý nước</a>
                <a href="{{ url('san-pham-thiet-bi') }}">Sản phẩm thiết bị</a>
            </li>
            <li>
                <a href="{{ url('cong-trinh-tieu-bieu') }}">Công trình tiêu biểu</a>
                <a href="{{ url('cac-mau-nha-dep') }}">Các mẫu nhà đẹp</a>
                <a href="{{ url('gioi-thieu') }}">Giới thiệu CEC</a>
            </li>
        </ul>
        <div class="footcontact">
            {% if myCompany is defined %}
                {{ myCompany.name }} <br/>
                Địa chỉ: {{ myCompany.address }} <br/>
                Tel: {{ myCompany.tel }} <br/>
                Fax: {{ myCompany.fax }}  <br/>
                Email: {{ myCompany.email }}
            {% endif %}
        </div>

        <p>© 2016 CEC Co., Ltd</p>
    </div>
</footer>
