// $("#signUpBtns").addClass("appear-animation");

setTimeout(function() {
    $("#signUpBtns").css('visibility', 'visible');
    $("#signUpBtns").addClass("appear-animation animated fadeInUp appear-animation-visible");
}, 1200);

$('#whatsappInput').hover(function(e) {
    $(this).addClass('placeholderClass');
}, function(a) {
    $(this).removeClass('placeholderClass');
});

$('#whatsappInput').mouseleave(function(e) {
    console.log("left");
    $(this).removeClass('placeholderClass');
})