<?php
    session_start();
    require_once "../model/settings.php";
    if(isset($_POST["postId"])){
        $sql = "SELECT user_id FROM posts_likes WHERE post_id = ? AND user_id = ?";
        $params = array($_POST["postId"], $_SESSION["user"]);
        $row = $db->row($sql, $params);
        if(isset($row->user_id)){
            $db->delete('posts_likes ', array('post_id'=>$_POST['postId'], 'user_id'=>$_SESSION["user"]));
            $data = array('status'=>200, 'liked'=>false,  'color'=>"#dadada");

        }else{
            $db->insert('posts_likes ', array('post_id'=>$_POST['postId'], 'user_id'=>$_SESSION["user"], 'date'=>date('Y-m-d H:i:s')));
            $data = array('status'=>200, 'liked'=>true, 'color'=>"#fc6d26be");
        }
        
        $sql = 'SELECT COUNT(user_id) as total FROM posts_likes WHERE post_id = ?';
        $params = array($_POST['postId']);
        $row = $db->row($sql, $params);
        $db->update('posts', array('total_likes'=>$row->total), array('id'=>$_POST['postId']));
        $data['total'] = $row->total;
        echo json_encode($data);
        exit();
    }