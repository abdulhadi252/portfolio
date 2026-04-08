<?php
session_start();
include("connect.php");

$projects = mysqli_query($connect, "SELECT * FROM projects ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Projects | Admin Panel</title>

    <?php include("sidebar.php"); ?>

    <style>

        /* ── Page header row ── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }

        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            color: var(--text);
        }

        .page-header p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 3px;
        }

        .btn-add {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 11px 22px;
            background: var(--primary);
            color: var(--dark);
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.88rem;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            background: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(201,169,127,0.25);
        }

        /* ── Cards Grid ── */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 22px;
        }

        /* Each card animates in */
        .project-card {
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            opacity: 0;
            transform: translateY(24px);
            animation: cardIn 0.5s ease forwards;
            transition: border-color 0.3s, box-shadow 0.3s, transform 0.3s;
        }

        .project-card:hover {
            border-color: var(--primary);
            transform: translateY(-4px);
            box-shadow: 0 20px 48px rgba(201,169,127,0.12);
        }

        /* Staggered delay per card */
        .project-card:nth-child(1)  { animation-delay: 0.05s; }
        .project-card:nth-child(2)  { animation-delay: 0.10s; }
        .project-card:nth-child(3)  { animation-delay: 0.15s; }
        .project-card:nth-child(4)  { animation-delay: 0.20s; }
        .project-card:nth-child(5)  { animation-delay: 0.25s; }
        .project-card:nth-child(6)  { animation-delay: 0.30s; }
        .project-card:nth-child(7)  { animation-delay: 0.35s; }
        .project-card:nth-child(8)  { animation-delay: 0.40s; }
        .project-card:nth-child(9)  { animation-delay: 0.45s; }
        .project-card:nth-child(10) { animation-delay: 0.50s; }

        @keyframes cardIn {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Image area */
        .card-img {
            width: 100%;
            height: 180px;
            overflow: hidden;
            background: var(--dark3);
            position: relative;
        }

        .card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }

        .project-card:hover .card-img img {
            transform: scale(1.06);
        }

        /* ID badge on image */
        .card-id {
            position: absolute;
            top: 12px; left: 12px;
            background: rgba(10,10,10,0.75);
            backdrop-filter: blur(6px);
            border: 1px solid var(--border);
            color: var(--primary);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        /* Card body */
        .card-body {
            padding: 18px 20px 20px;
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            color: var(--text);
            margin-bottom: 16px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Action buttons */
        .card-actions {
            display: flex;
            gap: 10px;
        }

        .btn-update {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 14px;
            background: rgba(201,169,127,0.1);
            border: 1px solid var(--border);
            color: var(--primary);
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.84rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-update:hover {
            background: var(--primary);
            color: var(--dark);
            border-color: var(--primary);
            transform: translateY(-1px);
        }

        .btn-delete {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 14px;
            background: rgba(220,80,80,0.08);
            border: 1px solid rgba(220,80,80,0.2);
            color: #e07070;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.84rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            background: rgba(220,80,80,0.18);
            border-color: rgba(220,80,80,0.4);
            transform: translateY(-1px);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            opacity: 0;
            animation: cardIn 0.6s ease 0.2s forwards;
        }

        .empty-icon {
            font-size: 3rem;
            color: var(--border);
            margin-bottom: 18px;
        }

        .empty-state h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        @media (max-width: 640px) {
            .projects-grid { grid-template-columns: 1fr; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 14px; }
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
                <span class="page-title">Projects</span>
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

            <div class="page-header">
                <div>
                    <h1>All Projects</h1>
                    <p>Manage your portfolio projects</p>
                </div>
                <a href="add-project.php" class="btn-add">
                    <i class="fas fa-plus"></i> Add Project
                </a>
            </div>

            <?php if(mysqli_num_rows($projects) > 0): ?>

            <div class="projects-grid">
                <?php while($row = mysqli_fetch_assoc($projects)): ?>
                <div class="project-card">

                    <div class="card-img">
                        <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
                        <span class="card-id"># <?php echo $row['id']; ?></span>
                    </div>

                    <div class="card-body">
                        <div class="card-title"><?php echo $row['title']; ?></div>
                        <div class="card-actions">
                            <a href="update.php?id=<?php echo $row['id']; ?>" class="btn-update">
                                <i class="fas fa-pen"></i> Update
                            </a>
                            <a href="delete-project.php?id=<?php echo $row['id']; ?>" class="btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>

                </div>
                <?php endwhile; ?>
            </div>

            <?php else: ?>

            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-briefcase"></i></div>
                <h3>No Projects Yet</h3>
                <p>You haven't added any projects. Start by adding your first one!</p>
                <a href="add_project.php" class="btn-add" style="display:inline-flex;">
                    <i class="fas fa-plus"></i> Add First Project
                </a>
            </div>

            <?php endif; ?>

        </div>
    </div>

</body>
</html>