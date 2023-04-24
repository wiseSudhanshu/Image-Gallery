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

        $result = $db->update('pictures', $data);

        if($result) {
            $_SESSION['success'] = 'Image Updated Successfully!';
            if($new_image != '') {
                $target_file_path = 'uploads/' . $new_image;
                move_uploaded_file($tmp_dir, $target_file_path);
                unlink('uploads/'.$old_image);
            }
            header('Location: index.php');
        } else {
            $_SESSION['error'] = 'An Error occured while updating your data! Please try again.';
            header('Location: edit.php?id='.$new_image_id);
        }
    }

    if(isset($_POST['delete_image'])) {
        $id = $_POST['del_id'];
        $image = $_POST['del_image'];

        echo $id;

        $data = [
            'id' => $id
        ];

        $result = $db->delete('images', $data);

        if($result) {
            $_SESSION['success'] = 'Data Deleted Permanently!';
            unlink('uploads/images/'.$image);
        } else {
            $_SESSION['error'] = 'An Error occured while deleting your data! Please try again.';
        }
        header('Location: index.php');
    }
?>