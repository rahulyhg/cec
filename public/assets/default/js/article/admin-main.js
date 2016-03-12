$(document).ready(function() {
    Dropzone.autoDiscover = false;
    $('div#uploadImages').dropzone({
        url: root_url + '/article/uploadimage',
        paramName: 'products',
        autoProcessQueue: true,
        uploadMultiple: true,
        parallelUploads: 10,
        maxFiles: 10,
        addRemoveLinks: true,
        dictDefaultMessage: "Drop the images you want to upload here",
        dictFileTooBig: "Image size is too big. Max size: 10mb.",
        dictMaxFilesExceeded: "Only 10 images allowed per upload.",
        acceptedFiles: ".jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF",
        init: function() {
            this.on("success", function(file, response) {
                if (response._meta.status == true) {
                    $.each(response._result, function(key, item) {
                        var path = '<input type="hidden" name="uploadfiles[]" value="' + item.path + '"/>';
                        $('.multipleFiles').append(path);
                    });
                }
                toastr.success(response._meta.message);
            });
            this.on("removedfile", function(file) {
                var name = file.name;
                $.ajax({
                    type: 'POST',
                    url: root_url + '/article/deleteimage',
                    data: "name="+name,
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        if (response._meta.status == true) {
                            // Remove input value
                            $('input[type=hidden]').each(function() {
                                if ($(this).val() == response._result) {
                                    $(this).remove();
                                }
                            });

                            toastr.success(response._meta.message);
                        } else {
                            toastr.error(response._meta.message);
                        }
                    }
                });
            });
        },
    });
});
