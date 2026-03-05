<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LLM Resayil — Your Personal AI Assistant in Arabic &amp; English</title>
    <meta name="description" content="Write, think, and create faster with AI. Get 1,000 free credits — no credit card required. Arabic & English AI assistant for Kuwait.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:             #0f1115;
            --bg-card:        rgba(19,22,29,0.82);
            --gold:           #d4af37;
            --gold-light:     #e8cc5a;
            --gold-dim:       rgba(212,175,55,0.12);
            --gold-border:    rgba(212,175,55,0.35);
            --purple:         #7c3aed;
            --border:         rgba(255,255,255,0.07);
            --text:           #f0f2f7;
            --text-secondary: #a1aab8;
            --text-muted:     #6b7280;
            --red:            #ef4444;
            --red-dim:        rgba(239,68,68,0.12);
            --font:           'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --shadow-gold:    0 0 40px rgba(212,175,55,0.15), 0 4px 24px rgba(0,0,0,0.4);
            --shadow-card:    0 4px 32px rgba(0,0,0,0.3);
            --r-sm: 8px; --r-md: 12px; --r-lg: 20px; --r-xl: 28px;
        }
        html { scroll-behavior: smooth; }
        body { font-family: var(--font); background: var(--bg); color: var(--text); line-height: 1.65; overflow-x: hidden; -webkit-font-smoothing: antialiased; }
        a { color: inherit; text-decoration: none; }
        button { font-family: var(--font); cursor: pointer; border: none; background: none; }
        ul { list-style: none; }
        input { font-family: var(--font); }
        body::before {
            content: ''; position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background-image: linear-gradient(rgba(255,255,255,0.022) 1px,transparent 1px), linear-gradient(90deg,rgba(255,255,255,0.022) 1px,transparent 1px);
            background-size: 64px 64px;
        }
        .container    { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; position: relative; z-index: 1; }
        .container-sm { max-width: 800px;  margin: 0 auto; padding: 0 1.5rem; position: relative; z-index: 1; }
        .container-xs { max-width: 520px;  margin: 0 auto; padding: 0 1.5rem; position: relative; z-index: 1; }
        .text-center { text-align: center; }
        .text-gradient {
            background: linear-gradient(135deg, var(--gold) 0%, #fff 55%, var(--gold-light) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .text-gradient-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .glass {
            background: var(--bg-card); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border); border-radius: var(--r-lg); box-shadow: var(--shadow-card);
        }
        .glass-gold {
            background: var(--bg-card); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--gold-border); border-radius: var(--r-xl); box-shadow: var(--shadow-gold);
        }
        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.75rem 1.5rem; border-radius: var(--r-md);
            font-weight: 600; font-size: 0.95rem;
            transition: all 0.2s ease; cursor: pointer; white-space: nowrap;
            position: relative; overflow: hidden;
        }
        .btn-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: #0f1115;
            box-shadow: 0 0 24px rgba(212,175,55,0.30), 0 4px 16px rgba(0,0,0,0.3);
        }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 0 40px rgba(212,175,55,0.50), 0 8px 24px rgba(0,0,0,0.3); }
        .btn-gold::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg,transparent 40%,rgba(255,255,255,0.18) 100%); pointer-events: none; }
        .btn-lg  { padding: 1.1rem 2.4rem; font-size: 1.1rem; }
        .btn-ghost { background: transparent; color: var(--text-secondary); border: 1px solid var(--border); }
        .btn-ghost:hover { border-color: var(--gold-border); color: var(--gold); background: var(--gold-dim); }
        .btn-outline-gold { background: transparent; color: var(--gold); border: 1px solid var(--gold-border); }
        .btn-outline-gold:hover { background: var(--gold-dim); }

        /* NAVBAR */
        .navbar { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; padding: 1rem 0; transition: all 0.3s; }
        .navbar.scrolled { background: rgba(15,17,21,0.92); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); padding: 0.65rem 0; }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; }
        .logo { display: flex; align-items: center; gap: 0.6rem; font-weight: 800; font-size: 1.2rem; letter-spacing: -0.02em; }
        .logo-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--gold); flex-shrink: 0; box-shadow: 0 0 12px rgba(212,175,55,0.8), 0 0 24px rgba(212,175,55,0.4); animation: pulse-dot 2.5s ease-in-out infinite; }
        @keyframes pulse-dot { 0%,100% { box-shadow: 0 0 12px rgba(212,175,55,0.8), 0 0 24px rgba(212,175,55,0.4); } 50% { box-shadow: 0 0 20px rgba(212,175,55,1), 0 0 40px rgba(212,175,55,0.6); } }
        .nav-links { display: flex; align-items: center; gap: 0.2rem; }
        .nav-links a { color: var(--text-secondary); font-size: 0.9rem; font-weight: 500; padding: 0.5rem 0.9rem; border-radius: var(--r-sm); transition: all 0.2s; }
        .nav-links a:hover { color: var(--text); background: rgba(255,255,255,0.04); }
        .nav-actions { display: flex; align-items: center; gap: 0.75rem; }
        .nav-toggle { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 0.4rem; }
        .nav-toggle span { display: block; width: 22px; height: 2px; background: var(--text-secondary); border-radius: 2px; }
        .mobile-nav { display: none; position: fixed; inset: 0; z-index: 999; background: rgba(15,17,21,0.97); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); flex-direction: column; align-items: center; justify-content: center; gap: 2rem; }
        .mobile-nav.open { display: flex; }
        .mobile-nav a { font-size: 1.5rem; font-weight: 700; color: var(--text-secondary); transition: color 0.2s; }
        .mobile-nav a:hover { color: var(--gold); }
        .mobile-nav-close { position: absolute; top: 1.5rem; right: 1.5rem; color: var(--text-muted); cursor: pointer; padding: 0.5rem; }
        .mobile-nav-close svg { width: 24px; height: 24px; }

        /* HERO */
        .hero { min-height: 100vh; display: flex; align-items: center; padding: 8rem 0 6rem; position: relative; overflow: hidden; }
        .orb { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; animation: orb-float 8s ease-in-out infinite; }
        .orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(212,175,55,0.18) 0%,transparent 70%); top: -100px; right: -100px; }
        .orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, rgba(124,58,237,0.20) 0%,transparent 70%); bottom: -80px; left: -80px; animation-delay: -3s; }
        .orb-3 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(212,175,55,0.10) 0%,transparent 70%); top: 40%; left: 30%; animation-delay: -5s; }
        @keyframes orb-float { 0%,100% { transform: translate(0,0) scale(1); } 33% { transform: translate(20px,-30px) scale(1.05); } 66% { transform: translate(-15px,20px) scale(0.95); } }
        .hero-content { position: relative; z-index: 2; text-align: center; }
        .hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(212,175,55,0.10); border: 1px solid rgba(212,175,55,0.25); border-radius: 100px; padding: 0.45rem 1.1rem; font-size: 0.9rem; font-weight: 600; color: var(--gold); margin-bottom: 2rem; animation: badge-glow 3s ease-in-out infinite; }
        .hero-badge svg { width: 18px; height: 18px; flex-shrink: 0; }
        @keyframes badge-glow { 0%,100% { box-shadow: none; } 50% { box-shadow: 0 0 20px rgba(212,175,55,0.22); } }
        .hero-h1 { font-size: clamp(2.5rem,6vw,4.5rem); font-weight: 900; line-height: 1.08; letter-spacing: -0.03em; margin-bottom: 1.5rem; }
        .hero-sub { font-size: clamp(1.05rem,2.5vw,1.3rem); color: var(--text-secondary); max-width: 600px; margin: 0 auto 2.5rem; line-height: 1.7; }
        .hero-cta-group { display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .hero-trust { font-size: 0.88rem; color: var(--text-muted); margin-bottom: 3rem; }
        .hero-trust strong { color: var(--text-secondary); }
        .stats-card { display: inline-flex; align-items: center; gap: 1.5rem; background: rgba(19,22,29,0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border); border-radius: 100px; padding: 0.7rem 1.8rem; animation: stats-float 4s ease-in-out infinite; }
        @keyframes stats-float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        .h-stat { display: flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; font-weight: 500; color: var(--text-secondary); }
        .sdot { width: 6px; height: 6px; border-radius: 50%; animation: sdot-pulse 2s ease-in-out infinite; }
        .sdot.g { background: #22c55e; box-shadow: 0 0 8px rgba(34,197,94,0.7); }
        .sdot.y { background: var(--gold); box-shadow: 0 0 8px rgba(212,175,55,0.7); }
        .sdot.b { background: #38bdf8; box-shadow: 0 0 8px rgba(56,189,248,0.7); }
        @keyframes sdot-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }
        .ssep { width: 1px; height: 16px; background: var(--border); }

        /* SECTIONS */
        section { position: relative; }
        .sp  { padding: 7rem 0; }
        .sps { padding: 5rem 0; }
        .slabel { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--gold); margin-bottom: 1rem; }
        .slabel::before { content: ''; display: block; width: 20px; height: 2px; background: var(--gold); border-radius: 2px; }
        .sh2 { font-size: clamp(1.9rem,4vw,3rem); font-weight: 800; line-height: 1.15; letter-spacing: -0.025em; margin-bottom: 1rem; }
        .ssub { font-size: 1.05rem; color: var(--text-secondary); max-width: 560px; line-height: 1.7; }
        .sheader { margin-bottom: 4rem; }

        /* BENTO */
        .bento { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.25rem; }
        .bc { background: var(--bg-card); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 2rem; transition: all 0.3s; position: relative; overflow: hidden; }
        .bc::before { content: ''; position: absolute; inset: 0; border-radius: var(--r-lg); background: linear-gradient(135deg, var(--gold-dim) 0%, transparent 60%); opacity: 0; transition: opacity 0.3s; }
        .bc:hover { border-color: var(--gold-border); transform: translateY(-4px); box-shadow: 0 0 30px rgba(212,175,55,0.10), 0 12px 40px rgba(0,0,0,0.3); }
        .bc:hover::before { opacity: 1; }
        .bc.w2 { grid-column: span 2; }
        .bi { width: 48px; height: 48px; background: var(--gold-dim); border: 1px solid rgba(212,175,55,0.20); border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; transition: all 0.3s; }
        .bc:hover .bi { background: rgba(212,175,55,0.20); box-shadow: 0 0 16px rgba(212,175,55,0.25); }
        .bi svg { width: 24px; height: 24px; color: var(--gold); }
        .bc h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; position: relative; }
        .bc p  { font-size: 0.92rem; color: var(--text-secondary); line-height: 1.65; position: relative; }

        /* HOOK */
        .hook-card { background: linear-gradient(135deg, rgba(19,22,29,0.92) 0%, rgba(25,20,10,0.92) 100%); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid var(--gold-border); border-radius: var(--r-xl); padding: 4rem 3rem; text-align: center; box-shadow: 0 0 60px rgba(212,175,55,0.12), 0 0 120px rgba(212,175,55,0.05); position: relative; overflow: hidden; }
        .hook-card::before { content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 60%; height: 1px; background: linear-gradient(90deg,transparent,var(--gold),transparent); }
        .hook-h2 { font-size: clamp(1.8rem,4vw,2.8rem); font-weight: 800; letter-spacing: -0.025em; margin-bottom: 0.75rem; }
        .hook-sub { color: var(--text-secondary); font-size: 1.05rem; margin-bottom: 3.5rem; }
        .hook-perks { display: grid; grid-template-columns: repeat(3,1fr); gap: 2rem; margin-bottom: 3.5rem; }
        .hp { display: flex; flex-direction: column; align-items: center; gap: 0.75rem; }
        .hp-icon { width: 64px; height: 64px; background: rgba(212,175,55,0.10); border: 1px solid rgba(212,175,55,0.25); border-radius: var(--r-md); display: flex; align-items: center; justify-content: center; }
        .hp-icon svg { width: 30px; height: 30px; color: var(--gold); }
        .hp-num { font-size: 2.4rem; font-weight: 900; line-height: 1; background: linear-gradient(135deg, var(--gold), var(--gold-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hp-lbl { font-size: 0.9rem; color: var(--text-secondary); font-weight: 500; }
        .cred-prog { background: rgba(255,255,255,0.05); border: 1px solid var(--border); border-radius: var(--r-md); padding: 1.5rem 2rem; margin: 0 auto 2.5rem; max-width: 560px; }
        .cred-prog-top { display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.75rem; }
        .cred-prog-top span:last-child { color: var(--gold); font-weight: 600; }
        .prog-track { height: 8px; background: rgba(255,255,255,0.06); border-radius: 100px; overflow: hidden; }
        .prog-fill { height: 100%; border-radius: 100px; background: linear-gradient(90deg, var(--gold), var(--gold-light)); box-shadow: 0 0 12px rgba(212,175,55,0.5); width: 0; transition: width 1.5s cubic-bezier(0.4,0,0.2,1); }
        .prog-fill.on { width: 50%; }
        .cred-desc { font-size: 0.82rem; color: var(--text-muted); margin-top: 0.6rem; text-align: center; }

        /* FORM */
        .reg-sec { padding: 7rem 0; }
        .reg-card { background: rgba(19,22,29,0.92); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid var(--border); border-radius: var(--r-xl); padding: 3rem 2.5rem; box-shadow: var(--shadow-card); position: relative; overflow: hidden; }
        .reg-card::before { content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 50%; height: 1px; background: linear-gradient(90deg,transparent,var(--gold),transparent); }
        .reg-head { text-align: center; margin-bottom: 2rem; }
        .reg-head h2 { font-size: 1.8rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.4rem; }
        .reg-head p  { font-size: 0.92rem; color: var(--text-muted); }
        .tbadges { display: flex; justify-content: center; gap: 1.25rem; flex-wrap: wrap; margin-bottom: 1.75rem; }
        .tbadge { display: flex; align-items: center; gap: 0.35rem; font-size: 0.78rem; color: var(--text-muted); font-weight: 500; }
        .tbadge svg { width: 14px; height: 14px; color: var(--gold); flex-shrink: 0; }
        .ff { margin-bottom: 1.1rem; }
        .fl { display: block; font-size: 0.84rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.45rem; }
        .fi { width: 100%; padding: 0.85rem 1rem; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.10); border-radius: var(--r-sm); color: var(--text); font-size: 0.95rem; outline: none; transition: all 0.2s; -webkit-appearance: none; }
        .fi::placeholder { color: var(--text-muted); }
        .fi:hover { border-color: rgba(255,255,255,0.18); }
        .fi:focus { border-color: var(--gold); background: rgba(212,175,55,0.04); box-shadow: 0 0 0 3px rgba(212,175,55,0.10); }
        .fi.err { border-color: var(--red); }
        .fi.err:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.10); }
        .ferr { display: none; font-size: 0.8rem; color: var(--red); margin-top: 0.35rem; align-items: center; gap: 0.3rem; }
        .ferr.on { display: flex; }
        .ferr svg { width: 13px; height: 13px; flex-shrink: 0; }
        .pw-wrap { position: relative; }
        .pw-wrap .fi { padding-right: 3rem; }
        .pw-tog { position: absolute; right: 0.85rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); cursor: pointer; padding: 0.2rem; display: flex; align-items: center; transition: color 0.2s; }
        .pw-tog:hover { color: var(--gold); }
        .pw-tog svg { width: 18px; height: 18px; }
        .eye-off { display: none; }
        .phone-row { display: flex; gap: 0.5rem; }
        .ph-pfx { flex-shrink: 0; padding: 0.85rem 1rem; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.10); border-radius: var(--r-sm); color: var(--text-secondary); font-size: 0.95rem; font-weight: 600; min-width: 76px; display: flex; align-items: center; justify-content: center; }
        .phone-row .fi { flex: 1; }
        .sub-btn { width: 100%; padding: 1.05rem; margin-top: 0.5rem; background: linear-gradient(135deg, var(--gold), var(--gold-light)); color: #0f1115; font-weight: 800; font-size: 1rem; border-radius: var(--r-sm); border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 0 24px rgba(212,175,55,0.30), 0 4px 16px rgba(0,0,0,0.3); position: relative; overflow: hidden; }
        .sub-btn:hover { transform: translateY(-2px); box-shadow: 0 0 40px rgba(212,175,55,0.50), 0 8px 24px rgba(0,0,0,0.3); }
        .sub-btn:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
        .sub-btn::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg,transparent 40%,rgba(255,255,255,0.18) 100%); pointer-events: none; }
        .f-legal { text-align: center; margin-top: 1.1rem; font-size: 0.8rem; color: var(--text-muted); }
        .f-legal a { color: var(--text-secondary); text-decoration: underline; text-underline-offset: 3px; }
        .f-legal a:hover { color: var(--gold); }
        .f-signin { text-align: center; margin-top: 1.25rem; font-size: 0.88rem; color: var(--text-muted); }
        .f-signin a { color: var(--gold); font-weight: 600; }
        .gerr { display: none; background: var(--red-dim); border: 1px solid rgba(239,68,68,0.25); border-radius: var(--r-sm); padding: 0.75rem 1rem; font-size: 0.88rem; color: #fca5a5; margin-bottom: 1rem; align-items: center; gap: 0.5rem; }
        .gerr.on { display: flex; }
        .gerr svg { width: 16px; height: 16px; flex-shrink: 0; }

        /* TESTIMONIALS */
        .t-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem; }
        .tc { background: var(--bg-card); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 1.75rem; transition: all 0.3s; }
        .tc:hover { border-color: var(--gold-border); transform: translateY(-3px); box-shadow: 0 0 24px rgba(212,175,55,0.08); }
        .tstars { display: flex; gap: 0.25rem; margin-bottom: 1rem; }
        .tstars svg { width: 16px; height: 16px; color: var(--gold); }
        .tq { font-size: 0.95rem; color: var(--text-secondary); line-height: 1.75; margin-bottom: 1.25rem; font-style: italic; }
        .ta { display: flex; align-items: center; gap: 0.75rem; }
        .tav { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem; flex-shrink: 0; }
        .av-p { background: rgba(124,58,237,0.25); color: #a78bfa; border: 1px solid rgba(124,58,237,0.35); }
        .av-g { background: rgba(212,175,55,0.20); color: var(--gold); border: 1px solid var(--gold-border); }
        .av-b { background: rgba(56,189,248,0.20); color: #38bdf8; border: 1px solid rgba(56,189,248,0.35); }
        .tn { font-weight: 700; font-size: 0.9rem; }
        .tr { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.1rem; }

        /* PRICING */
        .p-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.5rem; }
        .pc { background: var(--bg-card); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 2rem; transition: all 0.3s; position: relative; overflow: hidden; }
        .pc.feat { border-color: var(--gold-border); box-shadow: 0 0 40px rgba(212,175,55,0.12); }
        .pc.feat::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg,transparent,var(--gold),transparent); }
        .pbadge { display: inline-block; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #0f1115; background: linear-gradient(135deg,var(--gold),var(--gold-light)); border-radius: 100px; padding: 0.25rem 0.75rem; margin-bottom: 1rem; }
        .pname  { font-size: 1.15rem; font-weight: 700; margin-bottom: 0.25rem; }
        .pprice { font-size: 2.4rem; font-weight: 900; line-height: 1; margin: 0.75rem 0 0.25rem; color: var(--gold); }
        .pprice small { font-size: 0.9rem; font-weight: 400; color: var(--text-muted); }
        .pdesc  { font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.5rem; }
        .pfeat  { margin-bottom: 1.75rem; }
        .pfeat li { display: flex; align-items: flex-start; gap: 0.6rem; font-size: 0.88rem; color: var(--text-secondary); padding: 0.35rem 0; }
        .pfeat li svg { width: 16px; height: 16px; color: var(--gold); flex-shrink: 0; margin-top: 1px; }

        /* HOW IT WORKS */
        .hiw-steps { display: flex; flex-direction: column; gap: 1.5rem; margin-top: 3rem; }
        .hiw-step { display: flex; gap: 1.75rem; align-items: flex-start; background: var(--bg-card); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 2rem 2rem; transition: border-color 0.3s; }
        .hiw-step:hover { border-color: var(--gold-border); }
        .hiw-num { flex-shrink: 0; width: 48px; height: 48px; border-radius: 50%; background: var(--gold-dim); border: 1px solid var(--gold-border); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800; color: var(--gold); }
        .hiw-content { flex: 1; min-width: 0; }
        .hiw-content h3 { font-size: 1.15rem; font-weight: 700; margin-bottom: 0.5rem; }
        .hiw-content p { font-size: 0.93rem; color: var(--text-secondary); line-height: 1.7; margin: 0; }
        .hiw-content p strong { color: var(--text); font-weight: 600; }
        .hiw-code { margin-top: 1rem; background: rgba(0,0,0,0.55); border: 1px solid rgba(255,255,255,0.07); border-radius: var(--r-sm); padding: 1rem 1.25rem; font-family: 'Fira Code', 'Cascadia Code', 'Courier New', monospace; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.2rem; color: #c9d1d9; overflow-x: auto; }
        .hiw-code .hiw-code-comment { color: var(--text-muted); }
        .hiw-code .hiw-str { color: #79c0ff; }
        .hiw-badges { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem; }
        .hiw-badge { background: rgba(124,58,237,0.12); border: 1px solid rgba(124,58,237,0.25); color: #a78bfa; font-size: 0.8rem; font-weight: 600; padding: 0.25rem 0.75rem; border-radius: 999px; }

        /* DEVELOPER */
        .dev-sec { padding: 5rem 0; }
        .dev-bar { background: rgba(19,22,29,0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid var(--border); border-radius: var(--r-md); padding: 1.1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all 0.2s; user-select: none; }
        .dev-bar:hover { border-color: var(--gold-border); background: rgba(212,175,55,0.04); }
        .dev-bar-l { display: flex; align-items: center; gap: 0.75rem; }
        .dev-bar-ic { width: 36px; height: 36px; background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.25); border-radius: var(--r-sm); display: flex; align-items: center; justify-content: center; }
        .dev-bar-ic svg { width: 18px; height: 18px; color: #a78bfa; }
        .dev-bar-lbl { font-weight: 700; font-size: 1rem; }
        .dev-bar-lbl span { color: var(--text-muted); font-weight: 400; font-size: 0.88rem; margin-left: 0.5rem; }
        .dev-chev { width: 20px; height: 20px; color: var(--text-muted); transition: transform 0.3s; flex-shrink: 0; }
        .dev-chev.rot { transform: rotate(180deg); }
        .dev-body { overflow: hidden; max-height: 0; transition: max-height 0.5s cubic-bezier(0.4,0,0.2,1); }
        .dev-body.open { max-height: 800px; }
        .dev-inner { background: rgba(19,22,29,0.70); border: 1px solid var(--border); border-top: none; border-radius: 0 0 var(--r-md) var(--r-md); padding: 2rem 1.5rem; }
        .dev-inner h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.35rem; }
        .dev-inner > p { font-size: 0.92rem; color: var(--text-secondary); margin-bottom: 1.5rem; }
        .dev-url { display: flex; align-items: center; gap: 0.75rem; background: rgba(255,255,255,0.04); border: 1px solid var(--border); border-radius: var(--r-sm); padding: 0.7rem 1rem; margin-bottom: 1.5rem; }
        .dev-url-lbl { font-size: 0.78rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; flex-shrink: 0; }
        .dev-url code { font-family: 'Courier New',monospace; font-size: 0.88rem; color: var(--gold); }
        .ctabs { display: flex; gap: 0.5rem; margin-bottom: 0.75rem; }
        .ctab { padding: 0.4rem 0.9rem; font-size: 0.82rem; font-weight: 600; border-radius: var(--r-sm); color: var(--text-muted); background: rgba(255,255,255,0.04); border: 1px solid var(--border); cursor: pointer; transition: all 0.2s; }
        .ctab.on { color: var(--gold); border-color: var(--gold-border); background: var(--gold-dim); }
        pre.cblock { background: rgba(0,0,0,0.35); border: 1px solid rgba(255,255,255,0.06); border-radius: var(--r-sm); padding: 1.25rem; font-family: 'Courier New',monospace; font-size: 0.82rem; color: #a1aab8; line-height: 1.7; overflow-x: auto; display: none; white-space: pre; }
        pre.cblock.on { display: block; }
        .kw  { color: #a78bfa; }
        .str { color: #86efac; }
        .cm  { color: #6b7280; }
        .dev-note { font-size: 0.84rem; color: var(--text-muted); margin-top: 1rem; }
        .dev-note a { color: var(--gold); }

        /* FOOTER */
        footer { border-top: 1px solid var(--border); padding: 3rem 0; }
        .foot-in { display: flex; align-items: center; justify-content: space-between; gap: 2rem; flex-wrap: wrap; }
        .foot-links { display: flex; gap: 1.5rem; flex-wrap: wrap; }
        .foot-links a { font-size: 0.85rem; color: var(--text-muted); transition: color 0.2s; }
        .foot-links a:hover { color: var(--text-secondary); }
        .foot-copy { font-size: 0.82rem; color: var(--text-muted); }

        /* ANIMATIONS */
        .fu { opacity: 0; transform: translateY(28px); transition: opacity 0.65s ease, transform 0.65s ease; }
        .fu.on { opacity: 1; transform: translateY(0); }
        .d1 { transition-delay: 0.10s; }
        .d2 { transition-delay: 0.20s; }
        .d3 { transition-delay: 0.30s; }

        @media (prefers-reduced-motion: reduce) {
            .fu { opacity: 1; transform: none; transition: none; }
            .orb, .stats-card, .logo-dot { animation: none; }
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .bento { grid-template-columns: repeat(2,1fr); }
            .bc.w2 { grid-column: span 1; }
        }
        @media (max-width: 768px) {
            .nav-links, .nav-actions .btn-ghost, .nav-actions .btn-outline-gold { display: none; }
            .nav-toggle { display: flex; }
            .bento, .t-grid, .p-grid { grid-template-columns: 1fr; }
            .hook-perks { grid-template-columns: 1fr; gap: 1.25rem; }
            .hero { padding: 7rem 0 4rem; }
            .sp { padding: 5rem 0; }
            .hook-card { padding: 2.5rem 1.5rem; }
            .reg-card { padding: 2rem 1.25rem; }
            .stats-card { flex-direction: column; gap: 0.75rem; border-radius: var(--r-md); padding: 1rem 1.5rem; }
            .ssep { width: 100%; height: 1px; }
            .foot-in { flex-direction: column; text-align: center; }
            .foot-links { justify-content: center; }
            .dev-url { flex-direction: column; align-items: flex-start; gap: 0.35rem; }
        }
        @media (max-width: 480px) {
            .hero-h1 { font-size: 2.2rem; }
            .hero-cta-group { flex-direction: column; align-items: stretch; }
            .hero-cta-group .btn { justify-content: center; }
            .hiw-step { flex-direction: column; gap: 1rem; padding: 1.5rem 1.25rem; }
            .hiw-num { width: 40px; height: 40px; font-size: 1.05rem; }
        }
    </style>
</head>
<body>

<nav class="mobile-nav" id="mobile-nav" role="dialog" aria-label="Navigation menu">
    <button class="mobile-nav-close" id="mnav-close" aria-label="Close menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    <a href="#features" onclick="closeMnav()">Features</a>
    <a href="#pricing"  onclick="closeMnav()">Pricing</a>
    <a href="#register" onclick="closeMnav()">Get Started Free</a>
    <a href="/login"    onclick="closeMnav()">Sign In</a>
</nav>

<header class="navbar" id="navbar">
    <div class="container">
        <div class="nav-inner">
            <a href="/" class="logo" aria-label="LLM Resayil home">
                <div class="logo-dot" aria-hidden="true"></div>
                LLM Resayil
            </a>
            <nav class="nav-links" aria-label="Main navigation">
                <a href="#features">Features</a>
                <a href="#pricing">Pricing</a>
                <a href="#developer">For Developers</a>
            </nav>
            <div class="nav-actions">
                <a href="/login" class="btn btn-ghost" style="padding:.6rem 1.1rem;font-size:.88rem;">Sign In</a>
                <a href="#register" class="btn btn-gold" style="padding:.6rem 1.2rem;font-size:.88rem;">
                    Start Free
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>
                <button class="nav-toggle" id="nav-toggle" aria-label="Open menu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>
</header>

<main>

<!-- HERO -->
<section class="hero" aria-labelledby="hero-h1">
    <div class="orb orb-1" aria-hidden="true"></div>
    <div class="orb orb-2" aria-hidden="true"></div>
    <div class="orb orb-3" aria-hidden="true"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge" role="note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                7 Days Free — No Credit Card Needed
            </div>
            <h1 class="hero-h1" id="hero-h1">
                Your Personal AI Assistant<br>
                <span class="text-gradient">In Arabic &amp; English</span>
            </h1>
            <p class="hero-sub">
                Write, think, and create faster with AI.<br>
                Get <strong>1,000 free credits</strong> to start today — no payment required.
            </p>
            <div class="hero-cta-group">
                <a href="#register" class="btn btn-gold btn-lg">
                    Claim Your Free Trial
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </a>
                <a href="#features" class="btn btn-ghost btn-lg">See What It Can Do</a>
            </div>
            <p class="hero-trust"><strong>Join thousands of users</strong> already saving hours every day</p>
            <div class="stats-card" role="status" aria-label="Live platform statistics">
                <div class="h-stat"><div class="sdot g" aria-hidden="true"></div>1,247 users online</div>
                <div class="ssep" aria-hidden="true"></div>
                <div class="h-stat"><div class="sdot y" aria-hidden="true"></div>47ms avg response</div>
                <div class="ssep" aria-hidden="true"></div>
                <div class="h-stat"><div class="sdot b" aria-hidden="true"></div>99.9% uptime</div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="sp" id="features" aria-labelledby="feat-h2">
    <div class="container">
        <div class="sheader text-center">
            <div class="slabel fu">What you can do with it</div>
            <h2 class="sh2 fu d1" id="feat-h2">Everyday tasks, done <span class="text-gradient-gold">10x faster</span></h2>
            <p class="ssub fu d2" style="margin:0 auto;">No technical knowledge needed. Just type what you need, in Arabic or English.</p>
        </div>
        <div class="bento">
            <div class="bc fu">
                <div class="bi" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg></div>
                <h3>Write in Seconds</h3>
                <p>Emails, business reports, social media posts, articles — generate polished content instantly in your language.</p>
            </div>
            <div class="bc fu d1">
                <div class="bi" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg></div>
                <h3>Answer Anything</h3>
                <p>Research topics, get instant analysis, and find answers to complex questions without spending hours searching.</p>
            </div>
            <div class="bc fu d2">
                <div class="bi" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/></svg></div>
                <h3>Arabic &amp; English</h3>
                <p>Seamlessly switch between both languages. Perfect for Kuwait, the Gulf, and the broader Arab world.</p>
            </div>
            <div class="bc w2 fu">
                <div class="bi" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <h3>Available 24/7 — Always Instant</h3>
                <p>No waiting, no appointments, no delays. Your AI assistant is ready the moment you need it, any time of day or night. Whether it is 3am or during Ramadan evenings, we are always on.</p>
            </div>
            <div class="bc fu d1">
                <div class="bi" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <h3>Private &amp; Secure</h3>
                <p>Your conversations and data stay safe. Built for users in Kuwait with privacy as the default.</p>
            </div>
            <div class="bc fu d2">
                <div class="bi" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg></div>
                <h3>50+ AI Models</h3>
                <p>Choose the best AI brain for each task. Creative writing, data analysis, coding help — there is a model for everything.</p>
            </div>
        </div>
    </div>
</section>

<!-- HOOK -->
<section class="sp" aria-labelledby="hook-h2">
    <div class="container">
        <div class="hook-card fu">
            <div class="slabel" style="justify-content:center;margin-bottom:.75rem;">Limited Time Offer</div>
            <h2 class="hook-h2" id="hook-h2">Start Free. Pay Only When<br><span class="text-gradient-gold">You Love It.</span></h2>
            <p class="hook-sub">Everything you need to get started — completely free.</p>
            <div class="hook-perks">
                <div class="hp">
                    <div class="hp-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
                    <div class="hp-num">7</div>
                    <div class="hp-lbl">Days Completely Free</div>
                </div>
                <div class="hp">
                    <div class="hp-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
                    <div class="hp-num">1,000</div>
                    <div class="hp-lbl">Free AI Credits</div>
                </div>
                <div class="hp">
                    <div class="hp-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg></div>
                    <div class="hp-num">0</div>
                    <div class="hp-lbl">Credit Card Required</div>
                </div>
            </div>
            <div class="cred-prog">
                <div class="cred-prog-top"><span>Your 1,000 free credits</span><span>~500 AI responses</span></div>
                <div class="prog-track"><div class="prog-fill" id="prog-fill"></div></div>
                <p class="cred-desc">1 credit = roughly 1 sentence of AI output. 1,000 credits goes a long way.</p>
            </div>
            <a href="#register" class="btn btn-gold btn-lg">
                Get My Free Credits
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="sp" id="how-it-works" aria-labelledby="hiw-h2">
    <div class="container">
        <div class="sheader text-center">
            <div class="slabel fu">How It Works</div>
            <h2 class="sh2 fu d1" id="hiw-h2">Up and running in <span class="text-gradient-gold">under 5 minutes</span></h2>
            <p class="ssub fu d2" style="margin:0 auto;">No chat interface. No new tool to learn. Just plug our endpoint into your existing app and go.</p>
        </div>
        <div class="hiw-steps">
            <!-- Step 1 -->
            <div class="hiw-step fu">
                <div class="hiw-num" aria-hidden="true">1</div>
                <div class="hiw-content">
                    <h3>Sign Up &amp; Choose a Plan</h3>
                    <p>Register in 30 seconds, pick a plan (Starter / Basic / Pro), and top up credits. Start with a <strong>7-day free trial</strong> — no credit card required, no commitment.</p>
                </div>
            </div>
            <!-- Step 2 -->
            <div class="hiw-step fu d1">
                <div class="hiw-num" aria-hidden="true">2</div>
                <div class="hiw-content">
                    <h3>Generate Your API Key</h3>
                    <p>From your dashboard, create an API key in one click. Instant access — no waiting, no approval queue.</p>
                </div>
            </div>
            <!-- Step 3 -->
            <div class="hiw-step fu d2">
                <div class="hiw-num" aria-hidden="true">3</div>
                <div class="hiw-content">
                    <h3>Point Your App at Our Endpoint</h3>
                    <p>Replace your current OpenAI base URL with ours. That is the only change. Use the same OpenAI SDK you already know — <strong>zero migration effort</strong>.</p>
                    <div class="hiw-code" aria-label="Code example: set base_url to https://llm.resayil.io/api/v1">
                        <span class="hiw-code-comment"># Python — one line change</span>
                        <span>client = OpenAI(</span>
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;base_url=<span class="hiw-str">"https://llm.resayil.io/api/v1"</span>,</span>
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;api_key=<span class="hiw-str">"your-api-key-here"</span>,</span>
                        <span>)</span>
                    </div>
                </div>
            </div>
            <!-- Step 4 -->
            <div class="hiw-step fu d3">
                <div class="hiw-num" aria-hidden="true">4</div>
                <div class="hiw-content">
                    <h3>Use It Everywhere</h3>
                    <p>Works with <strong>Cursor, VS Code extensions, custom apps, automation scripts</strong> — anywhere that supports OpenAI-compatible APIs. Credits are deducted per token. Pay only for what you use.</p>
                    <div class="hiw-badges" aria-label="Compatible tools">
                        <span class="hiw-badge">Cursor</span>
                        <span class="hiw-badge">VS Code</span>
                        <span class="hiw-badge">Python SDK</span>
                        <span class="hiw-badge">Node.js</span>
                        <span class="hiw-badge">n8n</span>
                        <span class="hiw-badge">Any OpenAI SDK</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center fu" style="margin-top:2.5rem;">
            <a href="#register" class="btn btn-gold btn-lg">
                Get Your API Key Free
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="sp" aria-labelledby="test-h2">
    <div class="container">
        <div class="sheader text-center">
            <div class="slabel fu">Real Users, Real Results</div>
            <h2 class="sh2 fu d1" id="test-h2">People are saving <span class="text-gradient-gold">hours every day</span></h2>
        </div>
        <div class="t-grid">
            <div class="tc fu">
                <div class="tstars" aria-label="5 stars"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
                <p class="tq">"I write all my business emails in Arabic now in seconds. It understands exactly what I mean and the tone is always professional. It has completely changed how I run my business."</p>
                <div class="ta"><div class="tav av-p" aria-hidden="true">F</div><div><div class="tn">Fatima Al-Zahra</div><div class="tr">Business Owner, Kuwait City</div></div></div>
            </div>
            <div class="tc fu d1">
                <div class="tstars" aria-label="5 stars"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
                <p class="tq">"My students use it for research and it is incredible. The quality of answers in Arabic is something I have never seen before. It has made my teaching so much more effective."</p>
                <div class="ta"><div class="tav av-g" aria-hidden="true">M</div><div><div class="tn">Dr. Mohammed Al-Rashid</div><div class="tr">University Professor</div></div></div>
            </div>
            <div class="tc fu d2">
                <div class="tstars" aria-label="5 stars"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
                <p class="tq">"Saved me 3 hours every single day on content writing. I used to struggle with captions and articles — now I just describe what I want and it is done. Absolutely worth every fils."</p>
                <div class="ta"><div class="tav av-b" aria-hidden="true">N</div><div><div class="tn">Noor Hassan</div><div class="tr">Content Creator &amp; Influencer</div></div></div>
            </div>
        </div>
    </div>
</section>

<!-- REGISTRATION FORM -->
<section class="reg-sec" id="register" aria-labelledby="reg-h2">
    <div class="container-xs">
        <div class="text-center" style="margin-bottom:1.5rem;">
            <div class="slabel fu" style="justify-content:center;">Get Started Now</div>
            <p class="fu d1" style="font-size:.9rem;color:var(--text-muted);">30 seconds to set up. No credit card. Cancel anytime.</p>
        </div>
        <div class="reg-card fu d2" id="reg-card">
            <div class="reg-head">
                <h2 id="reg-h2">Create Your Free Account</h2>
                <p>Start in 30 seconds. No credit card required.</p>
            </div>
            <div class="tbadges">
                <div class="tbadge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>No credit card</div>
                <div class="tbadge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>7 days free</div>
                <div class="tbadge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>Cancel anytime</div>
            </div>
            <div class="gerr" id="gerr" role="alert" aria-live="assertive">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span id="gerr-msg">Something went wrong. Please try again.</span>
            </div>
            <form id="reg-form" novalidate>
                <div class="ff">
                    <label class="fl" for="r-name">Full Name</label>
                    <input class="fi" type="text" id="r-name" name="name" placeholder="Ahmad Al-Mansouri" autocomplete="name" required aria-required="true" aria-describedby="e-name">
                    <div class="ferr" id="e-name" role="alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span></span></div>
                </div>
                <div class="ff">
                    <label class="fl" for="r-email">Email Address</label>
                    <input class="fi" type="email" id="r-email" name="email" placeholder="ahmad@example.com" autocomplete="email" required aria-required="true" aria-describedby="e-email">
                    <div class="ferr" id="e-email" role="alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span></span></div>
                </div>
                <div class="ff">
                    <label class="fl" for="r-phone">Phone Number</label>
                    <div class="phone-row">
                        <div class="ph-pfx" aria-label="+965 Kuwait">+965</div>
                        <input class="fi" type="tel" id="r-phone" name="phone_local" placeholder="9XXXXXXX" autocomplete="tel-national" inputmode="numeric" required aria-required="true" aria-describedby="e-phone" aria-label="Local phone number">
                    </div>
                    <div class="ferr" id="e-phone" role="alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span></span></div>
                </div>
                <div class="ff">
                    <label class="fl" for="r-pw">Password</label>
                    <div class="pw-wrap">
                        <input class="fi" type="password" id="r-pw" name="password" placeholder="At least 8 characters" autocomplete="new-password" required aria-required="true" aria-describedby="e-pw">
                        <button type="button" class="pw-tog" id="pw-tog" aria-label="Show password" aria-pressed="false">
                            <svg id="eye-on"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg id="eye-off" class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22"/></svg>
                        </button>
                    </div>
                    <div class="ferr" id="e-pw" role="alert"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span></span></div>
                </div>
                <button type="submit" class="sub-btn" id="reg-btn">Start My Free Trial &rarr;</button>
                <p class="f-legal">By signing up you agree to our <a href="/terms" target="_blank" rel="noopener">Terms of Service</a> and <a href="/privacy" target="_blank" rel="noopener">Privacy Policy</a></p>
                <p class="f-signin">Already have an account? <a href="/login">Sign In</a></p>
            </form>
        </div>
    </div>
</section>

<!-- PRICING -->
<section class="sp" id="pricing" aria-labelledby="price-h2">
    <div class="container">
        <div class="sheader text-center">
            <div class="slabel fu">Pricing</div>
            <h2 class="sh2 fu d1" id="price-h2">Simple Pricing. <span class="text-gradient-gold">No Surprises.</span></h2>
            <p class="ssub fu d2" style="margin:0 auto;">Start free, upgrade only when you need more. Prices in Kuwaiti Dinar.</p>
        </div>
        <div class="p-grid">
            <div class="pc fu">
                <div class="pname">Free Trial</div>
                <div class="pprice">FREE<small> / 7 days</small></div>
                <p class="pdesc">Everything you need to fall in love with AI</p>
                <ul class="pfeat">
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>1,000 free credits</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>~500 AI responses</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>Arabic &amp; English</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>No credit card</li>
                </ul>
                <a href="#register" class="btn btn-outline-gold" style="width:100%;justify-content:center;padding:.85rem;">Start Free Trial</a>
            </div>
            <div class="pc feat fu d1">
                <div class="pbadge">Most Popular</div>
                <div class="pname">Basic</div>
                <div class="pprice">2 KWD<small> / month</small></div>
                <p class="pdesc">Great for daily personal use</p>
                <ul class="pfeat">
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>5,000 credits / month</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>~2,500 AI responses</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>All 50+ AI models</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>Priority support</li>
                </ul>
                <a href="#register" class="btn btn-gold" style="width:100%;justify-content:center;padding:.85rem;">Start Free, Then Upgrade</a>
            </div>
            <div class="pc fu d2">
                <div class="pname">Pro</div>
                <div class="pprice">5 KWD<small> / month</small></div>
                <p class="pdesc">For power users and small teams</p>
                <ul class="pfeat">
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>15,000 credits / month</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>~7,500 AI responses</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>All models + API access</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>Dedicated support</li>
                </ul>
                <a href="#register" class="btn btn-ghost" style="width:100%;justify-content:center;padding:.85rem;">Get Started</a>
            </div>
        </div>
    </div>
</section>

<!-- DEVELOPER -->
<section class="dev-sec" id="developer">
    <div class="container-sm">
        <div class="dev-bar fu" id="dev-bar" role="button" aria-expanded="false" aria-controls="dev-body" tabindex="0">
            <div class="dev-bar-l">
                <div class="dev-bar-ic" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg></div>
                <div class="dev-bar-lbl">Are you a developer?<span>Click to get API access</span></div>
            </div>
            <svg class="dev-chev" id="dev-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
        </div>
        <div class="dev-body" id="dev-body" aria-hidden="true">
            <div class="dev-inner">
                <h3>OpenAI-Compatible API — Drop-in Replacement</h3>
                <p>Switch your existing OpenAI integration to LLM Resayil in minutes. Same API format, lower cost, 50+ models including Arabic-optimised ones.</p>
                <div class="dev-url"><span class="dev-url-lbl">Base URL</span><code>https://llm.resayil.io/api/v1</code></div>
                <div class="ctabs" role="tablist">
                    <button class="ctab on" id="tab-py"   role="tab" aria-selected="true"  aria-controls="code-py"   data-tab="py">Python</button>
                    <button class="ctab"    id="tab-curl" role="tab" aria-selected="false" aria-controls="code-curl" data-tab="curl">cURL</button>
                </div>
<pre class="cblock on" id="code-py" role="tabpanel" aria-labelledby="tab-py"><span class="cm"># pip install openai</span>
<span class="kw">from</span> openai <span class="kw">import</span> OpenAI

client = OpenAI(
    base_url=<span class="str">"https://llm.resayil.io/api/v1"</span>,
    api_key=<span class="str">"your-api-key-here"</span>,
)

response = client.chat.completions.create(
    model=<span class="str">"llama3.2"</span>,
    messages=[{<span class="str">"role"</span>: <span class="str">"user"</span>, <span class="str">"content"</span>: <span class="str">"&#x645;&#x631;&#x62D;&#x628;&#x627;! &#x643;&#x64A;&#x641; &#x62D;&#x627;&#x644;&#x643;&#x61F;"</span>}]
)
<span class="kw">print</span>(response.choices[0].message.content)</pre>
<pre class="cblock" id="code-curl" role="tabpanel" aria-labelledby="tab-curl">curl https://llm.resayil.io/api/v1/chat/completions \
  -H <span class="str">"Authorization: Bearer your-api-key-here"</span> \
  -H <span class="str">"Content-Type: application/json"</span> \
  -d <span class="str">'{
    "model": "llama3.2",
    "messages": [{"role": "user", "content": "&#x645;&#x631;&#x62D;&#x628;&#x627;!"}]
  }'</span></pre>
                <p class="dev-note">Get your API key after registering above. Questions? <a href="mailto:support@resayil.io">support@resayil.io</a></p>
            </div>
        </div>
    </div>
</section>

<!-- FINAL CTA -->
<section class="sps" aria-labelledby="cta-h2">
    <div class="container">
        <div class="glass-gold fu" style="padding:3.5rem 2.5rem;text-align:center;position:relative;overflow:hidden;">
            <div style="position:absolute;top:-60px;right:-60px;width:200px;height:200px;background:radial-gradient(circle,rgba(212,175,55,.10) 0%,transparent 70%);border-radius:50%;pointer-events:none;" aria-hidden="true"></div>
            <div style="position:absolute;bottom:-40px;left:-40px;width:160px;height:160px;background:radial-gradient(circle,rgba(124,58,237,.08) 0%,transparent 70%);border-radius:50%;pointer-events:none;" aria-hidden="true"></div>
            <div class="slabel" style="justify-content:center;margin-bottom:.75rem;">Ready to start?</div>
            <h2 class="sh2" id="cta-h2" style="margin-bottom:.75rem;">Your AI assistant is <span class="text-gradient-gold">one click away</span></h2>
            <p style="color:var(--text-secondary);margin-bottom:2rem;font-size:1.05rem;">7 days free. 1,000 credits. No credit card required.</p>
            <a href="#register" class="btn btn-gold btn-lg">
                Start My Free Trial Now
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </a>
            <p style="margin-top:1rem;font-size:.84rem;color:var(--text-muted);">Cancel anytime. No commitment.</p>
        </div>
    </div>
</section>

</main>

<footer role="contentinfo">
    <div class="container">
        <div class="foot-in">
            <a href="/" class="logo" aria-label="LLM Resayil home"><div class="logo-dot" aria-hidden="true"></div>LLM Resayil</a>
            <nav class="foot-links" aria-label="Footer navigation">
                <a href="#features">Features</a>
                <a href="#pricing">Pricing</a>
                <a href="#developer">Developers</a>
                <a href="/login">Sign In</a>
                <a href="/terms">Terms</a>
                <a href="/privacy">Privacy</a>
            </nav>
            <p class="foot-copy">&copy; {{ date('Y') }} LLM Resayil. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
(function () {
    'use strict';

    // Navbar scroll
    var navbar = document.getElementById('navbar');
    window.addEventListener('scroll', function () {
        navbar.classList.toggle('scrolled', window.scrollY > 40);
    }, { passive: true });

    // Mobile nav
    var mobileNav = document.getElementById('mobile-nav');
    var navToggle = document.getElementById('nav-toggle');
    window.closeMnav = function () {
        mobileNav.classList.remove('open');
        navToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    };
    document.getElementById('mnav-close').addEventListener('click', closeMnav);
    navToggle.addEventListener('click', function () {
        mobileNav.classList.add('open');
        navToggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    });
    mobileNav.addEventListener('click', function (e) { if (e.target === mobileNav) closeMnav(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMnav(); });

    // Scroll animations
    var fuEls = document.querySelectorAll('.fu');
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { e.target.classList.add('on'); io.unobserve(e.target); }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        fuEls.forEach(function (el) { io.observe(el); });
    } else {
        fuEls.forEach(function (el) { el.classList.add('on'); });
    }

    // Progress bar
    var pf = document.getElementById('prog-fill');
    if (pf && 'IntersectionObserver' in window) {
        var pbio = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { setTimeout(function () { pf.classList.add('on'); }, 300); pbio.unobserve(e.target); }
            });
        }, { threshold: 0.5 });
        pbio.observe(pf.closest('.cred-prog'));
    }

    // Developer toggle
    var devBar  = document.getElementById('dev-bar');
    var devBody = document.getElementById('dev-body');
    var devChev = document.getElementById('dev-chev');
    function toggleDev() {
        var open = devBody.classList.contains('open');
        devBody.classList.toggle('open', !open);
        devBody.setAttribute('aria-hidden', open ? 'true' : 'false');
        devBar.setAttribute('aria-expanded', open ? 'false' : 'true');
        devChev.classList.toggle('rot', !open);
    }
    devBar.addEventListener('click', toggleDev);
    devBar.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleDev(); } });

    // Code tabs
    document.querySelectorAll('.ctab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.ctab').forEach(function (t) { t.classList.remove('on'); t.setAttribute('aria-selected', 'false'); });
            document.querySelectorAll('.cblock').forEach(function (b) { b.classList.remove('on'); });
            tab.classList.add('on');
            tab.setAttribute('aria-selected', 'true');
            var panel = document.getElementById('code-' + tab.dataset.tab);
            if (panel) panel.classList.add('on');
        });
    });

    // Password toggle
    var pwTog  = document.getElementById('pw-tog');
    var pwInp  = document.getElementById('r-pw');
    var eyeOn  = document.getElementById('eye-on');
    var eyeOff = document.getElementById('eye-off');
    if (pwTog && pwInp) {
        pwTog.addEventListener('click', function () {
            var hidden = pwInp.type === 'password';
            pwInp.type = hidden ? 'text' : 'password';
            eyeOn.style.display  = hidden ? 'none'  : 'block';
            eyeOff.style.display = hidden ? 'block' : 'none';
            pwTog.setAttribute('aria-label',   hidden ? 'Hide password' : 'Show password');
            pwTog.setAttribute('aria-pressed', hidden ? 'true' : 'false');
        });
    }

    // Form helpers
    var fmap = {
        name:     { inp: 'r-name',  err: 'e-name'  },
        email:    { inp: 'r-email', err: 'e-email' },
        phone:    { inp: 'r-phone', err: 'e-phone' },
        password: { inp: 'r-pw',    err: 'e-pw'    },
    };

    function clearErrors() {
        document.querySelectorAll('.ferr').forEach(function (el) { el.classList.remove('on'); el.querySelector('span').textContent = ''; });
        document.querySelectorAll('.fi').forEach(function (el) { el.classList.remove('err'); });
        document.getElementById('gerr').classList.remove('on');
    }

    function showErrors(errors) {
        var hasField = false;
        Object.keys(errors).forEach(function (field) {
            var m = fmap[field];
            if (m) {
                hasField = true;
                var inp = document.getElementById(m.inp);
                var err = document.getElementById(m.err);
                if (inp) inp.classList.add('err');
                if (err) { err.querySelector('span').textContent = Array.isArray(errors[field]) ? errors[field][0] : errors[field]; err.classList.add('on'); }
            }
        });
        if (!hasField) {
            document.getElementById('gerr-msg').textContent = errors.message || 'Something went wrong. Please try again.';
            document.getElementById('gerr').classList.add('on');
        }
        var card = document.getElementById('reg-card');
        if (card) card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    document.querySelectorAll('.fi').forEach(function (inp) {
        inp.addEventListener('input', function () {
            if (!inp.classList.contains('err')) return;
            inp.classList.remove('err');
            var eid = inp.getAttribute('aria-describedby');
            if (eid) { var el = document.getElementById(eid); if (el) { el.classList.remove('on'); el.querySelector('span').textContent = ''; } }
        });
    });

    // Registration submit
    var regForm = document.getElementById('reg-form');
    if (regForm) {
        regForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            clearErrors();
            var btn  = document.getElementById('reg-btn');
            var orig = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Creating your account...';

            var phoneLocal = (regForm.querySelector('[name="phone_local"]').value || '').trim().replace(/^0+/, '');
            var payload = {
                name:                  regForm.querySelector('[name="name"]').value.trim(),
                email:                 regForm.querySelector('[name="email"]').value.trim(),
                phone:                 '965' + phoneLocal,
                password:              regForm.querySelector('[name="password"]').value,
                password_confirmation: regForm.querySelector('[name="password"]').value,
            };

            var ce = {};
            if (!payload.name)                    ce.name     = ['Please enter your full name.'];
            if (!payload.email)                   ce.email    = ['Please enter your email address.'];
            if (!phoneLocal)                      ce.phone    = ['Please enter your phone number.'];
            if (!payload.password)                ce.password = ['Please choose a password.'];
            else if (payload.password.length < 8) ce.password = ['Password must be at least 8 characters.'];

            if (Object.keys(ce).length) {
                showErrors(ce);
                btn.disabled = false; btn.textContent = orig;
                return;
            }

            try {
                var csrf = document.querySelector('meta[name="csrf-token"]');
                var res  = await fetch('/register', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf ? csrf.content : '', 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                });
                if (res.ok || res.status === 201) {
                    btn.textContent = 'Success! Redirecting...';
                    window.location.href = '/dashboard';
                } else {
                    var data = await res.json().catch(function () { return {}; });
                    if (data.errors)       showErrors(data.errors);
                    else if (data.message) showErrors({ message: data.message });
                    else                   showErrors({ message: 'Registration failed. Please check your details and try again.' });
                    btn.disabled = false; btn.textContent = orig;
                }
            } catch (err) {
                showErrors({ message: 'A network error occurred. Please check your connection and try again.' });
                btn.disabled = false; btn.textContent = orig;
            }
        });
    }

})();
</script>
</body>
</html>
