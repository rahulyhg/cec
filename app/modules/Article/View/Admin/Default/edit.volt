{% extends "../../Core/View/Layout/Default/admin-main.volt" %}

{% block title %}
    {{ 'page-title-create'|i18n }} | {{ config.global.title }}
{% endblock %}

{% block css %}
    <link href="{{ static_url('min/index.php?g=cssDefaultArticleAdmin&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
{% endblock %}

{% block js %}
    <script type="text/javascript" src="{{ static_url('min/index.php?g=jsDefaultArticleAdmin&rev=' ~ config.global.version.js) }}"></script>
{% endblock %}

{% block content %}
<form
    id="edit-article"
    class="form-horizontal"
    role="form"
    method="post"
    action=""
    enctype="multipart/form-data">
    <input
        type="hidden"
        name="{{ security.getTokenKey() }}"
        value="{{ security.getToken() }}" />
    <div class="container-fluid container-fixed-lg bg-white">
        <div class="panel panel-transparent">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        {{ content() }}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                {{ 'form.category'|i18n }}
                                <span class="required">*</span>
                            </label>
                            <div class="col-sm-10">
                                <select
                                    class="cs-select cs-skin-slide"
                                    data-init-plugin="cs-select"
                                    name="cid">
                                    <option value="1">----</option>
                                    {% for n, cat in categories %}
                                        <option
                                            value="{{ cat.id }}"
                                            {% if formData['cid'] is defined and formData['cid'] == cat.id %}
                                            selected="selected"
                                            {% endif %}>
                                            {{ str_repeat('-', cat.level) }} {{ cat.name }}
                                        </option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                {{ 'form.title'|i18n }}
                                <span class="required">*</span>
                            </label>
                            <div class="col-sm-10">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder=""
                                    name="title"
                                    value="{% if formData['title'] is defined %}{{ formData['title'] }}{% endif %}"
                                     />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                {{ 'form.content'|i18n }}
                                <span class="required">*</span>
                            </label>
                            <div class="col-sm-10">
                                <div class="summernote-wrapper">
                                    <textarea
                                        class="form-control"
                                        placeholder=""
                                        name="content"
                                        id="summernote">{% if formData['content'] is defined %}{{ formData['content'] }}{% endif %}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                {{ 'form.status'|i18n }}
                                <span class="required">*</span>
                            </label>
                            <div class="col-sm-10">
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
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                {{ 'form.displaytohome'|i18n }}
                            </label>
                            <div class="col-sm-10">
                                <select
                                    class="cs-select cs-skin-slide"
                                    data-init-plugin="cs-select"
                                    name="displaytohome">
                                    {% for ishome in ishomeList %}
                                    <option
                                        value="{{ ishome['value'] }}"
                                        {% if formData['ishome'] is defined and formData['ishome'] == ishome['value'] %}
                                            selected="selected"
                                        {% endif %}>
                                        {{ ishome['name'] | i18n }}
                                    </option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                {{ 'form.type'|i18n }}
                                <span class="required">*</span>
                            </label>
                            <div class="col-sm-10">
                                <select
                                    class="cs-select cs-skin-slide"
                                    data-init-plugin="cs-select"
                                    name="type">
                                    {% for type in typeList %}
                                    <option
                                        value="{{ type['value'] }}"
                                        {% if formData['type'] is defined and formData['type'] == type['value'] %}
                                            selected="selected"
                                        {% endif %}>
                                        {{ type['name'] | i18n }}
                                    </option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                {{ 'form.seodescription'|i18n }}
                            </label>
                            <div class="col-sm-10">
                                <textarea
                                    class="form-control"
                                    placeholder=""
                                    name="seodescription">{% if formData['seodescription'] is defined %}{{ formData['seodescription'] }}{% endif %}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                {{ 'form.seokeyword'|i18n }}
                            </label>
                            <div class="col-sm-10">
                                <textarea
                                    class="form-control tagsinput custom-tag-input"
                                    placeholder=""
                                    name="seokeyword">{% if formData['seokeyword'] is defined %}{{ formData['seokeyword'] }}{% endif %}</textarea>
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
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">
                                {{ 'form.cover'|i18n }}
                                <img class="edit-image" src="{{ static_url(formData['thumbnailImage']) }}" alt="{{ formData['thumbnailImage'] }}" />
                            </label>
                            <div class="col-sm-10">
                                <div id="uploadCover" class="dropzone" style="min-height: 240px"></div>
                                <input
                                    type="hidden"
                                    name="image"
                                    value="{% if formData['image'] is defined %}{{ formData['image'] }}{% endif %}"
                                    id="uploadCoverInput"/>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{{ 'form.gallery'|i18n }}</label>
                            <div class="col-sm-10">
                                <div id="uploadImages" class="dropzone" style="min-height: 240px"></div>
                                <div class="multipleFiles"></div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-2">
                            <span class="required">*</span>: {{ 'default.required'|i18n }}
                            </div>
                            <div class="col-sm-10">
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
<script type="text/javascript">
    var imageList = `{{ formData['imageList'] }}`;
</script>
{% endblock %}
