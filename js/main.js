document.addEventListener('DOMContentLoaded', () => {

  // ========== DARK/LIGHT MODE TOGGLE ==========
  (function() {
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const themeText = document.getElementById('themeText');
    
    // Verifică tema salvată
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
      document.documentElement.setAttribute('data-theme', 'dark');
      if (themeIcon) themeIcon.textContent = '☀️';
      if (themeText) themeText.textContent = 'Light';
    } else {
      document.documentElement.setAttribute('data-theme', 'light');
      if (themeIcon) themeIcon.textContent = '🌙';
      if (themeText) themeText.textContent = 'Dark';
    }
    
    // Toggle la click
    if (themeToggle) {
      themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        if (currentTheme === 'dark') {
          document.documentElement.setAttribute('data-theme', 'light');
          localStorage.setItem('theme', 'light');
          if (themeIcon) themeIcon.textContent = '🌙';
          if (themeText) themeText.textContent = 'Dark';
        } else {
          document.documentElement.setAttribute('data-theme', 'dark');
          localStorage.setItem('theme', 'dark');
          if (themeIcon) themeIcon.textContent = '☀️';
          if (themeText) themeText.textContent = 'Light';
        }
      });
    }
  })();

  // ========== GENERATE BUTTON ==========
  const generateBtn = document.getElementById('generateBtn');
  if (generateBtn) {
    generateBtn.addEventListener('click', (e) => {
      if (!generateBtn.getAttribute('onclick') || 
          generateBtn.getAttribute('onclick').includes('return false')) {
        e.preventDefault();
        const originalText = generateBtn.textContent;
        generateBtn.textContent = '✦ Generating...';
        generateBtn.style.opacity = '0.65';
        generateBtn.classList.add('btn-loading');
        setTimeout(() => {
          generateBtn.textContent = '✓ Logos Generated!';
          generateBtn.style.opacity = '1';
          generateBtn.style.background = 'linear-gradient(135deg, #2dc653, #16a34a)';
          generateBtn.classList.remove('btn-loading');
          setTimeout(() => {
            generateBtn.textContent = originalText;
            generateBtn.style.background = '';
          }, 2000);
        }, 1800);
      }
    });
  }

  // ========== LOGO PREVIEWS ==========
  const previews = document.querySelectorAll('.logo-preview');
  previews.forEach(preview => {
    preview.addEventListener('click', () => {
      previews.forEach(p => p.style.borderColor = '');
      preview.style.borderColor = 'rgba(124, 92, 252, 0.8)';
      const logoName = preview.querySelector('.logo-name')?.textContent;
      const brandInput = document.getElementById('brandName');
      if (brandInput && logoName) brandInput.value = logoName;
    });
  });

  // ========== SMOOTH SCROLL ==========
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href !== '#' && href !== '') {
        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }
    });
  });

  // ========== ANIMATION DELAYS ==========
  const cards = document.querySelectorAll('.showcase-card, .pricing-card');
  cards.forEach((card, index) => { card.style.animationDelay = `${index * 0.1}s`; });

  // ========== NAVBAR SCROLL EFFECT ==========
  let lastScroll = 0;
  const nav = document.querySelector('nav');
  if (nav) {
    window.addEventListener('scroll', () => {
      const currentScroll = window.pageYOffset;
      const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
      
      if (currentScroll > 100) {
        if (isDark) {
          nav.style.background = 'rgba(10, 11, 16, 0.95)';
        } else {
          nav.style.background = 'rgba(255, 255, 255, 0.95)';
        }
        nav.style.backdropFilter = 'blur(20px)';
      } else {
        if (isDark) {
          nav.style.background = 'rgba(10, 11, 16, 0.8)';
        } else {
          nav.style.background = 'rgba(255, 255, 255, 0.8)';
        }
        nav.style.backdropFilter = 'blur(20px)';
      }
      lastScroll = currentScroll;
    });
  }

});