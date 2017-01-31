
<script id="raw-json-tmpl" type="text/x-jQuery-tmpl">
    {{for messages}}
        <pre id="msg{{: #index+1}}">
            <code>
                {{: ~urlize(~indent(#data)) }}
            </code>
        </pre>
    {{/for}}
</script>

