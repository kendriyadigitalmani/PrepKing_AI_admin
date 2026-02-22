<?php
// ============== CONFIG ==============
$ADMIN_USER     = 'admin';
$ADMIN_PASS     = 'test1234';      // CHANGE THIS in real projects!
$ADMIN_TITLE    = 'Luna Admin Test';

// Start session
session_start();

// Handle login
$login_error = '';
if (isset($_POST['login'])) {
    $user = trim($_POST['username'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    if ($user === $ADMIN_USER && $pass === $ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $user;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $login_error = 'Wrong username or password';
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Check if logged in
$is_logged_in = !empty($_SESSION['admin_logged_in']);

// Fake dashboard data (you can replace with real DB queries later)
$stats = [
    'users'     => 1428,
    'projects'  => 37,
    'revenue'   => '$48,920',
    'pending'   => 9
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($ADMIN_TITLE) ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg: #0a0a0a;
      --card: #111;
      --text: #e0e0e0;
      --accent: #00d4ff;
      --danger: #ff4d4d;
      --gray: #777;
    }

    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Inter', system-ui, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background: linear-gradient(135deg, #0f1a2a, #000);
      padding: 1.2rem 5%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #222;
    }

    h1 { font-size: 1.6rem; font-weight: 600; letter-spacing: -0.5px; }
    .logout { color: var(--danger); font-weight: 500; }

    main { flex: 1; padding: 3rem 5%; max-width: 1400px; margin: 0 auto; width: 100%; }

    .login-box {
      max-width: 420px;
      margin: 10vh auto;
      background: var(--card);
      padding: 2.5rem;
      border-radius: 12px;
      border: 1px solid #222;
      box-shadow: 0 20px 50px rgba(0,0,0,0.6);
    }

    .login-box h2 { text-align: center; margin-bottom: 1.8rem; font-size: 1.8rem; }
    .error { color: var(--danger); text-align: center; margin-bottom: 1rem; }

    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 0.9rem;
      margin: 0.6rem 0;
      background: #1a1a1a;
      border: 1px solid #333;
      border-radius: 6px;
      color: white;
      font-size: 1rem;
    }

    button {
      width: 100%;
      padding: 1rem;
      background: var(--accent);
      color: black;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      margin-top: 1rem;
      transition: all 0.25s;
    }

    button:hover { background: #00b8e0; transform: translateY(-2px); }

    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 1.8rem;
      margin-top: 2.5rem;
    }

    .card {
      background: var(--card);
      padding: 1.8rem;
      border-radius: 12px;
      border: 1px solid #222;
      text-align: center;
      transition: all 0.3s;
    }

    .card:hover {
      transform: translateY(-8px);
      border-color: var(--accent);
      box-shadow: 0 20px 40px rgba(0,212,255,0.12);
    }

    .card h3 { font-size: 2.6rem; margin: 0.4rem 0; color: var(--accent); }
    .card p { color: var(--gray); font-size: 0.95rem; }

    footer {
      text-align: center;
      padding: 2rem;
      color: var(--gray);
      font-size: 0.9rem;
      border-top: 1px solid #111;
    }
  </style>
</head>
<body>

<?php if ($is_logged_in): ?>

  <header>
    <h1><?= htmlspecialchars($ADMIN_TITLE) ?></h1>
    <a href="?logout=1" class="logout">Logout</a>
  </header>

  <main>
    <h2 style="text-align:center; margin-bottom:2rem; color:#aaa;">Welcome back, <?= htmlspecialchars($_SESSION['admin_user']) ?></h2>

    <div class="dashboard-grid">
      <div class="card">
        <p>Total Users</p>
        <h3><?= number_format($stats['users']) ?></h3>
      </div>
      <div class="card">
        <p>Active Projects</p>
        <h3><?= $stats['projects'] ?></h3>
      </div>
      <div class="card">
        <p>This Month Revenue</p>
        <h3><?= $stats['revenue'] ?></h3>
      </div>
      <div class="card">
        <p>Pending Tasks</p>
        <h3><?= $stats['pending'] ?></h3>
      </div>
    </div>

    <div style="margin-top:4rem; text-align:center; color:#555;">
      <p>This is a test admin panel — single file version</p>
      <p style="font-size:0.9rem;">Add your real CRUD / database logic here later</p>
    </div>
  </main>

<?php else: ?>

  <main>
    <div class="login-box">
      <h2>Admin Login</h2>

      <?php if ($login_error): ?>
        <div class="error"><?= htmlspecialchars($login_error) ?></div>
      <?php endif; ?>

      <form method="post">
        <input type="text"     name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Sign In</button>
      </form>

      <p style="margin-top:1.5rem; text-align:center; color:#555; font-size:0.9rem;">
        Test credentials: <strong>admin</strong> / <strong>test1234</strong>
      </p>
    </div>
  </main>

<?php endif; ?>

<footer>
  © <?= date('Y') ?> Luna Test Admin • Single-file demo
</footer>

</body>
</html>