<!DOCTYPE html>

<?php

  session_start();

  if (isset($_GET['sessionID'])) {
    $_SESSION['preset_session_ID'] = $_GET['sessionID'];
  }

?>

<html lang='en'>
  <head>
    <title>Skate Sessions</title>
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
    <script src='js/skate_sessions.js'></script>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet' href='css/main.css'>
    <link rel='stylesheet' href='css/skate_sessions.css'>
  </head>
  <body>

    <div class='row'>
      <div class='col-xs-12 col-sm-offset-2 col-sm-8'>
        <div id='main'>
          <div class='text-right'>
            <?php

              if (isset($_SESSION['arduino']) && isset($_SESSION['arduino']['userEmail'])) {
                echo "<button id='logout-btn' class='btn btn-xs btn-default'><i class='glyphicon glyphicon-log-out'></i> Logout</button>";
              } else {
                echo "<button id='login-modal-trigger-btn' class='btn btn-xs btn-default' data-toggle='modal' data-target='#login-modal'><i class='glyphicon glyphicon-log-in'></i> Login</button>";
              }

            ?>
          </div>

          <h1 class='text-center'>Skate Sessions</h1>

          <?php

            if (isset($_SESSION['arduino']['userEmail'])) {
              echo "<div id='main-notice' class='text-center'>No Skate Sessions Yet.</div>";
            } else {
              echo "<div id='main-notice' class='text-center'>You Are Not Logged In.</div>";
            }

          ?>
          
          <div id='session-list'>
            <!-- Populated by JS -->
          </div>
        </div>

        <footer>
          <small>Arduino Longboard Project</small>
        </footer>
      </div>
    </div>

    <div id='login-modal' class='modal fade' role='dialog'>
      <div class='modal-dialog modal-sm'>
        <div class='modal-content'>
          <div class='modal-header'>
            <button type='button' class='close' data-dismiss='modal' action='close-modal'>&times;</button>
            <h4 class='modal-title'>Login</h4>
          </div>
          <div class='modal-body'>
            <input type='text' id='login-email' class='form-control' placeholder="Email">
            <input type='password' id='login-pass' class='form-control' placeholder="Password">
          </div>
          <div class='modal-footer'>
            <button class='btn btn-default' data-dismiss='modal' id='close-btn'>Cancel</button>
            <button class='btn btn-default' id='login-btn'>Login</button>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
