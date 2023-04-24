<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Sriracha&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="./static/css/add.css">
      <title>Add Image</title>
  </head>
  <body>
    <!-- <h1>Add Image</h1> -->

    <form action="postAction.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" id="title" required name="title" class="form-control">
      </div>
      
      <div class="form-group">
        <label for="filename">Image:</label>
        <input type="file" id="filename" required name="filename" class="form-control-file">
      </div>

      <?php

        if($_SESSION != '' && isset($_SESSION['error'])) {
            ?>
            <p class="error"><?php echo $_SESSION['error']; ?></p>
            <?php
            unset($_SESSION['error']);
          }
      ?>

      <input type="submit" value="Upload" name="submit" class="btn btn-primary">
    </form>
  </body>
</html>