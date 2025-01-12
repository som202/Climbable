<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = file_get_contents("php://input");
    if (ctype_alnum($input)) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "testdb";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: ".$conn->connect_error);
        }
        $stmt = $conn->prepare("SELECT username FROM users WHERE username LIKE ? AND visible = 1 ORDER BY username");
        $exp = "%$input%";
        $stmt->bind_param("s", $exp);
        $stmt->execute();
        $res = $stmt->get_result();
        $found_users = array();
        while($row=$res->fetch_assoc()) {
            $found_users[] = $row["username"];
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
    die("Not post requests aren't served on this page");
}
?>