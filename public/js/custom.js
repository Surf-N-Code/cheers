// $("#signUpBtns").addClass("appear-animation");

// setTimeout(function() {
//     $("#signUpBtns").css('visibility', 'visible');
//     $("#signUpBtns").addClass("appear-animation animated fadeInUp appear-animation-visible");
// }, 1200);

$('#whatsappInput').hover(function(e) {
    $(this).addClass('placeholderClass');
}, function(a) {
    $(this).removeClass('placeholderClass');
});

$('#whatsappInput').mouseleave(function(e) {
    console.log("left");
    $(this).removeClass('placeholderClass');
})

$('#whatsappSignUpForm').on('submit', function(e) {
    e.preventDefault();
    console.log("logged");
    console.log("data",$('#whatsappInput').val());
    $btn = $('#whatsappSignUpBtn');
    $input = $('#whatsappInput');

    $btn.attr("disabled", "disabled");
    var loadingText = ' Registering <i class="fa fa-spinner fa-spin"></i>';
    var originText = 'SIGN UP NOW <i class="fas fa-arrow-right ml-1"></i>';
    if ($btn.html() !== loadingText) {
        $btn.html(loadingText);
    }

    $.ajax({
        type: 'POST',
        url: '/regWhatsapp',
        data: {
            'number': $('#whatsappInput').val()
        },
        dataType: 'json',
        success: function(data) {
            console.log(data.status);
            if (data.status == "success") {
                $btn.html(originText);
                $btn.attr("disabled", false);
                $input.val("");
                $btn.removeClass('btn btn-primary');
                $btn.addClass('btn btn-success');
                $btn.html('<i class="fas fa-check"></i> CHEERS BRO!');

            } else {

            }
        },
        error: function(e) {
            $btn.html(originText);
            $btn.attr("disabled", false);
        }
    });
});

is_processing = false;
last_page = false;
function addMoreElements() {
    console.log("add more elements");
    is_processing = true;
    $.ajax({
        type: "GET",
        //FOS Routing
        // url: Routing.generate('route_name', {page: page}),
        url: "/",
        success: function(data) {
            if (data.html.length > 0) {
                $('.selector').append(data.html);
                page = page + 1;
                //The server can answer saying it's the last page so that the browser doesn't make anymore calls
                last_page = data.last_page;
            } else {
                last_page = true;
            }
            is_processing = false;
        },
        error: function(data) {
            is_processing = false;
        }
    });
}

$(window).scroll(function() {
    var wintop = $(window).scrollTop(), docheight = $(document).height(), winheight = $(window).height();
    //Modify this parameter to establish how far down do you want to make the ajax call
    var scrolltrigger = 0.80;
    if ((wintop / (docheight - winheight)) > scrolltrigger) {
        //I added the is_processing variable to keep the ajax asynchronous but avoiding making the same call multiple times
        if (last_page === false && is_processing === false) {
            addMoreElements();
        }
    }
});