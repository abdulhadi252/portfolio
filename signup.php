<?php
session_start();
include("connect.php");

if (isset($_POST['signup'])) {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($connect, "INSERT INTO admins(name,email,password) 
    VALUES('$name','$email','$password')");

    $msg = "success";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Admin Panel</title>
    <?php
    include  "links.php";
    ?>
    <style>
        :root {
            --primary: #c9a97f;
            --accent: #d4af37;
            --dark: #0a0a0a;
            --dark2: #111111;
            --dark3: #1a1a1a;
            --border: rgba(201, 169, 127, 0.18);
            --text: #f0ece4;
            --text-muted: #9e9088;
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--dark2);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Background decoration */
        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(201, 169, 127, 0.06) 0%, transparent 70%);
            top: -100px;
            left: -100px;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.04) 0%, transparent 70%);
            bottom: -100px;
            right: -100px;
            pointer-events: none;
        }

        /* Card */
        .auth-card {
            width: 100%;
            max-width: 440px;
            background: var(--dark);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 44px 40px;
            position: relative;
            z-index: 1;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.5);
        }

        /* Brand */
        .auth-brand {
            text-align: center;
            margin-bottom: 36px;
        }

        .brand-logo {
            width: 58px;
            height: 58px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0 auto 16px;
        }

        .auth-brand h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            color: var(--text);
            margin-bottom: 6px;
        }

        .auth-brand p {
            font-size: 0.88rem;
            color: var(--text-muted);
        }

        /* Divider */
        .divider {
            height: 1px;
            background: var(--border);
            margin: 0 0 28px;
        }

        /* Alert */
        .alert-success {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(100, 200, 120, 0.1);
            border: 1px solid rgba(100, 200, 120, 0.25);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 22px;
            color: #7dd89a;
            font-size: 0.88rem;
            font-weight: 500;
        }

        /* Form */
        .form-group {
            margin-bottom: 18px;
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
            font-size: 0.9rem;
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
            background: rgba(201, 169, 127, 0.04);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        /* Password toggle */
        .toggle-pass {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            cursor: pointer;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .toggle-pass:hover {
            color: var(--primary);
        }

        /* Submit btn */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: var(--dark);
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background: var(--accent);
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(201, 169, 127, 0.3);
        }

        /* Note */
        .auth-note {
            background: rgba(201, 169, 127, 0.07);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 18px;
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }

        .auth-note i {
            color: var(--primary);
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* Footer link */
        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 0.88rem;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .auth-footer a:hover {
            color: var(--accent);
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 32px 24px;
                margin: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="auth-card">

        <div class="auth-brand">
            <div class="brand-logo">AH</div>
            <h2>Create Account</h2>
            <p>Register to access the admin panel</p>
        </div>

        <div class="divider"></div>

        <?php if (isset($msg) && $msg == "success"): ?>
            <div class="alert-success">
                <i class="fas fa-circle-check"></i>
                Signup successful! Please wait for approval.
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label>Full Name</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" class="form-control"
                        placeholder="Abdul Hadi" required>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control"
                        placeholder="you@example.com" required>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="passInput" class="form-control"
                        placeholder="Create a strong password" required>
                    <i class="fas fa-eye toggle-pass" onclick="togglePassword()"></i>
                </div>
            </div>

            <div class="auth-note">
                <i class="fas fa-shield-halved"></i>
                Your password is securely encrypted. After signup, admin approval may be required before login.
            </div>

            <button type="submit" name="signup" class="btn-submit">
                <i class="fas fa-user-plus"></i> Create Account
            </button>

        </form>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Login here</a>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passInput');
            const icon = document.querySelector('.toggle-pass');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</body>

</html>