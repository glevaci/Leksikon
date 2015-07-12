<?php
	header('Content-type: text/html; charset=UTF-8');
	require_once("php_functions.php");
	session_start();

	$action = isset($_GET["action"]) ? $_GET["action"] : null;

	$passkey = isset($_GET["passkey"]) ? $_GET["passkey"] : null;
    if ($passkey != null) {
        $stmt = $conn->prepare("SELECT * FROM Users WHERE code=:code");
        $stmt->bindParam(':code', $passkey, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchObject();

        if ($result) {
			$query = $conn->prepare("UPDATE Users SET admin=:admin WHERE code=:code");
            $admin = 1;
            $query->bindParam(":admin", $admin, PDO::PARAM_INT);
            $query->bindParam(":code", $passkey, PDO::PARAM_STR);
            $query->execute();
        }
    }

    header("Location: postlogin.php");