<?php
session_start();

require_once 'languages.php';

// ── Pagină protejată — redirect dacă nu e logat ──────────────────────────────
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=dashboard');
    exit;
}

$userId   = (int) $_SESSION['user_id'];
$userName = htmlspecialchars($_SESSION['user_name']);
$userEmail = htmlspecialchars($_SESSION['user_email'] ?? '');

// ── Citire utilizatori din users.json ────────────────────────────────────────
function getUsers($file) {
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true) ?? [];
}

// ── Citire / salvare logos.json ───────────────────────────────────────────────
function getLogos($file) {
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true) ?? [];
}

function saveLogos($file, $logos) {
    file_put_contents($file, json_encode($logos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$logosFile = 'data/logos.json';
$usersFile = 'data/users.json';

// ── Acțiune: șterge logo ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_logo'])) {
    $deleteId = (int) $_POST['delete_logo'];
    $logos    = getLogos($logosFile);
    $logos    = array_values(array_filter($logos, fn($l) => !($l['id'] === $deleteId && $l['user_id'] === $userId)));
    saveLogos($logosFile, $logos);
    header('Location: dashboard.php');
    exit;
}

// ── Filtrare logos pentru utilizatorul curent ─────────────────────────────────
$allLogos   = getLogos($logosFile);
$userLogos  = array_values(array_filter($allLogos, fn($l) => $l['user_id'] === $userId));
$totalLogos = count($userLogos);

// ── Date cont utilizator ──────────────────────────────────────────────────────
$users        = getUsers($usersFile);
$currentUser  = null;
foreach ($users as $u) {
    if ((int)$u['id'] === $userId) { $currentUser = $u; break; }
}
$memberSince = $currentUser ? date('M Y', strtotime($currentUser['created_at'])) : 'N/A';
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= t('dashboard_title') ?> — NeuroLogo AI</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>

    /* ── Layout ────────────────────────────────────────────────────────────── */
    .dash-wrapper {
      position: relative;
      z-index: 1;
      max-width: 1200px;
      margin: 0 auto;
      padding: 88px 48px 80px;
    }

    /* ── Top header ─────────────────────────────────────────────────────────── */
    .dash-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 36px;
      flex-wrap: wrap;
      gap: 16px;
    }
    .dash-greeting {
      font-family: 'Syne', sans-serif;
      font-size: clamp(22px, 3vw, 30px);
      font-weight: 800;
      color: var(--text);
      letter-spacing: -0.5px;
    }
    .dash-greeting span { color: var(--purple2); }
    .dash-subtext {
      font-size: 13px;
      color: var(--text3);
      margin-top: 4px;
    }
    .dash-actions {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    /* ── Stat cards ──────────────────────────────────────────────────────────── */
    .stat-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-bottom: 36px;
    }
    .stat-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 22px 24px;
      display: flex;
      align-items: center;
      gap: 16px;
      transition: border-color 0.2s;
    }
    .stat-card:hover { border-color: rgba(124, 92, 252, 0.3); }
    .stat-icon {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      flex-shrink: 0;
    }
    .stat-icon.purple { background: rgba(124, 92, 252, 0.15); }
    .stat-icon.blue   { background: rgba(79, 138, 255, 0.12); }
    .stat-icon.green  { background: rgba(34, 197, 94, 0.10); }
    .stat-value {
      font-family: 'Syne', sans-serif;
      font-size: 26px;
      font-weight: 800;
      color: var(--text);
      line-height: 1;
    }
    .stat-desc {
      font-size: 12px;
      color: var(--text3);
      margin-top: 3px;
    }

    /* ── Two-column layout ───────────────────────────────────────────────────── */
    .dash-cols {
      display: grid;
      grid-template-columns: 320px 1fr;
      gap: 24px;
      align-items: start;
    }

    /* ── Generator panel ─────────────────────────────────────────────────────── */
    .panel {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 28px;
    }
    .panel-title {
      font-family: 'Syne', sans-serif;
      font-size: 16px;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .panel-title-icon {
      width: 28px;
      height: 28px;
      background: rgba(124, 92, 252, 0.15);
      border-radius: 7px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
      margin-bottom: 14px;
    }
    .form-label {
      font-size: 12px;
      font-weight: 500;
      color: var(--text2);
    }
    .form-input, .form-select {
      background: var(--card2);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 13px;
      font-family: 'DM Sans', sans-serif;
      color: var(--text);
      outline: none;
      width: 100%;
      appearance: none;
      transition: border-color 0.2s;
    }
    .form-input::placeholder { color: var(--text3); }
    .form-input:focus,
    .form-select:focus { border-color: rgba(124, 92, 252, 0.5); }
    .select-wrap { position: relative; }
    .select-wrap::after {
      content: '▾';
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text3);
      font-size: 11px;
      pointer-events: none;
    }

    .generate-btn {
      width: 100%;
      background: linear-gradient(135deg, var(--purple), var(--blue));
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 13px;
      font-size: 14px;
      font-weight: 600;
      font-family: 'DM Sans', sans-serif;
      cursor: pointer;
      transition: opacity 0.2s, transform 0.15s;
      margin-top: 4px;
    }
    .generate-btn:hover { opacity: 0.9; transform: translateY(-1px); }
    .generate-btn:disabled { opacity: 0.5; cursor: not-allowed; }

    /* ── User profile card ───────────────────────────────────────────────────── */
    .profile-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 24px;
      margin-bottom: 20px;
      text-align: center;
    }
    .profile-avatar {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, var(--purple), var(--blue));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Syne', sans-serif;
      font-size: 22px;
      font-weight: 800;
      color: #fff;
      margin: 0 auto 12px;
    }
    .profile-name {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 15px;
      color: var(--text);
    }
    .profile-email {
      font-size: 12px;
      color: var(--text3);
      margin-top: 2px;
    }
    .profile-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: rgba(124, 92, 252, 0.1);
      border: 1px solid rgba(124, 92, 252, 0.2);
      border-radius: 20px;
      padding: 3px 10px;
      font-size: 11px;
      color: var(--purple2);
      margin-top: 10px;
    }
    .profile-since {
      font-size: 11px;
      color: var(--text3);
      margin-top: 8px;
    }

    /* ── Logos grid ──────────────────────────────────────────────────────────── */
    .logos-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
    }
    .logos-count {
      font-size: 12px;
      color: var(--text3);
      background: var(--card2);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 3px 10px;
    }

    .logos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 14px;
    }

    .logo-card {
      background: var(--card2);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 20px 16px 16px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      position: relative;
      transition: border-color 0.2s, transform 0.2s;
      animation: fadeIn 0.4s ease both;
    }
    .logo-card:hover {
      border-color: rgba(124, 92, 252, 0.4);
      transform: translateY(-2px);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(8px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .logo-card-icon {
      width: 52px;
      height: 52px;
      background: rgba(124, 92, 252, 0.1);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 26px;
    }
    .logo-card-name {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 14px;
      color: var(--text);
      text-align: center;
    }
    .logo-card-meta {
      font-size: 11px;
      color: var(--text3);
      text-align: center;
      line-height: 1.5;
    }
    .logo-card-tag {
      font-size: 10px;
      color: var(--purple2);
      background: rgba(124, 92, 252, 0.08);
      border: 1px solid rgba(124, 92, 252, 0.15);
      border-radius: 20px;
      padding: 2px 8px;
    }
    .logo-card-date {
      font-size: 10px;
      color: var(--text3);
      width: 100%;
      text-align: center;
      border-top: 1px solid var(--border);
      padding-top: 8px;
      margin-top: 2px;
    }

    .delete-btn {
      position: absolute;
      top: 8px;
      right: 8px;
      width: 24px;
      height: 24px;
      background: rgba(239, 68, 68, 0.0);
      border: none;
      border-radius: 6px;
      color: var(--text3);
      font-size: 14px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s, color 0.2s;
      opacity: 0;
    }
    .logo-card:hover .delete-btn {
      opacity: 1;
    }
    .delete-btn:hover {
      background: rgba(239, 68, 68, 0.15);
      color: #f87171;
    }

    /* ── Empty state ─────────────────────────────────────────────────────────── */
    .empty-state {
      grid-column: 1 / -1;
      text-align: center;
      padding: 48px 24px;
      color: var(--text3);
    }
    .empty-icon {
      font-size: 40px;
      margin-bottom: 12px;
      opacity: 0.4;
    }
    .empty-title {
      font-family: 'Syne', sans-serif;
      font-size: 16px;
      font-weight: 700;
      color: var(--text2);
      margin-bottom: 6px;
    }
    .empty-desc { font-size: 13px; }

    /* ── Success banner ──────────────────────────────────────────────────────── */
    .success-banner {
      background: rgba(34, 197, 94, 0.08);
      border: 1px solid rgba(34, 197, 94, 0.2);
      border-radius: 10px;
      padding: 12px 18px;
      font-size: 13px;
      color: #4ade80;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 24px;
      animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-8px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Nav user menu ───────────────────────────────────────────────────────── */
    .user-menu { display: flex; align-items: center; gap: 12px; }
    .user-greeting { font-size: 13px; color: var(--text2); }
    .user-greeting strong { color: var(--purple2); }
    .nav-link-btn {
      background: transparent;
      color: var(--text2);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 8px 16px;
      font-size: 13px;
      font-weight: 500;
      font-family: 'DM Sans', sans-serif;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      transition: border-color 0.2s, color 0.2s;
    }
    .nav-link-btn:hover { border-color: rgba(255,255,255,0.2); color: var(--text); }

    /* ── Preview image style ─────────────────────────────────────────────────── */
    .preview-image {
      max-width: 180px;
      border-radius: 12px;
      margin: 0 auto;
      display: block;
      border: 1px solid var(--border);
    }

    /* ── Dark/Light Mode Toggle Button ───────────────────────────────────────── */
    .theme-toggle-btn {
        background: var(--card2) !important;
        border: 1px solid var(--border) !important;
        border-radius: 30px !important;
        padding: 6px 14px !important;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .theme-toggle-btn:hover {
        background: var(--border) !important;
        transform: scale(1.02);
    }
    .theme-toggle-btn span {
        font-size: 13px;
        color: var(--text);
        font-weight: 500;
    }

    /* ── Language Switch Buttons ─────────────────────────────────────────────── */
    .language-switch {
        display: flex;
        gap: 5px;
        margin-right: 5px;
    }
    .lang-btn {
        background: transparent;
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 12px;
        cursor: pointer;
        color: var(--text);
        transition: all 0.2s ease;
        font-weight: 500;
    }
    .lang-btn:hover {
        background: var(--border);
    }
    .lang-btn.active {
        background: var(--purple);
        border-color: var(--purple);
        color: white;
    }

    /* ── Responsive ──────────────────────────────────────────────────────────── */
    @media (max-width: 900px) {
      .dash-cols { grid-template-columns: 1fr; }
      .stat-grid { grid-template-columns: 1fr 1fr; }
      .dash-wrapper { padding: 88px 20px 60px; }
    }
    @media (max-width: 560px) {
      .stat-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <!-- ── Nav ──────────────────────────────────────────────────────────────── -->
  <nav>
    <div class="nav-logo">Neuro<span>Logo</span> AI</div>
    <ul class="nav-links">
      <li><a href="index.php"><?= t('nav_home') ?></a></li>
      <li><a href="dashboard.php" style="color:var(--text);"><?= t('nav_dashboard') ?></a></li>
    </ul>
    <div class="user-menu">
      <!-- Butoane schimbare limbă -->
      <div class="language-switch">
        <a href="?lang=ro" style="text-decoration: none;">
          <button class="lang-btn <?= $current_lang == 'ro' ? 'active' : '' ?>">RO</button>
        </a>
        <a href="?lang=en" style="text-decoration: none;">
          <button class="lang-btn <?= $current_lang == 'en' ? 'active' : '' ?>">EN</button>
        </a>
      </div>
      
      <!-- Buton Dark/Light Mode -->
      <button id="themeToggle" class="theme-toggle-btn">
        <span id="themeIcon">🌙</span>
        <span id="themeText"><?= t('dark_mode') ?></span>
      </button>
      
      <span class="user-greeting"><?= t('hello') ?>, <strong><?= $userName ?></strong></span>
      <a href="logout.php" class="nav-link-btn"><?= t('nav_signout') ?></a>
    </div>
  </nav>

  <div class="dash-wrapper">

    <!-- ── Success banner ──────────────────────────────────────────────────── -->
    <?php if (isset($_GET['generated'])): ?>
      <div class="success-banner">
        ✓ <?= t('logo_saved_success') ?>
      </div>
    <?php endif; ?>

    <!-- ── Header ──────────────────────────────────────────────────────────── -->
    <div class="dash-header">
      <div>
        <div class="dash-greeting"><?= t('dashboard_welcome') ?> <span><?= $userName ?></span> 👋</div>
        <div class="dash-subtext"><?= t('dashboard_manage') ?></div>
      </div>
    </div>

    <!-- ── Stat cards ───────────────────────────────────────────────────────── -->
    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-icon purple">🎨</div>
        <div>
          <div class="stat-value"><?= $totalLogos ?></div>
          <div class="stat-desc"><?= t('dashboard_logos_generated') ?></div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon blue">📅</div>
        <div>
          <div class="stat-value"><?= $memberSince ?></div>
          <div class="stat-desc"><?= t('dashboard_member_since') ?></div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon green">✓</div>
        <div>
          <div class="stat-value"><?= t('dashboard_active') ?></div>
          <div class="stat-desc"><?= t('dashboard_account_status') ?></div>
        </div>
      </div>
    </div>

    <!-- ── Two-column layout ────────────────────────────────────────────────── -->
    <div class="dash-cols">

      <!-- Left: profile + generator -->
      <div>

        <!-- Profile card -->
        <div class="profile-card">
          <div class="profile-avatar"><?= mb_strtoupper(mb_substr($userName, 0, 1)) ?></div>
          <div class="profile-name"><?= $userName ?></div>
          <div class="profile-email"><?= $userEmail ?></div>
          <div class="profile-badge">⚡ Pro Member</div>
          <div class="profile-since"><?= t('dashboard_member_since') ?> <?= $memberSince ?></div>
        </div>

        <!-- Generator form -->
        <div class="panel">
          <div class="panel-title">
            <div class="panel-title-icon">✦</div>
            <?= t('dashboard_generate_new') ?>
          </div>

          <form id="generateForm">
            <div class="form-group">
              <label class="form-label"><?= t('form_brand') ?></label>
              <input class="form-input" type="text" name="brand_name" id="brand_name" placeholder="<?= t('form_brand_placeholder') ?>" required>
            </div>

            <div class="form-group">
              <label class="form-label"><?= t('form_industry') ?></label>
              <div class="select-wrap">
                <select class="form-select" name="industry" id="industry">
                  <option><?= t('form_industry_tech') ?></option>
                  <option><?= t('form_industry_finance') ?></option>
                  <option><?= t('form_industry_health') ?></option>
                  <option><?= t('form_industry_fashion') ?></option>
                  <option><?= t('form_industry_food') ?></option>
                  <option><?= t('form_industry_education') ?></option>
                  <option><?= t('form_industry_sports') ?></option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label"><?= t('form_style') ?></label>
              <div class="select-wrap">
                <select class="form-select" name="style" id="style">
                  <option><?= t('form_style_minimal') ?></option>
                  <option><?= t('form_style_modern') ?></option>
                  <option><?= t('form_style_bold') ?></option>
                  <option><?= t('form_style_playful') ?></option>
                  <option><?= t('form_style_elegant') ?></option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label"><?= t('form_color') ?></label>
              <input class="form-input" type="text" name="color" id="color" placeholder="<?= t('form_color_placeholder') ?>" value="Orange & Black">
            </div>

            <button type="submit" class="generate-btn" id="generateBtn">
              ✦ <?= t('btn_generate') ?>
            </button>
          </form>

          <!-- Aici se va afișa previzualizarea logo-ului generat -->
          <div id="generatedPreview" style="margin-top: 20px;"></div>
        </div>

      </div>

      <!-- Right: saved logos -->
      <div class="panel">
        <div class="logos-header">
          <div class="panel-title" style="margin-bottom:0;">
            <div class="panel-title-icon">🎨</div>
            <?= t('dashboard_my_logos') ?>
          </div>
          <span class="logos-count"><?= $totalLogos ?> <?= t('dashboard_saved') ?></span>
        </div>

        <div class="logos-grid">
          <?php if (empty($userLogos)): ?>
            <div class="empty-state">
              <div class="empty-icon">🎨</div>
              <div class="empty-title"><?= t('dashboard_no_logos') ?></div>
              <div class="empty-desc"><?= t('dashboard_no_logos_desc') ?></div>
            </div>
          <?php else: ?>
            <?php foreach (array_reverse($userLogos) as $i => $logo): ?>
              <div class="logo-card" style="animation-delay: <?= $i * 0.05 ?>s">

                <!-- Delete button -->
                <form method="POST" action="dashboard.php" style="display:contents;">
                  <button class="delete-btn" type="submit" name="delete_logo"
                          value="<?= (int)$logo['id'] ?>"
                          onclick="return confirm('<?= t('delete_confirm') ?>')">✕</button>
                </form>

                <div class="logo-card-icon">
                  <?php if (!empty($logo['image_url'])): ?>
                    <img src="<?= htmlspecialchars($logo['image_url']) ?>" style="width: 40px; height: 40px; object-fit: contain; border-radius: 8px;">
                  <?php else: ?>
                    <?= htmlspecialchars($logo['icon'] ?? '🎨') ?>
                  <?php endif; ?>
                </div>
                <div class="logo-card-name"><?= htmlspecialchars($logo['brand_name']) ?></div>
                <div class="logo-card-meta">
                  <?= htmlspecialchars($logo['color']) ?>
                </div>
                <div class="logo-card-tag"><?= htmlspecialchars($logo['style']) ?> · <?= htmlspecialchars($logo['industry']) ?></div>
                <div class="logo-card-date">
                  <?= date('d M Y, H:i', strtotime($logo['created_at'])) ?>
                </div>

              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

    </div><!-- /.dash-cols -->

  </div><!-- /.dash-wrapper -->

  <script>
  // Dark/Light Mode Toggle
  (function() {
      const themeToggle = document.getElementById('themeToggle');
      const themeIcon = document.getElementById('themeIcon');
      const themeText = document.getElementById('themeText');
      
      const savedTheme = localStorage.getItem('theme');
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      
      if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
          document.documentElement.setAttribute('data-theme', 'dark');
          if (themeIcon) themeIcon.textContent = '☀️';
          if (themeText) themeText.textContent = '<?= t('light_mode') ?>';
      } else {
          document.documentElement.setAttribute('data-theme', 'light');
          if (themeIcon) themeIcon.textContent = '🌙';
          if (themeText) themeText.textContent = '<?= t('dark_mode') ?>';
      }
      
      if (themeToggle) {
          themeToggle.addEventListener('click', () => {
              const currentTheme = document.documentElement.getAttribute('data-theme');
              if (currentTheme === 'dark') {
                  document.documentElement.setAttribute('data-theme', 'light');
                  localStorage.setItem('theme', 'light');
                  if (themeIcon) themeIcon.textContent = '🌙';
                  if (themeText) themeText.textContent = '<?= t('dark_mode') ?>';
              } else {
                  document.documentElement.setAttribute('data-theme', 'dark');
                  localStorage.setItem('theme', 'dark');
                  if (themeIcon) themeIcon.textContent = '☀️';
                  if (themeText) themeText.textContent = '<?= t('light_mode') ?>';
              }
          });
      }
  })();

  // Dashboard - Generare logo cu API real
  document.addEventListener('DOMContentLoaded', function() {
      const generateForm = document.getElementById('generateForm');
      if (!generateForm) return;
      
      generateForm.addEventListener('submit', async function(e) {
          e.preventDefault();
          
          const brandName = document.getElementById('brand_name').value;
          const industry = document.getElementById('industry').value;
          const style = document.getElementById('style').value;
          const color = document.getElementById('color').value;
          
          const generateBtn = document.getElementById('generateBtn');
          const originalText = generateBtn.textContent;
          const previewDiv = document.getElementById('generatedPreview');
          
          if (!brandName) {
              previewDiv.innerHTML = '<div style="background: rgba(239,68,68,0.1); border-radius: 12px; padding: 16px; color: #f87171; text-align: center;">❌ <?= t('error_brand_required') ?></div>';
              return;
          }
          
          // Reset preview
          previewDiv.innerHTML = '<div style="padding: 20px; background: var(--card2); border-radius: 12px; color: var(--text3); text-align: center;">⏳ <?= t('dashboard_generating') ?><br><small style="font-size: 11px;"><?= t('dashboard_generating_time') ?></small></div>';
          generateBtn.textContent = '✦ <?= t('generating') ?>...';
          generateBtn.disabled = true;
          
          try {
              // Generează logo cu API
              const generateResponse = await fetch('api/generate_logo.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({ brand_name: brandName, industry, style, color })
              });
              
              const generateResult = await generateResponse.json();
              console.log('API Response:', generateResult);
              
              if (generateResult.success && generateResult.image_url) {
                  // Afișează previzualizarea cu imaginea reală
                  let previewHtml = '<div style="background: var(--card2); border-radius: 12px; padding: 16px; margin-top: 16px; text-align: center;">';
                  previewHtml += '<p style="font-size: 12px; color: var(--text2); margin-bottom: 12px;">✨ <?= t('dashboard_ai_generated') ?></p>';
                  previewHtml += `<img src="${generateResult.image_url}" alt="Generated Logo" style="max-width: 100%; border-radius: 12px; border: 1px solid var(--border);">`;
                  previewHtml += '<button id="confirmSaveBtn" style="margin-top: 16px; background: linear-gradient(135deg, var(--purple), var(--blue)); color: white; border: none; border-radius: 8px; padding: 10px 24px; font-size: 13px; font-weight: 600; cursor: pointer;">✓ <?= t('dashboard_save') ?></button>';
                  previewHtml += '</div>';
                  previewDiv.innerHTML = previewHtml;
                  
                  // Salvează când utilizatorul apasă butonul
                  document.getElementById('confirmSaveBtn')?.addEventListener('click', async function() {
                      this.textContent = '<?= t('dashboard_saving') ?>...';
                      this.disabled = true;
                      
                      const saveResponse = await fetch('api/save_logo.php', {
                          method: 'POST',
                          headers: { 'Content-Type': 'application/json' },
                          body: JSON.stringify({ 
                              brand_name: brandName, 
                              industry, 
                              style, 
                              color,
                              image_url: generateResult.image_url,
                              icon: ''
                          })
                      });
                      
                      const saveResult = await saveResponse.json();
                      
                      if (saveResult.success) {
                          previewDiv.innerHTML = '<div style="background: rgba(34,197,94,0.1); border-radius: 12px; padding: 16px; text-align: center; color: #4ade80;">✓ <?= t('dashboard_saved_success') ?><br><?= t('refreshing') ?>...</div>';
                          generateBtn.textContent = '✓ <?= t('dashboard_generated') ?>';
                          setTimeout(() => { window.location.reload(); }, 1500);
                      } else {
                          alert('<?= t('error_saving') ?>: ' + (saveResult.error || '<?= t('unknown_error') ?>'));
                          this.textContent = '✓ <?= t('dashboard_save') ?>';
                          this.disabled = false;
                      }
                  });
                  
                  generateBtn.textContent = '✓ <?= t('dashboard_generated') ?>';
              } else {
                  // Afișează eroarea detaliată
                  let errorMsg = generateResult.error || '<?= t('unknown_error') ?>';
                  if (generateResult.message) errorMsg = generateResult.message;
                  previewDiv.innerHTML = `<div style="background: rgba(239,68,68,0.1); border-radius: 12px; padding: 16px; color: #f87171; text-align: center;">
                      <strong>❌ <?= t('dashboard_error') ?>:</strong> ${errorMsg}<br>
                      <small style="font-size: 11px;"><?= t('check_console') ?></small>
                  </div>`;
                  generateBtn.textContent = originalText;
              }
          } catch (error) {
              console.error('Error:', error);
              previewDiv.innerHTML = `<div style="background: rgba(239,68,68,0.1); border-radius: 12px; padding: 16px; color: #f87171; text-align: center;">
                  ❌ <?= t('dashboard_network_error') ?>: ${error.message}<br>
                  <small><?= t('check_xampp') ?></small>
              </div>`;
              generateBtn.textContent = originalText;
          }
          
          generateBtn.disabled = false;
      });
  });
  </script>

</body>
</html>