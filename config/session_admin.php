<?php
   include('config.php');
   session_start();

   function logEvent($message) {
      if ($message != '') {
          // Add a timestamp to the start of the $message
          $message = date("Y/m/d H:i:s").': '.$message;
          $fp = fopen('../log/log.txt', 'a');
          fwrite($fp, $message."\n");
          fclose($fp);
      }
   }

   if(!isset($_SESSION['login_admin'])){
      header("location:../index.php");
      die();
   }
   $user_check = $_SESSION['login_admin'];
   $ses_sql = mysqli_query($db,"select username from admin where username = '$user_check' ");
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   $login_session = $row['username'];
?>