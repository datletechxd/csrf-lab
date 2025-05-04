<?php
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

include 'db_config.php';

$username = $password = "";
$username_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
        $username_err = "Vui lòng nhập tên đăng nhập.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Vui lòng nhập mật khẩu.";
    } else{
        $password = trim($_POST["password"]);
    }

    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, username, password, token FROM users WHERE username = ?";
        
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_username);
 
            $param_username = $username;
            
            if($stmt->execute()){
                $stmt->store_result();

                if($stmt->num_rows == 1){     
                    $stmt->bind_result($id, $username, $hashed_password, $token);
                    
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            setcookie("user_token", $token, time() + 3600, "/");
                            header("location: index.php");
                        } else{
                            $password_err = "Mật khẩu không chính xác.";
                        }
                    }
                } else{
                    $username_err = "Không tìm thấy tài khoản với tên đăng nhập này.";
                }
            } else{
                echo "Đã xảy ra lỗi, vui lòng thử lại sau.";
            }

            $stmt->close();
        }
    }
    
    $conn->close();
}
?>
 
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-group .invalid-feedback {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .form-group .btn-login {
            width: 100%;
            padding: 12px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2>Đăng Nhập</h2>
            <p>Vui lòng điền thông tin để đăng nhập.</p>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <input type="text" name="username" value="<?php echo $username; ?>" class="<?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>    
                
                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" class="<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                
                <div class="form-group">
                    <input type="submit" class="btn btn-login" value="Đăng Nhập">
                </div>
                
                <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>.</p>
            </form>
        </div>
    </div>
</body>
</html>