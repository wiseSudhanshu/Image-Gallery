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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fleur+De+Leah&family=Roboto&family=Sriracha&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="./static/css/lightbox.css">
    <link rel="stylesheet" href="./static/css/index.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="./static/JS/lightbox-plus-jquery.js"></script>
    <title>Image Gallery</title>
</head>
<body>
    <nav>
        <div class="brand">
            <a class="navbar-brand" href="#">Galleria</a>
        </div>
        <div class="buttons">
            <form action="postAction.php" method="POST">
                <button type="submit" class="button-with-image" name="add">
                    <a href="add.php"><img src="./static/images/add.png" alt="Image"></a>
                    <span class="toolTipText">Add Image</span>
                </button>
            </form>
            <?php
                if(isset($_SESSION['user_id'])) {
                    ?>
                    <button class="button-with-image logout" onclick="return confirm('Are you sure, you want to logout?');">
                        <a href="index.php?logout=<?php echo $_SESSION['user_id']; ?>"><img src="./static/images/logout.png" alt="Image"></a>
                        <span class="toolTipText two">Logout</span>
                    </button>
                    <?php
                }
            ?>
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
                
                $sql = 'SELECT * FROM pictures';
    
                $result = $db->db->query($sql);
                
                if($result->num_rows > 0) {
                    foreach($result as $row) {
                        ?>
                        <div class="sr-image-container">
                            <a href="<?php echo "uploads/".$row['file_name']; ?>" data-lightbox="models" data-title="<?php echo $row['title']; ?>">
                                <img src="<?php echo "uploads/".$row['file_name']; ?>" width="100px" alt="image" class="sr-image">
                            </a>
                            <div class="sr-options">
                            <?php 
                            
                            if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['added_by']) {
                            
                            ?>
                                <a href="edit.php?id=<?php echo $row['id']; ?>">
                                    <img src="./static/images/pencil.png" alt="edit" class="sr-option edit">
                                </a>
                                <form id="delete-form" action="postAction.php" method="POST">
                                    <input type="hidden" name="del_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="del_image" value="<?php echo $row['file_name']; ?>">
                                    <button type="submit" name="delete_image" class="delete-button" onclick="return confirm('Are you sure, you want to permanently delete it?');">
                                        <img src="./static/images/trash.png" alt="delete" class="delete">
                                    </button>
                                </form>
                            <?php
                            
                            }
                            
                            ?>
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
</body>
</html>