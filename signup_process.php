<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    if (ctype_alnum($username) && strlen($username) >= 3 && strlen($username) <= 35 
        && preg_match("/^[A-Za-z0-9~!@#$%^&*]+$/",$password) && strlen($password) >= 8 && strlen($password) <= 64) {

        require('db_connect.php');
        $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        if(!$stmt->execute()) {
            die("Database error: ".$stmt->error);
        }
        $stmt->store_result();
        if ($stmt->num_rows === 0) {
            $stmt->close();
            $stmt2 = $conn->prepare("INSERT INTO users (username) VALUES (?)");
            $stmt2->bind_param("s", $username);
            if(!$stmt2->execute()) {
                die("Database error: ".$stmt2->error);
            }
            $user_id = $stmt2->insert_id;
            $hashed_pw = password_hash($password, PASSWORD_BCRYPT);
            $stmt2->close();
            $stmt3 = $conn->prepare("INSERT INTO passwords (user_id, hashed_value) VALUES (?,?)");
            $stmt3->bind_param("is", $user_id, $hashed_pw);
            if(!$stmt3->execute()) {
                die("Database error: ".$stmt3->error);
            }
            $stmt3->close();
            $conn->close();
            $_SESSION["signup_success"] = "You have successfully signed up, please log in to continue!";
            header("HTTP/1.1 303 See Other");
            header("Location: login.php");
            exit();
        }
        else {
            die("Specified username already exists");
        }
    }
    else {
        die("Data doesn't match the requested format");
    }
}
else {
    die("This page only supports post requests");
}
?>