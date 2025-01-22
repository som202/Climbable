<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST["username"]) || !isset($_POST["password"])) {
        die("Missing post parameters");
    }
    $username = $_POST["username"];
    $password = $_POST["password"];
    if (ctype_alnum($username) && strlen($username) >= 3 && strlen($username) <= 35 
        && preg_match("/^[A-Za-z0-9~!@#$%^&*]+$/",$password) && strlen($password) >= 8 && strlen($password) <= 64) {

        require('db_connect.php');
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 1) {
            $row = $res->fetch_assoc();
            $user_id = $row["id"];
            $stmt->close();
            $stmt2 = $conn->prepare("SELECT hashed_value FROM passwords WHERE user_id = ?");
            $stmt2->bind_param("s", $user_id);
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            if ($res2->num_rows === 1) {
                $row2 = $res2->fetch_assoc();
                $user_pw_hash = $row2["hashed_value"];
                $stmt2->close();
                $conn->close();
                if (password_verify($password,$user_pw_hash)) {
                    $_SESSION["user_id"] = $user_id;
                    header("Location: profile.php?id=".urlencode($user_id));
                    exit();
                }
                else {
                    $_SESSION["wrong_password"] = "Wrong password";
                    $_SESSION["entered_username"] = $username;
                    header("Location: login.php");
                    exit();
                }
            }
            else {
                $stmt2->close();
                $conn->close();
                die("User is found, but their password is missing from db");
            }
        }
        else {
            $stmt->close();
            $conn->close();
            $_SESSION["user_not_registered"] = "This username isn't registered";
            $_SESSION["entered_username"] = $username;
            header("Location: login.php");
            exit();
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