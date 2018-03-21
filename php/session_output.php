<?php

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

    $output = "";

    $output .= "<div class='session-block'>";
      $output .= "<div class='row'>";
        $output .= "<div class='col-xs-4'>";
          $output .= "<a href='javascript:void(0)' class='session-title' sessionid='" . $sessionID . "'>Session #" . $sessionID . "</a>";
        $output .= "</div>";
        $output .= "<div class='col-xs-4 text-right'>";
          $output .= getReadableDatetime($sessionStart);
        $output .= "</div>";
        $output .= "<div class='col-xs-4 text-right'>";
          $output .= getReadableDatetime($sessionEnd);
        $output .= "</div>";
      $output .= "</div>";

      $output .= "<div id='graph-block-" . $sessionID . "' class='graph-block hidden'>";
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

?>
