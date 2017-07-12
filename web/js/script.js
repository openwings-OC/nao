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
            maxRows: 15
        },
            success: function (data, statut) {
                console.log(data);
                reponse($.map(data, function (objet) {
                    return objet;
                }));
                $('#ui-id-1 li div').addClass('results');
                $('.ui-helper-hidden-accessible').hide();
            }
        });
    }
});

$('#ui-id-1').addClass('no-bullet');

