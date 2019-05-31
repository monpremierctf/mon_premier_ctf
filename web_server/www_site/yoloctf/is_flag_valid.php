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
        if (($isvalid)&&($count==0)) {
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
        } else {
            //echo "dont save";
        }
		/* close connection */
		$mysqli->close();
    }

    session_start ();
    if (isset($_SESSION['uid'] )) {
        include ("ctf_challenges.php");
        $uid = $_SESSION['uid'];
        $cid =  $_GET['id'];
        if (isset($_GET['flag'])) {
            $flag = trim($_GET['flag']);
            $flag = urldecode($flag);
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
        echo "ko";
    }
  
?>