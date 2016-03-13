
<link rel="stylesheet" type="text/css" href="{{ static_url('plugins/profiler/profiler.css') }}">
<script type="text/javascript" src="{{ static_url('plugins/profiler/profiler.js') }}"></script>
{{ partial("Profiler/window", ['name':'config', 'title':'Phalcon Config', 'content':htmlConfig]) }}
{{ partial("Profiler/window", ['name':'router', 'title':'Router', 'content':htmlRouter]) }}
{{ partial("Profiler/window", ['name':'memory', 'title':'Memory', 'content':htmlMemory]) }}
{{ partial("Profiler/window", ['name':'time', 'title':'Time', 'content':htmlTime]) }}
{{ partial("Profiler/window", ['name':'files', 'title':'Files', 'content':htmlFiles]) }}
{{ partial("Profiler/window", ['name':'sql', 'title':'SQL', 'content':htmlSql]) }}
{{ partial("Profiler/window", ['name':'errors', 'title':'Errors', 'content':htmlErrors]) }}

<div class="profiler">
    <div data-window="router" class="item">{{ handlerValues['router'] }}</div>
    <div data-window="memory" class="item item-right item-memory {{ handlerValues['memory']['class'] }}">{{ handlerValues['memory']['value'] }}
        kb
    </div>
    <div data-window="time" class="item item-right item-time {{ handlerValues['time']['class'] }}">{{ handlerValues['time']['value'] }}
        ms
    </div>
    <div data-window="files" class="item item-right item-files">{{ handlerValues['files'] }}</div>
    <div data-window="sql" class="item item-right item-sql">{{ handlerValues['sql'] }}</div>
    <div data-window="errors" class="item item-right item-errors {{ handlerValues['errors']['class'] }}">{{ handlerValues['errors']['value'] }}</div>
</div>
