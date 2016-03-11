$(document).ready(function() {
    Dropzone.autoDiscover = false;
    $('div#uploadAvatar').dropzone({
        url: root_url + '/user/uploadavatar',
        paramName: 'avatar',
        maxFileSize: 2,
        maxFiles: 1,
        init: function() {
            this.on("maxfilesexceeded", function(file) {
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
                    var path = response._result.avatar.path;
                    $("#uploadAvatarInput").val(path);
                }
                toastr.success(response._meta.message);
            });
        },
    });
});