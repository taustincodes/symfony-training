$('.productHover').hover(function(){
    id = "#hover" + $(this).data('key');
    $(id).slideToggle(500);
})

function showAdd(){
    $("#changeadd").slideToggle("slow");
}