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

        /* Style for the copy icon */
        .copy {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 20px;
            border-radius: 5px;
            text-align: center;
            background-color: #5784f5;
            border-color: #5784f5;
            outline: none;
            cursor: pointer;
            z-index: 1;
        }

        .copy img {
            filter: invert(90%);
            width: 1.7em;
            height: 1.7em;
            margin: 10px;
            display: inline-block;
        }

    </style>
</head>
<body>
    <?php

        require_once 'config.php';
        require_once 'DB.class.php';
        $db = new DB();

        if(!isset($_SESSION['user_id'])) {
            header('Location: login.php');
        }

        $key = SECRET_KEY;
        $method = METHOD;

        $encrypted = base64_decode($_GET['id']);
        $image_id = openssl_decrypt($encrypted, $method, $key, OPENSSL_RAW_DATA);

        $cond = ['image_id' => $image_id];

        $result = $db->select_data(SHARE_TABLE, $cond) or die('query failed!');

        if($result->num_rows > 0) {
            foreach($result as $row) {
              if($row['shared'] != 1) {
                ?>
                <div class="alert alert-danger mt-5 alert-dismissible col-6 fade show" role="alert" style="margin: 0 auto;">
                    Requested URL doesn't exist!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
                die();
              }
            }
        }
        else {
            $cond = [
                'image_id' => $image_id,
                'shared' => 1,
                'url' => 'localhost/Image-Gallery/view.php?' . $image_id
            ];
    
            $db->insert_shared_data(SHARE_TABLE, $cond);
        }

        $cond = ['id' => $image_id];

        $result = $db->select_data(IMAGE_TABLE, $cond) or die('query failed!');

        if($result->num_rows > 0) {
            foreach($result as $row) {
              ?>

                <div class="parent">
                    <img src="./uploads/<?php echo $row['file_name']; ?>" alt="Image">
                </div>
                <a href="#" onclick="copyToClipboard('<?php echo htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES); ?>');" class="copy">
                    <img src="./static/images/copy.png" alt="copy">
                </a>

              <?php
            }
        }
        else {
            ?>
            <div class="alert alert-danger mt-5 alert-dismissible col-6 fade show" role="alert" style="margin: 0 auto;">
                Requested URL doesn't exist!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
        }

    ?>

    <script>        
        function copyToClipboard(text) {
            let copyText = document.createElement("textarea");
            copyText.value = window.location.protocol + "//" + window.location.host + text;
            document.body.appendChild(copyText);
            copyText.select();
            document.execCommand("copy");
            document.body.removeChild(copyText);
            alert("URL copied to clipboard");
        }
    </script>
</body>
</html>