$(document).ready(function() {
    Dropzone.autoDiscover = false;

    // Cover upload
    $('div#uploadCover').dropzone({
        url: root_url + '/homepage/uploadimage',
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
                    url: root_url + '/homepage/deleteimage',
                    data: "name="+name,
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        if (response._meta.status == true) {
                            // Remove input value
                            $("#uploadCoverInput").val("");

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
