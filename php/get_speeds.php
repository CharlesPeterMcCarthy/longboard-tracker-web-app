<?php

  if (isset($_POST['info'])) {
    $info = json_decode($_POST['info'], true);
    $sessions = $info['sessions'];

    include_once "db_conn.php";

    $conn = getConn();
    $conn->begin_transaction();

    $sessionIDs = [];

    foreach ($sessions as $session) {
      array_push($sessionIDs, $session['sessionID']);
    }

    $response = GetSpeeds($conn, $sessionIDs);

    if ($response['isOk']) {
      $speeds = $response['speeds'];

      $sessions = AddSpeedsToSessions($sessions, $speeds);

      $response = [
        'isOk' => true,
        'sessions' => $sessions
      ];

      $conn->commit();
    } else {
      $conn->rollback();
    }

    $conn->close();

    echo json_encode($response);
  }

  function GetSpeeds($conn, $sessionIDs) {
    $IDs = implode($sessionIDs, ",");

    $sql = "SELECT speed_id, speed_kph, fk_session_id
      FROM skate_speeds
      WHERE fk_session_id IN ($IDs)
      ORDER BY speed_id";

    $stmt = $conn->prepare($sql);
    $isOk = $stmt->execute();

    if ($isOk) {
      $speeds = [];

      $stmt->bind_result($speedID, $speedKPH, $sessionID);

      while ($stmt->fetch()) {
        $speed = [
          'speedID' => $speedID,
          'speedKPH' => $speedKPH,
          'sessionID' => $sessionID
        ];

        array_push($speeds, $speed);
      }

      $response = [
        'isOk' => true,
        'speeds' => $speeds
      ];

      $stmt->free_result();
    } else {
      $response = [
        'isOk' => false,
        'displayError' => "Error Getting Speeds"
      ];
    }

    return $response;
  }

  function AddSpeedsToSessions($sessions, $speeds) {
    for ($i = 0; $i < count($sessions); $i++) {
      $curSession = $sessions[$i];

      for ($j = 0; $j < count($speeds); $j++) {
        $curSpeed = $speeds[$j];

        if ($curSession['sessionID'] == $curSpeed['sessionID']) {
          if (!isset($curSession['speeds'])) $curSession['speeds'] = [];

          unset($curSpeed['sessionID']);      // Remove key not needed

          array_push($curSession['speeds'], $curSpeed);
        }
      }

      $sessions[$i] = $curSession;    // Save updated version
    }

    return $sessions;
  }

?>
