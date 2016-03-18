{% extends "../../Core/View/Layout/Default/admin-main.volt" %}

{% block title %}
    {{ 'page-title-editfile'|i18n }} | {{ config.global.title }}
{% endblock %}

{% block content %}
<style type="text/css" media="screen">
    #editor {
        position: absolute;
        top: 115px;
        right: 0;
        bottom: 0;
        left: 247px;
        height: auto;
    }
</style>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg bg-white">
    <pre id="editor">
        {{ editContent|e }}
    </pre>
</div>
<!-- END CONTAINER FLUID -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/ace.js"></script>
<script type="text/javascript" src="{{ static_url('assets/default/js/core/jquery-base64encode.js') }}"></script>
<script>
    var filepath = `{{ filepath }}`;
    var editor = ace.edit("editor");
    editor.getSession().setMode("ace/mode/twig");
    editor.setTheme("ace/theme/terminal");
    editor.getSession().setTabSize(4);
    editor.getSession().setUseSoftTabs(true);
    editor.commands.addCommand({
        name: 'saveFile',
        bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
        exec: function(editor) {
            $.ajax({
                type: 'POST',
                url: root_url + '/saveeditfile',
                data: "file=" + Base64.encode(filepath) + "&content=" + Base64.encode(editor.getValue()),
                dataType: 'json',
                cache: false,
                success: function(response) {
                    if (response._meta.status == true) {
                        toastr.success(response._meta.message);
                    } else {
                        toastr.error(response._meta.message);
                    }
                }
            });
        },
        readOnly: true // false if this command should not apply in readOnly mode
    });
</script>
{% endblock %}
