<?php
session_start();
include("connect.php");

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$total_msgs     = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM messages"));
$total_projects = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM projects"));
$total_skills   = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM skills"));
$total_admins   = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM admins"));

$messages = mysqli_query($connect,"SELECT * FROM messages ORDER BY id DESC");
$projects = mysqli_query($connect,"SELECT * FROM projects ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin Panel</title>
    <?php include("sidebar.php"); ?>
    <style>

        /* ── Stats Cards ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 36px;
        }

        .stat-card {
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px 24px;
            position: relative;
            overflow: hidden;
            transition: border-color 0.3s, transform 0.3s, box-shadow 0.3s;
            opacity: 0;
            transform: translateY(18px);
            animation: cardIn 0.4s ease forwards;
        }

        .stat-card:nth-child(1) { animation-delay: 0.04s; }
        .stat-card:nth-child(2) { animation-delay: 0.10s; }
        .stat-card:nth-child(3) { animation-delay: 0.16s; }
        .stat-card:nth-child(4) { animation-delay: 0.22s; }

        @keyframes cardIn {
            to { opacity: 1; transform: translateY(0); }
        }

        .stat-card::before {
            content: '';
            position: absolute; top: 0; left: 0;
            width: 100%; height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            transform: scaleX(0); transform-origin: left;
            transition: transform 0.4s ease;
        }

        .stat-card:hover::before { transform: scaleX(1); }

        .stat-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 16px 40px rgba(201,169,127,0.1);
        }

        .stat-card-icon {
            width: 44px; height: 44px;
            background: rgba(201,169,127,0.1);
            border: 1px solid var(--border);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary); font-size: 1.1rem;
            margin-bottom: 16px;
        }

        .stat-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem; color: var(--primary);
            line-height: 1; margin-bottom: 6px;
        }

        .stat-card p {
            font-size: 0.82rem; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600;
        }

        /* ── Section header ── */
        .section-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }

        .section-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem; color: var(--text);
            display: flex; align-items: center; gap: 10px;
        }

        .section-header h2 i { color: var(--primary); font-size: 1rem; }

        .count-pill {
            background: rgba(201,169,127,0.1); border: 1px solid var(--border);
            color: var(--primary); font-size: 0.72rem; font-weight: 700;
            padding: 3px 10px; border-radius: 20px;
        }

        .btn-view-all {
            font-size: 0.82rem; color: var(--primary);
            text-decoration: none; font-weight: 600;
            display: flex; align-items: center; gap: 6px;
            transition: gap 0.2s;
        }

        .btn-view-all:hover { gap: 10px; }

        /* ── Tables ── */
        .table-card {
            background: var(--dark); border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
            margin-bottom: 32px;
            opacity: 0; transform: translateY(14px);
            animation: cardIn 0.5s ease forwards 0.3s;
        }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%; border-collapse: collapse;
        }

        thead th {
            background: var(--dark3);
            padding: 13px 18px;
            text-align: left; font-size: 0.72rem;
            font-weight: 700; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 0.8px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        tbody td {
            padding: 14px 18px;
            font-size: 0.88rem; color: var(--text);
            border-bottom: 1px solid rgba(201,169,127,0.06);
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }

        tbody tr {
            transition: background 0.2s;
        }

        tbody tr:hover { background: rgba(201,169,127,0.03); }

        /* ID badge */
        .id-badge {
            display: inline-flex; align-items: center;
            background: rgba(201,169,127,0.08); border: 1px solid var(--border);
            color: var(--primary); font-size: 0.72rem; font-weight: 700;
            padding: 3px 9px; border-radius: 6px; letter-spacing: 0.5px;
        }

        /* Sender name */
        .sender {
            display: flex; align-items: center; gap: 10px;
        }

        .sender-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700; color: var(--dark);
            flex-shrink: 0;
        }

        .sender span { font-weight: 500; color: var(--text); }

        /* Email */
        .email-text { color: var(--text-muted); font-size: 0.84rem; }

        /* Message truncated */
        .msg-text {
            color: var(--text-muted); font-size: 0.84rem;
            max-width: 280px; overflow: hidden;
            text-overflow: ellipsis; white-space: nowrap;
        }

        /* Project image */
        .proj-img {
            width: 52px; height: 40px;
            object-fit: cover; border-radius: 8px;
            border: 1px solid var(--border);
            display: block;
        }

        /* Project title */
        .proj-title { font-weight: 600; color: var(--text); font-size: 0.9rem; }
        .proj-desc  { color: var(--text-muted); font-size: 0.82rem; margin-top: 2px;
                      max-width: 260px; overflow: hidden;
                      text-overflow: ellipsis; white-space: nowrap; }

        /* Empty row */
        .empty-row td {
            text-align: center; padding: 40px;
            color: var(--text-muted); font-size: 0.9rem;
        }

        .empty-row td i { display: block; font-size: 2rem; color: var(--border); margin-bottom: 10px; }

        @media (max-width: 900px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 480px) {
            .stats-row { grid-template-columns: 1fr 1fr; }
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
                <span class="page-title">Dashboard</span>
            </div>
            <div class="topbar-right">
                <div class="admin-chip">
                    <div class="admin-avatar">A</div>
                    <span>Admin</span>
                </div>
            </div>
        </div>

        <div class="page-wrap">

            <!-- STAT CARDS -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-card-icon"><i class="fas fa-envelope"></i></div>
                    <h2><?php echo $total_msgs; ?></h2>
                    <p>Messages</p>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon"><i class="fas fa-briefcase"></i></div>
                    <h2><?php echo $total_projects; ?></h2>
                    <p>Projects</p>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon"><i class="fas fa-code"></i></div>
                    <h2><?php echo $total_skills; ?></h2>
                    <p>Skills</p>
                </div>
                <div class="stat-card">
                    <div class="stat-card-icon"><i class="fas fa-user-shield"></i></div>
                    <h2><?php echo $total_admins; ?></h2>
                    <p>Admins</p>
                </div>
            </div>

            <!-- MESSAGES TABLE -->
            <div class="section-header">
                <h2><i class="fas fa-envelope"></i> Messages
                    <span class="count-pill"><?php echo $total_msgs; ?></span>
                </h2>
                <a href="messages.php" class="btn-view-all">
                    View all <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="table-card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sender</th>
                                <th>Email</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($messages) > 0):
                                while($row = mysqli_fetch_assoc($messages)): ?>
                            <tr>
                                <td><span class="id-badge">#<?php echo $row['id']; ?></span></td>
                                <td>
                                    <div class="sender">
                                        <div class="sender-avatar">
                                            <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                        </div>
                                        <span><?php echo htmlspecialchars($row['name']); ?></span>
                                    </div>
                                </td>
                                <td><span class="email-text"><?php echo htmlspecialchars($row['email']); ?></span></td>
                                <td><div class="msg-text"><?php echo htmlspecialchars($row['message']); ?></div></td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr class="empty-row">
                                <td colspan="4">
                                    <i class="fas fa-envelope-open"></i>
                                    No messages yet
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PROJECTS TABLE -->
            <div class="section-header">
                <h2><i class="fas fa-briefcase"></i> Projects
                    <span class="count-pill"><?php echo $total_projects; ?></span>
                </h2>
                <a href="projects.php" class="btn-view-all">
                    View all <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="table-card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title & Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($projects) > 0):
                                while($p = mysqli_fetch_assoc($projects)): ?>
                            <tr>
                                <td><span class="id-badge">#<?php echo $p['id']; ?></span></td>
                                <td>
                                    <img src="uploads/<?php echo $p['image']; ?>"
                                         alt="<?php echo $p['title']; ?>"
                                         class="proj-img">
                                </td>
                                <td>
                                    <div class="proj-title"><?php echo htmlspecialchars($p['title']); ?></div>
                                    <div class="proj-desc"><?php echo htmlspecialchars($p['description']); ?></div>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr class="empty-row">
                                <td colspan="3">
                                    <i class="fas fa-briefcase"></i>
                                    No projects yet
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div><!-- /page-wrap -->
    </div><!-- /main-content -->

</body>
</html>