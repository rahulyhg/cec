{% extends "../../Core/View/Layout/Default/admin-main.volt" %}

{% block title %}
    {{ 'page-title-edit'|i18n }} | {{ config.global.title }}
{% endblock %}

{% block content %}
<form
    class="form-horizontal"
    role="form"
    method="post"
    action="">
    <input
        type="hidden"
        name="{{ security.getTokenKey() }}"
        value="{{ security.getToken() }}" />
    <div class="container-fluid container-fixed-lg bg-white">
        <div class="panel panel-transparent">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-8">
                        {{ content() }}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                {{ 'form.name'|i18n }}
                                <span class="required">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder=""
                                    name="name"
                                    value="{% if formData['name'] is defined %}{{ formData['name'] }}{% endif %}"
                                     />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                {{ 'form.address'|i18n }}
                                <span class="required">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder=""
                                    name="address"
                                    value="{% if formData['address'] is defined %}{{ formData['address'] }}{% endif %}"
                                     />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                {{ 'form.email'|i18n }}
                            </label>
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder=""
                                    name="email"
                                    value="{% if formData['email'] is defined %}{{ formData['email'] }}{% endif %}"
                                     />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                {{ 'form.tel'|i18n }}
                            </label>
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder=""
                                    name="tel"
                                    value="{% if formData['tel'] is defined %}{{ formData['tel'] }}{% endif %}"
                                     />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                {{ 'form.fax'|i18n }}
                            </label>
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder=""
                                    name="fax"
                                    value="{% if formData['fax'] is defined %}{{ formData['fax'] }}{% endif %}"
                                     />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                {{ 'form.status'|i18n }}
                                <span class="required">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select
                                    class="cs-select cs-skin-slide"
                                    data-init-plugin="cs-select"
                                    name="status">
                                    {% for status in statusList %}
                                    <option
                                        value="{{ status['value'] }}"
                                        {% if formData['status'] is defined and formData['status'] == status['value'] %}
                                            selected="selected"
                                        {% endif %}>
                                        {{ status['name'] | i18n }}
                                    </option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid container-fixed-lg">
        <div class="panel panel-transparent">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-3">
                            <span class="required">*</span>: {{ 'default.required'|i18n }}
                            </div>
                            <div class="col-sm-9">
                                <button class="btn btn-success" type="submit" name="fsubmit">{{ 'form.button-submit'|i18n }}</button>
                                <button class="btn btn-default" type="reset"><i class="pg-close"></i> {{ 'form.button-clear'|i18n }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
{% endblock %}
