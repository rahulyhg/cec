{% extends "../../Core/View/Layout/Default/admin-main.volt" %}

{% block title %}
    {{ 'page-title-create'|i18n }} | {{ config.global.title }}
{% endblock %}

{% block css %}
    <link href="{{ static_url('min/index.php?g=cssDefaultProductAdmin&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
{% endblock %}

{% block js %}
    <script type="text/javascript" src="{{ static_url('min/index.php?g=jsDefaultProductAdmin&rev=' ~ config.global.version.js) }}"></script>
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
                                {{ 'form.category'|i18n }}
                                <span class="required">*</span>
                            </label>
                            <div class="col-sm-9">
                                <select
                                    class="cs-select cs-skin-slide"
                                    data-init-plugin="cs-select"
                                    name="pcid">
                                    <option value="0">----</option>
                                    {% for n, cat in categories %}
                                        <option
                                            value="{{ cat.id }}"
                                            {% if formData['pcid'] is defined and formData['pcid'] == cat.id %}
                                            selected="selected"
                                            {% endif %}>
                                            {{ str_repeat('-', cat.level) }} {{ cat.name }}
                                        </option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
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
                                {{ 'form.seodescription'|i18n }}
                            </label>
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder=""
                                    name="seodescription"
                                    value="{% if formData['seodescription'] is defined %}{{ formData['seodescription'] }}{% endif %}"
                                     />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                {{ 'form.seokeyword'|i18n }}
                            </label>
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder=""
                                    name="seokeyword"
                                    value="{% if formData['seokeyword'] is defined %}{{ formData['seokeyword'] }}{% endif %}"
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
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{ 'form.cover'|i18n }}</label>
                            <div class="col-sm-9">
                                <div id="uploadCover" class="dropzone" style="min-height: 240px"></div>
                                <input
                                    type="hidden"
                                    name="image"
                                    value="{% if formData['image'] is defined %}{{ formData['image'] }}{% endif %}"
                                    id="uploadCoverInput"/>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-sm-3">
                            <span class="required">*</span>: Required field
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
