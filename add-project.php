<?php
session_start();
include("connect.php");

if (isset($_POST['submit'])) {
    $title  = $_POST['title'];
    $desc   = $_POST['description'];
    $github = $_POST['github'];
    $live   = $_POST['live'];
    $tags = $_POST['tags'];

    $image = $_FILES['image']['name'];
    $tmp   = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp, "uploads/" . $image);


    mysqli_query($connect, "INSERT INTO projects(title,description,image,github_link,live_link,tags)
VALUES('$title','$desc','$image','$github','$live','$tags')");

    $success = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project | Admin Panel</title>


    <?php
    include "links.php";
    ?>

    <?php include("sidebar.php"); ?>

    <style>
        .card {
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px;
            max-width: 820px;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .card-header-icon {
            width: 44px;
            height: 44px;
            background: rgba(201, 169, 127, 0.12);
            border: 1px solid var(--border);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
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

        /* Two column grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 24px;
        }

        .form-grid .form-group-full {
            grid-column: 1 / -1;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary);
            letter-spacing: 0.6px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .form-group label i {
            margin-right: 6px;
            font-size: 0.8rem;
        }

        .form-control {
            width: 100%;
            background: var(--dark3);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.3s, background 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: rgba(201, 169, 127, 0.03);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        textarea.form-control {
            min-height: 130px;
            resize: vertical;
            line-height: 1.7;
        }

        /* Link inputs with icon prefix */
        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .input-with-icon .form-control {
            padding-left: 40px;
        }

        /* Upload area */
        .upload-area {
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 32px 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s, background 0.3s;
            position: relative;
        }

        .upload-area:hover {
            border-color: var(--primary);
            background: rgba(201, 169, 127, 0.04);
        }

        .upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .upload-icon {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 12px;
        }

        .upload-area p {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .upload-area span {
            color: var(--primary);
            font-weight: 600;
        }

        /* Image preview */
        #img-preview {
            display: none;
            margin-top: 16px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        #img-preview img {
            width: 100%;
            max-height: 180px;
            object-fit: cover;
            display: block;
        }

        /* Tags hint */
        .hint {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 6px;
        }

        /* Buttons */
        .btn-row {
            display: flex;
            gap: 12px;
            margin-top: 10px;
        }

        .btn-save {
            padding: 13px 36px;
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
            box-shadow: 0 8px 20px rgba(201, 169, 127, 0.25);
        }

        .btn-back {
            padding: 13px 24px;
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border);
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.92rem;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
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

        /* Alerts */
        .alert-success {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(100, 200, 120, 0.1);
            border: 1px solid rgba(100, 200, 120, 0.25);
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 24px;
            color: #7dd89a;
            font-size: 0.9rem;
            font-weight: 500;
        }

        @media (max-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .btn-row {
                flex-direction: column;
            }
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
                <span class="page-title">Add Project</span>
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

            <?php if (isset($success)): ?>
                <div class="alert-success">
                    <i class="fas fa-circle-check"></i>
                    Project added successfully! <a href="projects.php" style="color:#7dd89a;margin-left:8px;">View all projects →</a>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <div class="card-header-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div>
                        <h2>Add New Project</h2>
                        <p>Fill in the details to add a project to your portfolio</p>
                    </div>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-grid">

                        <!-- Title -->
                        <div class="form-group form-group-full">
                            <label><i class="fas fa-heading"></i> Project Title</label>
                            <input type="text" name="title" class="form-control"
                                placeholder="e.g. Galleria Painting Website" required>
                        </div>

                        <!-- Description -->
                        <div class="form-group form-group-full">
                            <label><i class="fas fa-align-left"></i> Description</label>
                            <textarea name="description" class="form-control"
                                placeholder="Brief description of what this project does..." required></textarea>
                        </div>

                        <div class="form-group form-group-full">
                            <label><i class="fas fa-tags"></i> Tags</label>
                            <input type="text" name="tags" class="form-control"
                                placeholder="HTML, CSS, JavaScript">
                        </div>

                        <!-- GitHub -->
                        <div class="form-group">
                            <label><i class="fab fa-github"></i> GitHub Link</label>
                            <div class="input-with-icon">
                                <i class="fas fa-link"></i>
                                <input type="text" name="github" class="form-control"
                                    placeholder="https://github.com/...">
                            </div>
                        </div>

                        <!-- Live -->
                        <div class="form-group">
                            <label><i class="fas fa-globe"></i> Live Demo Link</label>
                            <div class="input-with-icon">
                                <i class="fas fa-link"></i>
                                <input type="text" name="live" class="form-control"
                                    placeholder="https://yourproject.netlify.app">
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="form-group form-group-full">
                            <label><i class="fas fa-image"></i> Project Image</label>
                            <div class="upload-area" id="uploadArea">
                                <input type="file" name="image" accept="image/*"
                                    onchange="previewImage(this)">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-arrow-up"></i>
                                </div>
                                <p>
                                    <span>Click to upload</span> or drag & drop<br>
                                    PNG, JPG, WEBP — Recommended: 800×500px
                                </p>
                            </div>
                            <div id="img-preview">
                                <img id="preview-img" src="" alt="Preview">
                            </div>
                            <p class="hint"><i class="fas fa-info-circle"></i> Image will be shown on your portfolio project card</p>
                        </div>

                    </div><!-- /form-grid -->

                    <div class="btn-row">
                        <button type="submit" name="submit" class="btn-save">
                            <i class="fas fa-plus"></i> Add Project
                        </button>
                        <a href="projects.php" class="btn-back">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                    </div>

                </form>
            </div>

        </div><!-- /page-wrap -->
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('img-preview').style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>

</html>