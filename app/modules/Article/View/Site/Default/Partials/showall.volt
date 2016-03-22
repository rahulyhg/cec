<script type="text/javascript">
    var paginateUrl = `{{ paginateUrl }}`;
    var page = `{{ myArticles.current }}`;
    var recordPerPage = `{{ myArticles.limit }}`;
    var totalPage = `{{ myArticles.total_pages }}`;
    var totalItems = `{{ myArticles.total_items }}`;
</script>
<ul class="projects" id="article-list">
    {% if myArticles.total_items > 0 %}
        {% for item in myArticles.items %}
            {% if item.type == php('\Article\Model\Article::TYPE_GALLERY') %}
                <li>
                    <a href="javascript:;" class="load_ajax" onclick="load_ajax()">
                        <img width="380" height="253" src="{{ static_url(item.getMediumImage()) }}" alt="" >
                        <h3>{{ item.title }}</h3>
                    </a>
                    <p>{{ item.seodescription }}</p>
                </li>
            {% else %}
                <li>
                    <a href="{{ url(item.getSeo().slug) }}">
                        <img width="380" height="253" src="{{ static_url(item.getMediumImage()) }}" alt="" >
                        <h3>{{ item.title }}</h3>
                    </a>
                    <p>{{ item.seodescription }}</p>
                </li>
            {% endif %}
        {% endfor %}
    {% else %}
        No data found
    {% endif %}
</ul>

{% if myArticles.current != myArticles.total_pages%}
<a href="javascript:void(0)" class="viewmore" id="article" data-page="{{ myArticles.next }}">Xem thêm còn <span class="rest">{{ myArticles.total_items - (myArticles.current * myArticles.limit) }}</span> công trình</a>
{% endif %}

<script>
    $(document).ready(function() {
        $('.load_ajax').click(function(e) {
            $('body').prepend('<div id="ajxarticle"></div>');
        });
    });

    function load_ajax(){
        $.ajax({
            url : root_url + "getslide",
            type : "get",
            dataType:"html",
            success : function (response){
                $('#ajxarticle').html(response);
            },
            complete: function () {
                $("#galleria").owlCarousel({
                    navigation : true, // Show next and prev buttons
                    slideSpeed : 300,
                    paginationSpeed : 400,
                    singleItem:true,
                    autoHeight: true,
                });

                $('.overlay').click(function(e) {
                    $('#ajxarticle').remove();
                })
                $('.closed').click(function(e) {
                    $('#ajxarticle').remove();
                })
            }
        });
    }
</script>
