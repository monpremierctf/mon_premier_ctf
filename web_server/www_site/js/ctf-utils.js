function ctf_validate(id, flag_field)
{
    flag = $(flag_field).val();
    $.get( "is_flag_valid.php?id="+id+"&flag="+flag, function( data, status ) {
        //alert("Data[" + data + "] Status[" + status+"]");
        if (data=='ok') {
            //alert("Flag validé ! Félicitation !!!");
            $(flag_field).css({ 'color': 'green', 'background-color': 'green' });
            $(flag_field).html(data);
        } else {
            alert("Flag non validé...");
            $(flag_field).css({ 'color': 'red', 'background-color': 'red' });
        }
    })
.fail(function() {
    $(flag_field).css({ 'color': 'black' });
    });
    ;
} 


