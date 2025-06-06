<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

include 'db_config.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        die("CSRF token không hợp lệ.");
    }
    if(empty(trim($_POST["content"]))){
        header("location: index.php");
        exit;
    } else {
        $sql = "INSERT INTO posts (user_id, content) VALUES (?, ?)";
        
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("is", $param_user_id, $param_content);
            
            $param_user_id = $_SESSION["id"];
            $param_content = trim($_POST["content"]);
            
            if($stmt->execute()){
                $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
                header("location: index.php");
                exit;
            } else{
                echo "Đã xảy ra lỗi. Vui lòng thử lại sau.";
            }
            $stmt->close();
        }
    }
    
    $conn->close();
} else {
    header("location: index.php");
    exit;
}
?>