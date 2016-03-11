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
                url: root_url + '/pcategory/sortable',
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
    });

})(window.jQuery);
