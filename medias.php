
<p><div id="source-filter" class="btn-group" data-toggle="buttons-radio">
	<button type="button" data-target="all" class="btn active">All sources</button>
	<button type="button" data-target="twitter" class="btn"><img src="img/icon-twitter.png"></button>
	<button type="button" data-target="facebook" class="btn"><img src="img/icon-facebook.png"></button>
	<button type="button" data-target="instagram" class="btn"><img src="img/icon-instagram.png"></button>
	<button type="button" data-target="tumblr" class="btn"><img src="img/icon-tumblr.png"></button>
	<button type="button" data-target="vine" class="btn"><img src="img/icon-vine.png"></button>
	<button type="button" data-target="blog" class="btn">blogs</button>
</div></p>

<ul class="thumbnails">
	<script id="media-tmpl" type="text/x-jQuery-tmpl">
		{{for messages}}
			<li class="span4 {{: ~getRealType(#data)}}" id="media{{: #index+1}}">
				{{include tmpl="#media-single-tmpl"/}}
			</li>
		{{/for}}
	</script>
</ul>

<script id="media-single-tmpl" type="text/x-jQuery-tmpl">
		<div class="thumbnail">
			{{if interaction.author}}
				<div class="caption media">
					<a class="pull-left" href="{{: interaction.author.link}}" targte="_blank">
						<img class="media-object" src="{{: interaction.author.avatar}}">
					</a>
					<div class="media-body">
						<h6 class="media-heading">{{: interaction.author.name}}</h4>
						<small class="muted">{{: interaction.created_at}}</small>
					</div>
				</div>
			{{/if}}
			<a href="{{: ~getRealLink(#data)}}" target="_blank" class="thumbnail">
				<img src={{if ~hasMedia(#data)}}"{{: ~getMediaURL(#data)}}"{{else}}"img/bubble.gif"{{/if}} alt="">
			</a>
			<div class="type-icon"><img src="img/icon-{{: ~getRealType(#data)}}.png"></div>
			{{if salience && salience.content.sentiment}}
				<div class="sentiment-icon" data-toggle="tooltip" title="{{: salience.content.sentiment}}">
					<i class={{if salience.content.sentiment > 0}}"icon-thumbs-up"{{else}}"icon-thumbs-down"{{/if}}></i>
				</div>
			{{/if}}
			<!--<a class="raw-icon" href="#/raw/{{: #index+1}}" data-toggle="tooltip" title="view source"><img src="img/source.png"></a>-->
			<div class="caption">
				<small>{{: ~urlize(interaction.content)}}</small>
			</div>
		</div>
	</script>