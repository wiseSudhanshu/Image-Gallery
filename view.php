<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <title>View Image</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: whitesmoke;
        }
        .parent {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .parent img {
            max-width: 100%;
            height: 100vh;
        }

    </style>
</head>
<body>
    <?php

        require_once 'DB.class.php';
        $db = new DB();

        if(!isset($_GET['id'])) {
            header('Location: index.php');
        }

        $image_id = $_GET['id'];

        $sql = "SELECT * from `pictures` WHERE id = '$image_id'";

        $result = $db->db->query($sql) or die('query failed!');

        if($result->num_rows > 0) {
            foreach($result as $row) {
              ?>

                <div class="parent">
                    <img src="./uploads/<?php echo $row['file_name']; ?>" alt="Image">
                </div>

              <?php
            }
        }
        else {
            ?>
            <div class="alert alert-success mt-5 alert-dismissible col-6 fade show" role="alert" style="margin: 0 auto;">
                Requested URL doesn't exist!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
        }

    ?>
</body>
</html>