{% extends "../../Core/View/Layout/Default/admin-main.volt" %}

{% block title %}
    {{ 'page-title-edit'|i18n }} | {{ config.global.title }}
{% endblock %}

{% block css %}
    <link href="{{ static_url('min/index.php?g=cssDefaultCategoryAdmin&rev=' ~ config.global.version.css) }}" rel="stylesheet" type="text/css">
{% endblock %}

{% block js %}
    <script type="text/javascript" src="{{ static_url('min/index.php?g=jsDefaultCategoryAdmin&rev=' ~ config.global.version.js) }}"></script>
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
                                    {{ status['name']|i18n }}
                                </option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                {{ content() }}
                <div class="col-sm-9">
                    <div class="panel">
                        <ul class="nav nav-tabs nav-tabs-right nav-tabs-simple">
                            {% for languageCode in formData['lang'] %}
                            <li class="{{ loop.first ? 'active' : '' }}">
                              <a data-toggle="tab" href="#tab_{{ languageCode['lcode'] }}">
                                  <span class="flag-icon flag-icon-{{ helper('utilities', 'core').getCountryCode(languageCode['lcode']) }}"></span>
                                  {{ languageCode['lcode'] }}
                              </a>
                            </li>
                            {% endfor %}
                        </ul>
                        <div class="tab-content" style="margin-left:-15px">
                            {% for catLang in formData['lang'] %}
                            <div class="tab-pane {{ loop.first ? 'active' : '' }}" id="tab_{{ catLang['lcode'] }}">
                                <div class="row column-seperation">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">
                                                {{ 'form.name'|i18n }}
                                                <span class="required">*</span>
                                            </label>
                                            <div class="col-sm-8">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder=""
                                                    name="lang[{{ catLang['lcode'] }}][name]"
                                                    value="{% if catLang['name'] is defined %}{{ catLang['name'] }}{% endif %}"
                                                    />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">{{ 'form.description'|i18n }}</label>
                                            <div class="col-sm-8">
                                                <textarea
                                                    class="form-control"
                                                    placeholder=""
                                                    name="lang[{{ catLang['lcode'] }}][description]">{% if catLang['description'] is defined %}{{ catLang['description'] }}{% endif %}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">{{ 'form.seodescription'|i18n }}</label>
                                            <div class="col-sm-8">
                                                <textarea
                                                    class="form-control"
                                                    placeholder=""
                                                    name="lang[{{ catLang['lcode'] }}][seodescription]">{% if catLang['seodescription'] is defined %}{{ catLang['seodescription'] }}{% endif %}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">{{ 'form.seokeyword'|i18n }}</label>
                                            <div class="col-sm-8">
                                                <textarea
                                                    class="form-control"
                                                    placeholder=""
                                                    name="lang[{{ catLang['lcode'] }}][seokeyword]">{% if catLang['seokeyword'] is defined %}{{ catLang['seokeyword'] }}{% endif %}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {% endfor %}
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
                        <label class="col-sm-3 control-label">
                            {{ 'form.iconpath'|i18n }}
                            <img class="edit-image" src="{{ static_url(formData['thumbnailImage']) }}" alt="{{ formData['thumbnailImage'] }}" />
                        </label>
                        <div class="col-sm-9">
                            <div id="uploadIconpath" class="dropzone" style="min-height: 240px"></div>
                            <input
                                type="hidden"
                                name="iconpath"
                                value="{% if formData['iconpath'] is defined %}{{ formData['iconpath'] }}{% endif %}"
                                id="uploadIconpathInput"/>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-3">
                        <span class="required">*</span>: Required field
                        </div>
                        <div class="col-sm-9">
                            <button class="btn btn-success" type="submit" name="fsubmit">Submit</button>
                            <button class="btn btn-default"><i class="pg-close"></i> Clear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
{% endblock %}
