<div class="leftcontact">

    {% if myCompany is defined %}
        <h2>{{ myCompany.name }}</h2>
        <span>Địa chỉ: {{ myCompany.address }}</span>
        <span>Tel: <b>{{ myCompany.tel }}</b></span>
        <span>Fax: <b>{{ myCompany.fax }}</b> </span>
        <span>Email: {{ myCompany.email }}</span>
    {% endif %}

    <p>Mọi thông tin, Quý Khách có thể liên với chùng tôi theo thông tin trên hoặc Quý Khách hãy điền thông tin theo mẫu form bên dưới để chúng tôi hỗ trợ cho Quý Khách. </p>
    <span style="color: blue">{{ content() }}</span>
    <form action="" method="post">
        <input
            type="hidden"
            name="{{ security.getTokenKey() }}"
            value="{{ security.getToken() }}" />
        <input type="text" name="company" placeholder="Công ty của Quý Khách *" required>
        <input type="text" name="fullname" placeholder="Họ Tên Quý Khách *" required>
        <input type="text" name="address" placeholder="Địa chỉ *" required>
        <input type="text" name="phone" placeholder="Điện thoại *" required>
        <input type="text" name="email" placeholder="Email *" required>
        <textarea name="content" cols="" rows="" placeholder="Nội dung *" required></textarea>
        <button type="reset">Xóa</button>
        <button type="submit" name="fsubmit">Gửi</button>
    </form>

</div>
<div class="rightcontact" id="gmap_canvas"></div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: root_url + 'getmap',
            data: "address=" + `{{ myCompany.address }}`,
            dataType: 'json',
            cache: false,
            success: function(response) {
                var lat = response._result[0];
                var long = response._result[1];
                var formatted_address = response._result[2];

                var myOptions = {
                    zoom: 14,
                    center: new google.maps.LatLng(lat, long),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
                marker = new google.maps.Marker({
                    map: map,
                    position: new google.maps.LatLng(lat, long)
                });
                infowindow = new google.maps.InfoWindow({
                    content: formatted_address
                });
                google.maps.event.addListener(marker, "click", function () {
                    infowindow.open(map, marker);
                });
                infowindow.open(map, marker);
            }
        });
    });
</script>
