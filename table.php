<table class="table table-condensed table-striped table-hover" style="width: auto;">
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Source</th>
            <th class="{sorter: 'text'}">Language</th>
            <th>Klout</th>
            <th>Sentiment</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
<script id="table-line-tmpl" type="text/x-jQuery-tmpl">
    {{for messages}}
        <tr>
            <td class="span2"><small>{{: ~simpleDate(interaction.created_at)}}</small></td>
            <td class="span3">
                {{: interaction.type}}
                {{if interaction.subtype}}
                    <small class="muted"> {{: interaction.subtype}}</small>
                {{else}}
                    {{if ~getRealType(#data) != interaction.type}}<small class="muted">{{: ~getRealType(#data)}}</small>{{/if}}
                {{/if}}
            </td>
            <td class="span4">{{: interaction.source}}</td>
            <td>
                <p class="text-center">
                    {{if language}}
                        {{: language.tag}} <small class="muted">{{: language.confidence}}</small>
                    {{/if}}
                </p>
            </td>
            <td class="span1"><p class="text-center">{{if klout}}{{: klout.score}}{{/if}}</p></td>
            <td class="span1"><p class="text-center">{{if salience.content.sentiment}}{{: salience.content.sentiment}}{{/if}}</p></td>
            <td class="span2">
                <p class="text-right">

                    {{if ~hasMedia(#data)}}
                        <a href="#/medias/{{: #parent.index+1}}" data-toggle="tooltip" title="view media"><i class='icon-picture'></i></a>
                    {{/if}}
                    {{if interaction.geo}}
                        <a href="#/map/{{: #parent.index+1}}" data-toggle="tooltip" title="view in map"><i class="icon-map-marker"></i></a>
                    {{/if}}
                    <a href="#/raw/{{: #index+1}}"  data-toggle="tooltip" title="view source"><img src="img/source.png"></a>
                    <a href="{{: interaction.link}}" data-toggle="tooltip" title="view link" target="_blank"><img src="img/link.png"></a>
                </p>
            </td>
        </tr>
    {{/for}}
</script>


    </tbody>
</table>


