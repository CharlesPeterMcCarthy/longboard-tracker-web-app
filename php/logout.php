<?php

  session_start();
  session_destroy();
  header("location: ../skate_sessions.php");

?>
