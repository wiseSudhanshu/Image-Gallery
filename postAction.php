<?php

    session_start();

    require_once 'DB.class.php';
    $db = new DB();

    if(isset($_POST['submit'])) {
        $data = [
            'file_name' => $_FILES['filename']['name'],
            'title' => $_POST['title']
        ];

        $file_name = $_FILES['filename']['name'];
        $tmp_dir = $_FILES['filename']['tmp_name'];

        $file_ext = explode('.', $file_name);
        $file_type = strtolower(end($file_ext));

        $file_ext_valid = ['png', 'jpg', 'jpeg'];
        
        if(file_exists("uploads/" . $file_name)) {
            $_SESSION['error'] = $file_name . " already exists!";
            header('Location: add.php');
            die();
        } 
        else {
            if(!in_array($file_type, $file_ext_valid)) {
                $_SESSION['error'] = "file must be of type 'png', 'jpg' or 'jpeg' only";
                header('Location: add.php');
                die();
            }

            $result = $db->insert('pictures', $data);

            if($result) {
                $_SESSION['success'] = 'Image Uploaded Successfully!';
                $target_file_path = 'uploads/' . $file_name;
                move_uploaded_file($tmp_dir, $target_file_path);
                header('Location: index.php');
            } else {
                $_SESSION['error'] = 'An Error occured while inserting your image! Please try again.';
                header('Location: add.php');
            }
        }
    }

    


?>