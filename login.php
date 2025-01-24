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
    <link rel="stylesheet" href="css/login.css">
    <script src="js/mark_fields.js" defer></script>
    <title>Log in</title>
</head>
<body>
    <header>
        <a href="index.php">Climbable</a>
        <a href="search.php">Find climbers</a>
        <div id="account">
            <a href="signup.php">Sign up</a>
        </div>
    </header>
    <div id="login-container">
        <?php
        if (isset($_SESSION["signup_success"])) {
            echo "<div class='tip'><span>".$_SESSION["signup_success"]."</span></div>";
            unset($_SESSION["signup_success"]);
        } 
        else if (isset($_SESSION["login_first_tip"])) {
            echo "<div class='tip'><span>".$_SESSION["login_first_tip"]."</span></div>";
            unset($_SESSION["login_first_tip"]);
        }
        ?>
        <form id="login-form" method="post" action="login_process.php">
            <div id="form-error">
            <?php
            if (isset($_SESSION["user_not_registered"])) {
                echo "<span>".$_SESSION["user_not_registered"]."</span>";
                unset($_SESSION["user_not_registered"]);
            }
            else if (isset($_SESSION["wrong_password"])) {
                echo "<span>".$_SESSION["wrong_password"]."</span>";
                unset($_SESSION["wrong_password"]);
            }
            ?>
            </div>
            <div class="field">
                <label for="username">Username</label><span class="required">*</span><br>
                <input type="text" id="username" name="username" placeholder="Username" autocomplete="off" required minlength="3" maxlength="35" pattern="[A-Za-z0-9]+" title="Username can only contain letters and numbers" value="<?php echo isset($_SESSION["entered_username"]) ? htmlspecialchars($_SESSION["entered_username"]) : ''; unset($_SESSION["entered_username"]);?>">
            </div>
            <div class="field" id="password-div">
                <label for="password">Password</label><span class="required">*</span><br>
                <input type="password" id="password" name="password" placeholder="Password" required minlength="8" maxlength="64" pattern="[A-Za-z0-9~!@#$%^&*]+" title="Password can only contain letters, numbers and following characters: ~!@#$%^&*">
            </div>
            <div id="button-div">
                <button type="submit">Sign in</button>
            </div>
        </form>
    </div>
</body>
</html>