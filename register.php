<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="./static/css/auth.css">

</head>
<body>
   
   <div class="form-container">

      <form action="postAction.php" method="post">
         <h3>register</h3>
         <input type="text" name="name" required placeholder="Username" class="box">
         <input type="email" name="email" required placeholder="Email" class="box">
         <input type="password" name="password" required placeholder="Password" class="box">
         <input type="password" name="cpassword" required placeholder="Confirm password" class="box">
         <?php

            if($_SESSION != '' && isset($_SESSION['error'])) {
               ?>
               <p class="error" style="color: red;"><?php echo $_SESSION['error']; ?></p>
               <?php
               unset($_SESSION['error']);
            }
         ?>
         <input type="submit" name="register" class="btn" value="register">
         <p>already have an account? <a href="login.php">login now</a></p>
      </form>

   </div>

</body>
</html>