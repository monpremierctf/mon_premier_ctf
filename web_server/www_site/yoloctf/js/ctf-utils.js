function ctf_validate(id, flag_field)
{
    var flag_raw = $(flag_field).val();
    var flag = encodeURIComponent(flag_raw);
    //alert("flag [" + flag_raw + "] encoded [" + flag+"]");
    $.get( "is_flag_valid.php?id="+id+"&flag="+flag, function( data, status ) {
        //alert("Data[" + data + "] Status[" + status+"]");
        if (data=='ok') {
            //alert("Flag validé ! Félicitation !!!");
            $(flag_field).css({ 'color': 'green', 'background-color': 'green' });
            $(flag_field).html(data);
        } else {
            alert("Flag non validé...");
            $(flag_field).css({ 'color': 'red' });
        }
    })
.fail(function() {
    $(flag_field).css({ 'color': 'black' });
    });
    ;
} 

function ctf_onload(id, flag_field)
{

    $.get( "is_flag_valid.php?id="+id, function( data, status ) {
        //alert("Data[" + data + "] Status[" + status+"]");
        if (data=='ok') {
            //alert("Flag validé ! Félicitation !!!");
            $(flag_field).css({ 'color': 'green', 'background-color': 'green' });
            $(flag_field).html(data);
        } else {
            
            $(flag_field).css({ 'color': 'red' });
        }
    })
.fail(function() {
    $(flag_field).css({ 'color': 'black' });
    });
    ;
} 
