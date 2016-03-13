<span class="text-primary">{{ title }}: </span>
{% if !(tag is empty) %}
    <{{ tag }} class="code">{{ value }}</{{ tag }}>
{% elseif noCode is true %}
    {{ value }}<br/>
{% else %}
    <span class="text-danger">{{ value }}</span>
    <br/>
{% endif %}
