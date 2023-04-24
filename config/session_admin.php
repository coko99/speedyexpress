<?php
   include('config.php');
   require_once 'vendor/autoload.php';

   session_start();

   function logEvent($message) {
      if ($message != '') {
          // Add a timestamp to the start of the $message
          $message = date("Y/m/d H:i:s").': '.$message;
          $fp = fopen('log.txt', 'a');
          fwrite($fp, $message."\n");
          fclose($fp);
      }
   }

   if(!isset($_SESSION['login_admin'])){
      header("location: login.php");
      die();
   }
   $user_check = mysqli_real_escape_string($db, $_SESSION['login_admin']);
   $ses_sql = mysqli_query($db,"select username from admin where username = '$user_check' ");
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   $count = mysqli_num_rows($ses_sql);
   if($count == 1){
      $login_session = $row['username'];
   }else{
      if(session_destroy()) {
         header("Location: login.php");
      }
      die();
   }
?>