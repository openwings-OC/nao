console.log('salut');

var liste = [
    "Draggable",
    "Droppable",
    "Resizable",
    "Selectable",
    "Sortable"
];

//var search = $('#search').val();

console.log(search);

$('#search').autocomplete({
    source : function(requete, reponse) {
        $.ajax({
            url: "http://localhost/nao/web/app_dev.php/autocomplete",
            type: "POST",
            dataType : 'JSON',
            data: {'bird': $('#search').val(),
            maxRows: 20
        },
            success: function (data, statut) {
                reponse($.map(data, function (objet) {
                    console.log(objet);
                    return objet;
                }));
                $('#ui-id-1 li div').addClass('results');
                $('#ui-id-1 li div').addClass('large-4');
                $('.ui-helper-hidden-accessible').hide();
            }
        });
    }
});

/*$('#ui-id-1').click(function(){
    $('#form').submit();
})*/

$('#ui-id-1').addClass('no-bullet');

