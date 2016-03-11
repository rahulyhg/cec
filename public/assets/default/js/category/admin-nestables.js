/* ============================================================
 * Nestables
 * Creates draggable, nested list structures using jQuery Nestable plugin
 * ============================================================ */
(function($) {

    'use strict';

    $(document).ready(function() {
        // Change display order
        var updateOutput = function(e) {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            // console.log(JSON.stringify(list.nestable('serialize')));
            $.ajax({
                url: root_url + '/category/sortable',
                type: 'post',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(list.nestable('serialize')),
                success: function() {
                    toastr.success('Category display order was changed.');
                },
                error: function() {
                    toastr.error('Unknown error.');
                }
            });
        };

        // activate Nestable for list 1
        $('#nestable').nestable({group: 1}).on('change', updateOutput);

        // Upload icon path
        Dropzone.autoDiscover = false;
        $('div#uploadIconpath').dropzone({
            url: root_url + '/category/uploadiconpath',
            paramName: 'iconpath',
            maxFileSize: 2,
            maxFiles: 1,
            init: function() {
                this.on("maxfilesexceeded", function(file){
                    toastr.error("Cannot upload more than 1 file!");
                });
                this.on("addedfile", function(file) {
                    var removeButton = Dropzone.createElement("<span class='pull-right'><button class='btn btn-default btn-xs'><i class='fa fa-times'></i></button></span>");
                    var _this = this;
                    removeButton.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        _this.removeFile(file);
                    });
                    file.previewElement.appendChild(removeButton);
                });
                this.on("success", function(file, response) {
                    if (response._meta.status == true) {
                        var path = response._result.iconpath.path;
                        $("#uploadIconpathInput").val(path);
                    }
                    toastr.success(response._meta.message);
                });
            },
        });
    });

})(window.jQuery);
