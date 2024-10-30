/* 
 * @package Inwave Event
 * @version 1.0.0
 * @created Jun 3, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of iwevent-script
 *
 * @developer duongca
 */
(function ($) {
    "use strict";

    function infunding() {
        /** Blog slider **/
        $(document).ready(function () {
//            var $container = $('.campaing-listing.infunding_style1');
//            $container.masonry({
//                isFitWidth: true,
//                itemSelector: '.post_item'
//            }).masonry('layout');
            $('.infunding-listing-page .order-dir').click(function () {
                var order_dir = $(this).find('i');
                if (order_dir.hasClass('fa-sort-amount-desc')) {
                    order_dir.removeClass('fa-sort-amount-desc').addClass('fa-sort-amount-asc');
                    $(this).find('input').val('asc');
                } else {
                    order_dir.removeClass('fa-sort-amount-asc').addClass('fa-sort-amount-desc');
                    $(this).find('input').val('desc');
                }
                document.filterForm.submit();
            });

            $('.filter-form select, .filter-form input[name="keyword"]').change(function () {
                document.filterForm.submit();
            });

            //Donate button click
            $('button.donate').click(function () {
                if ($(this).hasClass('disable')) {
                    return;
                } else {
                    var link = $(this).data('external-link');
                    if (link) {
                        var win = window.open(link, '_blank');
                        win.focus();
                    } else {
                        var id = $(this).data('id');
                        Custombox.open({
                            target: '#donate-form-' + id,
                            cache: true,
                            effect: 'fadein',
                            overlayOpacity: 0.8,
                            width: 800
                        });
                    }
                }
            });

            //Clse custombox
            $('.close-donate.button, .donate-title .close-donate').click(function () {
                Custombox.close();
            });

            $('input[name="anonymous"]').change(function () {
                if ($(this).prop('checked')) {
                    $('.inf-checkoutform .personal-info').slideUp(300, function () {
                        $('.inf-checkoutform .personal-info input').attr('disabled', 'disabled');
                    });
                } else {
                    $('.inf-checkoutform .personal-info').slideDown();
                    $('.inf-checkoutform .personal-info input').removeAttr('disabled');
                }
            }).trigger('change');

        });
    }
    function height_listing_slider() {
        $(window).on("load resize", function () {
            var h_campaigns_slider = $('.iw-campaign-listing-slider').outerHeight();
            $('.iw-campaign-image').css({"height": h_campaigns_slider});
        });
    }
    function height_img_slider() {
        $(window).on("load resize", function () {
            var h_campaigns_slider = $('.iw-campaign-listing-slider').outerHeight();
            $('.infunding_slider-v3 .iw-campaign-image').css({"height": h_campaigns_slider - 70});
        });
    }

    /**
     * Carousel social footer
     */
    function carousel_init() {
        $(".owl-carousel").each(function () {
            var slider = $(this);
            var defaults = {
                direction: $('body').hasClass('rtl') ? 'rtl' : 'ltr'
            };
            var config = $.extend({}, defaults, slider.data("plugin-options"));
            // Initialize Slider
            slider.owlCarousel(config).addClass("owl-carousel-init");
        });
    }
    
    function datetimepicker_init() {
        if ($.datetimepicker) {
            $('.datetimepicker-input').each(function () {
                var input = $(this);
                var extconfig = {};
//            if (input.hasClass('start')) {
//                extconfig = {
//                    onShow: function (ct) {
//                        console.log(input.parents('.datetimepicker-group').find('input.datetimepicker-input.end').val());
//                        this.setOptions({
//                            maxDate: input.parents('.datetimepicker-group').find('input.datetimepicker-input.end').val() ? input.parents('.datetimepicker-group').find('input.datetimepicker-input.end').val() : false
//                        });
//                    }
//                };
//            }
//            if (input.hasClass('end')) {
//                extconfig = {
//                    onShow: function (ct) {
//                        this.setOptions({
//                            minDate: input.parents('.datetimepicker-group').find('input.datetimepicker-input.start').val() ? input.parents('.datetimepicker-group').find('input.datetimepicker-input.start').val() : false
//                        });
//                    }
//                };
//            }
                var config = $.extend({}, extconfig, input.data("configs"));
                input.datetimepicker(config);
            });
        }
    }
    
    /*** RUN ALL FUNCTION */
    $(document).ready(function () {
        // run function here:
        infunding();
        height_listing_slider();
        height_img_slider();
        carousel_init();
        datetimepicker_init();
    });
})(jQuery);
