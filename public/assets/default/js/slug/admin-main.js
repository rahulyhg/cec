$.fn.editable.defaults.mode = 'inline';
$('.edit-slug').editable({
    url: root_url + '/slug/updateurl',
    success: function(response, newValue) {
        if (response.status == 'error') {
            toastr.error(response.msg);
        } else {
            toastr.success(response.msg);
        }
    }
});
