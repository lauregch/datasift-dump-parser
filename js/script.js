


function getMsgRealType(msg) {
    if ( msg.interaction.source=='Tumblr' || msg.interaction.source=='Instagram') {
        return msg.interaction.source.toLowerCase();
    }
    else if ( msg.interaction.source=='Vine - Make a Scene' ) {
        return 'vine';
    }
    else return msg.interaction.type.toLowerCase();
}

function getMsgRealLink(msg) {
    switch ( msg.interaction.source ) {
        case 'Instagram' :
        case 'Tumblr' :
        case 'Vine - Make a Scene' : { 
            if (msg && msg.links && msg.links.normalized_url ) return msg.links.normalized_url[0];
            else return msg.interaction.link;
        }
        default : {
            return msg.interaction.link;
        }
    }
}

function msgHasMedia(msg) {
    var res = false;
    res = res || ( msg.twitter && msg.twitter.media );
    res = res || ( msg.twitter && msg.interaction.source=='Instagram' );
    res = res || ( msg.twitter && msg.interaction.source=='Tumblr' && msg.links && msg.links.meta );
    res = res || ( msg.twitter && msg.interaction.source=='Vine - Make a Scene');
    return Boolean(res);
}

$.views.helpers({
        urlize: function(text) {
            var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
            return text.replace(exp,"<a href='$1' target='_blank'>$1</a>"); 
        },
        indent: function(text) {
            return JSON.stringify(text, undefined, 4);
        },
        simpleDate: function(date) {
            var d = new Date(date);
            return d.toLocaleDateString() + ' ' + d.getUTCHours() + ':' + d.getUTCMinutes();
        },
        getRealType: getMsgRealType,
        getRealLink: getMsgRealLink,
        hasMedia: msgHasMedia,
        getMediaURL: function(msg) {
            switch ( msg.interaction.source ) {
                case 'Instagram' : {
                    $.each( msg.links.meta.opengraph, function(i,o) {
                        if ( o.type=='instapp:photo' || o.type=='video') res = o.image;
                    });
                    break;
                }
                case 'Tumblr' : {
                    $.each( msg.links.meta.opengraph, function(i,o) {
                        if ( o.type=='tumblr-feed:photo' ) res = o.image;
                    });
                    break;
                }
                case 'Vine - Make a Scene' : {
                    $.each(  msg.links.meta.twitter, function(i,t) {
                        var private_url = t.image;
                        res = private_url.replace( 'https://vines.s3.amazonaws.com/', 'https://v.cdn.vine.co/v/').substring(0, private_url.indexOf('?'));
                    });
                    break;
                }
                default : {
                    $.each( msg.twitter.media, function(i,m) {
                        res = m.media_url;
                    });
                    break;
                }
            }
            return res;
        }
    });

    var sections = (function() {
        var list = {

            stats : (function() {
                return {
                    tmpl :  $('#stats-tmpl'),
                    container : $('#stats')
                };
            })(),

            table : (function() {
                return {
                    tmpl      : $('#table-line-tmpl'),
                    container : $('table tbody'),
                    onShow : function() {
                        $('#table table').tablesorter( { sortList:[[0,0]]} );
                        $('#table a').tooltip();
                    }
                };
            })(),

            medias : (function() {
                return {
                    tmpl      : $('#media-tmpl'),
                    container : $('#medias ul'),
                    onShow : function(msg_id) { 
                        $('#medias ul').masonry();
                        if ( msg_id ) {
                            var thumbnail = $('li#media' + msg_id);
                            thumbnail.addClass('current');
                            $(document).scrollTop( thumbnail.offset().top );
                        }
                        $('.sentiment-icon').tooltip( {placement: 'right'} );
                    }
                };
            })(),

            raw : (function() {
                return {
                    tmpl      : $('#raw-json-tmpl'),
                    container :  $('#raw'),
                    onShow : function(msg_id) {
                        if ( msg_id ) {
                            var msg_code = $('#msg' + msg_id);
                            msg_code.find('code').addClass('current');
                            $(document).scrollTop( msg_code.offset().top ); 
                        }
                    }
                };
            })(),

            map : (function() {
                var markers = [];
                var infowindow = null;
                var center = new google.maps.LatLng(48.8, 2.3);
                function showInfo( msg_id ) {
                    if (infowindow) {
                        infowindow.close();
                    }
                    infowindow = new google.maps.InfoWindow({
                        content: '<div class="span3">' + $('#media-single-tmpl').render( json_data.messages[msg_id-1] ) + '</div>'
                    });
                    infowindow.open( mapObject, markers[msg_id-1] );
                }
                return {
                    onRender : function(data) {
                        mapObject = new google.maps.Map( document.getElementById('google-map'), {
                            zoom: 2,
                            center: center,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });
                        $.each( data.messages, function(i,msg) {
                            if ( msg.interaction.geo ) {
                                var marker = new google.maps.Marker( { 
                                    position: new google.maps.LatLng( msg.interaction.geo.latitude, msg.interaction.geo.longitude ),
                                    map: mapObject,
                                    title: msg.interaction.content
                                });
                                markers[i] = marker;
                                google.maps.event.addListener( marker, 'click', function() {
                                    showInfo(i);
                                });
                            }
                        });
                    },
                    onShow : function(msg_id) {
                        if (msg_id) {
                            showInfo( msg_id )
                        }
                        google.maps.event.trigger(mapObject, 'resize');
                        mapObject.setCenter(center);
                    }
                };
            })()
        }

        function render(data) {
            $.each( list, function(i,s) {
                if ( s.tmpl && s.container ) {
                    s.container.html( s.tmpl.render(data) );
                }
                if (s.onRender) {
                    s.onRender(data);
                }
            });
        }

        function show(cat, param) {
            $('main').show();
            $('nav li').removeClass('active');
            $('nav *[data-target="' + cat + '"]').addClass('active');
            $('main').attr('class','').addClass(cat);
            $('#raw code, #medias li').removeClass('current');
            if (list[cat]) list[cat].onShow(param);
        }
        return {
            renderAll : render,
            show : show
        };
    })();


    function bindEvents() {
        var arrow_up = $('#arrow-up');
        $(window).scroll(function () {
            if ($(this).scrollTop() > 600) arrow_up.fadeIn(200);
            else arrow_up.fadeOut(200);
        });
        arrow_up.click(function () {
            $('body,html').animate({ scrollTop: 0 }, 800);
            return false;
        });

        $(window).on('hashchange',function() { 
            var fragments = location.hash.slice(1).replace('/','').split('/');
            sections.show( fragments[0], fragments[1] );
        });

        $('#medias #source-filter button').click( function() {
            var target = $(this).data('target');
            $('#medias ul').isotope({ filter: (target=='all' ? '.facebook, .tumblr, .vine, .twitter, .instagram, .blog':'.'+target) });
        });
    }

    $(function() {
        $('input[type=file]').bootstrapFileInput();

        if (json_data) {
            sections.renderAll( json_data );
            bindEvents();
            sections.show('table');
        }
    });

    $(window).load(function(){
        $('#loader').fadeOut(500);
    });