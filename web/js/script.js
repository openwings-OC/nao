$(document).ready(function() {
    $(".js-example-basic-single").select2();
});

//Nom image uploadée
var inputs = $('#appbundle_observation_image_file');
Array.prototype.forEach.call(inputs, function(input) {
    var label = $('#appbundle_observation_image div div label'),
        labelVal = label.innerHTML;

    input.addEventListener('change', function(e) {
        var value = $('#appbundle_observation_image_file').val();
        var filename = value.slice(12);
        label.html(filename)
    });
});

//Autocompletion moteur de recherche home
console.log("http://"+window.location.host+"/autocomplete")
$('#search-home').autocomplete({
    maxShowItems: 10,
    minLength: 3,
    source: function(requete, reponse) {
        $.ajax({
            url: $('#search-home').data('url'),
            type: "POST",
            dataType: 'JSON',
            data: {
                'bird': $('#search-home').val(),

            },
            success: function(data, statut) {
                reponse($.map(data, function(objet) {
                    return objet
                }));
                $('#ui-id-1 li div').addClass('results');
                $('#ui-id-1 li div').addClass('large-10');
                $('.ui-helper-hidden-accessible').hide();
            },
        });
    },
    select: function(event, ui) {
        $('#form-home').attr('action', '/espece/' + ui.item.id);
        $('#form-home').attr('action', $('#search-home').data('target') + 'espece/' + ui.item.id);
        $('#form-home').submit();
    }
});

//Autocompletion moteur de recherche page recherche
$('#search-page-search').autocomplete({
    maxShowItems: 10,
    minLength: 3,
    source: function(requete, reponse) {
        $.ajax({
            url: $('#search-page-search').data('url'),
            type: "POST",
            dataType: 'JSON',
            data: {
                'bird': $('#search-page-search').val(),

            },
            success: function(data, statut) {
                reponse($.map(data, function(objet) {
                    return objet
                }));
                $('#ui-id-2 li div').addClass('results');
                $('#ui-id-2 li div').addClass('large-10');
                $('.ui-helper-hidden-accessible').hide();
            },
        });
    },
    select: function(event, ui) {
        $('#form-search').attr('action', 'espece/' + ui.item.id);
        $('#form-search').submit();
    }
});

//Requete AJAX carte des observations
$selectBird = $('.observationMap');
$($selectBird).change(function() {
    $.ajax({
        url: $('.observationMap').data('url'),
        type: "POST",
        dataType: 'JSON',
        data: {
            'bird': $selectBird.val(),
        },
        success: function(data, status) {
            var uluru = [];
            for (var i = 0; i < data.list.length; i++) {
                uluru.push(data.list[i])
            }
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 5,
                center: new google.maps.LatLng(46.52863469527167, 2.43896484375),
                styles: [{
                        "elementType": "geometry",
                        "stylers": [{
                            "color": "#ebe3cd"
                        }]
                    },
                    {
                        "elementType": "labels.text.fill",
                        "stylers": [{
                            "color": "#523735"
                        }]
                    },
                    {
                        "elementType": "labels.text.stroke",
                        "stylers": [{
                            "color": "#f5f1e6"
                        }]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                            "color": "#c9b2a6"
                        }]
                    },
                    {
                        "featureType": "administrative.land_parcel",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                            "color": "#dcd2be"
                        }]
                    },
                    {
                        "featureType": "administrative.land_parcel",
                        "elementType": "labels.text.fill",
                        "stylers": [{
                            "color": "#ae9e90"
                        }]
                    },
                    {
                        "featureType": "landscape.natural",
                        "elementType": "geometry",
                        "stylers": [{
                            "color": "#dfd2ae"
                        }]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [{
                            "color": "#dfd2ae"
                        }]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "labels.text.fill",
                        "stylers": [{
                            "color": "#93817c"
                        }]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "geometry.fill",
                        "stylers": [{
                            "color": "#a5b076"
                        }]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "labels.text.fill",
                        "stylers": [{
                            "color": "#447530"
                        }]
                    },
                    {
                        "featureType": "road",
                        "elementType": "geometry",
                        "stylers": [{
                            "color": "#f5f1e6"
                        }]
                    },
                    {
                        "featureType": "road.arterial",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [{
                            "color": "#fdfcf8"
                        }]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry",
                        "stylers": [{
                            "color": "#f8c967"
                        }]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                            "color": "#e9bc62"
                        }]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "labels",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "road.highway.controlled_access",
                        "elementType": "geometry",
                        "stylers": [{
                            "color": "#e98d58"
                        }]
                    },
                    {
                        "featureType": "road.highway.controlled_access",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                            "color": "#db8555"
                        }]
                    },
                    {
                        "featureType": "road.local",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "labels.text.fill",
                        "stylers": [{
                            "color": "#806b63"
                        }]
                    },
                    {
                        "featureType": "transit.line",
                        "elementType": "geometry",
                        "stylers": [{
                            "color": "#dfd2ae"
                        }]
                    },
                    {
                        "featureType": "transit.line",
                        "elementType": "labels.text.fill",
                        "stylers": [{
                            "color": "#8f7d77"
                        }]
                    },
                    {
                        "featureType": "transit.line",
                        "elementType": "labels.text.stroke",
                        "stylers": [{
                            "color": "#ebe3cd"
                        }]
                    },
                    {
                        "featureType": "transit.station",
                        "elementType": "geometry",
                        "stylers": [{
                            "color": "#dfd2ae"
                        }]
                    },
                    {
                        "featureType": "water",
                        "elementType": "geometry.fill",
                        "stylers": [{
                            "color": "#b9d3c2"
                        }]
                    },
                    {
                        "featureType": "water",
                        "elementType": "labels.text.fill",
                        "stylers": [{
                            "color": "#92998d"
                        }]
                    }

                ],
                scrollwheel: false
            });
            for (var i = 0; i < uluru.length; i++) {
                var position = { lat: parseFloat(uluru[i][0]), lng: parseFloat(uluru[i][1]) };
                var marker = new google.maps.Marker({
                    position: position,
                    map: map
                })
            };
        }

    })
})

//Ajuste la largeur du moteur de recherche

$('#ui-id-1').addClass('large-4');