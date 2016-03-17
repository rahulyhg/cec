{% extends "../../Core/View/Layout/Default/admin-main.volt" %}

{% block title %}
    {{ 'page-title-index'|i18n }} | {{ config.global.title }}
{% endblock %}

{% block css %}
    <link href="{{ static_url('min/index.php?g=cssDefaultUserAdmin&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
{% endblock %}

{% block js %}
    <script type="text/javascript" src="{{ static_url('min/index.php?g=jsDefaultUserAdmin&rev=' ~ config.global.version.js) }}"></script>
{% endblock %}

{% block content %}
<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg bg-white">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->
    <!-- START PANEL -->
    <div class="panel panel-transparent">
        <div class="panel-heading">
            <div class="btn-group pull-right m-b-10">
                  <a href="{{ url('admin/user/create') }}" class="btn btn-complete"><i class="fa fa-plus"></i>&nbsp; {{ 'default.button-create'|i18n }}</a>
                </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            {{ content() }}
            <div class="table-responsive">
                <form method="post" action="">
                <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}" />
                <table class="table table-hover table-condensed" id="basicTable">
                    <thead>
                        <tr>
                            <th style="width:7%">
                                <div class="checkbox check-danger checkbox-circle">
                                  <input type="checkbox" value="checkall" id="checkall" class="check-all">
                                  <label for="checkall"></label>
                                </div>
                            </th>
                            <th style="width:10%">
                                <a href="{{ url.getBaseUri() }}admin/user?orderby=id&ordertype={% if formData['orderType']|lower == 'desc'%}asc{% else %}desc{% endif %}{% if formData['conditions']['keyword'] != '' %}&keyword={{ formData['conditions']['keyword'] }}{% endif %}">
                                    ID
                                </a>
                            </th>
                            <th>{{ 'th.name'|i18n }}</th>
                            <th>{{ 'th.email'|i18n }}</th>
                            <th style="width:15%">
                                <a href="{{ url.getBaseUri() }}admin/user?orderby=status&ordertype={% if formData['orderType']|lower == 'desc'%}asc{% else %}desc{% endif %}{% if formData['conditions']['keyword'] != '' %}&keyword={{ formData['conditions']['keyword'] }}{% endif %}">
                                    {{ 'th.status'|i18n }}
                                </a>
                            </th>
                            <th style="width:15%"></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="6">
                                <div class="bulk-actions">
                                    <select
                                        class="cs-select cs-skin-slide"
                                        data-init-plugin="cs-select"
                                        name="fbulkaction">
                                        <option value="">{{ 'default.select-action'|i18n }}</option>
                                        <option value="delete">{{ 'default.select-delete'|i18n }}</option>
                                    </select>
                                    <input type="submit" name="fsubmitbulk" class="btn btn-primary" value="{{ 'default.button-submit-bulk'|i18n }}" />
                                    </div>
                                <div class="clear"></div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                    {% for item in myUsers.items %}
                        <tr>
                            <td class="v-align-middle">
                                <input type="checkbox" name="fbulkid[]" value="{{ item.id }}" {% if formData['fbulkid'] is defined %}{% for key, value in formData['fbulkid'] if value == item.id %}checked="checked"{% endfor %}{% endif %} id="checkbox{{ item.id }}"/>
                            </td>
                            <td class="v-align-middle">{{ item.id }}</td>
                            <td class="v-align-middle">
                                <img src="{{ static_url(item.getThumbnailImage()) }}" class="img-rounded" alt="{{ item.getThumbnailImage() }}" width="50" height="50">
                                &nbsp; {{ item.name }} <br/>
                                <small class="user-role">{{ item.getRoleName() }}</small>
                            </td>
                            <td class="v-align-middle">{{ item.email }}</td>
                            <td class="v-align-middle"><span class="{{ item.getStatusStyle() }}">{{ item.getStatusName()|i18n }}</span></td>
                            <td class="v-align-middle">
                                <div class="btn-group btn-group-xs pull-right">
                                    <a href="{{ url('admin/user/edit/' ~ item.id) }}" class="btn btn-default"><i class="fa fa-pencil"></i>&nbsp; {{ 'td.edit'|i18n }}</a>
                                    <a href="javascript:deleteConfirm('{{ url('admin/user/delete/' ~ item.id) }}', '{{ item.id }}');" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                                </div>
                            </td>
                        </tr>
                    {% endfor %}
                    </tbody>
                </table>
                </form>
            </div>
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
