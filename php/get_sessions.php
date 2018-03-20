<?php

  if (isset($_POST['info'])) {
    $info = json_decode($_POST['info'], true);
    $sessionID = $info['lastSessionID'];

    include_once $_SERVER['DOCUMENT_ROOT'] . "/arduino/php/db_conn.php";

    $conn = getConn();
    $conn->begin_transaction();

    $response = GetSessions($conn, $sessionID);

    if ($response['isOk']) {
      $conn->commit();
    } else {
      $conn->rollback();
    }

    $conn->close();

    echo json_encode($response);
  }

      /*    Get skate sessions above supplied sessionID (new sessions)  */

  function GetSessions($conn, $sessionID) {
    $sql = "SELECT session_id, session_start, session_end
      FROM skate_sessions
      WHERE session_id > ?
      ORDER BY session_id
      DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sessionID);

    $isOk = $stmt->execute();

    if ($isOk) {
      $stmt->store_result();
      $rows = $stmt->num_rows;

      $sessions = [];

      if ($rows) {
        $stmt->bind_result($sessionID, $sessionStart, $sessionEnd);

        while ($stmt->fetch()) {
          $session = [
            'sessionID' => $sessionID,
            'sessionStart' => $sessionStart,
            'sessionEnd' => $sessionEnd
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
