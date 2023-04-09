(function($){
 
    $('#mainMenuMobile li.has-sub-menu i').on('click', function(e){
        e.preventDefault();
        $(this).parent('a').parent('li').toggleClass('show-sub')
    })

    $('.navbar-toggler').on('click', function(){
        $(this).toggleClass('actived')
        if($('.navbar-toggler').hasClass('actived')){
            $('.nav__mobile__menu').addClass('active')
        }else{
            $('.nav__mobile__menu').removeClass('active')
        }
    })

  
    var swiper = new Swiper('.swiper-galleries', {
        slidesPerView: 1,
        spaceBetween: 20,
        freeMode: true,
     
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
      },
      breakpoints: {
        768: {
            slidesPerView: 2,
            spaceBetween: 30,
        },
    }
    });


    var swiper = new Swiper('.scg__swiper', {
        pagination: {
            el: '.swiper-pagination',
        },
        slidesPerView: 'auto',
        autoplay: {
            delay: 5000,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
    
    /*
     * Product slide
     */
    var product = new Swiper('.scg__product__list', {
        slidesPerView: 2,
        spaceBetween: 10,
    
        // init: false,
        breakpoints: {
            576: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 4,
                spaceBetween: 25,
            }
        }
    });
    
    var gallery = new Swiper('.scg__galleries', {
        slidesPerView: 1,
        spaceBetween: 25,
        // init: false,
        breakpoints: {
            576: {
                slidesPerView: 1,
                spaceBetween: 15,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            992: {
                slidesPerView: 3,
                spaceBetween: 25,
            }
    
        }
    });


    var videoId;

    $('.scg__btnvideo').on('click', function(e) {
        e.preventDefault();
        videoId = $(this).data('id')
    })

    $('#scg__video').on('shown.bs.modal', function(e) {

        // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
        $("#scg__video__play").attr('src', 'https://www.youtube.com/embed/' + videoId +
            "?autoplay=1&amp;modestbranding=1&amp;showinfo=1");

    })

    $('#scg__video').on('hidden.bs.modal', function(e) {
        $("#scg__video__play").attr('src', null)
    })


    //ajax post by cat

    $('.ajax_post').each(function(){
        $(this).on('click', function(e){
            e.preventDefault();
            var termId = $(this).attr('data-id');
            var wrapPosts = $('.row__ideal');
            var htmlLoad = '<div id="load"><div>G</div><div>N</div><div>I</div><div>D</div><div>A</div><div>O</div><div>L</div></div>';
            $(this).parent('li').parent('.scg__tabs').find('li.active').removeClass('active');
            $(this).parent('li').addClass('active');
            $.ajax({
                type: "POST",
                url: object.ajaxUrl,
                data: {
                    action: 'dvu_posts_list',
                    id: termId,
                    nonce: object.wpNonce,
                },
                success: function (response) {
                    wrapPosts.html('');
                    wrapPosts.html(htmlLoad);
                    wrapPosts.addClass('loading');
                    var data = JSON.parse(response);
                    setTimeout(function(){
                        wrapPosts.removeClass('loading');
                        wrapPosts.html(data);
                    }, 1000)

                },
                error: function() {
                    console.log("can't connect to server");
                }
            });
            
        })
    });


    //ajax sendregistration



  
        $('#dvu_registerform').on('submit', function(e){
            
          
            e.preventDefault();
            var input =  $(this).find('input[name="email"]')
            var email = input.val();
            var mess = $(this).find('.message');
            var button = $(this).find('.js__registration');
           
            if(!validateEmail(email)){
                mess.html('Email không hợp lệ');
                return;
            }
            button.html('sending...');
            button.attr('disabled', true);
    
            $.ajax({
                type: "POST",
                url: object.ajaxUrl,
                data: {
                    action: 'dvu_sendmail',
                    email: email,
                    nonce: object.wpNonce,
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    
                    
                    setTimeout(function(){
                        if(data.status == 'success'){
                            mess.html('Cảm ơn bạn đã đăng ký nhận thông tin');
                            button.html('Gửi');
                            button.removeAttr("disabled");
                            input.val('');
                        }else{
                            mess.html('Lỗi không gửi được email, vui lòng thử lại sau');
                            button.html('Gửi');
                            button.removeAttr("disabled");
                            
                        }
                    }, 300);
                

                },
                error: function() {
                    console.log("can't connect to server");
                }
            });
            
        })
   

})(jQuery);

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}