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
    // Initializes search overlay plugin.
    // Replace onSearchSubmit() and onKeyEnter() with
    // your logic to perform a search and display results
    $('[data-pages="search"]').search({
        searchField: '#overlay-search',
        closeButton: '.overlay-close',
        suggestions: '#overlay-suggestions',
        brand: '.brand',
        onSearchSubmit: function(searchString) {
            console.log("Search for: " + searchString);
        },
        onKeyEnter: function(searchString) {
            console.log("Live search for: " + searchString);
            var searchField = $('#overlay-search');
            var searchResults = $('.search-results');
            clearTimeout($.data(this, 'timer'));
            searchResults.fadeOut("fast");
            var wait = setTimeout(function() {
                searchResults.find('.result-name').each(function() {
                    if (searchField.val().length != 0) {
                        $(this).html(searchField.val());
                        searchResults.fadeIn("fast");
                    }
                });
            }, 500);
            $(this).data('timer', wait);
        }
    });

    // seleted sidebar
    var activemenu = $('.container-fluid').attr('rel');
    var activemenuselector = $('#' + activemenu);
    if (activemenuselector.length) {
        activemenuselector.addClass('active');
        activemenuselector.parent().parent().addClass('active');
    }
    // Check all checkboxes when the one in a table head is checked:
    $('#basicTable .check-all').click( function(){
        $(this).parent().parent().parent().parent().parent().find("input[type='checkbox']").prop('checked', $(this).is(':checked'));
        // $(this).parent().parent().parent().parent().parent().find("tr").removeClass('selected');
        // $(this).parent().parent().parent().parent().parent().find("tr").addClass('selected');
    });

    //Single instance of tag inputs - can be initiated with simply using data-role="tagsinput" attribute in any input field
    $('.custom-tag-input').tagsinput({});
})
