<?php

  session_start();

  if (isset($_POST['info'])) {
    $info = json_decode($_POST['info'], true);
    $email = $info['email'];
    $pass = $info['pass'];

    include_once "encrypt_pass.php";
    include_once "db_conn.php";

    $conn = getConn();
    $conn->begin_transaction();

    $response = AttemptLogin($conn, $email);

    if ($response['emailExists']) {
      $response['validLogin'] = CheckPasswordsMatch($pass, $response['hashedPass']);

      unset($response['hashedPass']);
    }

    if ($response['isOk']) {
      if (isset($response['validLogin']) && $response['validLogin']) CreateUserSession($email);

      $conn->commit();
    } else {
      $conn->rollback();
    }

    $conn->close();

    echo json_encode($response);
  }

  function AttemptLogin($conn, $email) {
    $sql = "SELECT device_name, password
      FROM approved_devices
      WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);

    $isOk = $stmt->execute();

    if ($isOk) {
      $stmt->store_result();
      $rows = $stmt->num_rows;

      if ($rows) {
        $stmt->bind_result($deviceName, $hashedPass);
        $stmt->fetch();

        $response = [
          'isOk' => true,
          'emailExists' => true,
          'deviceName' => $deviceName,
          'hashedPass' => $hashedPass
        ];

        $stmt->free_result();
      } else {
        $response = [
          'isOk' => true,
          'emailExists' => false
        ];
      }
    } else {
      $response = [
        'isOk' => false,
        'displayError' => "Error Logging In"
      ];
    }

    return $response;
  }

  function CheckPasswordsMatch($passEntered, $hashedPass) {
    if (hash_equals($hashedPass, crypt($passEntered, $hashedPass))) {
      return true;
    } else {
      return false;
    }
  }

  function CreateUserSession($email) {
    $_SESSION['arduino']['userID'] = $email;
  }

?>
