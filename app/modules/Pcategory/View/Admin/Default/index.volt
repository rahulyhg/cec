{% extends "../../Core/View/Layout/Default/admin-main.volt" %}

{% block title %}
    {{ 'page-title-index'|i18n }} | {{ config.global.title }}
{% endblock %}

{% block css %}
    <link href="{{ static_url('min/index.php?g=cssDefaultProductCategoryAdmin&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
{% endblock %}

{% block js %}
    <script type="text/javascript" src="{{ static_url('min/index.php?g=jsDefaultProductCategoryAdmin&rev=' ~ config.global.version.js) }}"></script>
{% endblock %}

{% block content %}
<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg bg-white">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->
    <!-- START PANEL -->
    <div class="panel panel-transparent">
        <div class="panel-heading">
            <div class="btn-group pull-right m-b-10">
                <a href="{{ url('admin/pcategory/create') }}" class="btn btn-complete"><i class="fa fa-plus"></i>&nbsp; {{ 'title-create'|i18n }}</a>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <div class="cf nestable-lists">
                <div class="dd" id="nestable">
                {% set level = 0 %}
                {% for n, cat in myCategories %}
                    {% if cat.level == level %}
                        </li>
                    {% elseif cat.level > level %}
                        <ol class="dd-list">
                    {% else %}
                        </li>
                        {% set x = level - cat.level %}
                        {% for i in x..0 if i > 0 %}
                            </ol>
                            </li>
                        {% endfor %}
                    {% endif %}
                    <li class="dd-item dd3-item" data-id="{{ cat.id }}">
                    <div class="dd-handle dd3-handle">
                        {{ 'panel-drag'|i18n }}
                    </div>
                    <span class="pull-right btn-nestable-group">
                        <span class="label label-{{ cat.getStatusStyle() }}">{{ cat.getStatusName()|i18n }}</span> &nbsp;
                        <div class="btn-group btn-group-xs pull-right">
                            <a href="{{ url('admin/pcategory/edit/' ~ cat.id) }}" class="btn btn-default"><i class="fa fa-pencil"></i>&nbsp; {{ 'panel-edit'|i18n }}</a>
                            <a href="javascript:deleteConfirm('{{ url('admin/pcategory/delete/' ~ cat.id) }}', '{{ cat.id }}');" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                        </div>
                    </span>
                    <div class="dd3-content">
                        <p>{{ cat.name }}</p>
                        <div class="clearfix"></div>
                    </div>
                    {% set level = cat.level %}
                {% endfor %}
                {% for i in level..0 if i > 0 %}
                    </li>
                    </ol>
                {% endfor %}
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!-- END PANEL -->
    <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg">
    <div class="row">
        <div class="col-md-6">&nbsp;</div>
    </div>
</div>
{% endblock %}
