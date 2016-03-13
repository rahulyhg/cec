<div class="jumbotron" data-pages="parallax">
    <div class="container-fluid container-fixed-lg">
        <div class="inner">
            {% if bc is defined %}
            <!-- START BREADCRUMB -->
            <ul class="breadcrumb">
                {% for b in bc %}
                    {% if (b['active']) %}
                        <li><a href="javascript:void(0)" class="active">{{ b['text'] }}</a></li>
                    {% else %}
                        <li>
                            <p><a href="{{ b['link'] }}">{{ b['text'] }}</a></p>
                        </li>
                    {% endif %}
                {% endfor %}
            </ul>
            <!-- END BREADCRUMB -->
            {% endif %}
            {#{% if jumpbotron is defined %}
            <div class="row">
                <div class="col-lg-7 col-md-6 ">
                    <!-- START PANEL -->
                    <div class="full-height">
                        <div class="panel-body text-center">
                             <img class="image-responsive-height demo-mw-600" src="{{ static_url('img/demo/nest.png') }}" alt=""> 
                        </div>
                    </div>
                    <!-- END PANEL -->
                </div>
                <div class="col-lg-5 col-md-6 ">
                    <!-- START PANEL -->
                    <div class="panel panel-transparent">
                        <div class="panel-body">
                            <h3 class="">
                            Nestables
                            </h3>
                            <p>This is powered by the JQuery nestable plugin, we have customized it to suite the design scheme and color pallete
                            </p>
                            <br>
                            <div class="col-sm-12 no-padding">
                                <a href="http://dbushell.github.io/Nestable/" target="_blank" class="btn btn-complete">See Plugin</a>
                                <p class="small hinted-text inline p-l-10 no-margin col-middle">
                                    http://dbushell.github.io/Nestable/
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- END PANEL -->
                </div>
            </div>
            {% endif %}#}
        </div>
    </div>
</div>
