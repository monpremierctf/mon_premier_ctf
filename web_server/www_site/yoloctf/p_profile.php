<div class="col text-center">
<div class="col text-left"><h2>Mon Profile</h2><br><br></div>
<div class="col text-center"> 

<!---- UID, login, mail  --->

    <div class="">
      <div class="row chall-titre bg-secondary text-white">
        <div class="col-sm text-left">Identifiant</div>
      </div>
        <div class="form-group text-left row">
		  <label for="usr" class="col-2">Id</label>
		  <label for="usr" class="col-6" id="id" name="id">
              <?php echo isset($_SESSION['uid'])?htmlspecialchars($_SESSION['uid']):"XXXXXX"; ?>
          </label>
          <label for="usr" class="col-2"></label>
        </div>
		<div class="form-group text-left row">
		  <label for="usr" class="col-2">Login</label>
		  <input type="text" class="col-6 form-control" id="name" name="name" value="<?php echo isset($_SESSION['login'])?htmlspecialchars($_SESSION['login']):"Guest"; ?>">
          <label for="usr" class="col-2"></label>
        </div>
        <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">Mail</label>
		  <input type="text" class="col-6 form-control" id="mail" name="mail" value="<?php echo isset($_SESSION['mail'])?htmlspecialchars($_SESSION['mail']):""; ?>">
          <label for="usr" class="col-2"></label>
        </div>
        
        <div class="form-group text-right row ">
          <label for="usr" class="col-2"></label>
          <button type="submit" class="btn btn-primary" onclick="return onProfileSave()">Save</button>      
        </div> 


    </div>

<div class="form-group text-left  row ">
<hr>
</div>

<!---- status  --->
<div class="">
    <div class="row chall-titre bg-secondary text-white">
        <div class="col-sm text-left">Status du compte</div>
      </div>
        <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">Status</label>
		  <label for="usr" class="col-6" id="status" name="status">
              <?php echo isset($_SESSION['status'])?htmlspecialchars($_SESSION['status']):""; ?>
          </label>
          <label for="usr" class="col-2"></label>
        </div>
        
<?php if (isset($_SESSION['status']) and ($_SESSION['status']==='waiting_email_validation')) { ?>
        <div class="form-group text-right row ">
          <label for="usr" class="col-2"></label>
          <button type="submit" class="btn btn-primary" onclick="return onResendValidationMail()">Resend mail</button>      
        </div> 


<?php } ?>
</div>

<div class="form-group text-left  row ">
<hr>
</div>

<!---- Password  --->
<div class="">
      <div class="row chall-titre bg-secondary text-white">
        <div class="col-sm text-left">Mot de passe</div>
      </div>
        <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">Password</label>
		  <input type="password" class="col-6 form-control" id="password" name="password">
          <label for="usr" class="col-2"></label>
        </div>
        <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">Confirm Password</label>
		  <input type="password" class="col-6 form-control" id="confirm_password" name="confirm_password">
          <label for="usr" class="col-2"></label>
        </div>
        <div class="form-group text-right row ">
          <label for="usr" class="col-2"></label>
          <button type="submit" class="btn btn-primary" onclick="return onProfilePasswordChange()">Change</button>      
        </div> 
</div>

<div class="form-group text-left  row ">
<hr>
</div>

<!---- Join CTF  --->
<div class="">
      <div class="row chall-titre bg-secondary text-white">
        <div class="col-sm text-left">Rejoindre un CTF</div>
      </div>
        <div class="form-group text-left  row ">
		  <label for="usr" class="col-2">CTF</label>
		  <input type="text" class="col-6 form-control" id="ctf" name="ctf" value="<?php echo isset($_SESSION['ctf'])?htmlspecialchars($_SESSION['ctf']):"Guest"; ?>">
          <label for="usr" class="col-2"></label>
        </div>
       
        <div class="form-group text-right row ">
          <label for="usr" class="col-2"></label>
          <button type="submit" class="btn btn-primary" onclick="return onJoinCTF()">Join CTF</button>      
        </div> 

    
</div>

<div class="form-group text-left  row ">
<hr>
</div>

<!---- Organiser un CTF  --->
<div class="">
      <div class="row chall-titre bg-secondary text-white"><div class="col-sm text-left">Créer un CTF</div></div>

      <?php   
      require_once('ctf_sql.php');
      $request = "SELECT * FROM ctfs WHERE UIDADMIN='$uid'";
      $result = $mysqli->query($request);
      $count  = $result->num_rows;
      if($count>0) {
          $row = $result->fetch_array();
          // id , creation_date datetime, UIDCTF VARCHAR(45) NULL, ctfname VARCHAR(200) NULL, UIDADMIN 
          $ctfname =  $row['ctfname'];
          $creation_date =  $row['creation_date'];
          $d = DateTime ($creation_date);
      }
      ?>

      <div class="form-group text-left  row ">
		    <label for="usr" class="col-2">CTF</label>
		    <input type="text" class="col-6 form-control" id="createctf_name" name="createctf_name" value="<?php echo $ctfname; ?>">
        <label for="usr" class="col-2"></label>
      </div>
       
      <div class="form-group text-right row ">
        <label for="usr" class="col-2"></label>
        <button type="submit" class="btn btn-primary" onclick="return onCreateCTF()">Créer CTF</button>      
      </div> 

</div>

<div class="form-group text-left  row ">
<hr>
</div>

<script>
        function onProfileSave()
        {
            // Check name is available

            // Check fields are filled
            
            //alert("onProfileSave");
            // reload page from server
            window.location.reload(true); 
            return false;
        }
        function onProfilePasswordChange()
        {
            // Check name is available

            // Check fields are filled
            
            //alert("onProfileSave");
            // reload page from server
            return false;
        }
        function onResendValidationMail()
        {
            // Check name is available

            // Check fields are filled
            
            //alert("onProfileSave");
            // reload page from server
            return false;
        }
        function onJoinCTF()
        {
            // Check name is available

            // Check fields are filled
            
            //alert("onProfileSave");
            // reload page from server
            return false;
        }
        function onCreateCTF()
        {
          var ctfname_raw = $("#createctf_name").val();
          var ctfname = encodeURIComponent(ctfname_raw); 
           $.get( "cmd_ctf.php?create="+ctfname+"&content="+ctfname, function( data, status ) {
                alert(data);              
              })
            .fail(function() {
            });
            
            return false;
        }
    </script>