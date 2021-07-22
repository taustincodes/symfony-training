
window.onload = function cartLoop(){
    $("#cart").animate({left:'105%'}, 8000,)
        .animate({bottom:'110%'}, 10,)
        .animate({left:'-15%'}, 10,)
        .animate({bottom:'-10%'}, 10,)
        cartLoop();
}




