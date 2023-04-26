<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Sriracha&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="./static/css/addEdit.css">
      <title>Edit Image</title>
  </head>
  <body>
    <h1 class="heading">Edit Image</h1>

    <?php
      require_once 'DB.class.php';
      $db = new DB();

      $id = $_GET['id'];

      $sql = "SELECT * FROM pictures WHERE id='$id'";

      $result = $db->db->query($sql);

      if($result->num_rows > 0) {
        foreach($result as $row) {
          ?>

            <form action="postAction.php" method="post" enctype="multipart/form-data">

              <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

              <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" value="<?php echo $row['title']; ?>" required name="title" class="form-control">
              </div>
              
              <div class="form-group">
                <label for="filename">Image:</label>
                <img src="<?php echo "uploads/".$row['file_name']; ?>" alt="Image" width="150px">
                <input type="file" id="filename" name="filename" value="<?php echo $row['file_name']; ?>" class="form-control-file">
                <input type="hidden" name="old_image" value="<?php echo $row['file_name']; ?>">
              </div>

              <?php

                if($_SESSION != '' && isset($_SESSION['error'])) {
                    ?>
                    <p class="error"><?php echo $_SESSION['error']; ?></p>
                    <?php
                    unset($_SESSION['error']);
                  }
              ?>

              <div class="form-buttons">
                <input type="submit" value="Update" name="update" class="btn btn-primary">
                <a href="index.php" class="back">Back</a>
              </div>
            </form>
          <?php
        }
      }
      else {
        ?>
          <h1 style="color: gray;"><?php echo 'No such record found!'; ?></h1>
        <?php
        die();
      }
    ?>

  </body>
</html>