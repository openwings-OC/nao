//var search = $('#search').val();

$('#search').autocomplete({
    maxShowItems : 10,
    minLength: 3,
    source : function(requete, reponse) {
        $.ajax({
            url: "http://localhost:3000/autocomplete",
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
        $('#form').attr('action', 'observation/' + ui.item.id);
        $('#form').submit();
    }
});

$('#ui-id-1').addClass('no-bullet');

