<?php
session_start();
include("connect.php");

$id = $_SESSION['admin_id'];

if(isset($_POST['update'])){
$newpass = password_hash($_POST['password'], PASSWORD_DEFAULT);

mysqli_query($connect,"UPDATE admins SET password='$newpass' WHERE id='$id'");
$msg = "Password Updated!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Admin Panel</title>
    <?php
    include "links.php";
    ?>
    <?php include("sidebar.php"); ?>

    <style>
        .profile-wrap {
            max-width: 500px;
        }

        .card {
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 36px 32px;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 32px;
            padding-bottom: 22px;
            border-bottom: 1px solid var(--border);
        }

        .card-header-icon {
            width: 44px; height: 44px;
            background: rgba(201,169,127,0.12);
            border: 1px solid var(--border);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .card-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem;
            color: var(--text);
        }

        .card-header p {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .alert-success {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(100,200,120,0.1);
            border: 1px solid rgba(100,200,120,0.25);
            border-radius: 10px;
            padding: 13px 16px;
            margin-bottom: 24px;
            color: #7dd89a;
            font-size: 0.88rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.88rem;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            background: var(--dark3);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 13px 16px 13px 42px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.3s, background 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: rgba(201,169,127,0.04);
        }

        .form-control::placeholder { color: var(--text-muted); }

        .btn-row {
            display: flex;
            gap: 12px;
            margin-top: 8px;
        }

        .btn-save {
            padding: 13px 32px;
            background: var(--primary);
            color: var(--dark);
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.92rem;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            background: var(--accent);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(201,169,127,0.2);
        }

        .btn-back {
            padding: 13px 24px;
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border);
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.92rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        @media (max-width: 480px) {
            .btn-row { flex-direction: column; }
            .card { padding: 24px 20px; }
        }
    </style>
</head>
<body>

    <div class="main-content">

        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="openSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="page-title">Profile</span>
            </div>
            <div class="topbar-right">
                <div class="admin-chip">
                    <div class="admin-avatar">A</div>
                    <span>Admin</span>
                </div>
            </div>
        </div>

        <div class="page-wrap">
            <div class="profile-wrap">

                <?php if(isset($msg)): ?>
                <div class="alert-success">
                    <i class="fas fa-circle-check"></i>
                    <?php echo $msg; ?>
                </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <div class="card-header-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <h2>Update Password</h2>
                            <p>Change your admin account password</p>
                        </div>
                    </div>

                    <form method="POST">
                        <div class="form-group">
                            <label>New Password</label>
                            <div class="input-wrap">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password"
                                    class="form-control" placeholder="Enter new password">
                            </div>
                        </div>

                        <div class="btn-row">
                            <button name="update" class="btn-save">
                                <i class="fas fa-floppy-disk"></i> Update
                            </button>
                            <a href="dashboard.php" class="btn-back">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>

</body>
</html>