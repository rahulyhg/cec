{% extends "../../Core/View/Layout/Default/admin-main.volt" %}

{% block title %}
    {{ 'page-title-showcontact'|i18n }} | {{ config.global.title }}
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
                            <th>{{ 'th.company'|i18n }}</th>
                            <th>{{ 'th.fullname'|i18n }}</th>
                            <th>{{ 'th.content'|i18n }}</th>
                            <th style="width:15%"></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="5">
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
                    {% for item in myContacts.items %}
                        <tr>
                            <td class="v-align-middle">
                                <input type="checkbox" name="fbulkid[]" value="{{ item.id }}" {% if formData['fbulkid'] is defined %}{% for key, value in formData['fbulkid'] if value == item.id %}checked="checked"{% endfor %}{% endif %} id="checkbox{{ item.id }}"/>
                            </td>
                            <td class="v-align-middle">{{ item.company }}</td>
                            <td class="v-align-middle">
                                {{ item.fullname }} <br/>
                                <small><a href="mailto:{{ item.email }}" target="_top">{{ item.email }}</a></small> <br/>
                                <small>{{ item.phone }}</small> <br/>
                                <small>{{ item.address }}</small> <br/>
                            </td>
                            <td class="v-align-middle">{{ item.content }}</td>
                            <td class="v-align-middle">
                                <div class="btn-group btn-group-xs pull-right">
                                    <a href="javascript:deleteConfirm('{{ url('admin/user/deletecontact/' ~ item.id) }}', '{{ item.id }}');" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
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
