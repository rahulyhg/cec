{% extends "../../Core/View/Layout/Default/admin-main.volt" %}

{% block title %}
    {{ 'page-title-index'|i18n }} | {{ config.global.title }}
{% endblock %}

{% block js %}
    <script type="text/javascript" src="{{ static_url('min/index.php?g=jsDefaultSlugAdmin&rev=' ~ config.global.version.js) }}"></script>
{% endblock %}

{% block content %}
<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg bg-white">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->
    <!-- START PANEL -->
    <div class="panel panel-transparent">
        <div class="panel-heading">

        </div>
        <div class="panel-body">
            {% if mySlugs|length > 0 %}
            <div class="table-responsive">
                <form method="post" action="">
                <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}" />
                <table class="table table-hover table-condensed" id="basicTable">
                    <thead>
                        <tr>
                            <th>{{ 'th.slug'|i18n }}</th>
                            <th style="width:20%">{{ 'th.model'|i18n }}</th>
                            <th style="width:12%">
                                <a href="{{ url.getBaseUri() }}admin/user?orderby=status&ordertype={% if formData['orderType']|lower == 'desc'%}asc{% else %}desc{% endif %}{% if formData['conditions']['keyword'] != '' %}&keyword={{ formData['conditions']['keyword'] }}{% endif %}">
                                    {{ 'th.status'|i18n }}
                                </a>
                            </th>
                            <th style="width:12%"></th>
                        </tr>
                    </thead>
                    <tbody>
                    {% for item in mySlugs.items %}
                        <tr>
                            <td class="v-align-middle">
                                {{ item.getObjectName() }} <br/>
                                <small><a href="#" class="edit-slug" data-type="text" data-pk="{{ item.id }}">{{ item.slug }}</a></small>
                            </td>
                            <td class="v-align-middle">{{ item.getModelName()|i18n }}</td>
                            <td class="v-align-middle"><span class="{{ item.getStatusStyle() }}">{{ item.getStatusName()|i18n }}</span></td>
                            <td class="v-align-middle">
                                <div class="btn-group btn-group-xs pull-right">
                                    <a href="{{ url('admin/slug/edit/' ~ item.id) }}" class="btn btn-default"><i class="fa fa-pencil"></i>&nbsp; {{ 'td.edit'|i18n }}</a>
                                </div>
                            </td>
                        </tr>
                    {% endfor %}
                    </tbody>
                </table>
                </form>
            </div>
            {% else %}
                No data found.
            {% endif %}
        </div>
        <div class="pull-right">
        {% if paginator.items is defined and paginator.total_pages > 1 %}
            {% include "../../Core/View/Layout/Default/admin-paginator.volt" %}
        {% endif %}
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
