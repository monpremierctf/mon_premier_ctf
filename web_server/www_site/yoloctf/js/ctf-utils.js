


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
                    id = Math.floor(Math.random() * 46) + 1 ;
                    if (id>46) id=46;
                    filename='img/yes/'+id.toString()+'.gif';
                } else {
                    filename='player_02_200.png';
                }
                
                $('#myModalContent').html('<img src="'+filename+'" alt="Participant" style="height: 100%; width: 100%; object-fit: contain">');
                $('#myModal').modal('show');
            

            $(flag_field).css({ 'color': 'green', 'background-color': 'green' });
            $(flag_field).html(data);
        } 
        else if (data=='ok_not_logged') {
            $('#myModalTitle').html("Flag valide. Veuillez vous logguer...");
            if (use_animated_gif){
                id = Math.floor(Math.random() * 46) + 1 ;
                if (id>46) id=46;
                filename='img/yes/'+id.toString()+'.gif';
            } else {
                filename='player_02_200.png';
            }
            
            $('#myModalContent').html('<img src="'+filename+'" alt="Participant" style="height: 100%; width: 100%; object-fit: contain">');
            $('#myModal').modal('show');
        

            $(flag_field).css({ 'color': 'green', 'background-color': 'green' });
            $(flag_field).html(data);
        }
         
        else if (data=='ok_not_enabled') {
            $('#myModalTitle').html("Flag valide, mais email non vérifé ou compte bloqué...");
            if (use_animated_gif){
                id = Math.floor(Math.random() * 46) + 1 ;
                if (id>46) id=46;
                filename='img/yes/'+id.toString()+'.gif';
            } else {
                filename='player_02_200.png';
            }
            
            $('#myModalContent').html('<img src="'+filename+'" alt="Participant" style="height: 100%; width: 100%; object-fit: contain">');
            $('#myModal').modal('show');
        

            $(flag_field).css({ 'color': 'green', 'background-color': 'green' });
            $(flag_field).html(data);
        }  else {
        
            
            $(flag_field).css({ 'color': 'red' });

            if (data==='ko_not_enabled') {
                $('#myModalTitle').html("Email non validé, ou compte bloqué");
            } else if (data==='ko_not_logged') {
                $('#myModalTitle').html("Flag pas validé du tout");
            } else if (data==='ko_not_enabled') {
                $('#myModalTitle').html("Flag pas validé du tout");
            }
            
            $('#myModalTitle').html("Flag pas validé du tout");
            if (use_animated_gif){
                id = Math.floor(Math.random() * 26) + 1 ;
                if (id>26) id=26;
                filename='img/no/'+id.toString()+'.gif';
            } else {
                filename='admin_02_200.png';
            }
            
            $('#myModalContent').html('<img src="'+filename+'" alt="Participant" style="height: 100%; width: 100%; object-fit: contain">');
            $('#myModal').modal('show');

    
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