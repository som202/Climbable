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
require('uid_to_username.php');
// page will die if the user with specified user_id doesn't exist
$profile_username = uid_to_username($_GET["id"]);
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
    <title><?php echo htmlspecialchars($profile_username)."'s profile"?></title>
</head>
<body>
    <header>
        <a href="index.php">Climbable</a>
        <a href="search.php">Find climbers</a>
        <div id="account">
            <?php
            if (isset($_SESSION["username"])) {
                echo "<a href='logout.php'>Log out</a>";
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
            <strong>Name</strong><br><span>John Doe</span><br>
            <strong>About</strong><br><span>I love gaming and climbing plastic rocks!</span>
        </div>
        <?php
        if ($_SESSION["username"] === $profile_username) {
            echo <<<html
            <div id="action-buttons">
                <button type="button" class="action-button">Post</button>
                <button type="button" class="action-button">Edit posts</button>
                <button type="button" class="action-button">Settings</button>
            </div>
            html;
        }
        ?>
    </div>
    <div id="table-container">
        <table>
            <thead>
                <tr>
                    <th>Grade</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Video</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>6b+</td>
                    <td>-</td>
                    <td>Smichoff</td>
                    <td>-</td>
                    <td>This climb hurt my shoulder :(</td>
                </tr>
                <tr>
                    <td>7a</td>
                    <td>Sneaky route</td>
                    <td>Trinactka wall</td>
                    <td>-</td>
                    <td>A very cool climb with lots of dynos</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="overlay">
        <form id="post-form">
            <button id="btn-close">&times;</button>
            <div class="field">
                <label for="grade">Grade:</label><br>
                <input type="text" id="grade" name="grade" placeholder="Give climb a grade" required>
            </div>
            <div class="field">
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" placeholder="Name of the climb">
            </div>
            <div class="field">
                <label for="location">Location:</label><br>
                <input type="text" id="location" name="location" placeholder="Where did you climb" required>
            </div>
            <div class="field">
                <label for="description">Description:</label><br>
                <textarea id="description" name="description" placeholder="Tell people more about the climb" rows="3" cols="30"></textarea>
            </div>
            <div class="field">
                <label for="video">Video:</label><br>
                <input type="file" id="video" name="video">
            </div>
            <div id="submit-div">
                <button type="submit">Create post</button>
            </div>
        </form>
    </div>
</body>
</html>