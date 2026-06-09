<?php
session_start();
require_once 'languages.php';
include 'includes/header.php';
include 'includes/navbar.php';
?>
<main style="background-color: #0a0b10; min-height: 60vh; padding: 120px 24px 80px; color: #ffffff; font-family: 'DM Sans', sans-serif;">
  <div style="max-width: 800px; margin: 0 auto; text-align: center;">
    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(124,92,252,0.12); border: 1px solid rgba(124,92,252,0.28); border-radius: 100px; padding: 6px 14px; font-size: 12px; font-weight: 500; color: #7c5cfc; margin-bottom: 24px;">
      <span>📖</span> <?= $current_lang == 'ro' ? 'Ghiduri' : 'Guides' ?>
    </div>
    <h1 style="font-family: 'Syne', sans-serif; font-size: clamp(32px, 4vw, 48px); font-weight: 800; letter-spacing: -1px; margin-bottom: 40px;"><?= t('footer_documentation') ?></h1>
    <div style="text-align: left; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 32px; border-radius: 16px; margin-bottom: 40px;">
        <p style="color: #b4bccb; font-size: 16px; line-height: 1.8;">
          <?= $current_lang == 'ro' 
              ? 'Învață cum să obții cele mai bune rezultate vizuale. Documentația noastră cuprinde sfaturi esențiale despre scrierea prompt-urilor pentru AI, alegerea paletelor de culori potrivite pentru nișa ta și gestionarea corectă a formatelor de fișiere descărcate.' 
              : 'Learn how to get the best visual results. Our documentation includes essential tips on writing AI prompts, choosing the right color palettes for your niche, and properly managing downloaded file formats.' ?>
        </p>
    </div>
    <a href="index.php" style="display: inline-block; background: #7c5cfc; color: #ffffff; border-radius: 10px; padding: 12px 28px; font-size: 14px; font-weight: 600; text-decoration: none;"><?= $current_lang == 'ro' ? '← Înapoi' : '← Back' ?></a>
  </div>
</main>
<?php include 'includes/footer.php'; ?>