<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="./static/css/auth.css">

</head>
<body>
   
   <div class="form-container">

      <form action="postAction.php" method="post">
         <h3>login</h3>
         <input type="email" name="email" required placeholder="Email" class="box">
         <input type="password" name="password" required placeholder="Password" class="box">
         <?php

            if($_SESSION != '' && isset($_SESSION['error'])) {
               ?>
               <p class="error" style="color: red;"><?php echo $_SESSION['error']; ?></p>
               <?php
               unset($_SESSION['error']);
            }
         ?>
         <input type="submit" name="login" class="btn" value="login">
         <p>don't have an account? <a href="register.php">register now</a></p>
      </form>

   </div>

</body>
</html>