<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["user_id"])) {
    if (isset($_POST["username"])) {
        if (ctype_alnum($_POST["username"]) && strlen($_POST["username"]) >= 3 && strlen($_POST["username"]) <= 35) {

            $username_to_set = $_POST["username"];
            require('db_connect.php');
            $stmt = $conn->prepare("SELECT id, username FROM users WHERE username = ?");
            $stmt->bind_param("s",$username_to_set);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows === 0) {
                $stmt->close();
                $stmt2 = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
                $stmt2->bind_param("si", $username_to_set, $_SESSION["user_id"]);
                $stmt2->execute();
                $stmt2->close();
                $conn->close();
                header("Location: profile_settings.php");
                exit();
            }
            else {
                $row = $res->fetch_assoc();
                $found_username_id = $row["id"];
                $stmt->close();
                $conn->close();
                if ($_SESSION["user_id"] !== $found_username_id) {
                    $_SESSION["user_exists"] = "Specified username already exists";
                    $_SESSION["entered_username"] = $username;
                }
                header("Location: profile_settings.php");
                exit();
            }
        }
        else {
            die("Username doesn't match the requested format");
        }
    }
    else if (isset($_POST["name"]) || isset($_POST["about"])) {
        $field = isset($_POST["name"]) ? "name" : "about";
        $value = $_POST[$field];
        $maxLength = $field === "name" ? 128 : 256;
    
        if (strlen($value) <= $maxLength) {
            require('db_connect.php');
            $stmt = $conn->prepare("UPDATE users SET $field = ? WHERE id = ?");
            $stmt->bind_param("si",$value,$_SESSION["user_id"]);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            header("Location: profile_settings.php");
            exit();

        } else {
            die($field . " field doesn't match the requested format");
        }
    }
    else if (isset($_POST["cur-password"]) && isset($_POST["password"]) && isset($_POST["password-confirm"])) {
        $cur_pw = $_POST["cur-password"];
        $new_pw = $_POST["password"];
        $new_pw_confirm = $_POST["password-confirm"];
        if (preg_match("/^[A-Za-z0-9~!@#$%^&*]+$/",$cur_pw) && strlen($cur_pw) >= 8 && strlen($cur_pw) <= 64 && 
            preg_match("/^[A-Za-z0-9~!@#$%^&*]+$/",$new_pw) && strlen($new_pw) >= 8 && strlen($new_pw) <= 64) {

                if ($new_pw !== $new_pw_confirm) {
                    die("Passwords didn't match!");
                }
                require('db_connect.php');
                $stmt = $conn->prepare("SELECT hashed_value FROM passwords WHERE user_id = ?");
                $stmt->bind_param("i",$_SESSION["user_id"]);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows === 1) {
                    $row = $res->fetch_assoc();
                    $cur_pw_hash = $row["hashed_value"];
                    $stmt->close();
                    if (password_verify($cur_pw, $cur_pw_hash)) {
                        $new_pw_hash = password_hash($new_pw, PASSWORD_BCRYPT);
                        $stmt2 = $conn->prepare("UPDATE passwords SET hashed_value = ? WHERE user_id = ?");
                        $stmt2->bind_param("si",$new_pw_hash,$_SESSION["user_id"]);
                        $stmt2->execute();
                        $stmt2->close();
                        $conn->close();
                        $_SESSION["password_changed"] = "Password was changed successfully!";
                        header("Location: profile_settings.php");
                        exit();
                    }
                    else {
                        $conn->close();
                        $_SESSION["wrong_password"] = "Wrong password, changes were aborted.";
                        header("Location: profile_settings.php");
                        exit();
                    }
                }
                else {
                    $stmt->close();
                    $conn->close();
                    die("Your account is missing password in the db");
                }
        }
        else {
            die("Password doesn't match the requested format");
        }
    }
    else if (isset($_POST["visibility"])) {
        if ($_POST["visibility"] === "public" || $_POST["visibility"] === "private") {
            $is_public = ($_POST["visibility"] === "public") ? 1 : 0;
            require('db_connect.php');
            $stmt = $conn->prepare("UPDATE users SET is_public = ? WHERE id = ?");
            $stmt->bind_param("ii", $is_public, $_SESSION["user_id"]);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            
            $_SESSION["visibility_changed"] = "Visibility was changed successfully!";
            header("Location: profile_settings.php");
            exit();
        }
        else {
            die("Visibility data doesn't match the requested format");
        }
    }
    else if (isset($_FILES["image"])) {

        if ($_FILES["image"]["error"] !== 0) {
            die("There was en error uploading your image. Error code: ".$_FILES["image"]["error"]);
        }
        $image_file_type = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        if (!in_array($image_file_type, ['jpg', 'jpeg', 'png'])) {
            die("This video format is not supported. List of supported formats: mp4,avi,mov,mkv,flv,wmv");
        }
        if ($_FILES['image']['size'] > 10485760) {
            die("Image file size is more than 10MB limit.");
        }

        $new_file_id = uniqid();
        $target_dir = "profilepics/";
        while (file_exists($target_dir.$new_file_id.".".$image_file_type)) {
            $new_file_id = uniqid();
        }
        $new_image_name = $new_file_id .".".$image_file_type;

        require('profile_image_process.php');
        
        $destination = $target_dir . $new_image_name;
        compressImage($_FILES["image"]["tmp_name"], 256, 256, $destination);
        insert_image($destination, $_SESSION["user_id"]);
        header("Location: profile_settings.php");
        exit();
    }
    else if (isset($_POST["password-confirm-delete"])) {
        $pw = $_POST["password-confirm-delete"];
        if (preg_match("/^[A-Za-z0-9~!@#$%^&*]+$/",$pw) && strlen($pw) >= 8 && strlen($pw) <= 64) {
            require('db_connect.php');
            $stmt = $conn->prepare("SELECT hashed_value FROM passwords WHERE user_id = ?");
            $stmt->bind_param("i", $_SESSION["user_id"]);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows === 0) {
                $stmt->close();
                $conn->close();
                die("Password is missing from the database");
            }
            else {
                $row = $res->fetch_assoc();
                $pw_hash = $row["hashed_value"];
                $stmt->close();
                if (password_verify($pw, $pw_hash)) {
                    $stmt2 = $conn->prepare("DELETE FROM users WHERE id = ?");
                    $stmt2->bind_param("i", $_SESSION["user_id"]);
                    $stmt2->execute();
                    $stmt2->close();
                    $conn->close();
                    header("Location: logout.php");
                    exit();
                }
                else {
                    $conn->close();
                    $_SESSION["wrong_password"] = "Wrong password, changes were aborted.";
                    header("Location: profile_settings.php");
                    exit();
                }
            }
        }
        else {
            die("Password doesn't match the requested format");
        }
    }
    else {
        die("Missing post parameters");
    }
}
else {
    die("This page only supports post requests from authenticated users");
}
?>