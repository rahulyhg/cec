{% extends "../../Core/View/Layout/Default/admin-main.volt" %}

{% block title %}
    {{ 'page-title-changepassword'|i18n }} | {{ config.global.title }}
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
                            <label class="col-sm-3 control-label">{{ 'form.new-password'|i18n }}</label>
                            <div class="col-sm-9">
                                <input
                                    type="password"
                                    class="form-control"
                                    placeholder=""
                                    name="newpassword"
                                    value=""
                                     />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{ 'form.repeat-new-password'|i18n }}</label>
                            <div class="col-sm-9">
                                <input
                                    type="password"
                                    class="form-control"
                                    placeholder=""
                                    name="repeatnewpassword"
                                    value=""
                                     />
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
                            *: {{ 'default.required'|i18n }}
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
