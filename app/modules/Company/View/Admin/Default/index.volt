{% extends "../../Core/View/Layout/Default/admin-main.volt" %}

{% block title %}
    {{ 'page-title-index'|i18n }} | {{ config.global.title }}
{% endblock %}

{% block content %}
<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg bg-white">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->
    <!-- START PANEL -->
    <div class="panel panel-transparent">
        <div class="panel-heading">
            <div class="btn-group pull-right m-b-10">
                  <a href="{{ url('admin/company/create') }}" class="btn btn-complete"><i class="fa fa-plus"></i>&nbsp; {{ 'default.button-create'|i18n }}</a>
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
                            <th>{{ 'th.name'|i18n }}</th>
                            <th style="width:15%">
                                <a href="{{ url.getBaseUri() }}admin/company?orderby=status&ordertype={% if formData['orderType']|lower == 'desc'%}asc{% else %}desc{% endif %}{% if formData['conditions']['keyword'] != '' %}&keyword={{ formData['conditions']['keyword'] }}{% endif %}">
                                    {{ 'th.status'|i18n }}
                                </a>
                            </th>
                            <th style="width:15%"></th>
                        </tr>
                    </thead>
                    <tbody>
                    {% for item in myCompanys.items %}
                        <tr>


                            <td class="v-align-middle">
                                <strong>{{ item.name }}</strong> <br/>
                                <small class="company-role">{{ item.address }}</small> <br/>
                                <small class="company-role">{{ item.email }}</small> <br/>
                                <small class="company-role">{{ item.tel }}</small> <br/>
                                <small class="company-role">{{ item.fax }}</small>
                            </td>
                            <td class="v-align-middle"><span class="{{ item.getStatusStyle() }}">{{ item.getStatusName()|i18n }}</span></td>
                            <td class="v-align-middle">
                                <div class="btn-group btn-group-xs pull-right">
                                    <a href="{{ url('admin/company/edit/' ~ item.id) }}" class="btn btn-default"><i class="fa fa-pencil"></i>&nbsp; {{ 'td.edit'|i18n }}</a>
                                    <a href="javascript:deleteConfirm('{{ url('admin/company/delete/' ~ item.id) }}', '{{ item.id }}');" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
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
