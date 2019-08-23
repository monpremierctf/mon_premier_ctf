<?php
    function is_flag_validated($uid, $cid)
    {
        $ret=0;
        include "ctf_sql.php";
        $query = "select UID from flags where (UID='$uid' and CHALLID='$cid' and isvalid=TRUE);";
        //echo $query;
		if ($result = $mysqli->query($query)) {
            $count  = $result->num_rows;
    		$ret= $count;
            $result->close();
		}
        $mysqli->close();
        return $ret;
    }

    function save_flag_submission($uid, $cid, $flag, $isvalid)
    {
        include "ctf_sql.php";

        //echo $valid;
        $count = is_flag_validated($uid, $cid);
        //echo $count;
        if (($isvalid)&&($count>0)) {
            return;
        }
            //echo "Valid='$valid'";
            //insert into flags (UID,CHALLID, fdate, isvalid, flag) values ('user1','chall1', NOW(), TRUE, 'flag1');
            $flag = mysqli_real_escape_string($mysqli, $flag);
            $query = "insert into flags (UID,CHALLID, fdate, isvalid, flag) values ('$uid','$cid', NOW(), '$isvalid', '$flag');";
            //echo $query;
            
            if ($mysqli->query($query)===TRUE) {
                // ok
            } else {
                // ko
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
    
		/* close connection */
		$mysqli->close();
    }



    //
    // Handle request
    //
    session_start ();
    include ("ctf_challenges.php");

    // if, flag
    $cid =  $_GET['id'];
    $flag = trim($_GET['flag']);
    if (isset($flag)) {
        $flag = urldecode($flag);
    }

    if (isset($_SESSION['uid'] )) {
        $uid = $_SESSION['uid'];

        // Status != enabled
        if ($_SESSION['status'] !== 'enabled') {
            if (isFlagValid($cid,$flag)){
                print "ok_not_enabled";
            } else {
                echo "ko_not_enabled";
            }
            return;
        }
        
        if (isset($_GET['flag'])) {
            if (isFlagValid($cid,$flag)){
                print "ok";
                save_flag_submission($_SESSION['uid'], $cid, $flag, true);
            } else {
                print "ko";
                save_flag_submission($_SESSION['uid'], $cid, $flag, false);
            }   
        } else {
            $count = is_flag_validated($uid, $cid);
            //echo $count;
            if (($count>0)) {
                echo 'ok';
            } else {
                echo 'ko';
            }
        }
    } else {
        // User not logged
        if (isFlagValid($cid,$flag)){
            echo "ok_not_logged";
        } else {
            echo "ko_not_logged";
        }
        
    }
  
?>