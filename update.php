<?php
include("connect.php");

$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM projects WHERE id=$id"));

if(isset($_POST['update'])){

    $title  = $_POST['title'];
    $desc   = $_POST['description'];
    $github = $_POST['github'];
    $live   = $_POST['live'];
    $skills = $_POST['skills'];

    if($_FILES['image']['name'] != ""){
        $image = $_FILES['image']['name'];
        $tmp   = $_FILES['image']['tmp_name'];

        move_uploaded_file($tmp, "uploads/" . $image);

        mysqli_query($connect,"UPDATE projects SET 
        title='$title',
        skills='$skills',
        description='$desc',
        image='$image',
        github_link='$github',
        live_link='$live'
        WHERE id=$id");

    } else {
        mysqli_query($connect,"UPDATE projects SET 
        title='$title',
        skills='$skills',
        description='$desc',
        github_link='$github',
        live_link='$live'
        WHERE id=$id");
    }

    header("Location: projects.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Project | Admin Panel</title>

    <?php include("sidebar.php"); ?>

    <style>
        .card {
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px;
            max-width: 820px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.5s ease 0.1s forwards;
        }

        @keyframes fadeIn {
            to { opacity: 1; transform: translateY(0); }
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
            font-size: 1.4rem;
            color: var(--text);
        }

        .card-header p {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Two col grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 24px;
        }

        .col-full { grid-column: 1 / -1; }

        .form-group { margin-bottom: 22px; }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .form-group label i { margin-right: 6px; font-size: 0.78rem; }

        .input-wrap { position: relative; }

        .input-wrap i.ico {
            position: absolute;
            left: 14px; top: 50%;
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
            padding: 12px 16px 12px 42px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.3s, background 0.3s;
        }

        .form-control.no-icon { padding-left: 16px; }

        .form-control:focus {
            border-color: var(--primary);
            background: rgba(201,169,127,0.04);
        }

        .form-control::placeholder { color: var(--text-muted); }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
            line-height: 1.7;
            padding-top: 12px;
            padding-left: 16px;
        }

        /* Current image preview */
        .current-img {
            display: flex;
            align-items: center;
            gap: 14px;
            background: var(--dark3);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 12px;
        }

        .current-img img {
            width: 64px; height: 48px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .current-img div p  { font-size: 0.75rem; color: var(--text-muted); }
        .current-img div span { font-size: 0.88rem; color: var(--text); font-weight: 500; }

        /* Upload area */
        .upload-area {
            border: 2px dashed var(--border);
            border-radius: 10px;
            padding: 22px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s, background 0.3s;
            position: relative;
        }

        .upload-area:hover {
            border-color: var(--primary);
            background: rgba(201,169,127,0.04);
        }

        .upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%; height: 100%;
        }

        .upload-area i { font-size: 1.6rem; color: var(--primary); margin-bottom: 8px; display: block; }
        .upload-area p { font-size: 0.84rem; color: var(--text-muted); }
        .upload-area span { color: var(--primary); font-weight: 600; }

        /* Buttons */
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
            box-shadow: 0 8px 20px rgba(201,169,127,0.25);
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

        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; }
            .btn-row   { flex-direction: column; }
            .card      { padding: 24px 18px; }
        }
    </style>
</head>
<body>

    <div class="main-content">

        <!-- TOP BAR -->
        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="openSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="page-title">Update Project</span>
            </div>
            <div class="topbar-right">
                <div class="admin-chip">
                    <div class="admin-avatar">A</div>
                    <span>Admin</span>
                </div>
            </div>
        </div>

        <!-- PAGE CONTENT -->
        <div class="page-wrap">
            <div class="card">

                <div class="card-header">
                    <div class="card-header-icon">
                        <i class="fas fa-pen"></i>
                    </div>
                    <div>
                        <h2>Update Project</h2>
                        <p>Editing: <strong style="color:var(--primary)"><?php echo $data['title']; ?></strong></p>
                    </div>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-grid">

                        <!-- Title -->
                        <div class="form-group col-full">
                            <label><i class="fas fa-heading"></i> Project Title</label>
                            <div class="input-wrap">
                                <i class="fas fa-heading ico"></i>
                                <input type="text" name="title" class="form-control"
                                    value="<?php echo $data['title']; ?>" required>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group col-full">
                            <label><i class="fas fa-align-left"></i> Description</label>
                            <textarea name="description" class="form-control"><?php echo $data['description']; ?></textarea>
                        </div>

                        <!-- Skills -->
                        <div class="form-group col-full">
                            <label><i class="fas fa-code"></i> Skills / Tags</label>
                            <textarea name="skills" class="form-control"
                                style="min-height:80px"
                                placeholder="e.g. HTML, CSS, JavaScript"><?php echo $data['skills']; ?></textarea>
                        </div>

                        <!-- GitHub -->
                        <div class="form-group">
                            <label><i class="fab fa-github"></i> GitHub Link</label>
                            <div class="input-wrap">
                                <i class="fas fa-link ico"></i>
                                <input type="text" name="github" class="form-control"
                                    value="<?php echo $data['github_link']; ?>"
                                    placeholder="https://github.com/...">
                            </div>
                        </div>

                        <!-- Live -->
                        <div class="form-group">
                            <label><i class="fas fa-globe"></i> Live Demo Link</label>
                            <div class="input-wrap">
                                <i class="fas fa-link ico"></i>
                                <input type="text" name="live" class="form-control"
                                    value="<?php echo $data['live_link']; ?>"
                                    placeholder="https://yourproject.netlify.app">
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="form-group col-full">
                            <label><i class="fas fa-image"></i> Project Image</label>

                            <?php if(!empty($data['image'])): ?>
                            <div class="current-img">
                                <img src="uploads/<?php echo $data['image']; ?>" alt="Current">
                                <div>
                                    <p>Current image</p>
                                    <span><?php echo $data['image']; ?></span>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="upload-area">
                                <input type="file" name="image" accept="image/*">
                                <i class="fas fa-cloud-arrow-up"></i>
                                <p><span>Click to upload</span> new image or leave empty to keep current</p>
                            </div>
                        </div>

                    </div><!-- /form-grid -->

                    <div class="btn-row">
                        <button name="update" class="btn-save">
                            <i class="fas fa-floppy-disk"></i> Update Project
                        </button>
                        <a href="projects.php" class="btn-back">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>

</body>
</html>