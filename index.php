<?php 

session_start(); 

if(isset($_GET['logout'])) {
    unset($_SESSION['user_id']);
    session_destroy();
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Fleur+De+Leah&family=Roboto&family=Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./static/css/index.css">
    <title>Image Gallery</title>
</head>
<body>
    <div id="landing-header" class="container">
        <h1>We all <span>❤</span> Galleries</h1>

        <?php

        if($_SESSION != '' && !isset($_SESSION['user_id'])) {
        ?>
            <a href="login.php" class="btn btn-lg btn-success">Create Your Own</a>
        <?php 
        }
        elseif($_SESSION != '' && isset($_SESSION['user_id'])) {
        ?>
            <a href="gallery.php" class="btn btn-lg btn-success">Create Your Own</a>
        <?php
        }
        ?>
    </div>

    <ul class="slideshow">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</body>
</html>