<?php
session_start();
include("connect.php");

$data = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM contact_info WHERE id=1"));

if (isset($_POST['update'])) {
    $email    = mysqli_real_escape_string($connect, $_POST['email']);
    $phone    = mysqli_real_escape_string($connect, $_POST['phone']);
    $location = mysqli_real_escape_string($connect, $_POST['location']);

    mysqli_query($connect, "UPDATE contact_info SET 
        email='$email',
        phone='$phone',
        location='$location'
        WHERE id=1");

    $msg = "Updated Successfully!";
    $data = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM contact_info WHERE id=1"));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | Admin Panel</title>
    <?php include("sidebar.php"); ?>
    <style>
        .settings-wrap {
            max-width: 620px;
        }

        .card {
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px;
            opacity: 0;
            transform: translateY(16px);
            animation: slideIn 0.45s ease forwards 0.05s;
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            font-size: 1.35rem;
            color: var(--text);
        }

        .card-header p {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 0.6px;
            text-transform: uppercase;
            margin-bottom: 9px;
        }

        .form-group label i {
            font-size: 0.8rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i.ico {
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
            transition: border-color 0.3s, background 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: rgba(201, 169, 127, 0.03);
            box-shadow: 0 0 0 3px rgba(201, 169, 127, 0.08);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        /* Current value hint */
        .current-val {
            font-size: 0.76rem;
            color: var(--text-muted);
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .current-val i {
            color: var(--primary);
            font-size: 0.7rem;
        }

        /* Divider */
        .form-divider {
            height: 1px;
            background: var(--border);
            margin: 28px 0;
        }

        /* Buttons */
        .btn-row {
            display: flex;
            gap: 12px;
        }

        .btn-save {
            padding: 13px 32px;
            background: var(--primary);
            color: var(--dark);
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
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
            font-size: 0.9rem;
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

        /* Alert */
        .alert-success {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(100, 200, 120, 0.1);
            border: 1px solid rgba(100, 200, 120, 0.25);
            border-radius: 10px;
            padding: 13px 16px;
            margin-bottom: 24px;
            color: #7dd89a;
            font-size: 0.88rem;
            font-weight: 500;
            opacity: 0;
            animation: slideIn 0.4s ease forwards;
        }

        @media (max-width: 480px) {
            .btn-row {
                flex-direction: column;
            }

            .card {
                padding: 24px 18px;
            }
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
                <span class="page-title">Settings</span>
            </div>
            <div class="topbar-right">
                <div class="admin-chip">
                    <div class="admin-avatar">A</div>
                    <span>Admin</span>
                </div>
            </div>
        </div>

        <div class="page-wrap">
            <div class="settings-wrap">

                <?php if (isset($msg)): ?>
                    <div class="alert-success">
                        <i class="fas fa-circle-check"></i>
                        <?php echo $msg; ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <div class="card-header-icon"><i class="fas fa-gear"></i></div>
                        <div>
                            <h2>Contact Information</h2>
                            <p>Update your portfolio contact details</p>
                        </div>
                    </div>

                    <form method="POST">

                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Email Address</label>
                            <div class="input-wrap">
                                <i class="fas fa-envelope ico"></i>
                                <input type="text" name="email" class="form-control"
                                    value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>"
                                    placeholder="your@email.com">
                            </div>
                            <?php if (!empty($data['email'])): ?>
                                <div class="current-val">
                                    <i class="fas fa-circle-info"></i>
                                    Current: <?php echo htmlspecialchars($data['email']); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-phone"></i> Phone Number</label>
                            <div class="input-wrap">
                                <i class="fas fa-phone ico"></i>
                                <input type="text" name="phone" class="form-control"
                                    value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>"
                                    placeholder="03xx xxxxxxx">
                            </div>
                            <?php if (!empty($data['phone'])): ?>
                                <div class="current-val">
                                    <i class="fas fa-circle-info"></i>
                                    Current: <?php echo htmlspecialchars($data['phone']); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-map-marker-alt"></i> Location</label>
                            <div class="input-wrap">
                                <i class="fas fa-map-marker-alt ico"></i>
                                <input type="text" name="location" class="form-control"
                                    value="<?php echo htmlspecialchars($data['location'] ?? ''); ?>"
                                    placeholder="City, Country">
                            </div>
                            <?php if (!empty($data['location'])): ?>
                                <div class="current-val">
                                    <i class="fas fa-circle-info"></i>
                                    Current: <?php echo htmlspecialchars($data['location']); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-divider"></div>

                        <div class="btn-row">
                            <button name="update" class="btn-save">
                                <i class="fas fa-floppy-disk"></i> Save Changes
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