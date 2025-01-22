<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = file_get_contents("php://input");
    if (ctype_alnum($input)) {
        require('db_connect.php');
        if (isset($_SESSION["user_id"])) {
            $stmt = $conn->prepare("SELECT id, username FROM users WHERE username LIKE ? ORDER BY username");
        }
        else {
            $stmt = $conn->prepare("SELECT id, username FROM users WHERE username LIKE ? AND is_public = 1 ORDER BY username");
        }
        $exp = "%$input%";
        $stmt->bind_param("s", $exp);
        $stmt->execute();
        $res = $stmt->get_result();
        $found_users = array();
        while($row = $res->fetch_assoc()) {
            $found_users[$row["id"]] = $row["username"];
        }
        header('Content-Type: application/json');
        echo json_encode($found_users);
        $stmt->close();
        $conn->close();
    } 
    else {
        die("Username can't contain non-alphanumeric characters");
    }
}
else {
    die("Only post requests are served on this page");
}
?>