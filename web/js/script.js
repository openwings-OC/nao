
//Nom image uploadée
var inputs = $('#appbundle_observation_image_file');
Array.prototype.forEach.call( inputs, function( input )
{
    var label	 = $('#appbundle_observation_image div div label'),
        labelVal = label.innerHTML;

    input.addEventListener( 'change', function(e)
    {
        var value = $('#appbundle_observation_image_file').val();
        var filename = value.slice(12);
        label.html(filename)
    });
});

//Autocompletion moteur de recherche
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
                console.log(data)
                reponse($.map(data, function (objet) {
                    return objet
                }));
                $('#ui-id-1 li div').addClass('results');
                $('#ui-id-1 li div').addClass('large-12');
                $('.ui-helper-hidden-accessible').hide();
            },
        });
    },
    select : function(event, ui){
        $('#form').attr('action', 'specy/' + ui.item.id);
        $('#form').submit();
    }
});


//Requete AJAX carte des observations
$selectBird = $('#appbundle_observation_specy');
$($selectBird).change(function(){
    console.log($selectBird.val())
    $.ajax({
        url: "http://localhost/nao/web/app_dev.php/observation_map",
        type: "POST",
        dataType: 'JSON',
        data: {
            'bird': $selectBird.val(),
        },
        success: function (data, status){
            console.log(data)
            for(var i = 0 ; i < data.list.length ; i++){
                console.log(data.list[i])
            }

        }

    })
})

//Ajuste la largeur du moteur de recherche
$('#ui-id-1').addClass('large-4');

//Geolocalisation

$('#changecible').click(function(position){
    if(navigator.geolocation)
        navigator.geolocation.getCurrentPosition(maPosition);
    maPosition(position);
})

// if($('#infoposition') != null){
    function maPosition(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        document.getElementById("appbundle_observation_latitude").value = latitude;
        document.getElementById("appbundle_observation_longitude").value = longitude;
    }

    if(navigator.geolocation)
        navigator.geolocation.getCurrentPosition(maPosition);


