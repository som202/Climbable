<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title>Climbable</title>
</head>
<body>
    <header>
        <a href="index.php">Climbable</a>
        <a href="search.php">Find climbers</a>
        <div id="account">
            <?php
            if (isset($_SESSION["user_id"])) {
                echo "<a href='profile.php?id=".$_SESSION["user_id"]."'>Profile</a>";
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
    <div id="content">
        <div> 
            <h1>What is it?</h1>
            <p>Climbable is an app designed for climbers to share their progress with each other.</p>
        </div>
        <div>
            <h1> How does this work?</h1>
            <p>
                Climbers can share their progress on their profile. For each ascent a climber can publish one post.<br><br>
            </p>
        </div>
        <?php 
        if (!isset($_SESSION["user_id"])) {
            echo <<<html
            <div class="tip">
            <strong>You can find other climbers using "Find climbers" section</strong>
            </div>
            <div class="tip">
            <strong>Sign up or log in to create your account</strong>
            </div>
            html;
        }
        ?>
    </div>
</body>
</html>