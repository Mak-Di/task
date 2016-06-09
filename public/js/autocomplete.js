$(function() {
    $( "#selector" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "/employee/search",
                dataType: "json",
                data: {
                    name: request.term
                },
                success: function( data ) {
                    response( data );
                }
            });
        },
        minLength: 3,
        select: function (event, ui) {
            event.preventDefault();
            $( "#selector" ).val(ui.item.label);
            $( "#selectorStorage" ).val(ui.item.value).change();
        },
        focus: function (event, ui) {
            event.preventDefault();
            $( "#selector" ).val(ui.item.label);
        }
    });
});