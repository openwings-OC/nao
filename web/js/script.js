
$('#search').autocomplete({
    maxShowItems : 10,
    minLength: 3,
    source : function(requete, reponse) {
        $.ajax({
            url: "http://localhost/nao/web/app_dev.php/autocomplete",
            type: "POST",
            dataType : 'JSON',
            data: {'bird': $('#search').val(),

        },
            success: function (data, statut) {
                //console.log(data);
                reponse($.map(data, function (objet) {
                    //console.log(objet[0].id);
                    return objet;
                }));
                $('#ui-id-1 li div').addClass('results');
                $('#ui-id-1 li div').addClass('large-4');
                $('.ui-helper-hidden-accessible').hide();
            }
        });
    },
    select : function(event, ui){
        $('#form').attr('action', 'specy/' + ui.item.id);
        $('#form').submit();
    }
});

$('#ui-id-1').addClass('no-bullet');

//Geolocalisation


function maPosition(position) {

    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    document.getElementById("appbundle_observation_latitude").value = latitude;
    document.getElementById("appbundle_observation_longitude").value = longitude;
}

if(navigator.geolocation)
    navigator.geolocation.getCurrentPosition(maPosition);


