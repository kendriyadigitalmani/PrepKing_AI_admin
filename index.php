<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Luna — Creative Studio</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <script src="https://unpkg.com/springjs@latest/dist/spring.min.js"></script>

  <style>
    :root {
      --bg: #0f0f0f;
      --text: #f0f0f0;
      --accent: #00d4ff;
      --gray: #888;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Inter', system-ui, sans-serif;
      line-height: 1.6;
      overflow-x: hidden;
    }

    a { 
      color: inherit; 
      text-decoration: none; 
    }

    /* ─── Header / Hero ─── */
    header {
      height: 100vh;
      min-height: 680px;
      padding: 0 5vw;
      display: grid;
      place-items: center;
      position: relative;
      text-align: center;
    }

    .hero-content {
      max-width: 1100px;
      position: relative;
      z-index: 2;
    }

    .hero-title {
      font-size: clamp(3.8rem, 12vw, 14rem);
      font-weight: 700;
      letter-spacing: -0.06em;
      line-height: 0.88;
      margin-bottom: 0.2em;
    }

    .hero-subtitle {
      font-size: clamp(1.3rem, 4vw, 2.4rem);
      font-weight: 300;
      color: var(--gray);
      max-width: 780px;
      margin: 0 auto 3rem;
    }

    .btn-primary {
      display: inline-block;
      padding: 1rem 2.4rem;
      background: var(--accent);
      color: #000;
      font-weight: 600;
      border-radius: 50px;
      transition: all 0.4s ease;
    }

    .btn-primary:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 40px rgba(0,212,255,0.25);
    }

    /* ─── Sections ─── */
    section {
      padding: 14vh 5vw;
      min-height: 80vh;
    }

    .section-title {
      font-size: clamp(3.2rem, 8vw, 9rem);
      font-weight: 700;
      opacity: 0.08;
      letter-spacing: -0.04em;
      line-height: 0.85;
      margin-bottom: 1rem;
      user-select: none;
    }

    .content-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 4rem;
      max-width: 1400px;
      margin: 4rem auto;
    }

    .card {
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 16px;
      padding: 2.4rem;
      backdrop-filter: blur(10px);
      transition: all 0.5s cubic-bezier(0.23,1,0.32,1);
    }

    .card:hover {
      transform: translateY(-12px);
      border-color: var(--accent);
      box-shadow: 0 30px 60px rgba(0,0,0,0.4);
    }

    footer {
      text-align: center;
      padding: 6rem 0 3rem;
      color: var(--gray);
      font-size: 0.95rem;
    }

    /* Background gradient blob */
    .bg-blob {
      position: absolute;
      width: 80vmax;
      height: 80vmax;
      background: radial-gradient(circle at 30% 70%, rgba(0,212,255,0.12), transparent 40%);
      border-radius: 50%;
      top: -20%;
      left: -20%;
      pointer-events: none;
      z-index: 1;
    }
  </style>
</head>
<body>

  <div class="bg-blob"></div>

  <header>
    <div class="hero-content">
      <h1 class="hero-title" data-spring="scale:0.85 → 1; opacity:0 → 1; delay:200">LUNA</h1>
      <p class="hero-subtitle" data-spring="opacity:0 → 1; y:40 → 0; delay:500">
        We craft digital experiences<br>that feel alive
      </p>
      <a href="#work" class="btn-primary" data-spring="scale:0.7 → 1; delay:800">See Our Work</a>
    </div>
  </header>

  <section id="work">
    <h2 class="section-title" data-spring>Selected Work</h2>

    <div class="content-grid">
      <div class="card" data-spring="opacity:0 → 1; y:60 → 0; delay:200">
        <h3>ØRBIT</h3>
        <p>Next-gen spatial design platform • 2025</p>
      </div>
      <div class="card" data-spring="opacity:0 → 1; y:60 → 0; delay:350">
        <h3>VOID Studio</h3>
        <p>Experimental motion & sound identity</p>
      </div>
      <div class="card" data-spring="opacity:0 → 1; y:60 → 0; delay:500">
        <h3>Neon Pulse</h3>
        <p>AI-powered nightlife experience app</p>
      </div>
      <div class="card" data-spring="opacity:0 → 1; y:60 → 0; delay:650">
        <h3>ECLIPSE</h3>
        <p>Dark mode luxury fashion e-commerce</p>
      </div>
    </div>
  </section>

  <section id="about">
    <h2 class="section-title" data-spring>About</h2>
    <div style="max-width:800px; margin:0 auto; font-size:1.4rem; text-align:center;">
      <p data-spring="opacity:0 → 1; delay:400">
        We are a small collective of designers, developers and motion artists<br>
        obsessed with creating digital products that feel <strong>human</strong>, <strong>fast</strong> and <strong>strange</strong>.
      </p>
    </div>
  </section>

  <footer>
    <p>© 2026 Luna Collective — Made with ♡ & <a href="https://springjs.dev" target="_blank">Spring.js</a></p>
  </footer>

  <script>
    // Very gentle spring defaults for the whole page
    Spring.config({
      stiffness: 180,
      damping: 22,
      mass: 1.1,
      precision: 0.01
    });

    // Auto-apply to all elements with data-spring attribute
    document.querySelectorAll('[data-spring]').forEach(el => {
      const preset = el.dataset.spring || 'appear';
      
      if (preset.includes('→')) {
        // custom spring syntax directly in HTML (nice for designers)
        Spring.apply(el, preset);
      } else {
        // default smooth appear
        Spring.appear(el, {
          delay: el.dataset.delay || 0,
          threshold: 0.1
        });
      }
    });

    // Optional: parallax-ish blob movement
    window.addEventListener('mousemove', e => {
      const blob = document.querySelector('.bg-blob');
      const x = (e.clientX / window.innerWidth - 0.5) * 60;
      const y = (e.clientY / window.innerHeight - 0.5) * 60;
      blob.style.transform = `translate(${x}px, ${y}px)`;
    });
  </script>

</body>
</html>