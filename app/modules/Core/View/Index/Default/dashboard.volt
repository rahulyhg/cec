{% extends "../../Core/View/Layout/Default/admin-main.volt" %}

{% block title %}
    {{ 'page-title-index'|i18n }} | {{ config.global.title }}
{% endblock %}

{% block content %}
<style type="text/css" media="screen">
    #editor {
        position: absolute;
        top: 115px;
        right: 0;
        bottom: 0;
        left: 250px;
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
<script>
    var editor = ace.edit("editor");
    editor.getSession().setMode("ace/mode/twig");
    editor.setTheme("ace/theme/terminal");
    editor.getSession().setTabSize(4);
    editor.getSession().setUseSoftTabs(true);
    editor.commands.addCommand({
        name: 'myCommand',
        bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
        exec: function(editor) {
            console.log(editor.getValue());
        },
        readOnly: true // false if this command should not apply in readOnly mode
    });
</script>
{% endblock %}
