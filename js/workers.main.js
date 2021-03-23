/*! -------------------------------------------------------------------------------------------

JAVASCRIPT main engine!



* @Version:    1.0 - 2017

* @author:     Netgócio

* @email:      geral@netgocio.pt

* @website:    http://www.netgocio.pt

--------------------------------------------------------------------------------------------*/



//** MAIN LOAD FUNCTION

function loadPages(newContent, pageTransition, elem) {

    var $currentPage = $(".page-main.page-current"),

        $nextContent = $(".page-main.page-next"); // can't be global



    if (!pageTransition) pageTransition = "default";



    if (pageTransition == "default") { //animation by default



        if ($_debug) console.log("page: default");



        closeAllDetails();

        loadingTransition("start", function () {

            $nextContent.load(newContent, function (response, status, xhr) {

                var $this = $(this);



                if (!$this.html()) {

                    window.location = newContent;

                    load404();

                    return;

                }



                // ANIMATION GOES HERE

                var delay_time = 2 - _loaderTimer;



                delay_time < 0 && (delay_time = 0);



                //$.doTimeout(1e3 * delay_time, function() {

                $.doTimeout(delay_time, function () {

                    TweenLite.to($currentPage, 0, {

                        opacity: 0,

                        onComplete: function () {

                            clearPagesAfterloading(0);



                            $.doTimeout(400, function () {

                                handleMetas(0, elem, 0);

                            });



                            loadingTransition("finish");

                        }

                    });

                });

            });

        });

    } else if (pageTransition == "panelRight") {



        if ($_debug) console.log("page: panelRight");



        closeAllDetails();

        $nextContent.load(newContent, function (response, status, xhr) {

            var $this = $(this);



            if (!$this.html()) {

                window.location = newContent;

                load404();

                return;

            }



            $('.page-next').addClass('panelRight');



            $.doTimeout(500, function () {

                TweenLite.to($nextContent, 0.5, {

                    opacity: 1,

                    right: 0,

                    onComplete: function () {

                        clearPagesAfterloading(400);



                        $.doTimeout(400, function () {

                            handleMetas(0, elem, 0);

                        });

                    }

                });

            });

        });

    } else if (pageTransition == "noticias-detail") {

        if ($_debug) console.log("page: noticias-detail");



        $nextContent = $($noticias_rpc);



        if (!$($noticias_rpc).hasClass('active')) {

            $nextContent.load(newContent, function (response, status, xhr) {

                $($noticias_rpc).addClass('active');



                if ($($noticias_rpc + " .noticias-detail").length > 0) {

                    loadNoticiasDetail();



                    handleMetas(1, elem, 0);

                    var $this = $(this);



                    toogleModalDetail();

                    TweenMax.to($($noticias_rpc + " .noticias-detail"), 1, {

                        opacity: 1,

                        onComplete: function () {

                            whenDetailsOpen();

                        }

                    });

                }

            });

        } else {

            loadingTransition("start", function () {

                $nextContent.load(newContent, function (response, status, xhr) {

                    loadNoticiasDetail();

                    handleMetas(1, elem, 0);



                    loadingTransition("finish");

                    TweenMax.to($($noticias_rpc + " .noticias-detail"), 1, {

                        opacity: 1,

                        onComplete: function () {

                            whenDetailsOpen();

                        }

                    });

                });

            });

        }

    } else if (pageTransition == "close-noticias-detail") {

        if ($_debug) console.log("page: close-noticias-detail");



        if ($($noticias_rpc).hasClass('active')) {

            toogleModalDetail();

            handleMetas(0, 0, 0);

        }

    } 

}; //end function main load content



// Accordion ============//



$(document).ready(function(){

    $(".accordion").on("click", ".heading", function() {



    $(this).toggleClass("active").next().slideToggle();



    $(".contents").not($(this).next()).slideUp(300);

            

    $(this).siblings().removeClass("active");

    });

});

      

/*******************************************************************************************

 ****                                                                                   ****

    =PAGES HANDLE - NETGOCIO

 ****                                                                                   ****

*********************************************************************************************/

function loadHomepage() {

    if ($_debug) console.log("loadHomepage");



    initBanners();



    function initSliderOf3() {

        if ($('.slick-of3').length > 0) {

            $('.slick-of3').each(function () {

                if (!$(this).hasClass('slick-initialized')) {

                    var $dots = false;

                    if ($(this).hasClass('has_dots')) $dots = true;



                    $(this).slick({

                        //dots: $dots,

                        dots:false,

                        slidesToShow: 5,

                        slidesToScroll: 5,

                        arrows: true,

                        infinite: true,

                        adaptiveHeight: false,

                        responsive: [

                            {

                                breakpoint: 1440,

                                settings: {

                                    dots: false,

                                    centerMode: false,

                                    centerPadding: '60px',

                                    infinite: true,

                                    slidesToShow: 4,

                                    slidesToScroll:4,

                                }

                            },

                            {

                                breakpoint: 950,

                                settings: {

                                    dots: false,

                                    centerMode: true,

                                    centerPadding: '60px',

                                    infinite: true,

                                    slidesToShow: 3,

                                    slidesToScroll: 3,

                                }

                            },

                            {

                                breakpoint: 640,

                                settings: {

                                    dots: false,

                                    centerMode: false,

                                    centerPadding: '30px',

                                    slidesToShow: 2,

                                    slidesToScroll: 2,

                                    infinite: true,

                                    variableWidth: true

                                }   

                            },

                            {

                                breakpoint: 520,

                                settings: {

                                    dots: false,

                                    centerMode: false,

                                    centerPadding: '30px',

                                    slidesToShow: 1,

                                    slidesToScroll: 1,

                                    infinite: true,

                                    variableWidth: true

                                }   

                            }

                        ]

                    });

                }

            });

        }

    }



    initSliderOf3();



     function initnewsSliderOf3() {

        if ($('.slick-of3news').length > 0) {

            $('.slick-of3news').each(function () {

                if (!$(this).hasClass('slick-initialized')) {

                    var $dots = false;

                    if ($(this).hasClass('has_dots')) $dots = true;



                    $(this).slick({

                        //dots: $dots,

                        dots:false,

                        slidesToShow: 3,

                        slidesToScroll: 3,

                        arrows: true,

                        infinite: true,

                        adaptiveHeight: false,

                        responsive: [

                            {

                                breakpoint: 1280,

                                settings: {

                                    dots: false,

                                    centerMode: false,

                                    infinite: true,

                                    slidesToShow: 3,

                                    slidesToScroll:3,

                                }

                            },

                            {

                                breakpoint: 1024,

                                settings: {

                                    dots: false,

                                    centerMode: false,

                                    infinite: true,

                                    slidesToShow: 2,

                                    slidesToScroll: 2,

                                }

                            },

                            {

                                breakpoint: 768,

                                settings: {

                                    dots: false,

                                    centerMode: true,

                                    centerPadding: '30px',

                                    arrows: false,

                                    slidesToShow: 1,

                                    slidesToScroll: 1,

                                    infinite: true

                                }   

                            }

                        ]

                    });

                }

            });

        }

    }



    initnewsSliderOf3();



    window.dispatchEvent(new Event('resize'));

}; //END HOMEPAGE LOAD



function loadPaginas() {

    if ($_debug) console.log("loadPaginas");



    if ($('.outras_paginas').length > 0) {

        if ($('#outrasPaginas').length > 0 && $('body').innerWidth() > 950) {

            $('#outrasPaginas').sticky({

                parent: '',

                parentWidth: false,

                fixed: 'all',

                fixedTil: '.paginas_cont',

                position: 'top',

                metaBase: '',

                metatags: false,

                /*anchors: '#outrasPaginas a',*/

                line: false,

            });



            setTimeout(function () {

                $('#outrasPaginas').sticky('stickyContainer');

            }, 500);

        }

    }



    if ($('.gallery_slick').length > 0) {

        $('.gallery_slick').each(function () {

            var $el = $(this);

            $el.slick({

                dots: false,

                slidesToShow: 1,

                arrows: true,

                prevArrow: $('.gallery_arrows.prev', $el.parent()),

                nextArrow: $('.gallery_arrows.next', $el.parent()),

                infinite: false,

                adaptiveHeight: true

            });

            // if($_debug) console.log('Inicia destaques');

        });

    }



    if ($('.slick-timeline').length > 0) {

        $('.slick-timeline').slick({

            dots: false,

            arrows: false,

            slidesToShow: 1,

            slidesToScroll: 1,

            infinite: true,

            adaptiveHeight: false,

            fullHeight: true,

            fade: true,

            cssEase: 'linear',

            asNavFor: '.slick-years',

        });



        if ($('.slick-years').length > 0) {

            $('.slick-years').slick({

                dots: false,

                slidesToShow: 7,

                slidesToScroll: 1,

                arrows: false,

                infinite: true,

                adaptiveHeight: false,

                focusOnSelect: true,

                asNavFor: '.slick-timeline',

                responsive: [

                    {

                        breakpoint: 950,

                        settings: {

                            slidesToShow: 8,

                        }

                    },

                    {

                        breakpoint: 750,

                        settings: {

                            slidesToShow: 6,

                        }

                    },

                    {

                        breakpoint: 550,

                        settings: {

                            slidesToShow: 4,

                            slidesToScroll: 4,

                        }

                    },

                    {

                        breakpoint: 350,

                        settings: {

                            slidesToShow: 3,

                            slidesToScroll: 3,

                        }

                    }

                ]

            });

        }

    }



    if ($(".paginas_container table").length > 0) $(".paginas_container table").wrap("<div class='table_overflow'></div>");

}; //END PAGINAS LOAD



function loadNoticias() {

    if ($_debug) console.log("loadNoticias");

}; //END NOTICIAS LOAD



function loadNoticiasDetail() {

    if ($_debug) console.log("loadNoticiasDetail");



    // if ($('#noticia_info').length > 0 && $('.slick-cont').length > 0 && $('body').innerWidth() > 950) {

    //     $('#noticia_info').sticky({

    //         parent: '',

    //         parentWidth: true,

    //         fixed: 'all',

    //         fixedTil: '.all_stick',

    //         position: 'top',

    //         metaBase: '',

    //         metatags: false,

    //         anchors: '',

    //         activeClass: '',

    //         line: false,

    //     });



    //     setTimeout(function () {

    //         $('#noticia_info').sticky('stickyContainer');

    //     }, 500);

    // }



    initSlider();

    $(window).resize(function (e) {

        initSlider();

    });



    function initSlider() {

        if ($('.slick-cont').length > 0) {

            //if ($('body').innerWidth() <= 950) {

                if (!$('.slick-cont').hasClass('slick-initialized')) {

                    $('.slick-cont').slick({

                        dots: true,

                        slidesToShow: 1,

                        slidesToScroll: 1,

                        arrows: false,

                        infinite: false,

                        adaptiveHeight: false,

                    });

                }

            // } else {

            //     if ($('.slick-cont').hasClass('slick-initialized')) $('.slick-cont').slick('unslick');

            // }

        }

    }

};



loadNoticiasDetail();



function loadContactos() {

    if ($_debug) console.log("loadContactos");



    if (!checkMobile()) {

        $('#map_box').addClass('scrolloff');

        $('.mapa').on('click', function () {

            $('#map_box').removeClass('scrolloff');

        });



        $(".mapa").mouseleave(function () {

            $('#map_box').addClass('scrolloff');

        });

    }



    /*initMapa();*/

};

function loadFaqs() {

    if ($_debug) console.log("loadFaqs");



    if ($('.faqs_cats').length > 0) {

        if ($('#faqsCats').length > 0 && $('body').innerWidth() > 950) {

            $('#faqsCats').sticky({

                parent: '',

                parentWidth: false,

                fixed: 'all',

                fixedTil: '.faqs_cont',

                position: 'top',

                metaBase: '',

                metatags: false,

                anchors: '#faqsCats a',

                line: false,

            });



            setTimeout(function () {

                $('#faqsCats').sticky('stickyContainer');

            }, 500);

        }

    }

};

function load404() {

    if ($_debug) console.log("load404");



    TweenMax.to($_headerMain, 0, {

        opacity: 0

    });

};

function loadPesq() {

    if ($_debug) console.log("loadPesq");



    if ($('#pesquisa').length > 0) {

        $('#pesquisa').productsList({

            divs: '.pesq_div',

            toggles: '',

            toFixed: '',

            filters: {

                parent: '',

                groups: 'pesq_inpt',

                elements: 'inpt',

                has_url: '',

                accordions: '',

            },

            urlBase: 'pesquisa.php',

            fileRpc: _includes_path + 'pesquisa-list.php',

            breadcrumbs: '',

            banners: '',

            navigation: false,

            threshold: {

                btn: 'ias_trigger',

                text: $recursos['pesq_carregar_mais'],

                limit: 5,

            },

            createUrl: true,

            loader: '',

            limit: 12,

            extraFields: [],

            callbacks: function () {

                if ($('#total_prods').length > 0) {

                    $('.pesquisa_tit').text($recursos['pesq_resultados'] + $('#total_prods').val());

                } else {

                    $('.pesquisa_tit').text('');

                }



                $('.pesq_termo').html($('#pesq_value').val());

                $('#pesquisa').highlight($('#pesq_value').val());

            },

            callbacksFiltros: function () {



            },

        });

    }

};



function loadProdutos() {

    if ($_debug) console.log("loadProdutos");



    if ($('#produtos').length > 0) {

        $('#produtos').productsList({

            divs: '.produtos_divs',

            toggles: {

                openBtn: '.filtersToggle',

                closeBtn: '.filtersHead',

                filterBtn: '.btnFilters',

                element: '.listings_filters',

            },

            toFixed: {

                parent: '.listings_filters_bg',

                element: '.listings_filters_content',

                breakpoint: 1150,

            },

            filters: {

                parent: '#filtros_rpc',

                groups: 'filters_divs',

                elements: 'loja_inpt',

                has_url: 'has-url',

                accordions: {

                    elements: 'hidden_filters',

                    buttons: 'hidden_filters_btn',

                    openTxt: $recursos['ver_todos'],

                    closeTxt: $recursos['ver_menos'],

                    limit: 10,

                },

            },

            urlBase: '',

            fileRpc: _includes_path + 'produtos-list.php',

            breadcrumbs: '.breadcrumbs',

            banners: '#banners',

            navigation: true,

            threshold: {

                btn: 'ias_trigger',

                limit: 5,

            },

            createUrl: true,

            loader: $('.listing_mask'),

            limit: 12,

            extraFields: [],

            callbacks: function () {

                init_fades();

                initLazyLoad('.listings_divs');

                

                if ($initAtive == 1) {

                    $initAtive = 0;



                    if ($($prod_rpc).attr('data-willopen')) {

                        var $elementOpen = $('a[href="' + $($prod_rpc).attr('data-willopen') + '"]');



                        if ($elementOpen.length > 0 && $elementOpen.attr('data-detail') == 1) {

                            $elementOpen.click();

                        }

                    }

                }

                setTimeout(function () {

                    $(".mainDiv").cart('initBtnClick');

                    set_product_count();

                    set_category_count();

                    //

                    //set_count_main_cate();

                    if ($('#produtos').hasClass('brand-active')) {

                        hide_filter_cate();   

                    }

                }, 300);

            },

            callbacksFiltros: function () {

                initWindows();

                //listing_count();

            },

        });

    }



    if ($('#sticked_filter').length > 0) {

        $('#sticked_filter').sticky({

            parent: '',

            appearAt: 70,

            fixed: '950',

            position: 'top',

        });

    }



    if ($('#listMask').length > 0) {

        $('#listMask').sticky({

            parent: '',

            fixed: 'all',

            position: 'top',

            metaBase: '',

            metatags: false,

            anchors: '',

            activeClass: '',

            line: false,

        });

    }

};



function hide_filter_cate() {

    var json_cate_list = $('#json_cate_list').val();

    var json_cate_list = json_cate_list.split(',');



    $('.cat-wrap .div_100').each(function(index, el) {

        $(this).find('.catewrap').each(function(index, el) {

            var check_catewrap = 0; 

            var sub_input_val = $(this).children('.sub-filters').find('input').val();

            $(this).children('.filters').next('.inner_cate').find('.sub-sub-filters').each(function(index, el) {

               var input_val = $(this).find('input').val();

               if (json_cate_list.includes(input_val) == false) {

                    $(this).hide();

               }else{

                    console.log(input_val);

                    console.log(sub_input_val);  

                    check_catewrap = check_catewrap + 1;

                    $(this).show();

               }

            });



            if (sub_input_val == 26) {

                console.log('dd-'+check_catewrap);

            }



            if (check_catewrap == 0) {

                $(this).hide();

                //$(this).children('.sub-filters').hide();

            }else{

                $(this).show();

                //$(this).children('.sub-filters').show();

            }



        });        

    });

    setTimeout(function() {

        $('#marcas_div').hide();

        $('#filt_div').hide();

    }, 400);

    $('#produtos').removeClass('brand-active');

}

 $('.brand-slider').slick({

    dots: false,

    slidesToShow: 7,

    slidesToScroll: 7,

    centerPadding: '100px',

    arrows: false,

    infinite: true,

    autoplay: true,

    adaptiveHeight: false,

    centerMode: false,

    responsive: [

        {

            breakpoint: 1440,

            settings: {

                dots: false,

                centerMode: false,

                centerPadding: '80px',

                infinite: true,

                slidesToShow: 5,

                slidesToShow: 5,

            }

        },

        {

            breakpoint: 950,

            settings: {

                dots: false,

                centerMode: false,

                centerPadding: '60px',

                infinite: true,

                slidesToShow: 2,

                slidesToScroll: 2,

            }

        },

        {

            breakpoint: 640,

            settings: {

                dots: false,

                centerMode: true,

                centerPadding: '30px',

                slidesToShow: 1,

                slidesToScroll: 1,

                infinite: true,

                variableWidth: true

            }   

        }

    ]

});



  $('.after_av').slick({

    dots: false,

    slidesToShow: 4,

    arrows: false,

    infinite: false,

    autoplay: false,

    adaptiveHeight: false,

    centerMode: false,

    responsive: [

        {

            breakpoint: 950,

            settings: {

                dots: false,

                centerMode: false,

                centerPadding: '60px',

                infinite: false,

                slidesToShow: 2,

                slidesToScroll: 2,

            }

        },

        {

            breakpoint: 640,

            settings: {

                dots: false,

                centerMode: false,

                centerPadding: '30px',

                slidesToShow: 2,

                slidesToScroll: 2,

                infinite: true,

                variableWidth: true

            }   

        }

    ]

});



 $('.info-mobile_view').slick({

    dots: false,

    infinite: false,

    speed: 300,

    slidesToShow: 1,

    //centerMode: true,

    //variableWidth: true,

    slidesToShow: 3,

    responsive: [

        {

            breakpoint: 768,

            settings: {

                slidesToShow: 2,

                variableWidth: true,

                centerMode: true,

            }   

        },

        {

            breakpoint: 640,

            settings: {

                slidesToShow: 1,

                variableWidth: true,

                centerMode: true,

            }   

        }

    ]

});









function loadProductDetail(type) {

    if ($_debug) console.log("loadProductDetail");



    initIframes();



    //CARREGA AS CARACTERISTICAS DO TAMANHO POR DEFEITO

    if ($(".detalhe_divs").length > 0) {

        $('.detalhe_divs').each(function (index, element) {

            if ($('input:checked', this).length > 0) {

                $('input:checked', this).trigger('click');

            }

            if ($('select', this).length > 0) {

                $('select', this).trigger('change');

            }

        });

    }



    if ($('[accordion]').not('[accordion-active]').length > 0) {

        $('[accordion]').not('[accordion-active]').accordion();

    }



    if ($('#desc_accordion').length > 0) {

        $('#desc_accordion').find('a').trigger('click');

    }







    if (type == "modal") {

        init_shares();

    } else {

        $('#div_imagem').lightGallery({

            hash: false,

            selector: '.item',

            loop: false,

            download: false,

            hideBarsDelay: 3000

        });





        if ($('.slick-imgs').length > 0) {

            $('.slick-imgs').slick({

                dots: false,

                slidesToShow: 1,

                slidesToScroll: 1,

                arrows: false,

                infinite: true,

                adaptiveHeight: false,

                asNavFor: '.slick-thumbs',

                responsive: [

                    {

                        breakpoint: 950,

                        settings: {

                            arrows: false,

                        }

                    }]

            });



            if ($('.slick-thumbs').length > 0) {

                $('.slick-thumbs').slick({

                    dots: false,

                    slidesToShow: 3,

                    slidesToScroll: 3,

                    arrows: true,

                    infinite: true,

                    adaptiveHeight: false,

                    focusOnSelect: true,

                    asNavFor: '.slick-imgs',

                    vertical: true,

                    verticalSwiping: true,

                    prevArrow: $('.produtos_arrows.prev'),

                    nextArrow: $('.produtos_arrows.next'),

                    responsive: [

                        {

                            breakpoint: 950,

                            settings: {

                                vertical: true,

                                verticalSwiping: true,

                                //slidesPerRow: 1,

                                slidesToShow: 3,

                            }

                        },

                        {

                            breakpoint: 750,

                            settings: {

                                vertical: false,

                                verticalSwiping: false,

                                arrows: false,

                                infinite: false,

                                slidesToShow: 3,

                                slidesToScroll: 1,

                            }

                        },

                    ]

                });

            }

        }





        if ($('.slick-relacionados').length > 0) {

            $('.slick-relacionados').slick({

                dots: false,

                slidesToShow: 4,

                slidesToScroll: 1,

                arrows: true,

                infinite: true,

                adaptiveHeight: false,

                arrows: true,

                prevArrow: $('.related_produtos_arrows.prev'),

                nextArrow: $('.related_produtos_arrows.next'),

                responsive: [

                    {

                        breakpoint: 1200,

                        settings: {

                            slidesToShow: 3,

                            slidesToScroll: 1,

                        }

                    },

                    {

                        breakpoint: 950,

                        settings: {

                            dots: false,

                            centerMode: true,

                            centerPadding: '60px',

                            infinite: true,

                            slidesToShow: 2,

                            slidesToScroll: 2,

                        }

                    },

                    {

                        breakpoint: 640,

                        settings: {

                            dots: false,

                            centerMode: false,

                            centerPadding: '30px',

                            slidesToShow: 1,

                            slidesToScroll: 1,

                            infinite: true,

                            variableWidth: true

                        }   

                    }

                ]

            });

        }

    }

};



// NAO TEM BLOG, APAGAR

// function loadBlog(){

//     if($_debug) console.log("loadBlog");



//     if($('#blog').length>0){

//         $('#blog').productsList({

//             divs: '.blog_divs',

//             toggles : {

//                 openBtn: '.filtersToggle',

//                 closeBtn: '.filtersHead',

//                 filterBtn: '.btnFilters',

//                 element: '.listings_filters',

//             },

//             toFixed : { 

//                 parent: '.listings_filters_bg',

//                 element: '.listings_filters_content',

//                 breakpoint: 1150,       

//             },

//             filters : {

//                 parent : '#filtros_rpc',

//                 groups : 'filters_divs',

//                 elements : 'loja_inpt',

//                 has_url : '',

//                 accordions: {

//                     elements: 'hidden_filters',

//                     buttons: 'hidden_filters_btn',

//                     openTxt: $recursos['ver_todos'],

//                     closeTxt: $recursos['ver_menos'],

//                     limit: 10,

//                 },

//             },

//             urlBase: 'index.php',

//             fileRpc: _includes_path+'blog-list.php',

//             breadcrumbs: '',

//             banners: '',

//             navigation: false,

//             threshold : {

//                 btn : 'ias_trigger',

//                 text : $recursos['carregar_mais'],

//                 limit : 5,

//             },

//             createUrl: true,

//             loader: $('.listing_mask'),

//             limit: 12,

//             extraFields: [],

//             callbacks: function(){

//                 init_fades();

//                 initLazyLoad('.listings_divs'); 

//             },

//             callbacksFiltros: function(){

//                 initWindows();

//             },

//         });

//     }



//     if($('#sticked_filter').length>0){

//         $('#sticked_filter').sticky({

//             parent: '',

//             appearAt: 70,

//             fixed: '950',

//             position: 'bottom',

//         });

//     }

// } //END BLOG LOAD



// NAO TEM BLOG, APAGAR

// function loadBlogDetalhes(){

//     if($_debug) console.log("loadBlogDetalhes");



//     if($('.gallery_slick').length>0){

//         $('.gallery_slick').slick({

//             dots:false,

//             slidesToShow:1,

//             slidesToScroll:1,

//             arrows:false,

//             infinite: false,

//             adaptiveHeight: false,

//             fade: true,

//             cssEase: 'linear',

//             autoplay: true,

//             autoplaySpeed: 4000,    

//         });

//     }



// } //END BLOG DETALHES LOAD





// NAO TEM PORTFOLIO, APAGAR

// function loadPortfolio(){

//     if($_debug) console.log("loadPortfolio");



//     $('#categorias').on('change', function(){

//         var categoria = $(this).val();

//         $.post(_includes_path+"rpc.php", {op:"carregaMarcas", categoria:categoria}, function(data){

//             $('#marca').html(data);

//             $('#modelo').html('<option value="0">'+$recursos["modelo"]+'</option>');



//             $('#marca').on('change', function(){

//                 var categoria = $('#categorias').val();

//                 var marca = $(this).val();

//                 $.post(_includes_path+"rpc.php", {op:"carregaModelos", categoria:categoria, marca:marca}, function(data){

//                     $('#modelo').html(data);

//                 });

//             });

//         });

//     });



//     if($('#portfolio').length>0){

//         $('#portfolio').productsList({

//             divs: '.portfolio_divs',

//             toggles : '',

//             toFixed : '',

//             filters : {

//                 parent : '',

//                 groups : 'inpt_holder',

//                 elements : 'inpt',

//                 has_url : '',

//                 accordions: '',

//             },

//             urlBase: 'portfolio.php',

//             fileRpc: _includes_path+'portfolio-list.php',

//             breadcrumbs: '',

//             banners: '',

//             navigation: false,

//             threshold : {

//                 class: 'subtitulos uppercase icon-arrow',

//                 btn : 'ias_trigger',

//                 text : $recursos['carregar_mais'],

//                 limit : 2,

//             },

//             createUrl: true,

//             loader: '',

//             limit: 4,

//             extraFields: [],

//             callbacks: function(){

//                 init_fades();

//                 initLazyLoad('#portfolio');



//                 $('.portfolio_divs').off('click.portfolio');

//                 $('.portfolio_divs').on('click.portfolio', function(){

//                     var id = $(this).attr('data-id');

//                     $.post(_includes_path+"portfolio-list.php", {op:"detalhe", id:id}, function(data){

//                         $('#modalPortfolio [ntgmodalBody]').html(data);

//                         init_fades();

//                         initLazyLoad('#modalPortfolio');



//                         if($('.portfolio_slick').length>0){

//                             $('.portfolio_slick').slick({

//                                 dots:false,

//                                 slidesToShow:1,

//                                 slidesToScroll:1,

//                                 arrows:false,

//                                 infinite: false,

//                                 adaptiveHeight: false,

//                                 fade: true,

//                                 cssEase: 'linear',

//                                 autoplay: true,

//                                 autoplaySpeed: 4000,    

//                             });

//                         }



//                         $('.portf_trigger').trigger('click');

//                     });

//                 });



//             },

//             callbacksFiltros: function(){



//             },

//         });

//     }

// };



/*******************************************************************************************

 **                                                                                       **

    =GENERAL FUNCTIONS, PLUGINGS CONTROL AND HELPERS

 **                                                                                       **

*********************************************************************************************/



/*-------------------------------------------------------------------------------------------

=LOADING FOR EACH PAGE

--------------------------------------------------------------------------------------------*/

function loadingTransition(state, f) {

    var detailDuration = 0.7;

    var $loading = $(".loading-transition")

    var $loadingImg = $(".loading-transition").find(".loading-animation");



    if (state == "start") {

        TweenLite.to($loading, detailDuration, {

            x: 0,

            ease: Expo.easeOut,

            onComplete: function () {

                $loading.addClass('loading');



                if ($_debug) console.log("loader started completed");

                if (typeof f == "function") f();

            }

        });

    }



    if (state == "finish") {

        TweenLite.to($loading, detailDuration, {

            x: "-100%",

            ease: Expo.easeIn,

            onComplete: function () {

                $loading.remove();

                var $loadingContainer = $('<div class="loading-transition"><div class="div_100 loading-animation"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 132.6 155.82"><defs></defs><title>logo_1</title><g id="Camada_2" data-name="Camada 2"><g id="Camada_1-2" data-name="Camada 1"><path style="fill:#fff; fill-rule:evenodd;" d="M66.3,0a66.3,66.3,0,0,1,66.3,66.3c0,48.82-50,81.78-63.26,88.32a15.46,15.46,0,0,1-3,1.2,15,15,0,0,1-3.06-1.2C49.9,147.92,0,113.85,0,66.3A66.3,66.3,0,0,1,66.3,0ZM42,82.33a33.22,33.22,0,0,0,3.69-11.05c.27-2.7-1.5-1.4-2.64-.59a30.88,30.88,0,0,0-8.6,10.88c-1.08,2.33-3.07,8.21-2.25,11,.68,2.22,4,2.22,5.66,2.07a35.57,35.57,0,0,0,10.76-3.27c2.4-1.16,3-4.45,4.15-6.92,1-2,2.17-4.57,3.51-7a33.2,33.2,0,0,1,4.35-6.39c1.54-1.64,3.56-3.22,6.09-3.3a5.93,5.93,0,0,1,6.23,5.8c.23,3-1.45,6.15-3.47,8.38-1,1.11-3.59,3.25-5.19,3.29a.68.68,0,0,1-.72-.65,5.14,5.14,0,0,1,.67-1.73,27.29,27.29,0,0,0,2.49-7.59c.06-.33.63-4.59,0-4.57-.21,0-.53.36-1,1.08-1.6,2.63-2.7,6.41-3.57,9.35a73,73,0,0,0-2.22,10.25c-.05.24,0,.43.24.19a77,77,0,0,1,7.33-6.42c1.25-.94,4.42-3.15,6-1.47,1,1.13.81,3,.66,4.34a28.12,28.12,0,0,0-.19,5.26,8.53,8.53,0,0,0,.28,1.52,32.67,32.67,0,0,1,1.48-5.55,27.64,27.64,0,0,1,6.12-10.14c2.21-2.22,7-6.19,10.3-4.22,2.57,1.54,2.69,5.62,2.07,8.37-.83,3.68-4.63,10.35-8.56,11.33a3.08,3.08,0,0,1-1.32,0c1.46,3.95,8.42-3.34,9.48-4.92a8.69,8.69,0,0,0,1.76-4.55c.34-2.06.26-7.83.6-13.35s1.15-10.84,3.19-12.16c4.23-2.73,4.9,7.73,5,9.83.06,1.22-.14,2-.52,2.25s-.73-.34-1.13-1.73c-.22-.76-.86-3.92-1.66-4a7.64,7.64,0,0,0,0,1.49c.22,2.17.52,4.62.88,7s.77,4.86,1.22,7c.92-.53,2.37-6.64,2.63-8a40.26,40.26,0,0,0,.78-7.89,39.65,39.65,0,0,0-4.16-17.71c-.36-.71-1.07-.9-1.92-.32A8.46,8.46,0,0,1,95,49.18a5.48,5.48,0,0,1-4.06-1.57l-.15-.15c-.12-.08-.25,0-.48.11l-.52.29c-2.39,1.32-3.49,1.57-4.89,1.59-1.9,0-2.85-1.59-2.85-4.77q0-.49,0-.8L82,43.81a34.74,34.74,0,0,1-3.15,3.3A13.74,13.74,0,0,1,76.23,49a4.83,4.83,0,0,1-2.23.64,4.57,4.57,0,0,1-3.42-1.39c-.58-.59-.81-2.2-1.84-1.35a10.33,10.33,0,0,1-6.57,2.63,5.21,5.21,0,0,1-5.07-3.87c-.14-.65-.66,0-.9.16a15.61,15.61,0,0,1-3.62,2.38,10.42,10.42,0,0,1-4.17,1c-1.94,0-4.77-.62-6.2-2s-.92,1.22-4.17,1.94c-6.68,1.49-8-2.94-10.21,5.89A40.55,40.55,0,0,0,27,72.32c.18,1.14,1.18-.18,2.08-.92,5-4.07,12.42-7.06,18.61-5.65a7.86,7.86,0,0,1,4.7,2.92c2.81,3.5,1.23,9-.48,12.21a17.34,17.34,0,0,1-9,8.11c-1.25.44-5,1.1-4.64-1.42a3,3,0,0,1,.94-1.48A19.8,19.8,0,0,0,42,82.33Zm65.52-3.6c1.29-.74,1.82-.77,2.1.17a6.23,6.23,0,0,1,.08,2.33,9,9,0,0,1-2.09,5.05A16.11,16.11,0,0,1,103.94,89c.1.49.21,1,.32,1.46a67.12,67.12,0,0,1,1.09,6.9c.29,2.85.39,5.68-.19,6.79-1.13,2.16-6.35,4.42-7.81,2.36-1-1.37-1-7.6-1.22-9.43-.17-1.53-1.42-1.62-3-.36A77.15,77.15,0,0,1,86.38,101c-2.78,1.43-6.39,2.15-9.2.48-.38-.22-.56-.24-.87,0A16,16,0,0,1,69.77,104c-3.75.28-5-3.63-5.09-6.45,0-.49.29-2.8,0-3C62,97.18,60.22,99,59.23,99.8c-3.56,3-6.4,4.39-8.33,4.25-2.13-.16-2.24-2.77-2.59-4.29-.17-.72-.79-1-2.23-.53-6.2,1.89-14.26,3.48-19.78-1.63a16.62,16.62,0,0,1-4.67-9.94,15.72,15.72,0,0,1,2.32-10.4c1-1.64.81-2.49.46-4.59a42.38,42.38,0,0,1,.91-18.22c1.6-5.82,4.13-11.88,5.82-17.9.61-2.18,2.85-11,4.87-12.34,1.21-.78,4.8-1.21,5.9-.52a1.23,1.23,0,0,1,.51,1.13,11.76,11.76,0,0,1-.67,2.88C39.85,33.6,37.15,39,35.34,45.11c1-.09,3-.34,4.74-.66s0-6.31,3.95-10.09a9.46,9.46,0,0,1,4.56-2.45c4.26-.74,8.77,1.15,10.07,5.51.27.9,1-.39,1.89-1.57a16,16,0,0,1,3.66-3.5,5.1,5.1,0,0,1,2.88-1h.07a3.21,3.21,0,0,1,2.23.81,2.62,2.62,0,0,1,.83,2,4.5,4.5,0,0,1-.64,2.23A5.83,5.83,0,0,1,68,38.28,3.28,3.28,0,0,1,66,39c-.38,0-.57-.13-.57-.4a1.52,1.52,0,0,1,.22-.65,5.71,5.71,0,0,0,.73-2.81c0-.53-.06-.8-.18-.8s-.41.22-.73.66a7.06,7.06,0,0,0-.8,1.57,22.44,22.44,0,0,0-.94,3,12.35,12.35,0,0,0-.37,2.72,4.33,4.33,0,0,0,.38,2,1.15,1.15,0,0,0,1.05.72c1.31,0,4.35-1.69,5.1-3.34a22.33,22.33,0,0,1,1.83-3.51A17.82,17.82,0,0,1,76.6,33.4a10.57,10.57,0,0,1,5.84-2,7.12,7.12,0,0,1,3.89,1,2.85,2.85,0,0,1,1.53,2.45,4.4,4.4,0,0,1-.76,2.3,2.29,2.29,0,0,1,1.26.21c.17.14.26.46.26,1a4.35,4.35,0,0,0-.09.76c-.09,1.22-.1,2.36-.1,3.42,0,.55,0,1,0,1.26a11.3,11.3,0,0,0,.1,1.6c.08.44.48.44,1.1.11a6.59,6.59,0,0,1-.27-1.95,16.3,16.3,0,0,1,.88-4.85,34.35,34.35,0,0,1,2.37-5.6A35.15,35.15,0,0,1,95.88,28a17.31,17.31,0,0,1,3.5-3.51,4,4,0,0,1,2.38-.79,2.92,2.92,0,0,1,1.8.62A3.93,3.93,0,0,1,104.83,26c1,2.53.12,5.6-.86,8a25,25,0,0,1-7.89,9.71,2,2,0,0,0,.1.26c.68,1.46,4.72-.84,6.09-.8,1.68.05,4.79,9.21,5.2,10.62a42.4,42.4,0,0,1,.83,20c-.11.56-1.77,5.47-.82,4.92Zm-7-47.75a17.71,17.71,0,0,0,.49-3.49c0-.22-.06-.33-.18-.33s-.54.44-1,1.32a17.81,17.81,0,0,0-1.24,2.68c-.71,2-1.26,3.67-1.64,5-.31,1.06-.55,2-.7,2.78,2-2.64,3.78-5.59,4.3-7.94ZM88.61,38.31h0ZM50.78,34.73c-.59-.47-1.07-.81-2,.23a8.61,8.61,0,0,0-1.58,3,10.71,10.71,0,0,0-.56,3.48,5.19,5.19,0,0,0,.95,3.25,3,3,0,0,0,2.46,1.23,3,3,0,0,0,2.47-1.45,5.58,5.58,0,0,0,1.05-3.38,6.83,6.83,0,0,0-.66-3A6.28,6.28,0,0,0,51,35.78c-.36-.28-.54-.49-.54-.61s.1-.26.3-.44ZM83.2,39.58l-.35.11a7.25,7.25,0,0,1-1.92.37c-.48,0-.72-.13-.72-.37a1.78,1.78,0,0,1,.3-.68,5.72,5.72,0,0,0,1-3,3.76,3.76,0,0,0-.28-1.46c-.18-.44-.38-.66-.61-.66s-.5.2-.9.63A17.07,17.07,0,0,0,77,38.38a7.83,7.83,0,0,0-1,3.5,3.14,3.14,0,0,0,.42,1.68,1.21,1.21,0,0,0,1,.66,4.66,4.66,0,0,0,2-1.14c1-.76,1.95-1.92,3.31-3l.5-.4-.08-.08Zm.94,54.18a9.33,9.33,0,0,0,1.71-2.07,17.76,17.76,0,0,0,2-8c0-.61,0-4.94-1.06-4.61-.54.16-1.18,1.62-1.93,4.37-.82,3-1.86,7.38-.73,10.26ZM66.47,6A59.92,59.92,0,1,1,6.54,65.91,59.92,59.92,0,0,1,66.47,6Z"/></g></g></svg></div></div>');

                $($loadingContainer).insertAfter('.mainDiv')

                if ($_debug) console.log("loader completed");



                if (typeof f == "function") f();

            }

        });

    }



    $_loaderState = state;

};



/*-------------------------------------------------------------------------------------------

=CLEARPAGE - CLEAR PAGES AFTER LOADING NEW CONTENT

--------------------------------------------------------------------------------------------*/

function clearPagesAfterloading(delay) {



    var $currentPage = $(".page-main.page-current"),

        $nextContent = $(".page-main.page-next"); // can't be global



    $.doTimeout(delay, function () {

        if ($_debug) console.log("clearPagesAfterloading");



        $currentPage.remove();

        $nextContent.removeClass("page-next panelRight").addClass("page-current").removeAttr("aria-hidden");



        var $newCurrentPage = $(".page-main.page-current");

        $newCurrentPage.after('<div class="page-main page-next" aria-hidden="true"></div>');

        $newCurrentPage.attr("style", "");



        onStartPageWhenRefresh(1);

    });

};



/*-------------------------------------------------------------------------------------------

=ONSTARTPAGE - HANDLE PAGE RELOADED

--------------------------------------------------------------------------------------------*/

function onStartPageWhenRefresh(byRefresh) {

    if ($_debug) console.log("onStartPageWhenRefresh");



    var timeRefresh = 0;



    var $element = 'body';



    hideHeaderFooter(0, 0);



    if (byRefresh == 1) {

        $(window).scrollTop(0);

        $("html,body").scrollTop(0);

        $element = '.page-main';

    } else {

        timeRefresh = 1250;

        $(".mask").fadeOut('slow', function () {

            $("html").addClass('doc-ready');



            initPesq()

            initCookies();



            initMenuMobile();

        });

    }



    startScripts($element, timeRefresh);

    onStartPage();



    $_body.removeClass("js-detail-open js-loading-page");



    globalAllowClick = 1;

}; //end onStartPageWhenRefresh



/*-------------------------------------------------------------------------------------------

=hideHeaderFooter - SHOW/HIDE HEADER & FOOTER, INIT STICKY

--------------------------------------------------------------------------------------------*/

function hideHeaderFooter(header, footer) {

    if ($_debug) console.log("hideHeaderFooter");



    if (typeof $('#header').data('sticky') != "undefined") {

        $('#header').sticky('destroy');

    }



    $('.header').removeClass('hidden');

    $('.footer').removeClass('hidden');

    $('.page-main').removeClass('hide-header hide-footer');



    if ($('.page-load').hasClass('hide-header') || header == 1) {

        $('.page-main').addClass('hide-header');

        $('.page-load').removeClass('hide-header');



        if (!$('.header').hasClass('hidden')) {

            $('.header').addClass('hidden');

        }

    }

    if ($('.page-load').hasClass('hide-footer') || footer == 1) {

        $('.page-main').addClass('hide-footer');

        $('.page-load').removeClass('hide-footer');



        if (!$('.footer').hasClass('hidden')) {

            $('.footer').addClass('hidden');

        }

    }



    if (!$('.page-load').hasClass('hide-header') && header != 1) {

        if ($('#header').length > 0) {

            $('#header').sticky({

                parent: '',

                fixed: '950',

                position: 'top',

                hideScroll: false,

                metaBase: '',

                metatags: false,

                anchors: '',

                shrinkAt: 300,

                activeClass: '',

                line: false,

            });



            $.doTimeout(500, function () {

                $('#header').sticky('stickyContainer');

            });

        }

    }

};



/*-------------------------------------------------------------------------------------------

=startScripts - HANDLE PAGE SCRIPTS

--------------------------------------------------------------------------------------------*/

function startScripts($element, timeRefresh) {

    if ($_debug) console.log("startScripts: " + $element);



    init_svg();

    initLazyLoad($element);

    init_shares();

    initVideo();

    init_inputs();

    initVoltarTopo();

    initEqualizer();

    maskInputs();



    initModals();

    initAccordions();

    initTooltips();

    initMultiSteps();

    initWindows();

    initCalendar();



    if ($('.header_menu_drop').length > 0) {

        escondeMenu();

    }



    $.doTimeout(timeRefresh, function () {

        init_fades();

        init_animation();

    });



    $.doTimeout(2350, function () {

        initFormValidator();

        init_captchas();

    });



    window.dispatchEvent(new Event('resize'));

};//end startScripts



/*-------------------------------------------------------------------------------------------

=STARTPAGE - EACH PAGE - call of functions and events

--------------------------------------------------------------------------------------------*/

function onStartPage() {

    if ($_debug) console.log("onStartPage");



    var $element = '.page-current';



    if ($($element + " .homepage").length > 0) loadHomepage();

    if ($($element + " .paginas").length > 0) loadPaginas();

    if ($($element + " .produtos").length > 0) loadProdutos();

    if ($($element + " .product-detail").length > 0) loadProductDetail();

    if ($($element + " .faqs").length > 0) loadFaqs();

    if ($($element + " .noticias").length > 0) loadNoticias();

    if ($($element + " .contactos").length > 0) loadContactos();



    //if ($($element + " .portfolio").length > 0) loadPortfolio();



    if ($($element + " .pesquisa").length > 0) loadPesq();

    // if($($element+" .blog_list").length>0) loadBlog(); 

    // if($($element+" .blog_detalhes").length>0) loadBlogDetalhes();



    if ($(".page-404").length > 0 || $(".manutencao").length > 0) load404();



}; //end StartPage



/*-------------------------------------------------------------------------------------------

=HANDLEMETAS - change metatags

--------------------------------------------------------------------------------------------*/

function handleMetas(is_product, elem, is_load) {

    /*-------------------------------------------------------------------------------------------

    =METATAGS - CHANGES METATAGS FOR EACH PAGE

    --------------------------------------------------------------------------------------------*/

    if ($_debug) console.log("altera metatags");



    var element = '.page-current';

    if (is_product == 1) {

        element = $el_ativate;

    }



    if ($(element + ' #meta_tit').length > 0) {

        var title = $(element + ' #meta_tit').val() + " | " + $_nomeSite;

        var description = $(element + ' #meta_desc').val();

        var keywords = $(element + ' #meta_key').val();

        var link = $(element + ' #meta_url').val();

        var image = $(element + ' #meta_img').val();

        var exists = 0;



        handleParams();



        if (typeof $(elem).attr("data-keepParams") != "undefined") {

            if ($_debug) console.log("Keeping params");



            link += "?" + $_urlParams;

        }



        if ($_forPopstate) {

            history.pushState({}, title, link);

            window.history.replaceState('Object', document.title, link);



            //atualizar os links das línguas

            if ($('.lang_cont').length > 0) {

                $('.lang_cont').each(function () {

                    var $elem = $(this);

                    var lang_act = $elem.attr('attr-lang');

                    if ($('.lang_link', $elem).length > 0 && link.indexOf("/" + lang_act + "/") > 0) {

                        $('.lang_link', $elem).each(function () {

                            var $elem_link = $(this);

                            var lang = $elem_link.attr('attr-lang');

                            var href_act = $elem_link.attr('href');



                            var res = link.replace("/" + lang_act + "/", "/" + lang + "/");



                            if (href_act.indexOf("?") > 0) {

                                href_act = href_act.split("?");

                                res = res + "?" + href_act[1];

                            }



                            $elem_link.attr('href', res);

                        });

                    }

                });

            }

        }



        if ($('input[name="titulo_pag"]').length > 0) {

            $('input[name="titulo_pag"]').val($(element + ' #meta_tit').val());

        }



        document.title = title;

        $('meta[name=description]').attr('content', description);

        $('meta[name=keywords]').attr('content', keywords);



        url_old = window.location.href;



        if ($(element + ' #menu_sel').length > 0) {

            var data_sel = $(element + ' #menu_sel').val();

            $('a[data-sel]').removeClass('active');

            $('a[data-sel="' + data_sel + '"]').addClass('active');



        }



        if ($_gaActive && is_load == 0) {

            if (typeof ga != 'undefined') {

                var d = location.pathname;

                ga('set', { page: d, title: title });

                ga('send', 'pageview');

            }

        }



        $_forPopstate = 1;

    } else {

        handleParams();

    }

};

function handleParams() {

    if ($_urlParams) {

        if ($_loaderState == "finish" && $('.mask').css('display') == "none") {

            clearInterval($_loaderTimeout);

            if ($_debug) console.log("handleParams: " + $_urlParams);



            if ($_urlParams.indexOf("&") > 0) {

                var all_params = $_urlParams.split("&");

                all_params.forEach(function (param) {

                    var paramSplited = param.split("=");

                    if (paramSplited[0] == "anchor") {

                        $('html').stop(true, true).animate({

                            scrollTop: $('#' + paramSplited[1]).offset().top

                        }, 1500, 'easeInOutExpo', function () { });

                    }

                });

            } else {

                var paramSplited = $_urlParams.split("=");

                if (paramSplited[0] == "anchor") {

                    $('html').stop(true, true).animate({

                        scrollTop: $('#' + paramSplited[1]).offset().top

                    }, 1500, 'easeInOutExpo', function () { });

                }

            }

        } else {

            clearInterval($_loaderTimeout);

            $_loaderTimeout = setInterval(function () {

                handleParams();

            }, 100);

        }

    }

};





/*-------------------------------------------------------------------------------------------

=HANDLE MENU EFFECT - NETGOCIO

--------------------------------------------------------------------------------------------*/

function initMenuMobile() {

    if ($('nav#menu').length > 0) {

        $(".menu_holder").on("click", function () {

            triggerMobileMenu();

        });



        if ($('nav#menu .has-sub').length > 0) {

            $(".has-sub > a").on("click", function () {

                $(this).parent().toggleClass('is-active');

                $(this).next('ul').slideToggle('slow');

            });

        }

        if ($('.footer h3').length > 0) {

            $(".footer h3").on("click", function () {

                if ($(this).hasClass('active')) {

                    $(this).toggleClass('active');

                    $(this).next('ul').slideToggle('slow');

                } else {

                    $(".footer h3.active").removeClass('active');

                    $(".footer h3.active").next('ul').slideUp('slow');



                    $(this).toggleClass('active');

                    $(this).next('ul').slideToggle('slow');

                }



            });

        }

        $('body').on('click', function (e) {

            'use strict';

            if ($(e.target).closest('.menu_holder').length == 0 && $(e.target).closest('nav#menu').length == 0) {

                if ($('nav#menu').hasClass('menu-opened')) triggerMobileMenu();

            }

        });



    }

}

function triggerMobileMenu() {

    if ($('nav#menu').length > 0) {

        if ($('.off-canvas').length > 0) {

            $('.off-canvas').toggleClass('hidden');

        }

        if ($('.search').length > 0) {

            $('.search').toggleClass('hidden');

        }

        if ($('.testimonials-all.is-visible').length > 0) {

            $('.testimonials-all.is-visible').toggleClass('hidden');

        }



        if ($('[ntgmodal]').length > 0 && $('[ntgmodal][ntgmodal-opened]').length > 0) {

            $('[ntgmodal][ntgmodal-opened]').ntgmodal('closeModal');

        }



        $('body').toggleClass('overHidden');

        $('.mainDiv').toggleClass('has-overlay');

        $('nav#menu').toggleClass('menu-opened');



        setTimeout(function () {

            $(".menu_holder").toggleClass("active");

        }, 100);

    }

}



/*-------------------------------------------------------------------------------------------

=HANDLE SEARCH EFFECT - NETGOCIO

--------------------------------------------------------------------------------------------*/

function initPesq() {

    if ($('.search').length > 0 || $('.search_form').length > 0) {

        if ($_debug) console.log("initPesq");



        if ($('.search-trigger').length > 0) {

            $('.search-trigger').on('click', function () {

                if ($_debug) console.log("open pesq");

                var timer = 0;



                if ($('.search').hasClass('scaled')) {

                    timer = 350;

                    $('.page-load').addClass('page-load-hide');

                    $('.detail-ajax').addClass('page-load-hide');

                    $('.header').addClass('page-load-hide');

                    $('.footer').addClass('page-load-hide');

                }



                if ($('nav#menu').length > 0 && $('nav#menu').hasClass('menu-opened')) {

                    triggerMobileMenu();

                    timer = 150;

                }



                if ($('[ntgmodal]').length > 0 && $('[ntgmodal][ntgmodal-opened]').length > 0) {

                    $('[ntgmodal][ntgmodal-opened]').ntgmodal('closeModal');

                }



                $.doTimeout(timer, function () {

                    $('.search').addClass('search-open');

                    $('body').addClass('overHidden');



                    window.dispatchEvent(new Event('resize'));

                });

            });

        }



        if ($('.search_suggestion a').length > 0) {

            $('.search_suggestion a').on('click', function () {

                closeSearch();

            });

        }



        if ($('.search_form').length > 0) {

            $('.search_form:visible').on('submit', function () {

                var valid = validaForm($(this).attr('id'), 1, 1);

                if (!valid) return false;



                $('.search').addClass('search-loading');



                var $link = $('.search-submit');



                if ($link.attr('data-ajaxUrl')) {

                    var searchVal = _includes_path + "pages/produtos.php?search=" + $('.search .search_input').val();

                    $link.attr('data-ajaxUrl', searchVal);

                    $link.click();

                    closeSearch();



                    return false;

                }

            });

        }



        $('input[name="search"]:visible').on('blur keyup', function (event) {

            if ($(this).val().length >= 1) {

                $(this).addClass('filled');

            } else {

                $(this).removeClass('filled');

            }

        });

        if ($('input[name="search"]:visible').hasClass('autocomplete')) {

            $('input[name="search"]:visible').on('blur keyup', function (event) {

                if (event.which != 27) {

                    clearTimeout(_timePesq);

                    var $this = $(this);



                    if ($this.val().length >= 2) {

                        $('.header_menu_drop').stop(true, true).slideUp('slow').removeClass('active').removeAttr('data-open');



                        _timePesq = window.setTimeout(function () {

                            $.post(_includes_path + "rpc.php", { op: "carrega_menu_pesquisa", search: $this.val() }, function (data) {

                                if ($('.header_pesq_drop').css('display') != "none") {

                                    $('.header_pesq_drop').removeClass('opened');

                                }



                                $('body').addClass('overHidden');

                                $('.mainDiv').addClass('has-overlay');



                                if ($('.header_pesq_drop').css('display') == "none") {

                                    $('.header_pesq_drop').html(data);



                                    $('.header_pesq_drop').addClass('opened');

                                    $('.header_pesq_drop').css('height', "auto");



                                    $('.header_pesq_drop').slideDown('slow', function () {

                                        setMenuHeight($('.header_pesq_drop'), 620, 0);

                                        $('.header_pesq_drop').highlight($this.val());

                                    });

                                } else {

                                    setTimeout(function () {

                                        $('.header_pesq_drop').html(data);



                                        $('.header_pesq_drop').css('height', "auto");

                                        setMenuHeight($('.header_pesq_drop'), 620, 0);

                                        $('.header_pesq_drop').addClass('opened');

                                    }, 500);

                                }

                            });

                        }, 350);

                    } else {

                        closeSearchAutocomplete();

                    }

                } else {

                    closeSearchAutocomplete();

                }

            });



            if ($('.search-closer').length > 0) {

                $('.search-closer').on('click', function () {

                    $('input[name="search"]:visible').val('');

                    $('input[name="search"]:visible').removeClass('filled');

                    closeSearchAutocomplete();

                });

            }



            function closeSearchAutocomplete() {

                if ($('.header_pesq_drop').css('display') != "none") {

                    $('body').removeClass('overHidden');

                    $('.mainDiv').removeClass('has-overlay');



                    $('.header_pesq_drop').slideUp('slow').removeClass('opened');

                }

            }

        }

    }

};

function closeSearch() {

    if ($('.search').length > 0 && $('.search').hasClass('search-open')) {

        if ($_debug) console.log("close pesq");



        if ($('.search').hasClass('scaled')) {

            $('.page-load').removeClass('page-load-hide');

            $('.detail-ajax').removeClass('page-load-hide');

            $('.header').removeClass('page-load-hide');

            $('.footer').removeClass('page-load-hide');

        }



        $('.search').removeClass('search-open');

        $('body').removeClass('overHidden');



        $('.search').removeClass('search-loading');



        $('.search .search_input').blur();

        $('.search .search_input').val('');





        if ($('.header_pesq_drop').length > 0 && $('.header_pesq_drop').hasClass('active')) {

            $('.header_pesq_drop').slideUp('slow').removeClass('active');

            $('body').removeClass('overHidden');

            $('.mainDiv').removeClass('has-overlay');

        }



        if ($('.search_form').length > 0 && $('.search_form').hasClass('active')) {

            $('.search_form').removeClass('active');

        }



        if (($('.header_pesq_drop').length > 0 && $('.header_pesq_drop').hasClass('active')) || ($('.search_form').length > 0 && $('.search_form').hasClass('active'))) {

            $('input[name="search"]').val('');

            clearTimeout(_timePesq);

        }

    }

};



/*-------------------------------------------------------------------------------------------

=MODAL DETAIL ANIMATION - NETGOCIO

--------------------------------------------------------------------------------------------*/

function toogleModalDetail() {

    if ($_debug) console.log("toogleModalDetail");



    //var detailDuration = 1e3;

    var detailDuration = 0.5;



    var detailAnimation = new TimelineMax({ paused: false, repeat: 0 });



    if (!modalLoaded) {

        var detailOrientation;

        var detailSize = "200";

        var animeValue = "";



        if ($modalIntro.hasClass('is-shape-bg')) {

            detailSize = "100";

        }



        if ($modalIntro.hasClass('horizontal')) {

            detailOrientation = "vw";

            animeValue = detailSize + detailOrientation;



            detailAnimation.to($modalIntro, detailDuration, {

                transform: 'translateX(-' + animeValue + ')',

                force3D: true,

                ease: Power1.easeOut,

                onComplete: function () {

                    $(this.target).css('transform', this.vars.css.transform);

                }

            });

        } else {

            detailOrientation = "vh";

            animeValue = detailSize + detailOrientation;



            detailAnimation.to($modalIntro, detailDuration, {

                transform: 'translateY(-' + animeValue + ')',

                force3D: true,

                ease: Power1.easeOut,

                onComplete: function () {

                    $(this.target).css('transform', this.vars.css.transform);

                }

            });

        }



        if (!$modalIntro.hasClass('is-shape-bg')) {

            detailAnimation.fromTo($modalShape, 0.55,

                { scaleY: "0.8, 1.8" },

                { scaleY: 1 }

            );



            detailAnimation.to($modalPath, detailDuration, {

                ease: Power1.easeOut,

                attr: { points: $modalPath[0].getAttribute('pathdata:id') }

            });

        } else {

            $.doTimeout(detailDuration + 800, function () {

                if ($modalIntro.hasClass('horizontal')) {

                    animeValue = (detailSize * 2);

                    detailAnimation.to($modalIntro, detailDuration, {

                        transform: 'translate3d(-' + animeValue + 'vw, 0, 0)',

                        force3D: true,

                        ease: Power1.easeOut,

                        onComplete: function () {

                            $(this.target).css('transform', this.vars.css.transform);

                        }

                    });

                } else {

                    animeValue = (detailSize * 2) + detailOrientation;

                    detailAnimation.to($modalIntro, detailDuration, {

                        transform: 'translateY(-' + animeValue + ')',

                        force3D: true,

                        ease: Power1.easeOut,

                        onComplete: function () {

                            $(this.target).css('transform', this.vars.css.transform);

                        }

                    });

                }

            });

        }



        $modalIntro.addClass('active');

        modalLoaded = true;

    } else {

        detailAnimation.to($modalIntro, 1, {

            opacity: 0,

            onComplete: function () {

                $_body.removeClass("js-detail-open js-loading-page");

                $modalIntro.attr("style", "");

                $($noticias_rpc).html("").removeClass('active');

                $($prod_rpc).html("").removeClass('active');

                globalAllowClick = 1;



                if ($modalIntro.hasClass('horizontal')) {

                    TweenLite.set($modalIntro, {

                        transform: 'translateX(100vw)',

                        force3D: true,

                        onComplete: function () {

                            $(this.target).css('transform', this.vars.css.transform);

                        }

                    });

                } else {

                    TweenLite.set($modalIntro, {

                        transform: 'translateY(100vh)',

                        force3D: true,

                        onComplete: function () {

                            $(this.target).css('transform', this.vars.css.transform);

                        }

                    });

                }

            }

        });



        $modalIntro.removeClass('active');

        modalLoaded = false;

    }

};

function whenDetailsOpen(delay) {

    $.doTimeout(delay, function () {

        if ($_debug) console.log("whenDetailsOpen");



        globalAllowClick = 1;

        $_body.removeClass("js-loading-page");



        var $element;

        if ($('.detail-ajax.active').length > 0) {

            $element = $('.detail-ajax.active');

        }



        startScripts($element, 550);



        $(".detail-ajax.active").scrollTop(0);



        closeSearch();

    });

};

function closeAllDetails() {

    if ($($el_ativate).hasClass('active')) {

        if ($_debug) console.log("closeAllDetails");



        if ($_body.hasClass("js-detail-open")) {

            $_body.removeClass("js-detail-open");

        }

        if ($($prod_rpc).hasClass('active')) {

            loadPages('', 'close-produtos-detail', '');

        }

        if ($($noticias_rpc).hasClass('active')) {

            loadPages('', 'close-noticias-detail', '');

        }

    }

};

/*******************************************************************************************

 ****                                                                                   ****

    =CLICK BINDING - NETGOCIO

 ****                                                                                   ****

*********************************************************************************************/

function initRemote() {

    if ($_debug) console.log("initRemote");



    $('body').on("click", 'a[data-remote="true"]', function (event) {

        event.preventDefault();

        var $this = $(this);



        if ($_debug) console.log("remote link: " + $this.attr("data-ajaxUrl"));



        //HANDLE CLOSE ELEMENTS

        if ($('nav#menu').length > 0 && $('nav#menu').hasClass('menu-opened')) {

            triggerMobileMenu();

        }



        if ($('[ntgmodal]').length > 0 && $('[ntgmodal][ntgmodal-opened]').length > 0) {

            $('[ntgmodal][ntgmodal-opened]').ntgmodal('closeModal');

        }



        //HANDLE URL

        var ajaxUrl = $this.attr("data-ajaxUrl"),

            ajaxUrlParams = $this.attr("data-ajaxUrl"),

            ajaxParams;



        if ($this.attr("data-ajaxUrl").indexOf("?") > 0 && !$(this).attr('data-detail')) {

            if ($_debug) console.log("found params on remote btn: " + $_urlParams);



            var ajaxUrlField = $this.attr("data-ajaxUrl").split("?");

            ajaxUrlParams = ajaxUrlField[0];

            $_urlParams = ajaxUrlField[1];

        } else {

            $_urlParams = "";

        }



        if ($this.attr('data-ajaxTax')) {

            if ($_debug) console.log("found taxId on remote btn: " + $this.attr('data-ajaxTax'));



            if (ajaxUrl.indexOf("?") > 0) {

                ajaxUrl += "&id=" + $this.attr('data-ajaxTax');

            } else {

                ajaxUrl += "?id=" + $this.attr('data-ajaxTax');

            }

        }



        if ((($current_page != ajaxUrlParams && $current_taxid != $this.attr('data-ajaxTax')) || ($current_page == ajaxUrlParams && $current_taxid != $this.attr('data-ajaxTax')) || ($current_page != ajaxUrlParams && $current_taxid == $this.attr('data-ajaxTax'))) || (typeof $this.attr("data-pageTrans") != "undefined" && $this.attr("data-pageTrans").indexOf('close-') > -1) || $_urlParams.indexOf('search') > -1 || $current_page.indexOf('produtos.php') > -1) {

            if ($_debug) console.log("btn remote clicked");



            if ($this.attr('data-detail') != 1) {

                $('a[data-remote="true"]').removeClass('current');

                $('a[data-detail="1"]').removeClass('current');

                $this.addClass('current');

                $current_page = ajaxUrlParams;

                if ($this.attr('data-ajaxTax')) $current_taxid = $this.attr('data-ajaxTax');

            }



            if (!globalAllowClick) {

                if ($_debug) console.log("globalAllowClick is 0, exit");

                return false; // exit and have normal click

            } else {

                $_body.addClass("js-loading-page");



                if ($this.attr('data-detail') == 1) {

                    $_body.addClass("js-detail-open");

                }



                if ($this.attr('data-product')) {

                    if ($_debug) console.log("btn prod clicked");



                    $(".product-active").removeClass('product-active');

                    $this.addClass("product-active");



                    if ($this.data('parent')) {

                        var parent = $this.data('parent');

                        $this = $this.parents(parent);

                    }



                    if ($this.find(".product-loader").length > 0) {

                        var $prodLoader = $this.find(".product-loader");

                        $prodLoader.addClass("active");

                        TweenLite.to($prodLoader, .5, {

                            autoAlpha: 1

                        });

                    }



                    if (!$($prod_rpc).hasClass('active')) {

                        if (!$this.data("image-loaded") && $_animationType == 1 && globalAllowClick == 1) {

                            $this.addClass('disabled');



                            $.doTimeout(350, function () {

                                var img_path = $this.find(".productImg").attr('data-big');

                                var $bigImage = $("<div class='product-dummy-image has_bg contain'></div>");



                                //$bigImage.imagesLoaded({background: true}, function() {

                                if ($_debug) console.log("dummy image loaded");



                                var offset_top = ($this.find(".productImg").offset().top - $(window).scrollTop()),

                                    offset_left = $this.find(".productImg").offset().left,

                                    product_width_for_small = $this.find(".productImg").width(),

                                    product_height_for_small = $this.find(".productImg").height();



                                $bigImage.css({

                                    position: "fixed",

                                    top: offset_top + "px",

                                    left: offset_left + "px",

                                    width: product_width_for_small + "px",

                                    height: product_height_for_small + "px",

                                    "backgroundImage": "url('" + img_path + "')",

                                    opacity: "1",

                                    "z-index": "1002"

                                });



                                $('.mainDiv').append($bigImage);



                                $this.find(".productImg").addClass("js-productImg");

                                $this.data("image-loaded", "true");



                                if ($_debug) console.log("dummy image appended");

                            });

                            //});

                        }

                    }

                }



                var pageTrans = $this.attr("data-pageTrans")

                if ($this.attr("data-ajaxUrl").indexOf('area-reservada') != "-1") {

                    pageTrans = "panelRight";

                }



                loadPages(ajaxUrl, pageTrans, $this);

                globalAllowClick = 0;

            }

        } else {

            if ($_debug) {

                console.log($current_page + "!=" + ajaxUrlParams);

                console.log($current_taxid + "!=" + $this.attr('data-ajaxTax'));

                console.log($current_page.indexOf('produtos.php'));

            }



            handleMetas(0, $this, 0);

        }

    });

};

function lookForActive() {

    var path = document.location.href.substr(document.location.href.lastIndexOf('/') + 1);



    if (path) {

        if ($_debug) console.log("looking for active link: " + path);



        var $element = $('a[data-remote="true"][data-ajaxurl*="' + path + '"]');

        var $elementOpen = $('a[data-remote="true"][href*="' + path + '"]');



        if ($element.length <= 0) $element = $('a[data-sel*="' + path + '"]');



        if ($element.length > 0 || ($elementOpen.length > 0 && $elementOpen.attr('data-detail') == 1)) {

            if ($element.length > 0) {

                if ($_debug) console.log("found active");



                if ($element.attr("data-ajaxUrl")) {

                    $current_page = $element.attr("data-ajaxUrl");

                } else {

                    $current_page = $element.attr("href");

                }



                if ($current_page.indexOf("?") > 0) {

                    var ajaxUrlField = $current_page.split("?");

                    $current_page = ajaxUrlField[0];

                }



                $element.addClass('current');



                handleMetas(0, $element, 1);

                return;

            }



            if ($element.length == 0 && $elementOpen.length > 0 && $elementOpen.attr('data-detail') == 1) {

                if ($_debug) console.log("found active 2");

                $elementOpen.addClass('current');

                $elementOpen.click();

                return;

            }

        } else {

            handleMetas(0, 0, 1);

        }

    } else {

        handleMetas(0, 0, 1);

    }

};



/*******************************************************************************************

****                                                                                   ****

    =DOCUMENT =READY =START Document ready

****                                                                                   ****

*********************************************************************************************/

var $initAtive = 1,

    $extensao = "",

    $recursos = "",

    $_nomeSite = $('meta[name=site_name]').attr('content'),

    $_window = $(window),

    $_body = $("body"),

    $_html = $("html"),

    $_headerMain = $(".header"),

    $_footerMain = $(".footer"),

    _dateFormat = "DD/MM/YYYY", // sempre em maiusculas

    $_urlParams = '',

    $_gaActive = 1,

    $_debug = 1,

    $_forPopstate = 0,

    $_animationType = 2,

    $_cartType = 1, // 1 = carrinho em cima, 2 = carrinho em baixo

    $current_page = "",

    $current_taxid = "",

    $_loaderState = "finish",

    $_loaderTimeout = 0,

    _loaderTimer = 0,

    _timePesq = 0,

    url_old = '',



    $noticias_rpc = "#noticias-detail-ajax",

    $prod_rpc = "#produtos-detail-ajax",

    $el_ativate = "#details-modal",



    modalLoaded = false,

    $modalIntro = $($el_ativate),

    $modalShape = $('svg.shape', $modalIntro),

    $modalPath = $('path', $modalShape),

    $modalBg = $('shape-bg', $modalIntro),



    globalAllowClick = 0; //When loading do not allow clicks by user ( onStartPage revers to true);   





//Retirado document.addEventListener('DOMContentLoaded' apresenta problemas no safari

window.onload = function () {

    if ($_debug) console.log("doc ready");



    FastClick.attach(document.body);



    // //por causa do linguas criei este script aqui

    initialScripts();



    var params = document.location.href.substr(document.location.href.lastIndexOf('/') + 1);

    if (params.indexOf('?') > 0) {

        params = params.split('?');

        $_urlParams = params[1];

    }



    onStartPageWhenRefresh(0);



    //** =INIT CLCKS

    initRemote();



    //** =LOOK FOR ACTIVE

    lookForActive();



    /*if ('serviceWorker' in navigator) {

        navigator.serviceWorker.register('service-worker.js');

    }*/



    // HISTORY

    //  note: Chrome and Safari will fire a popstate event when the page loads but Firefox doesnt. When your page loads, it might have a non-null state object and the page will receive an onload event, but no popstate event. (window.history.state; on refresh page)

    if (window.addEventListener) {

        window.addEventListener("popstate", function (e) {// firefox vs webkit and safari trigers on

            if ($_debug) console.log("entrou no popstate");

            if (!$_html.hasClass("mobile")) {

                if (e.state || url_old != window.location.href) {

                    $_forPopstate = 0;

                    $.doTimeout(500, function () {

                        window.location = window.location;

                    });

                } else {

                    $_forPopstate = 1;

                }

            }

        });//endif: does not excute for <= IE8

    }



    /*-------------------------------------------------------------------------------------------

    =KEYS

    --------------------------------------------------------------------------------------------*/

    $(document).on("keydown", function (event) {

        switch (event.which) {

            case 40: //down

                //return false;

                break;

            case 38: //up

                // return false;

                break;

            case 13: // enter

                //return false;

                break;

            case 39: //next

                // return false;

                break;

            case 37: //prev

                // return false;

                break;

            case 27:

                if ($($el_ativate).hasClass('active')) {

                    if ($($prod_rpc).hasClass('active') && $_animationType == 2) {

                        loadPages('', 'close-produtos-detail', '');

                    }

                    if ($($noticias_rpc).hasClass('active')) {

                        loadPages('', 'close-noticias-detail', '');

                    }

                }



                if ($('nav#menu').length > 0 && $('nav#menu').hasClass('menu-opened')) {

                    triggerMobileMenu();

                }



                closeSearch();



                return;

        }

    }); //end keypress

}; //end function





/*! -------------------------------------------------------------------------------------------

JAVASCRIPT specific engine!

--------------------------------------------------------------------------------------------*/

$(document).ready(function () {



    initialScripts();



    // //BROWSER

    if ($('body').innerWidth() > 900) {

        //call plugin function after DOM ready

        outdatedBrowser({

            lowerThan: 'Edge',

            languagePath: _server_hostname + 'linguas/lang/' + $extensao.substring(1) + '.html'

        });

    }



    var cart_position = "cart-top";

    var cart_appendTo = ".header";



    if ($_cartType == 2) {

        cart_position = "cart-bottom";

        cart_appendTo = "";

    }



    if ($('#cart-trigger').length > 0 || $('.cart-btn').length > 0) {

        $(".mainDiv").cart({

            parent: 'body',

            class: 'cart',

            linkCart: 'carrinho.php',

            fileRpc: _includes_path + 'carrinho-rpc.php',

            position: cart_position,

            trigger: '.cart-btn',

            appendTo: cart_appendTo,

            menu_sel: $current_page,

            hasOverflow: 1,

            modal: {

                openModal: 1,

                action: '.modal-action',

            },

            animation: 2,

            breakpoint: 950,

            showPrices: true,

            elements: {

                divs: '.produtos_divs',

                image: '.productImg',

                linker: '.produtos_link',

                action: '.action',

                detalheBtn: '.detalhe_adiciona',

                detalheImg: '.product-detail-img .slick-current',

            },

            texts: {

                //ECCOMMERCE

                header: $recursos["carrinho"],

                footer: $recursos["ir_carrinho"] + ' - ',

                onAdd: $recursos["stock_zero"],

                delTitle: $recursos["car_remover_artigo"],

                delMsg: $recursos["car_remover_artigo_txt"],

                selecione: $recursos["selecione"],

                sucesso: $recursos["artigo_adicionado_suc"],

                plano: $recursos["selecione_plano2"],

                //PEDIR ORÇAMENTO

                // header: $recursos["solicitar_contacto"],

                // footer: $recursos["carrinho_contacto2"],

                // onAdd: $recursos["stock_zero"],

                // delTitle: $recursos["car_remover_artigo"],

                // delMsg: $recursos["car_remover_artigo_txt"],

                // selecione: $recursos["selecione"],

                // sucesso: $recursos["artigo_adicionado_suc"],

                ok: $recursos["car_confimar"],

                cancel: $recursos["car_cancelar"]

            },

            modalCallbacks: function () {

                loadProductDetail('modal');

            },

        });

    }

    // if($('.morphArea_content').length>0){

    //     $('.morphArea_content').each(function(index, element){

    //         new UIMorphingArea(element);

    //     });

    // }



    /* PRODUTOS: para os produtos que tenham um tamanho por defeito, simular o click para mostrar as outras opções */

    if ($('[data-attr="carrega_atributos"]').length > 0) {

        $('select[data-attr="carrega_atributos"]').trigger('change');

        $('input[data-attr="carrega_atributos"]').click();

    }

});



/*Scriprs que necessitam de correr antes de todos os outros -> Tiago*/

function initialScripts() {

    // carregar linguas

    $.ajax({

        data: { op: "fetchLang", what: "all" },

        type: "post",

        async: false,

        cache: false,

        url: _includes_path + "rpc.php",

        success: function (response) {

            $recursos = jQuery.parseJSON(response);

        },

        error: function (response) {

            console.log(response);

        }

    });

    $extensao = $recursos['extensao'];

}



/**

 * Function call when user change lang or coin 

 * HEADER - MODALLINGUAS

 */

function changeSettings() {

    $.post(_includes_path + "carrinho-rpc.php", { op: "mudaMoeda", currency: $('#h_moeda').val() }, function () {

        if ($('#h_lingua').length > 0) {

            document.location = $('#h_lingua').val();

        } else {

            document.location = $('#h_lingua_hidden').val();

        }

    });

}







// Product details page tabes



$(document).ready(function(){

    

    $('ul.tabs li').click(function(){

        event.preventDefault();

        var tab_id = $(this).attr('data-tab');



        $('ul.tabs li').removeClass('current');

        $('.tab-content').removeClass('current');



        $(this).addClass('current');

        $("#"+tab_id).addClass('current');

    })



});





jQuery(document).ready(function($) {

        

    $('.rating-plug').find('a').click(function(event) {

        event.preventDefault();



        var data_rate = $(this).attr('data-rate');

        $('#user_rating').val(data_rate);

        

        $(this).addClass('active')

        $(this).siblings('a').removeClass('active');



        $(this).find('i').addClass('fa-star');

        $(this).find('i').removeClass('fa-star-o');



        $(this).nextAll('a').find('i').addClass('fa-star-o');

        $(this).nextAll('a').find('i').removeClass('fa-star');



        $(this).prevAll('a').find('i').addClass('fa-star');

        $(this).prevAll('a').find('i').removeClass('fa-star-o');

    });





    $("#user-rating-form").validate({

        rules: {

            user_rating: "required",

            user_rating_comment: {

                required: true,

            }

        },

        submitHandler: function(form)

        {   

            console.log(_includes_path);

            var user_rating = $('#user_rating').val();

            var user_rating_comment = $('#user_rating_comment').val();

            var rating_product_id = $('#rating_product_id').val();

            

            $.ajax({

                data: { user_rating: user_rating, user_rating_comment: user_rating_comment, rating_product_id: rating_product_id },

                type: "post",

                async: false,

                cache: false,

                url: _includes_path + "rpc.php",

                success: function (response) {

                    //console.log(response);

                    $('.detailed-ratings-and-reviews').append(response);

                },

                error: function (response) {

                    console.log(response);

                }

            });



        }

    });





    jQuery(document).on('click', '#load-more-btn', function(event) {

        event.preventDefault();

        /* Act on the event */

        var offset = $('.detailed-ratings-and-reviews .reviews-members').length;

        //console.log(offset);

        var rating_product_id = $('#rating_product_id').val();

        var total_review = $(this).attr('total-review');

        $.ajax({

            data: { product_id: rating_product_id, offset: offset },

            type: "post",

            async: false,

            cache: false,

            url: _includes_path + "rpc.php",

            success: function (response) {

                $('.detailed-ratings-and-reviews').append(response);

                setTimeout(function() {

                    var final_count = $('.detailed-ratings-and-reviews .reviews-members').length;

                    if (total_review <= final_count) {

                        $('#load-more-btn').hide();

                    }

                }, 500);

            },

            error: function (response) {

                console.log(response);

            }

        });

    });



    jQuery(document).on('keyup', '.search_form_head .inpt', function(event) {

        /* Act on the event */



        var product_name = $(this).val();



        $.ajax({

            data: { product_search: 'product_search', product_name: product_name },

            type: "post",

            async: false,

            cache: false,

            url: _includes_path + "produtos-list.php",

            success: function (response) {

                console.log(response);

                $('.search_form_head .search-result').html(response);                

            },

            error: function (response) {

                console.log(response);

            }

        });



    });



    jQuery(document).on('focusout', '.search_form_head', function(event) {

        event.preventDefault();

        /* Act on the event */

        setTimeout(function() {

            $('.search-result').hide();

        }, 500);

    });

    jQuery(document).on('focusin', '.search_form_head', function(event) {

        event.preventDefault();

        /* Act on the event */

        setTimeout(function() {

            $('.search-result').show();

        }, 500);

    });





});









function set_category_count() {

    //var pro_length = $('#produtos .produtos_divs ').length

    var total_cat = $('#total_cat').val();

    $('.listing-count').text(total_cat);

}



function set_product_count() {

    //var pro_length = $('#produtos .produtos_divs ').length

    var total_prods = $('#total_prods').val();

    $('.listing-count').text(total_prods);

}







/*jQuery(document).ready(function($) {

    setTimeout(function() {

        var total_prods = $('#total_prods').val();

        console.log(total_prods);

        $('.listing-count > font > font').text(total_prods);

    }, 2000);

});

*/



jQuery(document).ready(function($) {

    // $(function() {  

    //     $(".cat-wrap").niceScroll({

    //         smoothscroll: true,

    //         hwacceleration: true,

    //         cursorwidth: "10px", 

    //         autohidemode:false,

    //         preservenativescrolling: true,

    //         enablescrollonselection:true,

    //         cursorcolor: "#2FAD49",

    //         background: "#F5F5F5",

    //     });

    // });



    // $(".cat-wrap").mCustomScrollbar({

    //     scrollbarPosition:'inside',

    //     setTop: 20

    // });

});



jQuery(document).ready(function($) {

    

    $(document).on('click', '.accordion-head', function(event) {

        event.preventDefault();

        /* Act on the event */

        $(this).toggleClass('active');

        $(this).next('.accordion-content').toggleClass('active');

        $(this).next('.accordion-content').slideToggle(400);

        $(this).parents('.filters_divs').toggleClass('active');

    });



});

// jQuery(document).on('hover', '.nav-link.active', function(event) {

//     event.preventDefault();

//     /* Act on the event */

//     var offsetLeft = $(this).offsetLeft;

//     console.log(offsetLeft);

//     setTimeout(function() {

//         jQuery('.menu_desktop').find('.menu_categorias').css('margin-left', offsetLeft);

//     }, 1500);

// });







jQuery(document).ready(function($) {

    

    jQuery(document).on('change', '.filter-select', function(event) {

        event.preventDefault();

        /* Act on the event */

        var filter_select = $(this).val();

        if (filter_select != 0) {

            $('.filter-by').find('.filters').each(function(index, el) {

                var loja_inpt = $(this).find('.loja_inpt').val();

                if (loja_inpt == filter_select) {

                    console.log('ok');

                    $(this).addClass('class_name');

                    $(this).find('.loja_inpt')[0].click();

                    $(this)[0].click();

                    //loadPesq();

                    $('.filtersFoot .btnFilters:last-child').click();

                }

                

            });   

        }else{

            $('.filter-by').find('.loja_inpt:checked')[0].click();

        }

        

    });



});



// sticky header

    function sticky_relocate() {

    var window_top = jQuery(window).scrollTop();

    var footer_top = jQuery(".section-direitos").offset().top;

    

    var div_height = jQuery("header").height();

   

    if (window_top + div_height > footer_top)

      jQuery('header').css({top: (window_top + div_height - footer_top) * -1})

      else if (window_top > 100) {

        jQuery('header').addClass('cy_sticky');

        jQuery('header').css({top: 30})

      } else {

        jQuery('header').removeClass('cy_sticky');

      }

  }



  jQuery(function () {

    jQuery(window).scroll(sticky_relocate);

    sticky_relocate();

  });



jQuery(document).ready(function($) {

    //main-cate-count

    set_count_main_cate();

       

});



  function set_count_main_cate() {

    $('.catewrap').each(function(index, el) {

        var total_count = 0; 

        $(this).find('.inner_cate').each(function(index, el) {

            var text = 0;

            $(this).find('.filters').each(function(index, el) {

                text = $(this).find('p.list_txt').attr('single-count');

                total_count += parseInt(text);  

            });

        });

        //console.log(total_count);

        $(this).find('.main-cate-count').text('('+total_count+')');

        //console.log(total_count);        

    }); 

}





  // jQuery(document).ready(function($) {

  //     $('.header_drop').hover(function() {

  //         /* Stuff to do when the mouse enters the element */

  //       console.log('add');

  //       $(this).addClass('open_menu');

  //       setTimeout(function() {

  //           if ($('.header_drop').hasClass('open_menu')) {

  //               $('.open_menu').trigger("mouseover");

  //           }

  //       }, 500);

  //     }, function() {

  //         /* Stuff to do when the mouse leaves the element */

  //         console.log('remove');

  //          $(this).removeClass('open_menu');

  //     });

  // });





 

  $(document).on('click', '.popup', function(event) {

  event.preventDefault();



  $.ajax({

    url: _includes_path + "enquiry_popup.php",

    type: 'get',

    dataType: 'html',

  })

  .done(function(html) {

    console.log("success");

    $("#animatedModal").html(html);

    $.fancybox.open($('#animatedModal'));

    console.log(e_product_id);



  })

  .fail(function() {

    console.log("error");

  })

  .always(function() {

    console.log("complete");

  });

  

});


function datechanged(el) { 
     var dilcheck = $('#datepicker').val();

    $.ajax({
      url:  _includes_path + 'carrinho-rpc.php',
      type: 'POST',
      dataType: 'json',
      data: {
        op: 'datechecker',
        dilcheck: dilcheck,
      },
    })
    .done(function(res) {
    console.log(res);
      console.log("success");
      if(res.status == 1)
      {
      $("#myLabel").text(res.lable);
      }
      else
      {
        $("#myLabel").text("");
      }
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
  }


$(document).ready(function(){ /* PREPARE THE SCRIPT */



$(".store_detail").change(function(){ /* WHEN YOU CHANGE AND SELECT FROM THE SELECT FIELD */

    var store_id = $(this).val();

    var e_product_id = $('.popup').attr('id');



  $.ajax({ /* THEN THE AJAX CALL */

    type: "POST", /* TYPE OF METHOD TO USE TO PASS THE DATA */

    url: _includes_path + "enquiry_detail.php", /* PAGE WHERE WE WILL PASS THE DATA */

    dataType: 'html',

    data: {store_id: store_id, e_product_id: e_product_id}, /* THE DATA WE WILL BE PASSING */

    success: function(response){ /* GET THE TO BE RETURNED DATA */

      $("#showvalue").html(response); /* THE RETURNED DATA WILL BE SHOWN IN THIS DIV */

    }

  });



});

});