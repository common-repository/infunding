(function (j$) {
    j$(document).ready(function () {

        j$('.rd_pie_01').waypoint(function () {


//======== CONFIGURATION WINDOW
//======== i made this configuration code here you can change value and experiment
            var x = 260;//set the x - center here
            var y = 200;//set the y - center here
            var r = 154;//set the radius here
            var linewidth = 22;//set the line width here
            var SET_PERCENTAGE = j$(this).children('.rd_pc_01').data('percentage-value');
            var bar_color = j$(this).children('.rd_pc_01').data('bar-color');
            var alt_color = j$(this).children('.rd_pc_01').data('bar-alt-color');

//======== 
            var c = j$(this).children('.rd_pc_01').get(0);
            var id = j$(this).attr('id');
            var status = j$('#' + id + '.rd_pc_status');
            var loaded = false;

            window.onload = function () {

                loaded = true;
            };


            var ROTATION = 0;
            function setcanvas()
            {
                if (typeof c === 'undefined')
                    return;
                var ctx = c.getContext("2d");

                ctx.translate(x, y);
                ctx.rotate((Math.PI / 180) * (-ROTATION));
                ctx.translate(-x, -y);



                ctx.clearRect(0, 0, c.width, c.height);


            }

            function getPoint(c1, c2, radius, angle) {
                return [c1 + Math.cos(angle) * radius, c2 + Math.sin(angle) * radius];
            }

            function setPercent(uplimit)
            {
                if (typeof c === 'undefined')
                    return;
                var ctx = c.getContext("2d");
                ctx.beginPath();
                ctx.translate(x, y);
                ROTATION = 270;
                ctx.rotate((Math.PI / 180) * ROTATION);
                ctx.translate(-x, -y);
                ctx.lineWidth = linewidth;//40
                ctx.lineCap = "round";
                var my_gradient = ctx.createLinearGradient(-0, 0, 0, 520);
                my_gradient.addColorStop(0, bar_color);
                my_gradient.addColorStop(1, alt_color);

                ctx.strokeStyle = my_gradient;
                ctx.arc(x, y, r, (Math.PI / 180) * (uplimit), 0);
                ctx.globalAlpha = 1;
                ctx.stroke();



            }

            function callcanvas(degree)
            {
                setcanvas();
                setPercent(360 - degree);
            }

            var degree = parseInt((SET_PERCENTAGE * 360) / 100);
            var start = 0;
            var it = window.setInterval(function () {
                callcanvas(start);
                start++;
                if (start == degree) {
                    start = degree;
                    window.clearInterval(it);
                }
                if (loaded)
                    status.html(parseInt((start * 100) / 360) + '%');
            }, 1);
            j$(this).children('.rd_pc_01').removeClass('rd_pc_01');

        }, {offset: '85%'});


        j$(".rd_count_to").each(function (i, el) {
            var el = j$(el);
            if (el.is(':visible')) {


                var countAsset = j$(this),
                        countNumber = countAsset.find('.count_number'),
                        countDivider = countAsset.find('.count_line').find('span'),
                        countSubject = countAsset.find('.count_title');
                el.removeClass("rd_count_to");
                el.addClass("rd_count_to_over");
                countNumber.countTo({
                    onComplete: function () {
                        countDivider.animate({
                            'width': 50
                        }, 400, 'easeOutCubic');
                        countSubject.delay(100).animate({
                            'opacity': 1,
                            'bottom': '0px'
                        }, 600, 'easeOutCubic');

                    }
                });
            }
        });

        var count = j$(".rd_count_to");
        count.each(function (i, el) {
            var el = j$(el);
            if (el.visible(true)) {


                var countAsset = j$(this),
                        countNumber = countAsset.find('.count_number'),
                        countDivider = countAsset.find('.count_line').find('span'),
                        countSubject = countAsset.find('.count_title');

                el.removeClass("rd_count_to");
                el.addClass("rd_count_to_over");
                countNumber.countTo({
                    onComplete: function () {
                        countDivider.animate({
                            'width': 50
                        }, 400, 'easeOutCubic');
                        countSubject.delay(100).animate({
                            'opacity': 1,
                            'bottom': '0px'
                        }, 600, 'easeOutCubic');

                    }

                });


            }
        });

        j$('.count_sc').each(function () {

            var countAsset = j$(this),
                    countNumber = countAsset.find('.count_number'),
                    countDivider = countAsset.find('.count_line').find('span'),
                    countSubject = countAsset.find('.count_title');

            countNumber.countTo({
                onComplete: function () {
                    countDivider.animate({
                        'width': 50
                    }, 400, 'easeOutCubic');
                    countSubject.delay(100).animate({
                        'opacity': 1,
                        'bottom': '0px'
                    }, 600, 'easeOutCubic');
                }
            });


        });

    });

})(jQuery);
