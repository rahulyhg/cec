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
                    <a href="javascript:;">
                        <img width="380" height="253" src="{{ static_url(item.getMediumImage()) }}" alt="" >
                        <h3>{{ item.title }}</h3>
                    </a>
                    <p>{{ item.seodescription }}</p>
                    {% set myGalleries = item.getGalleries() %}
                    {% if myGalleries|length > 0 %}
                        <div class="my-gallery" itemscope itemtype="http://schema.org/ImageGallery">
                        {% for img in myGalleries %}
                            <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
                                <a href="{{ static_url(img.path) }}" itemprop="contentUrl" data-size="964x1024">
                                    <img src="{{ static_url(img.getThumbnailImage()) }}" itemprop="thumbnail" alt="{{ img.name }}" />
                                </a>
                            <figcaption itemprop="caption description">{{ img.name }}</figcaption>
                            </figure>
                        {% endfor %}
                        </div>
                    {% endif %}
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

<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe.
         It's a separate element, as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
        <!-- don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div>
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

          </div>

        </div>

</div>
