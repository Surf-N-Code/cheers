// $("#signUpBtns").addClass("appear-animation");

// setTimeout(function() {
//     $("#signUpBtns").css('visibility', 'visible');
//     $("#signUpBtns").addClass("appear-animation animated fadeInUp appear-animation-visible");
// }, 1200);

$('.whatsappInput').hover(function(e) {
    console.log("hovering");
    $(this).addClass('placeholderClass');
}, function(a) {
    $(this).removeClass('placeholderClass');
});

$('.whatsappInput').mouseleave(function(e) {
    console.log("left");
    $(this).removeClass('placeholderClass');
})

$('.whatsappSignUpForm').on('submit', function(e) {
    e.preventDefault();
    console.log("logged");
    console.log("data",$('.whatsappInput').val());
    $btn = $('.whatsappSignUpBtn');
    $input = $('.whatsappInput');

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
            'number': $input.val()
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

$('#newsletterForm').on('submit', function(e) {
    e.preventDefault();
    $btn = $('#signUpBtnFooter');
    $input = $('#signUpInputFooter');

    var originText = "LET'S GO <i class='fas fa-arrow-right ml-1'></i>";

    $.ajax({
        type: 'POST',
        url: '/regWhatsapp',
        data: {
            'number': $input.val()
        },
        dataType: 'json',
        success: function(data) {
            if (data.status == "success") {
                // $btn.html(originText);
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
lastPage = false;
productIteration = 1;
function addMoreElements() {
    console.log("add more elements");
    is_processing = true;

    $.ajax({
        type: "GET",
        //FOS Routing
        // url: Routing.generate('route_name', {page: page}),
        url: "/fetchNextProducts/"+productIteration,
        success: function(data) {
            console.log(data.html);
            if (data.html.length > 0) {
                console.log("should append");
                $('.loadMoreProductsWrapper').hide();
                $('.loadMoreProductsBtn').html("Hey Bro, mehr bitte");
                $('#mainProductList').append(data.html);
                lastPage = data.lastPage;
            } else {
                lastPage = true;
            }
            is_processing = false;
        },
        error: function(data) {
            is_processing = false;
        }
    });
}
$(document).on('click', '.loadMoreProductsBtn', function(e) {
    e.preventDefault();
    $('.loadMoreProductsBtn').html("Ich suche <i class='fa fa-spinner fa-spin'></i>");
    addMoreElements();
    productIteration++;
});

$(window).scroll(function() {
    var wintop = $(window).scrollTop(), docheight = $(document).height(), winheight = $(window).height();
    //Modify this parameter to establish how far down do you want to make the ajax call
    var scrolltrigger = 0.5;
    if ((wintop / (docheight - winheight)) > scrolltrigger) {

        //I added the is_processing variable to keep the ajax asynchronous but avoiding making the same call multiple times
        if (lastPage === false && is_processing === false) {
            $('.loadMoreProductsWrapper').show();

            // addMoreElements();
            // productIteration++;
        }
    }
});

$(document).on('click', '.likeProductBtn', function(e) {
    e.preventDefault();
    $likesElem = $(this).find('.likeCount');
    $likes = parseInt($likesElem.html());

    var change = 0;
    if($(this).hasClass('btn-success')) {
        $likesElem.html($likes-1);
        change = -1;
    } else {
        $likesElem.html($likes+1);
        change = 1;
    }

    $.ajax({
        method: 'POST',
        url: $(this).attr('href'),
        data: { delta: change, productId: $(this).attr('data-productId')}
    });

    $(this).toggleClass('btn-danger').toggleClass('btn-success');
});