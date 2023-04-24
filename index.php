<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Sriracha&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="./static/css/lightbox.css">
    <link rel="stylesheet" href="./static/css/index.css">
    <script src="./static/JS/lightbox-plus-jquery.js"></script>
    <title>Image Gallery</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Image Gallery</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="add.php">Add Image</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php

        if($_SESSION != '' && isset($_SESSION['success'])) {
            ?>
            <div class="alert alert-success mt-5 alert-dismissible col-6 fade show" role="alert" style="margin: 0 auto;">
                <?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            unset($_SESSION['success']);
        }
    ?>

    <div class="sr-container">
        <div class="sr-gallery">
            <?php
                require_once 'DB.class.php';
                $db = new DB();
                
                $sql = 'SELECT * FROM images';
    
                $result = $db->db->query($sql);
                
                if($result->num_rows > 0) {
                    foreach($result as $row) {
                        ?>
                        <div class="sr-image-container">
                            <a href="<?php echo "uploads/images/".$row['file_name']; ?>" data-lightbox="models" data-title="<?php echo $row['title']; ?>">
                                <img src="<?php echo "uploads/images/".$row['file_name']; ?>" width="100px" alt="image" class="sr-image">
                            </a>
                            <div class="sr-options">
                                <a href="edit.php?id=<?php echo $row['id']; ?>">
                                    <img src="./static/images/pencil.png" alt="edit" class="sr-option edit">
                                </a>
                                <a href="#">
                                    <img src="./static/images/trash.png" alt="delete" class="sr-option delete">
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                } 
                else {
                ?>
                    <h1 style="color: gray;"><?php echo 'No records found!'; ?></h1>
                <?php
                die();
                }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>