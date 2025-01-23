<?php
session_start();
if (isset($_SESSION["user_id"])) {
    header("Location: profile.php?id=".urlencode($_SESSION["user_id"]));
    exit();
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
    <link rel="stylesheet" href="css/signup.css">
    <script src="js/check_pw_match.js" defer></script>
    <script src="js/mark_fields.js" defer></script>
    <title>Sign up</title>
</head>
<body>
    <header>
        <a href="index.php">Climbable</a>
        <a href="search.php">Find climbers</a>
        <div id="account">
            <a href="login.php">Log in</a>
        </div>
    </header>
    <div id="signup-container">
        <form id="signup-form" method="post" action="signup_process.php">
            <div id="form-error">
            <?php
            if (isset($_SESSION["user_exists"])) {
                echo "<span>".$_SESSION["user_exists"]."</span>";
                unset($_SESSION["user_exists"]);
            }
            ?>
            </div>
            <div class="field">
                <label for="username">Username</label><span class="required">*</span><br>
                <input type="text" id="username" name="username" placeholder="Create a username" autocomplete="off" minlength="3" maxlength="35" required pattern="[A-Za-z0-9]+" title="Username can only contain letters and numbers" value="<?php echo isset($_SESSION["entered_username"]) ? htmlspecialchars($_SESSION["entered_username"]) : ''; unset($_SESSION["entered_username"]); ?>"><br>
                <span class="required-notice">required</span>
            </div>
            <div class="field">
                <label for="password">Password</label><span class="required">*</span><br>
                <input type="password" id="password" name="password" placeholder="Create a password" autocomplete="off" minlength="8" maxlength="64" pattern="[A-Za-z0-9~!@#$%^&*]+" title="Password can only contain letters, numbers and following characters: ~!@#$%^&*" required><br>
                <span class="required-notice">required</span>
            </div>
            <div class="field">
                <label for="password-confirm">Confirm password</label><span class="required">*</span><br>
                <input type="password" id="password-confirm" name="password-confirm" placeholder="Type the same password" minlength="8" maxlength="64" required><br>
                <span class="required-notice">required</span>
            </div>
            <div id="button-div">
                <button type="submit">Sign up</button>
            </div>
        </form>
    </div>
</body>
</html>