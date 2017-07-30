var $seeButton = $('#see'),
    $editButton = $('#edit'),
    $deleteButton = $('#delete'),
    $blockButton = $('#block'),
    $checkboxes = $("[data-js='checkbox']");
$checkboxes.change(function () {
    $checkboxes.not(this).prop('checked', false);
});
var returnIdOfSelectedCheckbox = function () {
    var checkboxId = false;
    for(var i = 0; i < $checkboxes.length; i++){
        if ($checkboxes[i].checked){
            checkboxId = $checkboxes[i].id;
            break;
        }
    }
    return checkboxId;
};
$seeButton.click(function (e) {
    e.preventDefault();
    var checkboxId = returnIdOfSelectedCheckbox();
    if(checkboxId) {
        document.location.href = $(this).data('url').replace('__id__', checkboxId);
    }
});
$editButton.click(function (e) {
    e.preventDefault();
    var checkboxId = returnIdOfSelectedCheckbox();
    if(checkboxId) {
        document.location.href = $(this).data('url').replace('__id__', checkboxId);
    }
});
$blockButton.click(function (e) {
    e.preventDefault();
    var checkboxSelected = returnIdOfSelectedCheckbox();
    if(checkboxSelected) {
        $('form#block'+checkboxSelected).submit();
    }
});
$deleteButton.click(function (e) {
    e.preventDefault();
    var checkboxSelected = returnIdOfSelectedCheckbox();
    if(checkboxSelected) {
        if( confirm("Êtes vous sûr de vouloir supprimer cet élément ?") == true){
            $('form#'+checkboxSelected).submit();
        }
    }
});