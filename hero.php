<?php
include("connect.php");

$result = mysqli_query($connect, "SELECT * FROM hero WHERE id=1");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
    $title     = $_POST['title'];
    $subtitle  = $_POST['subtitle'];
    $desc      = $_POST['description'];
    $btn1_text = $_POST['button_text'];
    $btn1_link = $_POST['button_link'];
    $btn2_text = $_POST['button2_text'];
    $btn2_link = $_POST['button2_link'];
    $image     = $_FILES['image']['name'];

    if(!empty($image)){
        $tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmp, "uploads/".$image);
        mysqli_query($connect,"UPDATE hero SET title='$title',subtitle='$subtitle',description='$desc',button_text='$btn1_text',button_link='$btn1_link',button2_text='$btn2_text',button2_link='$btn2_link',image='$image' WHERE id=1");
    } else {
        mysqli_query($connect,"UPDATE hero SET title='$title',subtitle='$subtitle',description='$desc',button_text='$btn1_text',button_link='$btn1_link',button2_text='$btn2_text',button2_link='$btn2_link' WHERE id=1");
    }

    $success = true;
    $row = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM hero WHERE id=1"));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hero Section | Admin Panel</title>
    <?php include("sidebar.php"); ?>
    <style>
        .card {
            background: var(--dark); border: 1px solid var(--border);
            border-radius: 16px; padding: 32px; max-width: 860px;
            animation: fadeIn 0.5s ease forwards;
        }
        @keyframes fadeIn { to { opacity:1; transform:translateY(0); } }

        .card-header {
            display: flex; align-items: center; gap: 14px;
            margin-bottom: 32px; padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }
        .card-header-icon {
            width: 44px; height: 44px;
            background: rgba(201,169,127,0.12); border: 1px solid var(--border);
            border-radius: 12px; display: flex; align-items: center;
            justify-content: center; color: var(--primary); font-size: 1.1rem;
        }
        .card-header h2 { font-family:'Playfair Display',serif; font-size:1.4rem; color:var(--text); }
        .card-header p  { font-size:0.82rem; color:var(--text-muted); margin-top:2px; }

        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:0 24px; }
        .col-full  { grid-column: 1 / -1; }
        .form-group { margin-bottom:20px; }

        .form-group label {
            display:block; font-size:0.8rem; font-weight:600;
            color:var(--primary); letter-spacing:0.5px;
            text-transform:uppercase; margin-bottom:8px;
        }
        .form-group label i { margin-right:6px; font-size:0.78rem; }

        .input-wrap { position:relative; }
        .input-wrap i.ico {
            position:absolute; left:14px; top:50%;
            transform:translateY(-50%);
            color:var(--text-muted); font-size:0.88rem; pointer-events:none;
        }

        .form-control {
            width:100%; background:var(--dark3); border:1px solid var(--border);
            border-radius:10px; padding:12px 16px 12px 42px;
            color:var(--text); font-family:'DM Sans',sans-serif;
            font-size:0.92rem; outline:none;
            transition:border-color 0.3s, background 0.3s;
        }
        .form-control.no-icon { padding-left:16px; }
        .form-control:focus   { border-color:var(--primary); background:rgba(201,169,127,0.04); }
        .form-control::placeholder { color:var(--text-muted); }
        textarea.form-control { min-height:120px; resize:vertical; padding-left:16px; padding-top:12px; }

        /* Section divider */
        .section-label {
            font-size:0.72rem; font-weight:700; letter-spacing:1.5px;
            text-transform:uppercase; color:var(--text-muted);
            padding:0 0 10px; margin:8px 0 4px;
            border-bottom:1px solid var(--border);
            grid-column: 1 / -1;
        }

        /* Upload */
        .upload-area {
            border:2px dashed var(--border); border-radius:10px;
            padding:24px; text-align:center; cursor:pointer;
            transition:border-color 0.3s, background 0.3s; position:relative;
        }
        .upload-area:hover { border-color:var(--primary); background:rgba(201,169,127,0.04); }
        .upload-area input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        .upload-area i { font-size:1.6rem; color:var(--primary); margin-bottom:8px; display:block; }
        .upload-area p { font-size:0.84rem; color:var(--text-muted); }
        .upload-area span { color:var(--primary); font-weight:600; }

        /* Current bg preview */
        .current-bg {
            display:flex; align-items:center; gap:14px;
            background:var(--dark3); border:1px solid var(--border);
            border-radius:10px; padding:12px 16px; margin-bottom:12px;
        }
        .current-bg img { width:80px; height:50px; object-fit:cover; border-radius:6px; }
        .current-bg div p    { font-size:0.75rem; color:var(--text-muted); }
        .current-bg div span { font-size:0.88rem; color:var(--text); font-weight:500; }

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

        .alert-success {
            display:flex; align-items:center; gap:12px;
            background:rgba(100,200,120,0.1); border:1px solid rgba(100,200,120,0.25);
            border-radius:10px; padding:14px 18px; margin-bottom:24px;
            color:#7dd89a; font-size:0.9rem; font-weight:500;
        }

        @media(max-width:640px){ .form-grid{grid-template-columns:1fr;} .btn-row{flex-direction:column;} }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="openSidebar()"><i class="fas fa-bars"></i></button>
                <span class="page-title">Hero Section</span>
            </div>
            <div class="topbar-right">
                <div class="admin-chip"><div class="admin-avatar">A</div><span>Admin</span></div>
            </div>
        </div>

        <div class="page-wrap">
            <?php if(isset($success)): ?>
            <div class="alert-success"><i class="fas fa-circle-check"></i> Hero section updated successfully!</div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <div class="card-header-icon"><i class="fas fa-house"></i></div>
                    <div><h2>Update Hero Section</h2><p>Edit your main headline, subtitle, description and buttons</p></div>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-grid">

                        <div class="form-group col-full">
                            <label><i class="fas fa-heading"></i> Main Title</label>
                            <div class="input-wrap">
                                <i class="fas fa-heading ico"></i>
                                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" placeholder="e.g. Hi, I'm Abdul Hadi 👋">
                            </div>
                        </div>

                        <div class="form-group col-full">
                            <label><i class="fas fa-tag"></i> Subtitle / Role</label>
                            <div class="input-wrap">
                                <i class="fas fa-tag ico"></i>
                                <input type="text" name="subtitle" class="form-control" value="<?php echo htmlspecialchars($row['subtitle']); ?>" placeholder="e.g. Frontend Developer">
                            </div>
                        </div>

                        <div class="form-group col-full">
                            <label><i class="fas fa-align-left"></i> Description</label>
                            <textarea name="description" class="form-control"><?php echo htmlspecialchars($row['description']); ?></textarea>
                        </div>

                        <div class="section-label">Button 1</div>

                        <div class="form-group">
                            <label><i class="fas fa-font"></i> Button 1 Text</label>
                            <div class="input-wrap">
                                <i class="fas fa-font ico"></i>
                                <input type="text" name="button_text" class="form-control" value="<?php echo htmlspecialchars($row['button_text']); ?>" placeholder="e.g. My Projects">
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-link"></i> Button 1 Link</label>
                            <div class="input-wrap">
                                <i class="fas fa-link ico"></i>
                                <input type="text" name="button_link" class="form-control" value="<?php echo htmlspecialchars($row['button_link']); ?>" placeholder="#projects">
                            </div>
                        </div>

                        <div class="section-label">Button 2 (Resume)</div>

                        <div class="form-group">
                            <label><i class="fas fa-font"></i> Button 2 Text</label>
                            <div class="input-wrap">
                                <i class="fas fa-font ico"></i>
                                <input type="text" name="button2_text" class="form-control" value="<?php echo htmlspecialchars($row['button2_text']); ?>" placeholder="e.g. Download Resume">
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-file-pdf"></i> Button 2 Link</label>
                            <div class="input-wrap">
                                <i class="fas fa-link ico"></i>
                                <input type="text" name="button2_link" class="form-control" value="<?php echo htmlspecialchars($row['button2_link']); ?>" placeholder="uploads/resume.pdf">
                            </div>
                        </div>

                        <div class="form-group col-full">
                            <label><i class="fas fa-image"></i> Background Image</label>
                            <?php if(!empty($row['image'])): ?>
                            <div class="current-bg">
                                <img src="uploads/<?php echo $row['image']; ?>" alt="Current">
                                <div><p>Current image</p><span><?php echo $row['image']; ?></span></div>
                            </div>
                            <?php endif; ?>
                            <div class="upload-area">
                                <input type="file" name="image" accept="image/*">
                                <i class="fas fa-cloud-arrow-up"></i>
                                <p><span>Click to upload</span> or leave empty to keep current</p>
                            </div>
                        </div>

                    </div>

                    <div class="btn-row">
                        <button type="submit" name="update" class="btn-save"><i class="fas fa-floppy-disk"></i> Update Hero</button>
                        <a href="dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>