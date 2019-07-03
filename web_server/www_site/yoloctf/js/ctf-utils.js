function ctf_validate(id, flag_field)
{
    var flag_raw = $(flag_field).val();
    var flag = encodeURIComponent(flag_raw);
    var use_animated_gif = true;

    //alert("flag [" + flag_raw + "] encoded [" + flag+"]");
    $.get( "is_flag_valid.php?id="+id+"&flag="+flag, function( data, status ) {
        //alert("Data[" + data + "] Status[" + status+"]");
        if (data=='ok') {
                $('#myModalTitle').html("Flag validé");
                if (use_animated_gif){
                    id = Math.floor(Math.random() * 50) + 1 ;
                    if (id>50) id=50;
                    filename='img/yes/'+id.toString()+'.gif';
                } else {
                    filename='player_02_200.png';
                }
                
                $('#myModalContent').html('<img src="'+filename+'" alt="Participant" style="height: 100%; width: 100%; object-fit: contain">');
                $('#myModal').modal('show');
            

            $(flag_field).css({ 'color': 'green', 'background-color': 'green' });
            $(flag_field).html(data);
        } else {
        
            
            $(flag_field).css({ 'color': 'red' });
            $('#myModalTitle').html("Flag pas validé du tout");
            if (use_animated_gif){
                id = Math.floor(Math.random() * 32) + 1 ;
                if (id>32) id=32;
                filename='img/no/'+id.toString()+'.gif';
            } else {
                filename='admin_02_200.png';
            }
            
            $('#myModalContent').html('<img src="'+filename+'" alt="Participant" style="height: 100%; width: 100%; object-fit: contain">');
            $('#myModal').modal('show');
            //alert("Flag non validé...");
    
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



function ctf_toggle_hide(id)
{
    $(id).toggle();
}