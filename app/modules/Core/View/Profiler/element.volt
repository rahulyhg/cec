<span class="label">{{ title }}: </span>
{% if !(tag is empty) %}
    <{{ tag }} class="code">{{ value }}</{{ tag }}>
{% elseif noCode is true %}
    {{ value }}<br/>
{% else %}
    <span class="code">{{ value }}</span>
    <br/>
{% endif %}
