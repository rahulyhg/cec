$(document).ready(function() {
    $('.viewmore#article').on('click', function(e) {
        var is_busy = false;
        var stopped = false;
        var element = $('ul.projects');
        var button = $(this);

        if (is_busy == true) {
            return false;
        }

        page++;
        button.html('Loading...');

        $.ajax({
            type: 'GET',
            url: paginateUrl,
            data: "page=" + page,
            dataType: 'json',
            cache: false,
            success: function(response) {
                if (response._meta.status == true) {
                    var data = response._result.data;
                    var html = '';

                    $.each(data, function(key, obj) {
                        html += '<li>';
                        html += '   <a href="' + obj.slug +'">';
                        html += '       <img src="' + obj.image + '"></img>';
                        html += '       <h3>' + obj.title + '</h3>';
                        html += '   </a>';
                        html += '   <p>' + obj.seodescription + '</p>';
                        html += '</li>';
                    });

                    element.append(html);
                    if (page == totalPage) {
                        button.remove();
                    }
                } else {
                    // toastr.error(response._meta.message);
                }
            },

        })
        .always(function() {
            button.html('Xem thêm còn ' + (totalItems - (page * recordPerPage)) + ' công trình');
            is_busy = false;
        });
    });

    if ($('#article-list').length > 0) {
        // execute above function
        initPhotoSwipeFromDOM('.my-gallery');
    }

    $('.viewmore#product').on('click', function(e) {
        var is_busy = false;
        var stopped = false;
        var element = $('ul.product');
        var button = $(this);

        if (is_busy == true) {
            return false;
        }

        page++;
        button.html('Loading...');

        $.ajax({
            type: 'GET',
            url: paginateUrl,
            data: "page=" + page,
            dataType: 'json',
            cache: false,
            success: function(response) {
                if (response._meta.status == true) {
                    var data = response._result.data;
                    var html = '';

                    $.each(data, function(key, obj) {
                        html += '<li>';
                        html += '   <img src="' + obj.image + '"></img>';
                        html += '   <h3>' + obj.name + '</h3>';
                        html += '</li>';
                    });

                    element.append(html);
                    if (page == totalPage) {
                        button.remove();
                    }
                } else {
                    // toastr.error(response._meta.message);
                }
            },

        })
        .always(function() {
            button.html('Xem thêm còn ' + (totalItems - (page * recordPerPage)) + ' sản phẩm');
            is_busy = false;
        });
    });

    // Mobile menu
    $('.menu').click(function(e) {
        $('nav').slideToggle(500);
        $(this).toggleClass("actmenu");
        $("section").toggleClass("fixbody");
        $('footer').toggleClass("fixbody");
    });

    $(".over").click(function(){
        $("nav").slideToggle();
        $(".actmenu").removeClass("actmenu");
        $("section").removeClass("fixbody");
        $("footer").removeClass("fixbody");
    });

    $('.closemenu').click(function(e) {
        $("nav").slideToggle();
        $(".actmenu").removeClass("actmenu");
        $("section").removeClass("fixbody");
        $("footer").removeClass("fixbody");
    });
});
