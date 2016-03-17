<script type="text/javascript">
    var paginateUrl = `{{ paginateUrl }}`;
    var page = `{{ myProducts.current }}`;
    var recordPerPage = `{{ myProducts.limit }}`;
    var totalPage = `{{ myProducts.total_pages }}`;
    var totalItems = `{{ myProducts.total_items }}`;
</script>
<section>
    <ul class="product" id="product-list">
        {% if myProducts.total_items > 0 %}
            {% for item in myProducts.items %}
                <li>
                    <img width="220" height="200" src="{{ static_url(item.getThumbnailImage()) }}" alt="">
                    <h3>{{ item.name }}</h3>
                </li>
            {% endfor %}

        {% else %}
            No data found.
        {% endif %}
    </ul>
    {% if myProducts.current != myProducts.total_pages%}
    <a href="javascript:void(0)" class="viewmore" id="product" data-page="{{ myProducts.next }}">Xem thêm còn <span class="rest">{{ myProducts.total_items - (myProducts.current * myProducts.limit) }}</span> sản phẩm</a>
    {% endif %}
</section>
