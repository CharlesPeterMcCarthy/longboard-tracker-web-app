<?php

  session_start();

  if (isset($_POST['info']) && isset($_SESSION['arduino'])
    && isset($_SESSION['arduino']['userEmail']) && isset($_SESSION['arduino']['deviceID'])) {

    $info = json_decode($_POST['info'], true);
    $sessionID = $info['lastSessionID'];

    include_once "db_conn.php";

    $conn = getConn();
    $conn->begin_transaction();

    $response = GetSessions($conn, $sessionID);

    if ($response['isOk']) {
      $response['isLoggedIn'] = true;
      
      $conn->commit();
    } else {
      $conn->rollback();
    }

    $conn->close();

    echo json_encode($response);
  } else {
    echo json_encode([
      'isOk' => false,
      'isLoggedIn' => false
    ]);
  }

      /*    Get skate sessions above supplied sessionID (new sessions)  */

  function GetSessions($conn, $sessionID) {
    $sql = "SELECT session_id, session_start, session_end, session_distance
      FROM skate_sessions
      WHERE session_id > ?
      AND fk_device_id = ?
      ORDER BY session_id
      ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $sessionID, $_SESSION['arduino']['deviceID']);

    $isOk = $stmt->execute();

    if ($isOk) {
      $stmt->store_result();
      $rows = $stmt->num_rows;

      $sessions = [];

      if ($rows) {
        $stmt->bind_result($sessionID, $sessionStart, $sessionEnd, $sessionDistance);

        while ($stmt->fetch()) {
          $session = [
            'sessionID' => $sessionID,
            'sessionStart' => $sessionStart,
            'sessionEnd' => $sessionEnd,
            'sessionDistance' => $sessionDistance
          ];

          array_push($sessions, $session);
        }

        $response = [
          'isOk' => true,
          'hasSessions' => true,
          'sessions' => $sessions
        ];

        $stmt->free_result();
      } else {
        $response = [
          'isOk' => true,
          'hasSessions' => false
        ];
      }
    } else {
      $response = [
        'isOk' => false,
        'displayError' => "Error Getting Sessions"
      ];
    }

    return $response;
  }

?>
