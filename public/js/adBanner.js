
var slideIndex = 0;
var y = 0;
var txt = document.getElementById("demo").textContent;
var txt2 = document.getElementById("demo2").textContent;
var speed = 50;
carousel();



function typeWriter() {
    if (y < txt.length) {
        $("#demo").append(txt.charAt(y));
        y++;
        setTimeout(typeWriter, speed);
    }
}
function typeWriter2() {
    if (y < txt2.length) {
        $("#demo2").append(txt2.charAt(y));
        y++;
        setTimeout(typeWriter2, speed);
    }
}
function carousel() {

    var i;
    var x = document.getElementsByClassName("mySlides");
    console.log(x);

    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    slideIndex++;
    if (slideIndex > x.length) {
        slideIndex = 1
    }
    x[slideIndex - 1].style.display = "block";
    if (slideIndex == 1 || slideIndex == 0) {
        y = -1
        typeWriter();
    }
    if (slideIndex == 2) {
        y = -1;
        typeWriter2();
    }
    document.getElementById("demo").innerHTML = "";
    document.getElementById("demo2").innerHTML = "";

    setTimeout(carousel, 7000);
}

