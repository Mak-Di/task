$(function() {
    function setData( event, ui ) {
        event.preventDefault();
        $( "#selector" ).val(ui.item.label);
        $( "#selectorStorage" ).val(ui.item.value);
    };
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
        select: setData,
        focus: setData
    });
});