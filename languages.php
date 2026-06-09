<?php
// languages.php - Configurare limbi disponibile

// Pornește sesiunea doar dacă nu este deja activă
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limbi disponibile
$available_languages = ['ro', 'en'];

// Setează limba curentă
if (isset($_GET['lang']) && in_array($_GET['lang'], $available_languages)) {
    $_SESSION['language'] = $_GET['lang'];
    // Redirecționează înapoi la aceeași pagină
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Limba implicită
$current_lang = $_SESSION['language'] ?? 'ro';

// Traduceri pentru toate paginile
$translations = [
    'ro' => [
        // Navbar
        'nav_home' => 'Acasă',
        'nav_features' => 'Caracteristici',
        'nav_designs' => 'Design-uri',
        'nav_pricing' => 'Prețuri',
        'nav_dashboard' => 'Panou de control',
        'nav_signout' => 'Deconectare',
        'nav_signin' => 'Autentificare',
        'nav_start' => 'Începe acum',
        
        // Hero section
        'hero_badge' => 'Generare Logo cu AI',
        'hero_title' => 'Creează<br>Logo-uri <span class="highlight">Generate<br>de AI</span><br>Care Ies în Evidență',
        'hero_desc' => 'Generează logo-uri premium instantaneu folosind tehnologie AI avansată. Perfect pentru start-up-uri, creatori, agenții și mărci moderne care caută identități vizuale uimitoare în câteva secunde.',
        'hero_btn_dashboard' => 'Mergi la Panou',
        'hero_btn_free' => 'Încearcă Gratuit',
        'hero_btn_demo' => 'Vezi Demo',
        'hero_stat1_label' => 'Logo-uri Generate',
        'hero_stat2_label' => 'Satisfacție Clienți',
        'hero_stat3_label' => 'Disponibilitate',
        
        // Generator card
        'generator_title' => 'Generator Logo AI',
        'generator_signin_text' => 'Autentifică-te pentru a genera și salva logo-urile tale',
        'generator_signin_link' => 'Autentifică-te',
        'form_brand' => 'Numele Mărcii',
        'form_industry' => 'Industrie',
        'form_style' => 'Stil',
        'form_color' => 'Culoare Principală',
        'form_industry_tech' => 'Tehnologie',
        'form_industry_finance' => 'Finanțe',
        'form_industry_health' => 'Sănătate',
        'form_industry_fashion' => 'Modă',
        'form_industry_food' => 'Alimente & Băuturi',
        'form_style_minimal' => 'Minimalist',
        'form_style_modern' => 'Modern',
        'form_style_bold' => 'Îndrăzneț',  // ← CORECTAT (ghilimele adăugate)
        'form_style_playful' => 'Jucăuș',
        'form_style_elegant' => 'Elegant',
        'btn_generate' => '✦ Generează Logo',
        'btn_dashboard' => '✦ Mergi la Panou',
        'btn_signin_generate' => '🔒 Autentifică-te pentru a genera',
        
        // Welcome banner
        'welcome_back' => 'Bine ai revenit,',
        'welcome_account_active' => '! Contul tău este activ —',
        'welcome_go_dashboard' => 'Mergi la Panou',
        'welcome_generate_manage' => 'pentru a genera și gestiona logo-urile tale.',
        
        // Showcase section
        'showcase_badge' => 'Design-uri Populare AI',
        'showcase_title' => 'Explorează utilizări unice în jocuri și<br>jucării ale AI pentru a construi calitate inteligentă',
        'showcase_card1_title' => 'Branding Start-up',
        'showcase_card1_desc' => 'Sisteme moderne de identitate pentru start-up-uri cu gradiente fantastice și tipografie curată.',
        'showcase_card2_title' => 'Identitate de Lux',
        'showcase_card2_desc' => 'Logo premium elegant generat pentru mărci de lux și modă.',
        'showcase_card3_title' => 'Logo-uri Gaming',
        'showcase_card3_desc' => 'Logo-uri distopice pentru jocuri generate de AI cu estetică modernă.',
        
        // Pricing section
        'pricing_badge' => 'Prețuri Simple',
        'pricing_title' => 'Alege planul perfect<br>pentru afacerea ta',
        'pricing_subtitle' => 'Începe cu un cost redus și licență lunară.',
        'pricing_starter' => 'Începător',
        'pricing_starter_desc' => 'Perfect pentru persoane și creatori.',
        'pricing_pro' => 'Profesional',
        'pricing_pro_desc' => 'Cel mai bun pentru start-up-uri și afaceri.',
        'pricing_enterprise' => 'Enterprise',
        'pricing_enterprise_desc' => 'Instrumente avansate pentru agenții și echipe.',
        'pricing_unlimited' => 'Logo nelimitat',
        'pricing_psd' => 'Export PSD',
        'pricing_templates' => 'Șabloane',
        'pricing_support' => 'Suport',
        'pricing_brand_kit' => 'Kit de brand',
        'pricing_priority' => 'Suport prioritar',
        'pricing_white_label' => 'White Label',
        'btn_learn_more' => 'Află mai multe →',
        
        // CTA section
        'cta_title' => 'Începe să-ți Construiești Brandul Astăzi',
        'cta_desc' => 'Generează logo-uri unice în câteva secunde și lansează-ți afacerea cu o soluție modernă bazată pe AI.',
        'cta_btn' => 'Află mai multe →',
        
        // Footer
        'footer_product' => 'Produs',
        'footer_premium' => 'Premium',
        'footer_templates' => 'Șabloane',
        'footer_pricing' => 'Prețuri',
        'footer_integrations' => 'Integrări',
        'footer_resources' => 'Resurse',
        'footer_documentation' => 'Documentație',
        'footer_help' => 'Centru de Ajutor',
        'footer_api' => 'API',
        'footer_company' => 'Companie',
        'footer_about' => 'Despre',
        'footer_careers' => 'Cariere',
        'footer_privacy' => 'Confidențialitate',
        'footer_terms' => 'Termeni',
        'footer_copyright' => 'Toate drepturile rezervate.',
        
        // Dashboard
        'dashboard_title' => 'Panou de control',
        'dashboard_welcome' => 'Bine ai revenit,',
        'dashboard_manage' => 'Gestionează-ți logo-urile și creează altele noi mai jos.',
        'dashboard_logos_generated' => 'Logo-uri generate',
        'dashboard_member_since' => 'Membru din',
        'dashboard_account_status' => 'Status cont',
        'dashboard_active' => 'Activ',
        'dashboard_generate_new' => 'Generează Logo Nou',
        'dashboard_my_logos' => 'Logo-urile Mele',
        'dashboard_saved' => 'salvate',
        'dashboard_no_logos' => 'Niciun logo încă',
        'dashboard_no_logos_desc' => 'Folosește formularul din stânga pentru a genera primul tău logo.',
        'dashboard_generating' => 'Se generează logo-ul cu AI...',
        'dashboard_generating_time' => 'Acest proces poate dura 20-40 de secunde',
        'dashboard_ai_generated' => '✨ Logo Generat de AI:',
        'dashboard_save' => '✓ Salvează în Logo-urile Mele',
        'dashboard_saving' => 'Se salvează...',
        'dashboard_saved_success' => '✓ Logo salvat cu succes! Se reîmprospătează pagina...',
        'dashboard_generated' => '✓ Generat!',
        'dashboard_error' => 'Eroare',
        'dashboard_network_error' => 'Eroare de rețea',
        'dark_mode' => 'Dark',
        'light_mode' => 'Luminos',
        'hello' => 'Salut',
        'logo_saved_success' => 'Logo salvat cu succes!',
        'delete_confirm' => 'Ștergi acest logo?',
        'error_brand_required' => 'Te rugăm să introduci un nume pentru marcă',
        'generating' => 'Se generează',
        'error_saving' => 'Eroare la salvare',
        'unknown_error' => 'Eroare necunoscută',
        'refreshing' => 'Se reîmprospătează',
        'check_console' => 'Verifică consola pentru detalii (F12)',
        'check_xampp' => 'Asigură-te că XAMPP Apache rulează',
        'form_brand_placeholder' => 'Introdu numele mărcii',
        'form_color_placeholder' => 'ex. Violet & Albastru',
        'form_industry_education' => 'Educație',
        'form_industry_sports' => 'Sport',
        
        // Auth pages
        'login_title' => 'Autentificare',
        'login_email' => 'Email',
        'login_password' => 'Parolă',
        'login_btn' => 'Autentifică-te',
        'login_no_account' => 'Nu ai cont?',
        'login_register' => 'Înregistrează-te',
        'register_title' => 'Înregistrare',
        'register_name' => 'Nume',
        'register_email' => 'Email',
        'register_password' => 'Parolă',
        'register_confirm' => 'Confirmă Parola',
        'register_btn' => 'Înregistrează-te',
        'register_have_account' => 'Ai deja cont?',
        'register_login' => 'Autentifică-te',
    ],
    
    'en' => [
        // Navbar
        'nav_home' => 'Home',
        'nav_features' => 'Features',
        'nav_designs' => 'Designs',
        'nav_pricing' => 'Pricing',
        'nav_dashboard' => 'Dashboard',
        'nav_signout' => 'Sign Out',
        'nav_signin' => 'Sign In',
        'nav_start' => 'Start Creating',
        
        // Hero section
        'hero_badge' => 'AI-Powered Logo Generation',
        'hero_title' => 'Create <span class="highlight">AI<br>Generated</span> Logos<br>That Stand Out',
        'hero_desc' => 'Generate premium logos instantly using advanced AI technology. Perfect for startups, creators, agencies and modern brands looking for stunning visual identities in seconds.',
        'hero_btn_dashboard' => 'Go to Dashboard',
        'hero_btn_free' => 'Try for Free',
        'hero_btn_demo' => 'Watch Demo',
        'hero_stat1_label' => 'Generated Logos',
        'hero_stat2_label' => 'Customer Satisfaction',
        'hero_stat3_label' => 'Availability',
        
        // Generator card
        'generator_title' => 'AI Logo Generator',
        'generator_signin_text' => 'Sign in to generate and save your logos',
        'generator_signin_link' => 'Sign in',
        'form_brand' => 'Brand Name',
        'form_industry' => 'Industry',
        'form_style' => 'Style',
        'form_color' => 'Primary Color',
        'form_industry_tech' => 'Technology',
        'form_industry_finance' => 'Finance',
        'form_industry_health' => 'Health',
        'form_industry_fashion' => 'Fashion',
        'form_industry_food' => 'Food & Beverage',
        'form_style_minimal' => 'Minimal',
        'form_style_modern' => 'Modern',
        'form_style_bold' => 'Bold',
        'form_style_playful' => 'Playful',
        'form_style_elegant' => 'Elegant',
        'btn_generate' => '✦ Generate Logo',
        'btn_dashboard' => '✦ Go to Dashboard',
        'btn_signin_generate' => '🔒 Sign In to Generate',
        
        // Welcome banner
        'welcome_back' => 'Welcome back,',
        'welcome_account_active' => '! Your account is active —',
        'welcome_go_dashboard' => 'Go to Dashboard',
        'welcome_generate_manage' => 'to generate and manage your logos.',
        
        // Showcase section
        'showcase_badge' => 'Popular AI Designs',
        'showcase_title' => 'Explore unique game and toy<br>use of AI to build intelligence quality in.',
        'showcase_card1_title' => 'Startup Branding',
        'showcase_card1_desc' => 'Modern startup identity systems with fantastic gradients and clean typography.',
        'showcase_card2_title' => 'Luxury Identity',
        'showcase_card2_desc' => 'Elegant premium logo generated for luxury and fashion brands.',
        'showcase_card3_title' => 'Gaming Logos',
        'showcase_card3_desc' => 'Dystopian AI generated gaming logo based with modern icon aesthetics.',
        
        // Pricing section
        'pricing_badge' => 'Simple Pricing',
        'pricing_title' => 'Choose the perfect plan<br>for your business',
        'pricing_subtitle' => 'Start with a low cost and monthly license.',
        'pricing_starter' => 'Starter',
        'pricing_starter_desc' => 'Perfect for individuals and creators.',
        'pricing_pro' => 'Professional',
        'pricing_pro_desc' => 'Best for startups and businesses.',
        'pricing_enterprise' => 'Enterprise',
        'pricing_enterprise_desc' => 'Advanced tools for agencies and teams.',
        'pricing_unlimited' => 'Unlimited Logo',
        'pricing_psd' => 'PSD Export',
        'pricing_templates' => 'Templates',
        'pricing_support' => 'Support',
        'pricing_brand_kit' => 'Brand Kit',
        'pricing_priority' => 'Priority Support',
        'pricing_white_label' => 'White Label',
        'btn_learn_more' => 'Learn More →',
        
        // CTA section
        'cta_title' => 'Start Building Your Brand Today',
        'cta_desc' => 'Generate unique logos in seconds and launch your business with a modern AI-powered solution today.',
        'cta_btn' => 'Learn More →',
        
        // Footer
        'footer_product' => 'Product',
        'footer_premium' => 'Premium',
        'footer_templates' => 'Templates',
        'footer_pricing' => 'Pricing',
        'footer_integrations' => 'Integrations',
        'footer_resources' => 'Resources',
        'footer_documentation' => 'Documentation',
        'footer_help' => 'Help Center',
        'footer_api' => 'API',
        'footer_company' => 'Company',
        'footer_about' => 'About',
        'footer_careers' => 'Careers',
        'footer_privacy' => 'Privacy',
        'footer_terms' => 'Terms',
        'footer_copyright' => 'All rights reserved.',
        
        // Dashboard
        'dashboard_title' => 'Dashboard',
        'dashboard_welcome' => 'Welcome back,',
        'dashboard_manage' => 'Manage your logos and create new ones below.',
        'dashboard_logos_generated' => 'Logos generated',
        'dashboard_member_since' => 'Member since',
        'dashboard_account_status' => 'Account status',
        'dashboard_active' => 'Active',
        'dashboard_generate_new' => 'Generate New Logo',
        'dashboard_my_logos' => 'My Logos',
        'dashboard_saved' => 'saved',
        'dashboard_no_logos' => 'No logos yet',
        'dashboard_no_logos_desc' => 'Use the form on the left to generate your first logo.',
        'dashboard_generating' => 'Generating logo with AI...',
        'dashboard_generating_time' => 'This may take 20-40 seconds',
        'dashboard_ai_generated' => '✨ AI Generated Logo:',
        'dashboard_save' => '✓ Save to My Logos',
        'dashboard_saving' => 'Saving...',
        'dashboard_saved_success' => '✓ Logo saved successfully! Refreshing page...',
        'dashboard_generated' => '✓ Generated!',
        'dashboard_error' => 'Error',
        'dashboard_network_error' => 'Network error',
        'dark_mode' => 'Dark',
        'light_mode' => 'Light',
        'hello' => 'Hello',
        'logo_saved_success' => 'Logo saved successfully!',
        'delete_confirm' => 'Delete this logo?',
        'error_brand_required' => 'Please enter a brand name',
        'generating' => 'Generating',
        'error_saving' => 'Error saving',
        'unknown_error' => 'Unknown error',
        'refreshing' => 'Refreshing',
        'check_console' => 'Check console for details (F12)',
        'check_xampp' => 'Make sure XAMPP Apache is running',
        'form_brand_placeholder' => 'Enter brand name',
        'form_color_placeholder' => 'e.g. Purple & Blue',
        'form_industry_education' => 'Education',
        'form_industry_sports' => 'Sports',
        
        // Auth pages
        'login_title' => 'Sign In',
        'login_email' => 'Email',
        'login_password' => 'Password',
        'login_btn' => 'Sign In',
        'login_no_account' => 'Don\'t have an account?',
        'login_register' => 'Register',
        'register_title' => 'Register',
        'register_name' => 'Name',
        'register_email' => 'Email',
        'register_password' => 'Password',
        'register_confirm' => 'Confirm Password',
        'register_btn' => 'Register',
        'register_have_account' => 'Already have an account?',
        'register_login' => 'Sign In',
    ]
];

// Funcție pentru a obține traducerea
function t($key) {
    global $translations, $current_lang;
    return $translations[$current_lang][$key] ?? $translations['en'][$key] ?? $key;
}

// Funcție pentru a obține URL-ul cu parametrul de limbă
function lang_url($lang) {
    $url = strtok($_SERVER['REQUEST_URI'], '?');
    return $url . '?lang=' . $lang;
}
?>