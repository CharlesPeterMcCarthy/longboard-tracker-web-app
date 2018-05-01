<?php

  if (isset($_POST['info'])) {
    $info = json_decode($_POST['info'], true);
    $sessionID = $info['lastSessionID'];

    include_once "db_conn.php";

    $conn = getConn();
    $conn->begin_transaction();

    $response = GetSessions($conn, $sessionID, false);

    if ($response['isOk']) {
      $conn->commit();
    } else {
      $conn->rollback();
    }

    $conn->close();

    echo json_encode($response);
  }

      /*    Get skate sessions above supplied sessionID (new sessions)  */

  function GetSessions($conn, $sessionID, $singleOnly) {
    $sql = "SELECT session_id, session_start, session_end, session_distance
      FROM skate_sessions
      WHERE session_id"
    $sql .= $singleOnly ? " = " : " > ";
    $sql .= "ORDER BY session_id
      ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sessionID);

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
