 function deleteConfirm(theURL, id) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function() {
        swal("Deleted!", "Item id "+ id +" has been deleted.", "success");
        window.location = theURL;
    });
}
 $(document).ready(function() {

    // Check all checkboxes when the one in a table head is checked:
    $('#basicTable .check-all').click( function(){
        $(this).parent().parent().parent().parent().parent().find("input[type='checkbox']").prop('checked', $(this).is(':checked'));
        // $(this).parent().parent().parent().parent().parent().find("tr").removeClass('selected');
        // $(this).parent().parent().parent().parent().parent().find("tr").addClass('selected');
    });

    //Single instance of tag inputs - can be initiated with simply using data-role="tagsinput" attribute in any input field
    $('.custom-tag-input').tagsinput({});
})
