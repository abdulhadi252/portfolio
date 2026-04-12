<?php
include("connect.php");
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

// agar login nahi hai to redirect
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$id = $_SESSION['admin_id'];
$msg = "";

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
    <?php include("sidebar.php"); ?>
    <style>
        .profile-grid {
            display:grid; grid-template-columns:300px 1fr;
            gap:28px; max-width:920px;
        }

        /* LEFT info card */
        .info-card {
            background:var(--dark); border:1px solid var(--border);
            border-radius:16px; padding:32px 24px; text-align:center;
        }
        .avatar-wrap { position:relative; width:88px; margin:0 auto 18px; }
        .avatar {
            width:88px; height:88px; border-radius:50%;
            background:linear-gradient(135deg,var(--primary),var(--accent));
            display:flex; align-items:center; justify-content:center;
            font-family:'Playfair Display',serif;
            font-size:2rem; font-weight:700; color:var(--dark);
            border:3px solid rgba(201,169,127,0.3);
        }
        .avatar-dot {
            width:14px; height:14px; background:#5cb85c;
            border:2px solid var(--dark); border-radius:50%;
            position:absolute; bottom:4px; right:2px;
        }
        .info-card h3 { font-family:'Playfair Display',serif; font-size:1.25rem; color:var(--text); margin-bottom:6px; }
        .role-badge {
            display:inline-block; background:rgba(201,169,127,0.12);
            border:1px solid var(--border); color:var(--primary);
            font-size:0.72rem; font-weight:700; padding:4px 14px;
            border-radius:20px; letter-spacing:0.6px; text-transform:uppercase; margin-bottom:24px;
        }
        .info-divider { height:1px; background:var(--border); margin:0 0 18px; }
        .info-row {
            display:flex; align-items:center; gap:12px; padding:10px 0;
            border-bottom:1px solid rgba(201,169,127,0.07); text-align:left;
        }
        .info-row:last-child { border-bottom:none; }
        .row-icon {
            width:32px; height:32px; background:rgba(201,169,127,0.1);
            border-radius:8px; display:flex; align-items:center;
            justify-content:center; color:var(--primary); font-size:0.82rem; flex-shrink:0;
        }
        .info-row div p    { font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:2px; }
        .info-row div span { font-size:0.88rem; color:var(--text); font-weight:500; }

        /* RIGHT password card */
        .pass-card {
            background:var(--dark); border:1px solid var(--border);
            border-radius:16px; padding:32px;
        }
        .card-header {
            display:flex; align-items:center; gap:14px;
            margin-bottom:28px; padding-bottom:20px; border-bottom:1px solid var(--border);
        }
        .card-header-icon {
            width:44px; height:44px; background:rgba(201,169,127,0.12);
            border:1px solid var(--border); border-radius:12px;
            display:flex; align-items:center; justify-content:center;
            color:var(--primary); font-size:1.1rem;
        }
        .card-header h2 { font-family:'Playfair Display',serif; font-size:1.3rem; color:var(--text); }
        .card-header p  { font-size:0.82rem; color:var(--text-muted); margin-top:2px; }

        .form-group { margin-bottom:20px; }
        .form-group label {
            display:block; font-size:0.8rem; font-weight:600;
            color:var(--primary); letter-spacing:0.5px;
            text-transform:uppercase; margin-bottom:8px;
        }
        .input-wrap { position:relative; }
        .input-wrap i.ico {
            position:absolute; left:14px; top:50%;
            transform:translateY(-50%);
            color:var(--text-muted); font-size:0.88rem; pointer-events:none;
        }
        .eye-btn {
            position:absolute; right:14px; top:50%;
            transform:translateY(-50%);
            background:none; border:none; color:var(--text-muted);
            cursor:pointer; font-size:0.88rem; transition:color 0.2s;
        }
        .eye-btn:hover { color:var(--primary); }
        .form-control {
            width:100%; background:var(--dark3); border:1px solid var(--border);
            border-radius:10px; padding:12px 44px 12px 42px;
            color:var(--text); font-family:'DM Sans',sans-serif;
            font-size:0.92rem; outline:none;
            transition:border-color 0.3s, background 0.3s;
        }
        .form-control:focus { border-color:var(--primary); background:rgba(201,169,127,0.04); }
        .form-control::placeholder { color:var(--text-muted); }

        .alert-success {
            display:flex; align-items:center; gap:10px;
            background:rgba(100,200,120,0.1); border:1px solid rgba(100,200,120,0.25);
            border-radius:10px; padding:13px 16px; margin-bottom:22px;
            color:#7dd89a; font-size:0.88rem; font-weight:500;
        }

        .match-msg { font-size:0.78rem; margin-top:6px; min-height:18px; }

        .btn-row { display:flex; gap:12px; margin-top:8px; }
        .btn-save {
            padding:13px 32px; background:var(--primary); color:var(--dark);
            border:none; border-radius:10px; font-weight:700; font-size:0.92rem;
            cursor:pointer; font-family:'DM Sans',sans-serif;
            display:flex; align-items:center; gap:8px; transition:all 0.3s;
        }
        .btn-save:hover { background:var(--accent); transform:translateY(-1px); box-shadow:0 8px 20px rgba(201,169,127,0.2); }
        .btn-back {
            padding:13px 24px; background:transparent; color:var(--text-muted);
            border:1px solid var(--border); border-radius:10px;
            font-weight:500; font-size:0.92rem; text-decoration:none;
            display:flex; align-items:center; gap:8px; transition:all 0.3s;
        }
        .btn-back:hover { border-color:var(--primary); color:var(--primary); }

        @media(max-width:860px){ .profile-grid { grid-template-columns:1fr; } }
        @media(max-width:480px){ .btn-row { flex-direction:column; } }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
                <span class="page-title">Profile</span>
            </div>
            <div class="topbar-right">
                <div class="admin-chip"><div class="admin-avatar">A</div><span>Admin</span></div>
            </div>
        </div>

        <div class="page-wrap">
            <div class="profile-grid">

                <!-- LEFT info -->
                <div class="info-card">
                    <div class="avatar-wrap">
                        <div class="avatar">A</div>
                        <div class="avatar-dot"></div>
                    </div>
                    <h3>Administrator</h3>
                    <div class="role-badge">Super Admin</div>
                    <div class="info-divider"></div>
                    <div class="info-row">
                        <div class="row-icon"><i class="fas fa-shield-halved"></i></div>
                        <div><p>Status</p><span style="color:#7dd89a;">● Active</span></div>
                    </div>
                    <div class="info-row">
                        <div class="row-icon"><i class="fas fa-id-badge"></i></div>
                        <div><p>Admin ID</p><span>#<?php echo str_pad($id, 4, '0', STR_PAD_LEFT); ?></span></div>
                    </div>
                    <div class="info-row">
                        <div class="row-icon"><i class="fas fa-lock"></i></div>
                        <div><p>Password</p><span>Encrypted</span></div>
                    </div>
                    <div class="info-row">
                        <div class="row-icon"><i class="fas fa-calendar"></i></div>
                        <div><p>Access Level</p><span>Full Access</span></div>
                    </div>
                </div>

                <!-- RIGHT password form -->
                <div class="pass-card">
                    <div class="card-header">
                        <div class="card-header-icon"><i class="fas fa-lock"></i></div>
                        <div>
                            <h2>Change Password</h2>
                            <p>Update your admin account password</p>
                        </div>
                    </div>

                    <?php if($msg): ?>
                    <div class="alert-success">
                        <i class="fas fa-circle-check"></i> <?php echo $msg; ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="form-group">
                            <label>New Password</label>
                            <div class="input-wrap">
                                <i class="fas fa-lock ico"></i>
                                <input type="password" name="password" id="passField"
                                    class="form-control" placeholder="Enter new password">
                                <button type="button" class="eye-btn" onclick="togglePass('passField',this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label>
                            <div class="input-wrap">
                                <i class="fas fa-lock ico"></i>
                                <input type="password" id="confirmField"
                                    class="form-control" placeholder="Re-enter new password"
                                    oninput="checkMatch()">
                                <button type="button" class="eye-btn" onclick="togglePass('confirmField',this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="match-msg" id="matchMsg"></p>
                        </div>

                        <div class="btn-row">
                            <button name="update" class="btn-save">
                                <i class="fas fa-floppy-disk"></i> Update Password
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

    <script>
        function togglePass(id, btn) {
            var f = document.getElementById(id);
            var icon = btn.querySelector('i');
            f.type = f.type === 'password' ? 'text' : 'password';
            icon.className = f.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        }
        function checkMatch(){
            var np  = document.getElementById('passField').value;
            var cp  = document.getElementById('confirmField').value;
            var msg = document.getElementById('matchMsg');
            if(!cp){ msg.textContent=''; return; }
            msg.textContent = np === cp ? '✓ Passwords match' : '✗ Passwords do not match';
            msg.style.color = np === cp ? '#7dd89a' : '#e07070';
        }
    </script>
</body>
</html>