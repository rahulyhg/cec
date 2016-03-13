$(document).ready(function() {
    Dropzone.autoDiscover = false;

    // Cover upload
    $('div#uploadCover').dropzone({
        url: root_url + '/article/uploadimage',
        paramName: 'image',
        maxFileSize: 10,
        maxFiles: 1,
        addRemoveLinks: true,
        acceptedFiles: ".jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF",
        init: function() {
            this.on("maxfilesexceeded", function(file) {
                toastr.error("Cannot upload more than 1 file!");
            });
            this.on("success", function(file, response) {
                if (response._meta.status == true) {
                    var path = response._result.image.path;
                    $("#uploadCoverInput").val(path);
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

    // Gallery upload
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

    $('#summernote').summernote({
        height: 300,
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['fontname', 'strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'hr']],
            ['view', ['fullscreen', 'codeview']],
        ],
        onfocus: function(e) {
            $('body').addClass('overlay-disabled');
        },
        onblur: function(e) {
            $('body').removeClass('overlay-disabled');
        },
        onImageUpload: function(files, editor, welEditable) {
            sendFile(files[0], editor, welEditable);
        }
    });

    // upload image to server
    function sendFile(file, editor, welEditable) {
        data = new FormData();
        data.append("file", file);
        $.ajax({
            data: data,
            type: "POST",
            url: root_url + "/article/uploadimage",
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                var url = static_url + response._result.file.path;
                editor.insertImage(welEditable, url);
            }
        });
    }
});
