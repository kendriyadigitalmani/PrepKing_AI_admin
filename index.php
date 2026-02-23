<?php
// ============== CONFIG ==============
$ADMIN_USER     = 'admin';
$ADMIN_PASS     = 'test1234';      // ← CHANGE THIS in real projects!
$ADMIN_TITLE    = 'Luna Admin Test Admin4';
$VERSION        = '1.3.0';         // ← You can update this manually when you make changes

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

// Fake dashboard data
$stats = [
    'users'     => 1428,
    'projects'  => 37,
    'revenue'   => '$48,920',
    'pending'   => 9
];

// Current date & time (updated on every page load)
$current_datetime = date('Y-m-d H:i:s');
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
      --warning: #ffcc00;
      --warning-bg: #4a3c00;
      --warning-border: #ffaa00;
      --success: #00ff9d;
      --gray: #777;
      --info: #4da6ff;
      --version: #00ff9d;     /* green-ish for version */
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

    main { flex: 1; padding: 2rem 5%; max-width: 1400px; margin: 0 auto; width: 100%; }

    .notice-banner {
      background: var(--warning-bg);
      border: 2px solid var(--warning-border);
      color: #fff;
      padding: 1.2rem;
      border-radius: 8px;
      margin: 1.5rem 0 2.5rem;
      text-align: center;
      font-weight: 600;
      font-size: 1.15rem;
      box-shadow: 0 4px 12px rgba(255,170,0,0.25);
    }

    .notice-banner strong { color: var(--warning); }

    .highlight-warning {
      background: var(--warning);
      color: #000;
      padding: 0.15rem 0.4rem;
      border-radius: 4px;
      font-weight: 600;
    }

    .highlight-danger {
      color: var(--danger);
      font-weight: 600;
      background: rgba(255,77,77,0.15);
      padding: 0.1rem 0.35rem;
      border-radius: 4px;
    }

    .highlight-info {
      color: var(--info);
      font-weight: 500;
    }

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

    .error { color: var(--danger); text-align: center; margin-bottom: 1rem; font-weight: 500; }

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
      padding: 2.2rem 1rem;
      color: var(--gray);
      font-size: 0.95rem;
      border-top: 1px solid #111;
      background: rgba(0,0,0,0.3);
    }

    .version-info {
      color: var(--version);
      font-weight: 500;
      margin-top: 0.6rem;
      font-size: 1rem;
    }

    .version-info span {
      color: #88ffcc;
      font-weight: 600;
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

    <div class="notice-banner">
      <strong>TEST / DEVELOPMENT MODE ACTIVE</strong><br>
      Hardcoded credentials • Do <span class="highlight-danger">NOT</span> use in production!
    </div>

    <h2 style="text-align:center; margin-bottom:2rem; color:#ccc; font-size:1.9rem;">
      Welcome back, <span class="highlight-info"><?= htmlspecialchars($_SESSION['admin_user']) ?></span>
    </h2>

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

    <div style="margin-top:4rem; text-align:center; color:#555; font-size:1.05rem;">
      <p>This is a <span class="highlight-warning">test admin panel</span> — single file version</p>
      <p style="font-size:0.95rem; margin-top:0.6rem;">
        <span class="highlight-danger">Security reminder:</span> Password is hardcoded → 
        <strong class="highlight-warning">change it immediately</strong> for anything real
      </p>
    </div>

  </main>

<?php else: ?>

  <main>
    <div class="login-box">
      <h2>Admin Login admin 9</h2>

      <?php if ($login_error): ?>
        <div class="error"><?= htmlspecialchars($login_error) ?></div>
      <?php endif; ?>

      <form method="post">
        <input type="text"     name="username"   placeholder="Username"   required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Sign In</button>
      </form>

      <p style="margin-top:1.8rem; text-align:center; color:#666; font-size:0.95rem;">
        Test credentials: <strong class="highlight-warning">admin</strong> / 
        <strong class="highlight-warning">test1234</strong><br>
        <span class="highlight-danger">(Change password in production!)</span>
      </p>
    </div>
  </main>

<?php endif; ?>

<footer>
  © <?= date('Y') ?> Luna Test Admin • Single-file demo
  <div class="version-info">
    Version <span><?= htmlspecialchars($VERSION) ?></span>  
    — Last updated / generated: <span><?= htmlspecialchars($current_datetime) ?></span>
  </div>
</footer>

</body>
</html>