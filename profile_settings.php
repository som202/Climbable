<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!isset($_SESSION["user_id"])) {
        die("You have to log in to view this page");
    }
}
else {
    die("This page only supports get requests");
}

require('uid_get_data.php');
// page will die if the user with specified id doesn't exist
$profile_username = uid_get_data($_SESSION["user_id"],"username");
$name = uid_get_data($_SESSION["user_id"],"name");
if (is_null($name) || $name === "") {
    $name = "-";
}
$about = uid_get_data($_SESSION["user_id"],"about");
if (is_null($about) || $about === "") {
    $about = "-";
}
$picture = uid_get_data($_SESSION["user_id"],"picture_file");
if (is_null($picture) || $picture === "") {
    $picture = "pfp/user_default.png";
}
$visibility = uid_get_data($_SESSION["user_id"],"is_public");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile_settings.css">
    <script src="js/profile_settings.js" defer></script>
    <script src="js/mark_fields.js" defer></script>
    <script src="js/check_pw_match.js" defer></script>
    <title>Profile settings</title>
</head>
<body>
    <header>
        <?php echo "<a href='profile.php?id=".$_SESSION["user_id"]."'>Close settings</a>"; ?>
    </header>
    <div id="settings">
        <div id="profile-settings">
            <div id="pic-container">
                <div id="profile-pic">
                    <img src="<?php echo htmlspecialchars($picture, ENT_QUOTES); ?>" alt="profile picture">
                </div>
                <button type="button" id="pic-edit-button">Upload new profile pic</button>
                <form id="picture-edit" action="profile_update.php" method="post" enctype="multipart/form-data">
                    <div id="picture-error"></div>
                    <label for="image">Upload an image:</label><br>
                    <input type="file" id="image" name="image" accept="image/*" required><br>
                    <button type="submit" class="save-button">save</button>
                    <button type="button" id="picture-edit-cancel" class="cancel-button">cancel</button>
                </form>                    
            </div>
            <div id="profile-info">
                <label for="username">Username</label><br>
                <span id="username-display"><?php echo htmlspecialchars($profile_username);?></span>
                <button type="button" id="username-edit-button" class="edit-button" data-field="username">edit</button>
                <form id="username-edit" action="profile_update.php" method="POST">
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($profile_username); ?>" required minlength="3" maxlength="35" pattern="[A-Za-z0-9]+" title="Username can only contain letters and numbers" autocomplete="off">
                    <button type="submit" class="save-button">save</button>
                    <button type="button" class="cancel-button" data-field="username">cancel</button>
                </form><br>
                <?php
                if (isset($_SESSION["user_exists"])) {
                    echo "<div class='error'><span>".$_SESSION["user_exists"]."</span></div>";
                    unset($_SESSION["user_exists"]);
                }
                ?>
                <label for="name">Name</label><br>
                <span id="name-display"><?php echo htmlspecialchars($name); ?></span>
                <button type="button" id="name-edit-button" class="edit-button" data-field="name">edit</button>
                <form id="name-edit" action="profile_update.php" method="POST">
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" maxlength="128">
                    <button type="submit" class="save-button">save</button>
                    <button type="button" class="cancel-button" data-field="name">cancel</button>
                </form><br> 

                <label for="about">About</label><br>
                <span id="about-display"><?php echo htmlspecialchars($about); ?></span>
                <button type="button" id="about-edit-button" class="edit-button" data-field="about">edit</button>
                <form id="about-edit" action="profile_update.php" method="post">
                    <textarea name="about" id="about" maxlength="256"><?php echo htmlspecialchars($about); ?></textarea>
                    <button type="submit" class="save-button">save</button>
                    <button type="button" class="cancel-button" data-field="about">cancel</button>
                </form>
            </div>    
        </div>
        <div id="profile-data">
            <?php
            if (isset($_SESSION["password_changed"])) {
                echo "<div><span>".$_SESSION["password_changed"]."</span></div>";
                unset($_SESSION["password_changed"]);
            }
            else if (isset($_SESSION["wrong_password"])) {
                echo "<div id='pw-error'><span>".$_SESSION["wrong_password"]."</span></div>";
                unset($_SESSION["wrong_password"]);
            }
            else if (isset($_SESSION["visibility_changed"])) {
                echo "<div><span>".$_SESSION["visibility_changed"]."</span></div>";
                unset($_SESSION["visibility_changed"]);
            }
            ?>
            <button type="button" id="password-edit-button">Change password</button><br>
            <form id="password-edit" action="profile_update.php" method="post">
                <div id="password-error">
                </div>
                <div class="field">
                    <label for="cur-password">Current password:</label><span class="required">*</span><br>
                    <input type="password" id="cur-password" name="cur-password" required autocomplete="off" minlength="8" maxlength="64" pattern="[A-Za-z0-9~!@#$%^&*]+" title="Password can only contain letters, numbers and following characters: ~!@#$%^&*"><br>
                </div>
                <div class="field">
                    <label for="password">New password:</label><span class="required">*</span><br>
                    <input type="password" id="password" name="password" required autocomplete="off" minlength="8" maxlength="64" pattern="[A-Za-z0-9~!@#$%^&*]+" title="Password can only contain letters, numbers and following characters: ~!@#$%^&*"><br>
                </div>
                <div class="field">
                    <label for="password-confirm">Confirm new password:</label><span class="required">*</span><br>
                    <input type="password" id="password-confirm" name="password-confirm" required autocomplete="off" minlength="8" maxlength="64" pattern="[A-Za-z0-9~!@#$%^&*]+" title="Password can only contain letters, numbers and following characters: ~!@#$%^&*"><br>
                </div>   
                <button type="submit" class="save-button">save</button>
                <button type="button" id="password-edit-cancel" class="cancel-button">cancel</button>
            </form>
            <button type="button" id="visibility-edit-button">Change profile visibility</button>
            <form id="visibility-edit" action="profile_update.php" method="post">
                <div id="form-msg">
                </div>
                <input type="radio" id="public" name="visibility" value="public" <?php echo $visibility === 1 ? "checked" : "" ?>>
                <label for="public">Public</label><br>
                <input type="radio" id="private" name="visibility" value="private" <?php echo $visibility === 0 ? "checked" : "" ?>>
                <label for="private">Private</label><br>
                <button type="submit" class="save-button">save</button>
                <button type="button" id="visibility-edit-cancel" class="cancel-button">cancel</button>
            </form>
        </div>
    </div>
</body>
</html>