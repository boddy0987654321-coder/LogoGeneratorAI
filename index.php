<?php
session_start();
require_once 'languages.php';  // ← ADĂUGAT

$isLoggedIn = isset($_SESSION['user_id']);
$userName   = $isLoggedIn ? htmlspecialchars($_SESSION['user_name']) : '';
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NeuroLogo AI — AI Logo Generator</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .user-menu { display: flex; align-items: center; gap: 12px; }
    .user-greeting { font-size: 13px; color: var(--text2); }
    .user-greeting strong { color: var(--purple2); }
    .logout-btn {
      background: transparent; color: var(--text2); border: 1px solid var(--border);
      border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 500;
      font-family: 'DM Sans', sans-serif; cursor: pointer; text-decoration: none;
      transition: border-color 0.2s, color 0.2s; display: inline-block;
    }
    .logout-btn:hover { border-color: rgba(255,255,255,0.2); color: var(--text); }
    .welcome-banner {
      position: relative; z-index: 1; max-width: 1200px; margin: 80px auto 0; padding: 16px 48px;
    }
    .welcome-banner .banner-inner {
      background: rgba(124,92,252,0.08); border: 1px solid rgba(124,92,252,0.2);
      border-radius: 10px; padding: 12px 20px; font-size: 13px; color: var(--purple2);
      display: flex; align-items: center; gap: 10px;
    }
    .banner-inner a { color: var(--purple2); font-weight: 700; text-decoration: underline; margin-left: 4px; }
    .design-showcase {
      position: relative; z-index: 1; max-width: 1200px; margin: 0 auto; padding: 60px 48px 40px;
    }
    .section-header { text-align: center; margin-bottom: 48px; }
    .section-badge {
      display: inline-flex; align-items: center; gap: 8px; background: rgba(124,92,252,0.12);
      border: 1px solid rgba(124,92,252,0.28); border-radius: 100px; padding: 6px 14px;
      font-size: 12px; font-weight: 500; color: var(--purple2); margin-bottom: 16px;
    }
    .section-title {
      font-family: 'Syne', sans-serif; font-size: clamp(32px, 3.5vw, 44px);
      font-weight: 800; letter-spacing: -1px; color: var(--text);
    }
    .section-subtitle {
      font-size: 15px; color: var(--text2); margin-top: 12px; max-width: 600px; margin-left: auto; margin-right: auto;
    }
    .showcase-grid {
      display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;
    }
    .showcase-card {
      background: var(--card); border: 1px solid var(--border); border-radius: var(--radius-lg);
      padding: 28px; transition: transform 0.2s, border-color 0.2s;
    }
    .showcase-card:hover { transform: translateY(-4px); border-color: rgba(124,92,252,0.3); }
    .showcase-icon {
      width: 56px; height: 56px; background: linear-gradient(135deg, rgba(124,92,252,0.15), rgba(79,138,255,0.1));
      border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 20px;
    }
    .showcase-card h3 { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 700; margin-bottom: 8px; }
    .showcase-card p { font-size: 13px; color: var(--text3); line-height: 1.6; }
    .pricing-section {
      position: relative; z-index: 1; max-width: 1200px; margin: 0 auto; padding: 60px 48px 80px;
    }
    .pricing-grid {
      display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-top: 48px;
    }
    .pricing-card {
      background: var(--card); border: 1px solid var(--border); border-radius: var(--radius-lg);
      padding: 32px 28px; transition: transform 0.2s, border-color 0.2s;
    }
    .pricing-card.featured {
      border-color: var(--purple); background: linear-gradient(135deg, var(--card), rgba(124,92,252,0.05));
    }
    .pricing-card:hover { transform: translateY(-4px); }
    .pricing-name { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 700; margin-bottom: 16px; }
    .pricing-price { font-family: 'Syne', sans-serif; font-size: 36px; font-weight: 800; margin-bottom: 4px; }
    .pricing-price span { font-size: 14px; font-weight: 400; color: var(--text3); }
    .pricing-desc { font-size: 13px; color: var(--text3); margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }
    .pricing-features { list-style: none; margin-bottom: 28px; }
    .pricing-features li { font-size: 13px; color: var(--text2); padding: 8px 0; display: flex; align-items: center; gap: 10px; }
    .pricing-features li::before { content: '✓'; color: var(--purple2); font-weight: 700; }
    .pricing-btn {
      width: 100%; background: transparent; border: 1px solid var(--border); border-radius: 10px;
      padding: 12px; font-size: 14px; font-weight: 600; font-family: 'DM Sans', sans-serif;
      color: var(--text); cursor: pointer; transition: all 0.2s;
    }
    .pricing-btn.featured { background: var(--purple); border-color: var(--purple); color: #fff; }
    .pricing-btn:hover { background: var(--purple2); border-color: var(--purple2); color: #fff; }
    .cta-section {
      position: relative; z-index: 1; max-width: 900px; margin: 0 auto; padding: 60px 48px 80px; text-align: center;
    }
    .cta-card {
      background: linear-gradient(135deg, rgba(124,92,252,0.08), rgba(79,138,255,0.05));
      border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 64px 48px;
    }
    .cta-title { font-family: 'Syne', sans-serif; font-size: clamp(28px, 3vw, 38px); font-weight: 800; margin-bottom: 16px; }
    .cta-desc { font-size: 14px; color: var(--text3); margin-bottom: 32px; max-width: 500px; margin-left: auto; margin-right: auto; }
    .footer {
      position: relative; z-index: 1; border-top: 1px solid var(--border); padding: 48px 48px 32px;
      background: rgba(10,11,16,0.6);
    }
    .footer-grid {
      max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 48px;
    }
    .footer-logo { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 18px; margin-bottom: 16px; }
    .footer-logo span { color: var(--purple2); }
    .footer-desc { font-size: 13px; color: var(--text3); line-height: 1.6; max-width: 250px; }
    .footer-col h4 { font-size: 14px; font-weight: 600; margin-bottom: 20px; color: var(--text); }
    .footer-col ul { list-style: none; }
    .footer-col ul li { margin-bottom: 10px; }
    .footer-col ul li a { color: var(--text3); text-decoration: none; font-size: 13px; transition: color 0.2s; }
    .footer-col ul li a:hover { color: var(--purple2); }
    .footer-bottom {
      max-width: 1200px; margin: 0 auto; text-align: center; padding-top: 40px; margin-top: 40px;
      border-top: 1px solid var(--border); font-size: 12px; color: var(--text3);
    }
    
    /* Dark/Light Mode Toggle Button */
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
    
    /* Language Switch Buttons */
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
    
    @media (max-width: 900px) {
      .showcase-grid, .pricing-grid, .footer-grid { grid-template-columns: 1fr; gap: 20px; }
      .design-showcase, .pricing-section, .cta-section, .footer { padding-left: 24px; padding-right: 24px; }
      .hero { grid-template-columns: 1fr; padding: 100px 24px 60px; }
    }
  </style>
</head>
<body>

<nav>
  <div class="nav-logo">Neuro<span>Logo</span> AI</div>
  <ul class="nav-links">
    <li><a href="index.php"><?= t('nav_home') ?></a></li>
    <li><a href="#features"><?= t('nav_features') ?></a></li>
    <li><a href="#designs"><?= t('nav_designs') ?></a></li>
    <li><a href="#pricing"><?= t('nav_pricing') ?></a></li>
  </ul>

  <?php if ($isLoggedIn): ?>
    <div class="user-menu">
      <!-- Language Switch Buttons -->
      <div class="language-switch">
        <a href="?lang=ro" style="text-decoration: none;">
          <button class="lang-btn <?= $current_lang == 'ro' ? 'active' : '' ?>">RO</button>
        </a>
        <a href="?lang=en" style="text-decoration: none;">
          <button class="lang-btn <?= $current_lang == 'en' ? 'active' : '' ?>">EN</button>
        </a>
      </div>
      
      <button id="themeToggle" class="theme-toggle-btn">
        <span id="themeIcon">🌙</span>
        <span id="themeText"><?= t('dark_mode') ?></span>
      </button>
      <span class="user-greeting"><?= t('hello') ?>, <strong><?= $userName ?></strong></span>
      <a href="dashboard.php" class="logout-btn"><?= t('nav_dashboard') ?></a>
      <a href="logout.php" class="logout-btn"><?= t('nav_signout') ?></a>
    </div>
  <?php else: ?>
    <div class="user-menu">
      <!-- Language Switch Buttons -->
      <div class="language-switch">
        <a href="?lang=ro" style="text-decoration: none;">
          <button class="lang-btn <?= $current_lang == 'ro' ? 'active' : '' ?>">RO</button>
        </a>
        <a href="?lang=en" style="text-decoration: none;">
          <button class="lang-btn <?= $current_lang == 'en' ? 'active' : '' ?>">EN</button>
        </a>
      </div>
      
      <button id="themeToggle" class="theme-toggle-btn">
        <span id="themeIcon">🌙</span>
        <span id="themeText"><?= t('dark_mode') ?></span>
      </button>
      <a href="login.php"><button class="logout-btn"><?= t('nav_signin') ?></button></a>
      <a href="register.php"><button class="nav-btn"><?= t('nav_start') ?></button></a>
    </div>
  <?php endif; ?>
</nav>

<?php if ($isLoggedIn): ?>
  <div class="welcome-banner">
    <div class="banner-inner">
      <span class="banner-icon">✦</span>
      <?= t('welcome_back') ?> <strong style="margin: 0 4px;"><?= $userName ?></strong>! <?= t('welcome_account_active') ?>
      <a href="dashboard.php"><?= t('welcome_go_dashboard') ?></a> <?= t('welcome_generate_manage') ?>
    </div>
  </div>
<?php endif; ?>

<!-- Hero Section -->
<section class="hero">
  <div class="hero-left">
    <div class="hero-badge">
      <span class="badge-dot"></span>
      <?= t('hero_badge') ?>
    </div>
    <h1 class="hero-title">
      <?= t('hero_title') ?>
    </h1>
    <p class="hero-desc">
      <?= t('hero_desc') ?>
    </p>
    <div class="hero-ctas">
      <?php if ($isLoggedIn): ?>
        <a href="dashboard.php"><button class="btn-primary"><?= t('hero_btn_dashboard') ?></button></a>
      <?php else: ?>
        <a href="register.php"><button class="btn-primary"><?= t('hero_btn_free') ?></button></a>
      <?php endif; ?>
      <button class="btn-secondary">
        <span class="play-icon">▶</span>
        <?= t('hero_btn_demo') ?>
      </button>
    </div>
    <div class="hero-stats">
      <div class="stat"><div class="stat-num">1M+</div><div class="stat-label"><?= t('hero_stat1_label') ?></div></div>
      <div class="stat"><div class="stat-num">98%</div><div class="stat-label"><?= t('hero_stat2_label') ?></div></div>
      <div class="stat"><div class="stat-num">24/7</div><div class="stat-label"><?= t('hero_stat3_label') ?></div></div>
    </div>
  </div>
  <div class="generator-card">
    <div class="card-title"><?= t('generator_title') ?></div>
    <?php if (!$isLoggedIn): ?>
      <div style="background: rgba(124,92,252,0.07); border: 1px solid rgba(124,92,252,0.18); border-radius: 8px; padding: 10px 14px; font-size: 12px; color: var(--text3); margin-bottom: 4px; text-align: center;">
        <a href="login.php" style="color: var(--purple2); text-decoration: none; font-weight: 600;"><?= t('generator_signin_link') ?></a> <?= t('generator_signin_text') ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <label class="form-label"><?= t('form_brand') ?></label>
      <input class="form-input" type="text" placeholder="<?= t('form_brand_placeholder') ?>" id="brandName">
    </div>
    <div class="form-group">
      <label class="form-label"><?= t('form_industry') ?></label>
      <div class="select-wrap">
        <select class="form-select" id="industry">
          <option><?= t('form_industry_tech') ?></option>
          <option><?= t('form_industry_finance') ?></option>
          <option><?= t('form_industry_health') ?></option>
          <option><?= t('form_industry_fashion') ?></option>
          <option><?= t('form_industry_food') ?></option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="form-label"><?= t('form_style') ?></label>
      <div class="select-wrap">
        <select class="form-select" id="style">
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
      <input class="form-input" type="text" placeholder="<?= t('form_color_placeholder') ?>" id="color">
    </div>
    <button class="generate-btn" id="generateBtn"
      <?php if ($isLoggedIn): ?>
        onclick="window.location.href='dashboard.php'; return false;"
      <?php else: ?>
        onclick="window.location.href='login.php'; return false;"
      <?php endif; ?>>
      <?= $isLoggedIn ? t('btn_dashboard') : t('btn_signin_generate') ?>
    </button>
    <div class="logo-grid">
      <div class="logo-preview"><div class="logo-icon">⚡</div><div class="logo-name">Voltix</div></div>
      <div class="logo-preview"><div class="logo-icon">✦</div><div class="logo-name">Nova</div></div>
      <div class="logo-preview"><div class="logo-icon">◎</div><div class="logo-name">Orbit</div></div>
      <div class="logo-preview"><div class="logo-icon">⬡</div><div class="logo-name">Hexa</div></div>
    </div>
  </div>
</section>

<!-- Popular AI Designs Section -->
<section class="design-showcase" id="designs">
  <div class="section-header">
    <div class="section-badge"><span>✦</span> <?= t('showcase_badge') ?></div>
    <h2 class="section-title"><?= t('showcase_title') ?></h2>
  </div>
  <div class="showcase-grid">
    <div class="showcase-card"><div class="showcase-icon">🚀</div><h3><?= t('showcase_card1_title') ?></h3><p><?= t('showcase_card1_desc') ?></p></div>
    <div class="showcase-card"><div class="showcase-icon">👑</div><h3><?= t('showcase_card2_title') ?></h3><p><?= t('showcase_card2_desc') ?></p></div>
    <div class="showcase-card"><div class="showcase-icon">🎮</div><h3><?= t('showcase_card3_title') ?></h3><p><?= t('showcase_card3_desc') ?></p></div>
  </div>
</section>

<!-- Pricing Section -->
<section class="pricing-section" id="pricing">
  <div class="section-header">
    <div class="section-badge"><span>💰</span> <?= t('pricing_badge') ?></div>
    <h2 class="section-title"><?= t('pricing_title') ?></h2>
    <div class="section-subtitle"><?= t('pricing_subtitle') ?></div>
  </div>
  <div class="pricing-grid">
    <div class="pricing-card">
      <div class="pricing-name"><?= t('pricing_starter') ?></div>
      <div class="pricing-price">$9 <span>/month</span></div>
      <div class="pricing-desc"><?= t('pricing_starter_desc') ?></div>
      <ul class="pricing-features"><li>50 AI Logo</li><li>PSD Export</li><li>Beta Templates</li><li>Community Support</li></ul>
      <button class="pricing-btn" onclick="window.location.href='register.php'"><?= t('btn_learn_more') ?></button>
    </div>
    <div class="pricing-card featured">
      <div class="pricing-name"><?= t('pricing_pro') ?></div>
      <div class="pricing-price">$29 <span>/month</span></div>
      <div class="pricing-desc"><?= t('pricing_pro_desc') ?></div>
      <ul class="pricing-features"><li>Unlimited Logo</li><li>500 AI PSD Export</li><li>Premium Templates</li><li>Brand Kit</li><li>Priority Support</li></ul>
      <button class="pricing-btn featured" onclick="window.location.href='register.php'"><?= t('btn_learn_more') ?></button>
    </div>
    <div class="pricing-card">
      <div class="pricing-name"><?= t('pricing_enterprise') ?></div>
      <div class="pricing-price">$99 <span>/month</span></div>
      <div class="pricing-desc"><?= t('pricing_enterprise_desc') ?></div>
      <ul class="pricing-features"><li>Yearly Workplace</li><li>AI-Generated Logo</li><li>AI-Generated Template</li><li>AI-Generated Support</li><li>White Label</li></ul>
      <button class="pricing-btn" onclick="window.location.href='register.php'"><?= t('btn_learn_more') ?></button>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
  <div class="cta-card">
    <h2 class="cta-title"><?= t('cta_title') ?></h2>
    <p class="cta-desc"><?= t('cta_desc') ?></p>
    <button class="btn-primary" onclick="window.location.href='register.php'"><?= t('cta_btn') ?></button>
  </div>
</section>

<!-- Footer -->
<footer class="footer">
  <div class="footer-grid">
    <div class="footer-col">
      <div class="footer-logo">Neuro<span>Logo</span> AI</div>
      <p class="footer-desc">AI-powered logo generation platform for startups and businesses.</p>
    </div>
    <div class="footer-col"><h4><?= t('footer_product') ?></h4><ul><li><a href="#"><?= t('footer_premium') ?></a></li><li><a href="#"><?= t('footer_templates') ?></a></li><li><a href="#"><?= t('footer_pricing') ?></a></li><li><a href="#"><?= t('footer_integrations') ?></a></li></ul></div>
    <div class="footer-col"><h4><?= t('footer_resources') ?></h4><ul><li><a href="#"><?= t('footer_documentation') ?></a></li><li><a href="#"><?= t('footer_help') ?></a></li><li><a href="#"><?= t('footer_api') ?></a></li></ul></div>
    <div class="footer-col"><h4><?= t('footer_company') ?></h4><ul><li><a href="#"><?= t('footer_about') ?></a></li><li><a href="#"><?= t('footer_careers') ?></a></li><li><a href="#"><?= t('footer_privacy') ?></a></li><li><a href="#"><?= t('footer_terms') ?></a></li></ul></div>
  </div>
  <div class="footer-bottom">© 2023 NeuroLogo AI. <?= t('footer_copyright') ?></div>
</footer>

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

// Additional JavaScript for interactivity
document.addEventListener('DOMContentLoaded', () => {
    const generateBtn = document.getElementById('generateBtn');
    if (generateBtn && !generateBtn.getAttribute('onclick')) {
        generateBtn.addEventListener('click', () => {
            const brandName = document.getElementById('brandName')?.value;
            const industry = document.getElementById('industry')?.value;
            const style = document.getElementById('style')?.value;
            const color = document.getElementById('color')?.value;
            
            if (!brandName) {
                alert('<?= t('error_brand_required') ?>');
                return;
            }
            
            generateBtn.textContent = '✦ <?= t('generating') ?>...';
            generateBtn.style.opacity = '0.65';
            
            setTimeout(() => {
                generateBtn.textContent = '✓ Ready!';
                generateBtn.style.opacity = '1';
                setTimeout(() => {
                    generateBtn.textContent = '✦ <?= t('btn_generate') ?>';
                }, 2000);
            }, 1800);
        });
    }
});
</script>
</body>
</html>