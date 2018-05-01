<?php

  session_start();

  if (isset($_POST['info'])) {
    $info = json_decode($_POST['info'], true);

    $sessions = $info['sessions'];
    $outputs = [];

    foreach ($sessions as $session) {
      array_push($outputs, GetSessionListItemOutput($session));
    }

    $response = [
      'isOk' => true,
      'outputs' => $outputs
    ];

    echo json_encode($response);
  }

  function GetSessionListItemOutput($session) {
    $sessionID = $session['sessionID'];
    $sessionStart = $session['sessionStart'];
    $sessionEnd = $session['sessionEnd'];
    $sessionDistance = $session['sessionDistance'];
    $presetSessionID = 0;

    $output = "";

    $output .= "<div class='session-block";
      if (isset($_SESSION['preset_session_ID']) && $_SESSION['preset_session_ID'] == $sessionID) {
          // User has been directed here to view specific session
          // Show this session as highlighted
        $output .= " preset'>";
        unset($_SESSION['preset_session_ID']);
      } else {
        $output .= "'>";
      }
      $output .= "<div class='row'>";
        $output .= "<div class='col-xs-4'>";
          $output .= "<a href='javascript:void(0)' class='session-title' sessionid='" . $sessionID . "'>Session #" . $sessionID . "</a>";
        $output .= "</div>";
        $output .= "<div class='col-xs-4 text-right'>";
          $output .= "<span>" . getReadableDatetime($sessionStart) . "</span>";
        $output .= "</div>";
        $output .= "<div class='col-xs-4 text-right'>";
          $output .= "<span>" . getReadableDatetime($sessionEnd) . "</span>";
        $output .= "</div>";
      $output .= "</div>";

      $output .= "<div id='graph-block-" . $sessionID . "' class='graph-block hidden'>";
        $output .= "<div class='skate-extra-info'>";
          $output .= "<span>Skate Length: " . getSecsBetween($sessionStart, $sessionEnd) . " Seconds</span><br>";
          $output .= "<span>Skate Distance: " . $sessionDistance . " KM</span>";
        $output .= "</div>";
        $output .= "<div id='graph-" . $sessionID . "' style='height: 300px; width: 100%;'></div>";
      $output .= "</div>";
    $output .= "</div>";

    return $output;
  }

  function getReadableDatetime($datetime) {
    $date = date("jS F", strtotime($datetime));
    $time = date("H:i:s A", strtotime($datetime));

    $datetime = $date . " - " . $time;

    return $datetime;
  }

  function getSecsBetween($datetime1, $datetime2) {
    $mins = date(strtotime($datetime2) - strtotime($datetime1));

    return $mins;
  }

?>
