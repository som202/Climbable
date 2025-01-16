<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!isset($_GET["id"])) {
        die("Get request is missing parameters that specify the user");
    }
}
else {
    die("This page only supports get requests");
}

require('uid_get_data.php');
// page will die if the user with specified user_id doesn't exist
$profile_username = uid_get_data($_GET["id"],"username");
$name = uid_get_data($_GET["id"],"name");
if (is_null($name)) {
    $name = "-";
}
$about = uid_get_data($_GET["id"],"about");
if (is_null($about)) {
    $about = "-";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile.css">
    <script src="js/profile.js" defer></script>
    <script src="js/mark_fields.js" defer></script>
    <title><?php echo htmlspecialchars($profile_username)."'s profile"?></title>
</head>
<body>
    <header>
        <a href="index.php">Climbable</a>
        <a href="search.php">Find climbers</a>
        <div id="account">
            <?php
            if (isset($_SESSION["user_id"])) {
                if ($_GET["id"] == $_SESSION["user_id"]) {
                    echo "<a href='logout.php'>Log out</a>";
                }
                else {
                    echo "<a href='profile.php?id=".$_SESSION["user_id"]."'>Profile</a>";
                }
            }
            else {
                echo <<<html
                <a href="login.php">Log in</a>
                <a href="signup.php">Sign up</a>
                html;
            }
            
            ?>
        </div>
    </header>
    <div id="profile">
        <div id="profile-pic">
            <img src="icons/user.png" alt="profile picture">
        </div>
        <div id="profile-description">
            <strong>Username</strong><br><span><?php echo htmlspecialchars($profile_username);?></span><br>
            <strong>Name</strong><br><span><?php echo htmlspecialchars($name);?></span><br>
            <strong>About</strong><br><span><?php echo htmlspecialchars($about);?></span>
        </div>
        <?php
        if ($_SESSION["user_id"] == $_GET["id"]) {
            echo <<<html
            <div id="action-buttons">
                <button type="button" class="action-button">Post climb</button>
                <button type="button" class="action-button">Edit posts</button>
                <button type="button" class="action-button">Settings</button>
            </div>
            html;
        }
        ?>
    </div>
    <div id="table-container">
    <?php
    $stmt = $conn->prepare("SELECT grade, name, location, description, video_file FROM posts WHERE user_id = ?");
    $stmt->bind_param("i", $_GET["id"]);
    if (!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        die("Database error: ".$stmt->error);
    }
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        echo <<<html
        <table>
        <thead>
            <tr>
                <th>Grade</th>
                <th>Name</th>
                <th>Location</th>
                <th>Description</th>
                <th>Video</th>
            </tr>
        </thead>
        <tbody>
        html;
        while($row = $res->fetch_assoc()) {
            echo "<tr><td>".htmlspecialchars($row["grade"])."</td>";
            echo "<td>".htmlspecialchars($row["name"] === "" ? "-" : "")."</td>";
            echo "<td>".htmlspecialchars($row["location"])."</td>";
            echo "<td>".htmlspecialchars($row["description"] === "" ? "-" : "")."</td>";
            if ($row["video_file"] === NULL) {
                echo "<td>-</td></tr>";
            }
            else {
                echo "<td><a href='".htmlspecialchars($row["video_file"])."'>".htmlspecialchars(basename($row["video_file"]))."</a>"."</td></tr>";
            }
        }
        echo <<<html
        </tbody>
        </table>
        html;
    }
    else {
        echo "<span>User has no posts yet</span>";
    }
    $stmt->close();
    $conn->close();
    ?>
    </div>
    <?php
    if ($_SESSION["user_id"] == $_GET["id"]) {
        echo <<<html
        <div id="overlay">
            <form id="post-form" method="post" enctype="multipart/form-data" action="post_process.php">
                <button id="btn-close">&times;</button>
                <div id="form-error">
                </div>
                <div class="field">
                    <label for="grade">Grade:</label><span class="required">*</span><br>
                    <input type="text" id="grade" name="grade" placeholder="Give climb a grade" required autocomplete="off" maxlength="15">
                </div>
                <div class="field">
                    <label for="name">Name:</label><br>
                    <input type="text" id="name" name="name" placeholder="Name of the climb" autocomplete="off" maxlength="60">
                </div>
                <div class="field">
                    <label for="location">Location:</label><span class="required">*</span><br>
                    <input type="text" id="location" name="location" placeholder="Where did you climb" required autocomplete="off" maxlength="60">
                </div>
                <div class="field">
                    <label for="description">Description:</label><br>
                    <textarea id="description" name="description" placeholder="Tell people more about the climb" rows="3" cols="30" maxlength="256"></textarea>
                </div>
                <div class="field">
                    <label for="video">Video:</label><br>
                    <input type="file" id="video" name="video" accept="video/*">
                </div>
                <div id="submit-div">
                    <button type="submit">Create post</button>
                </div>
            </form>
        </div>
        html;
    }
    ?>
    
</body>
</html>