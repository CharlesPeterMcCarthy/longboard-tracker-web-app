<?php

  function ConfirmDevice($conn, $deviceID, $sessionID) {
    $sql = "SELECT COUNT(*)
      FROM skate_sessions
      WHERE fk_device_id = ?
      AND session_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $deviceID, $sessionID);

    $isOk = $stmt->execute();

    if ($isOk) {
      $stmt->bind_result($count);
      $stmt->fetch();

      if ($count) {
        $response = [
          'isOk' => true
        ];
      } else {
        $response = [
          'isOk' => false,
          'displayError' => "Failed To Confirm Device"
        ];
      }

      $stmt->free_result();
    } else {
      $response = [
        'isOk' => false,
        'displayError' => "Error Confirming Device"
      ];
    }

    return $response;
  }

?>
