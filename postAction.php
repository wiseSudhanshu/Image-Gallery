<?php

    session_start();

    require_once 'DB.class.php';
    $db = new DB();

    if(isset($_POST['add'])) {
        if(isset($_SESSION['user_id'])) {
            header('Location: add.php');
        }
        else {
            header('Location: login.php');
        }
    }

    if(isset($_POST['submit'])) {
        $file_name = $_FILES['filename']['name'];
        $tmp_dir = $_FILES['filename']['tmp_name'];

        $file_ext = explode('.', $file_name);
        $file_type = strtolower(end($file_ext));

        $file_ext_valid = ['png', 'jpg', 'jpeg'];
        
        if(!in_array($file_type, $file_ext_valid)) {
            $_SESSION['error'] = "file must be of type 'png', 'jpg' or 'jpeg' only";
            header('Location: add.php');
            die();
        }
        $file_name = $file_ext[0] . 'uid=' . $_SESSION['user_id'] . '.' . $file_type;
        
        $sql = "SELECT COUNT(*) FROM pictures WHERE file_name LIKE '$file_name%' AND added_by = {$_SESSION['user_id']}";
        $result = $db->db->query($sql);
        $count = mysqli_fetch_array($result)[0];


        if($count > 0) {
            $parts = explode('.', $file_name);
            $file_name = $parts[0].'('.$count.').'.$file_type;
        }

        $data = [
            'file_name' => $file_name,
            'title' => $_POST['title'],
            'added_by' => $_SESSION['user_id']
        ];

        $result = $db->insert_data('pictures', $data);

        if($result) {
            $_SESSION['success'] = 'Image Uploaded Successfully!';
            $target_file_path = 'uploads/' . $file_name;
            move_uploaded_file($tmp_dir, $target_file_path);
            header('Location: gallery.php');
        } else {
            $_SESSION['error'] = 'An Error occured while inserting your image! Please try again.';
            header('Location: add.php');
        }
    }

    if(isset($_POST['update'])) {
        $new_image_id = $_POST['id'];
        $new_image = $_FILES['filename']['name'];
        $old_image = $_POST['old_image'];

        $updated_filename = $old_image;;
        if($new_image != '') {
            $updated_filename = $new_image;
        }

        if($updated_filename !== $old_image && file_exists("uploads/" . $updated_filename)) {
            $_SESSION['error'] = $new_image . " already exists!";
            header('Location: edit.php?id='.$new_image_id);
            die();
        } 

        $tmp_dir = $_FILES['filename']['tmp_name'];

        $file_ext = explode('.', $updated_filename);
        $file_type = strtolower(end($file_ext));

        $file_ext_valid = ['png', 'jpg', 'jpeg'];
        
        if(!in_array($file_type, $file_ext_valid)) {
            $_SESSION['error'] = "file must be of type 'png', 'jpg' or 'jpeg' only";
            header('Location: edit.php?id='.$new_image_id);
            die();
        }

        $data = [
            'id' => $new_image_id,
            'title' => $_POST['title'],
            'file_name' => $updated_filename
        ];

        $result = $db->update_data('pictures', $data);

        if($result) {
            $_SESSION['success'] = 'Image Updated Successfully!';
            if($new_image != '') {
                $target_file_path = 'uploads/' . $new_image;
                move_uploaded_file($tmp_dir, $target_file_path);
                unlink('uploads/'.$old_image);
            }
            header('Location: gallery.php');
        } else {
            $_SESSION['error'] = 'An Error occured while updating your data! Please try again.';
            header('Location: edit.php?id='.$new_image_id);
        }
    }

    // if(isset($_POST['delete_image'])) {
    //     $id = $_POST['del_id'];
    //     $image = $_POST['del_image'];

    //     $data = [
    //         'id' => $id
    //     ];

    //     $result = $db->delete_data('pictures', $data);

    //     if($result) {
    //         $_SESSION['success'] = 'Data Deleted Permanently!';
    //         unlink('uploads/'.$image);
    //     } else {
    //         $_SESSION['error'] = 'An Error occured while deleting your data! Please try again.';
    //     }
    //     header('Location: gallery.php');
    // }

    if(isset($_POST['action'])) {
        $id = $_POST['id'];

        $select = $db->db->query("SELECT * FROM `pictures` WHERE id = '$id'") or die('query failed');
        $row = $select->fetch_assoc();
        $file_name = $row['file_name'];

        $data = [
            'id' => $id
        ];

        if($_POST['action'] == 'delete') {
            $db->delete_data('pictures', $data);
            unlink('uploads/'.$file_name);
        }
        header('Location: gallery.php');
    }
    
    if(isset($_POST['register'])) {
        
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $cpass = $_POST['cpassword'];

        if($pass !== $cpass) {
            $_SESSION['error'] = "Passwords don't match!";
            header('Location: register.php');
            die();
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format";
            header('Location: register.php');
            die();
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => md5($pass)
        ];
        
        $select = $db->db->query("SELECT * FROM `users` WHERE email = '$email'") or die('query failed');
        
        if($select->num_rows > 0) {
            $_SESSION['error'] = 'User already exists!';
            header('Location: register.php');
        }else{
            $result = $db->add_user('users', $data);

            if($result) {
                $encryptedpass = md5($pass);
                $select = $db->db->query("SELECT * FROM `users` WHERE email = '$email' AND password = '$encryptedpass'") or die('query failed');
                $row = $select->fetch_assoc();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['success'] = 'Registered successfully!';
                header('Location: gallery.php');
            }
            else {
                $_SESSION['error'] = 'An Error occured while registering user! Please try again.';
                header('Location: register.php');
            }
        }
        
    }

    if(isset($_POST['login'])) {

        $email = $_POST['email'];
        $pass = md5($_POST['password']);
        
        $select = $db->db->query("SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');
        
        if($select->num_rows > 0){
            $row = $select->fetch_assoc();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['success'] = 'Howdy, ' . $row['name'];
            header('Location: gallery.php');
        }else{
            $_SESSION['error'] = 'incorrect password or email!';
            header('Location: login.php');
        }
    }
?>