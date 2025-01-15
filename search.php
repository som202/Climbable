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
    <link rel="stylesheet" href="css/search.css">
    <script src="js/search.js" defer></script>
    <title>User search</title>
</head>
<body>
    <header>
        <a href="index.php">Climbable</a>
        <div id="account">
            <?php
            if (isset($_SESSION["username"]) && isset($_SESSION["user_id"])) {
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
    <div id="search-container">
        <div id="search-bar">
            <input type="text" id="search-field" name="name" placeholder="Search for climbers" autocomplete="off">
        </div>
        <div id="results">
            <p class="user-tip">Use the search bar above to look for climbers!</p>
            <p class="print-info">The found users are displayed here</p>
            <ol>
            </ol>
        </div>
    </div>
</body>
</html>