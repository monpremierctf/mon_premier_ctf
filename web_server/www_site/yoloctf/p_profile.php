<div class="col text-center">
<div class="col text-left"><h2>My Profile</h2><br><br></div>
<div class="col text-center"> 

<!---- UID, login, mail  --->
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

        <div class="form-group text-left  row ">
        <hr>
        </div>

<!---- status  --->
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

        <div class="form-group text-left  row ">
        <hr>
        </div>
<?php } ?>

<!---- Password  --->
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




<!---- Join CTF  --->
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
    </script>