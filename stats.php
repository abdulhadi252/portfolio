<?php
include("connect.php");

$data = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM stats WHERE id=1"));

if(isset($_POST['update'])){
    $p   = $_POST['projects'];
    $s   = $_POST['small'];
    $e   = $_POST['experience'];
    $sem = $_POST['seminars'];

    mysqli_query($connect,"UPDATE stats SET
    projects_completed='$p',
    small_projects='$s',
    experience='$e',
    seminars='$sem'
    WHERE id=1");

    header("Location: stats.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats | Admin Panel</title>
    <?php include("sidebar.php"); ?>
    <style>
        .stats-wrap { max-width: 700px; }

        .card {
            background:var(--dark); border:1px solid var(--border);
            border-radius:16px; padding:32px;
            animation:fadeIn 0.5s ease forwards;
        }
        @keyframes fadeIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }

        .card-header {
            display:flex; align-items:center; gap:14px;
            margin-bottom:32px; padding-bottom:20px; border-bottom:1px solid var(--border);
        }
        .card-header-icon {
            width:44px; height:44px; background:rgba(201,169,127,0.12);
            border:1px solid var(--border); border-radius:12px;
            display:flex; align-items:center; justify-content:center;
            color:var(--primary); font-size:1.1rem;
        }
        .card-header h2 { font-family:'Playfair Display',serif; font-size:1.4rem; color:var(--text); }
        .card-header p  { font-size:0.82rem; color:var(--text-muted); margin-top:2px; }

        /* 2 col grid */
        .stats-grid { display:grid; grid-template-columns:1fr 1fr; gap:0 24px; }

        .form-group { margin-bottom:22px; }
        .form-group label {
            display:block; font-size:0.8rem; font-weight:600;
            color:var(--primary); letter-spacing:0.5px;
            text-transform:uppercase; margin-bottom:8px;
        }
        .form-group label i { margin-right:6px; }

        .stat-input-wrap {
            position:relative;
            background:var(--dark3); border:1px solid var(--border);
            border-radius:10px; overflow:hidden;
            transition:border-color 0.3s;
            display:flex; align-items:stretch;
        }
        .stat-input-wrap:focus-within { border-color:var(--primary); }

        .stat-prefix {
            background:rgba(201,169,127,0.1); padding:0 12px;
            display:flex; align-items:center; justify-content:center;
            color:var(--primary); font-size:0.9rem; font-weight:700;
            border-right:1px solid var(--border); min-width:40px;
            flex-shrink:0;
        }

        .stat-input {
            flex:1; min-width:0; width:0; background:transparent; border:none;
            padding:13px 12px; color:var(--text);
            font-family:'DM Sans',sans-serif; font-size:1rem;
            font-weight:600; outline:none;
        }
        .stat-input::placeholder { color:var(--text-muted); font-size:0.92rem; font-weight:400; }

        .stat-suffix {
            padding:0 10px; display:flex; align-items:center;
            color:var(--text-muted); font-size:0.72rem;
            border-left:1px solid var(--border); white-space:nowrap;
            flex-shrink:0;
        }

        /* Preview strip */
        .preview-strip {
            display:grid; grid-template-columns:repeat(4,1fr);
            gap:12px; margin-bottom:28px;
            padding:18px; background:var(--dark3);
            border:1px solid var(--border); border-radius:12px;
        }
        .preview-item { text-align:center; }
        .preview-item h4 { font-size:1.6rem; color:var(--primary); line-height:1; margin-bottom:4px; }
        .preview-item p  { font-size:0.72rem; color:var(--text-muted); }

        /* Buttons */
        .btn-row { display:flex; gap:12px; margin-top:8px; }
        .btn-save {
            padding:13px 32px; background:var(--primary); color:var(--dark);
            border:none; border-radius:10px; font-weight:700; font-size:0.92rem;
            cursor:pointer; font-family:'DM Sans',sans-serif;
            display:flex; align-items:center; gap:8px; transition:all 0.3s;
        }
        .btn-save:hover { background:var(--accent); transform:translateY(-1px); box-shadow:0 8px 20px rgba(201,169,127,0.25); }
        .btn-back {
            padding:13px 24px; background:transparent; color:var(--text-muted);
            border:1px solid var(--border); border-radius:10px;
            font-weight:500; font-size:0.92rem; text-decoration:none;
            display:flex; align-items:center; gap:8px; transition:all 0.3s;
        }
        .btn-back:hover { border-color:var(--primary); color:var(--primary); }

        .strip-label {
            font-size:0.72rem; color:var(--text-muted); letter-spacing:0.8px;
            text-transform:uppercase; margin-bottom:10px;
        }

        @media(max-width:600px){ .stats-grid{grid-template-columns:1fr;} .preview-strip{grid-template-columns:1fr 1fr;} .btn-row{flex-direction:column;} }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
                <span class="page-title">Stats</span>
            </div>
            <div class="topbar-right">
                <div class="admin-chip"><div class="admin-avatar">A</div><span>Admin</span></div>
            </div>
        </div>

        <div class="page-wrap">
            <div class="stats-wrap">

                <div class="card">
                    <div class="card-header">
                        <div class="card-header-icon"><i class="fas fa-chart-bar"></i></div>
                        <div><h2>Update Stats</h2><p>Numbers shown in your portfolio stats section</p></div>
                    </div>

                    <!-- Live preview -->
                    <p class="strip-label">Current values</p>
                    <div class="preview-strip">
                        <div class="preview-item">
                            <h4><?php echo $data['projects_completed']; ?>+</h4>
                            <p>Projects</p>
                        </div>
                        <div class="preview-item">
                            <h4><?php echo $data['small_projects']; ?>+</h4>
                            <p>Small Projects</p>
                        </div>
                        <div class="preview-item">
                            <h4><?php echo $data['experience']; ?>+</h4>
                            <p>Experience</p>
                        </div>
                        <div class="preview-item">
                            <h4><?php echo $data['seminars']; ?>+</h4>
                            <p>Seminars</p>
                        </div>
                    </div>

                    <form method="POST">
                        <div class="stats-grid">

                            <div class="form-group">
                                <label><i class="fas fa-briefcase"></i> Projects Completed</label>
                                <div class="stat-input-wrap">
                                    <div class="stat-prefix"><i class="fas fa-briefcase"></i></div>
                                    <input type="number" name="projects" class="stat-input"
                                        value="<?php echo $data['projects_completed']; ?>" placeholder="20">
                                    <div class="stat-suffix">projects</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-code"></i> Small Projects</label>
                                <div class="stat-input-wrap">
                                    <div class="stat-prefix"><i class="fas fa-code"></i></div>
                                    <input type="number" name="small" class="stat-input"
                                        value="<?php echo $data['small_projects']; ?>" placeholder="30">
                                    <div class="stat-suffix">projects</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-calendar"></i> Years Experience</label>
                                <div class="stat-input-wrap">
                                    <div class="stat-prefix"><i class="fas fa-calendar"></i></div>
                                    <input type="number" name="experience" class="stat-input"
                                        value="<?php echo $data['experience']; ?>" placeholder="1">
                                    <div class="stat-suffix">years</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-users"></i> Seminars Attended</label>
                                <div class="stat-input-wrap">
                                    <div class="stat-prefix"><i class="fas fa-users"></i></div>
                                    <input type="number" name="seminars" class="stat-input"
                                        value="<?php echo $data['seminars']; ?>" placeholder="10">
                                    <div class="stat-suffix">seminars</div>
                                </div>
                            </div>

                        </div>

                        <div class="btn-row">
                            <button type="submit" name="update" class="btn-save">
                                <i class="fas fa-floppy-disk"></i> Update Stats
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