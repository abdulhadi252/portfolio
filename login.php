<?php
session_start();
include("connect.php");

$msg = "";

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($connect, "SELECT * FROM admins WHERE username='$username'");
    
    if(mysqli_num_rows($query) > 0){
        $row = mysqli_fetch_assoc($query);

        if(password_verify($password, $row['password'])){
            $_SESSION['admin_id'] = $row['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $msg = "❌ Wrong Password!";
        }
    } else {
        $msg = "❌ Username not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: 'Segoe UI', sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(135deg, #0a0a0a, #1a1a1a);
}

.login-container{
    width:350px;
    background: rgba(255,255,255,0.05);
    border:1px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    border-radius:16px;
    padding:35px 30px;
    box-shadow:0 10px 40px rgba(0,0,0,0.5);
}

.login-container h2{
    text-align:center;
    color:#c9a97f;
    margin-bottom:25px;
}

.input-group{
    margin-bottom:18px;
}

.input-group label{
    color:#aaa;
    font-size:14px;
}

.input-group input{
    width:100%;
    padding:12px;
    margin-top:6px;
    border:none;
    outline:none;
    border-radius:8px;
    background:#222;
    color:#fff;
}

.input-group input:focus{
    border:1px solid #c9a97f;
}

button{
    width:100%;
    padding:12px;
    background:#c9a97f;
    border:none;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#d4af37;
}

.error{
    text-align:center;
    color:#ff4d4d;
    margin-bottom:12px;
}

.footer{
    text-align:center;
    margin-top:15px;
    font-size:13px;
    color:#888;
}
</style>
</head>
<body>

<div class="login-container">
    <h2>Admin Login</h2>

    <?php if($msg){ echo "<div class='error'>$msg</div>"; } ?>

    <form method="POST">
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button name="login">Login</button>
    </form>

    <div class="footer">
        © Abdul Hadi Panel
    </div>
</div>

</body>
</html>