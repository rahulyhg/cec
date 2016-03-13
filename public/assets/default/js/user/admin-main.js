$(document).ready(function() {
    Dropzone.autoDiscover = false;

    $('div#uploadAvatar').dropzone({
        url: root_url + '/user/uploadavatar',
        paramName: 'avatar',
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
                    $("#uploadAvatarInput").val(path);
                }
                toastr.success(response._meta.message);
            });
            this.on("removedfile", function(file) {
                var name = file.name;
                $.ajax({
                    type: 'POST',
                    url: root_url + '/user/deleteavatar',
                    data: "name="+name,
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        if (response._meta.status == true) {
                            $("#uploadAvatarInput").val("");
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
