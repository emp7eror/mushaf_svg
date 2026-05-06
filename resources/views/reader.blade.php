<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="dark" data-lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $info['name'] }} — القرآن الكريم</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Cinzel+Decorative:wght@400;700&family=Lato:wght@300;400;700&family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        /* ─── التنسيقات والرموز ─── */
        :root {
            --gold: #C9A84C; --gold-lt: #E8D5A0; --gold-dk: #8B6914;
            --accent: {{ $info['accent'] }};
            --mushaf-color: {{ $info['color'] }};
        }

        [data-theme="dark"] {
            --bg: #12100A; --bg2: #1C1810; --bg3: #252015; --text: #FAF6EE;
            --text-dim: rgba(250,246,238,0.55); --text-muted: rgba(250,246,238,0.3);
            --border: rgba(201,168,76,0.2); --bar-bg: rgba(18,16,10,0.95);
            --spine-bg: linear-gradient(90deg,#1a1208,#3d2e18,#1a1208);
            --page-bg: #f8f3e8; --shadow: rgba(0,0,0,0.8); --input-bg: #252015;
        }

        [data-theme="light"] {
            --bg: #E8DEC8; --bg2: #F0E8D0; --bg3: #F5EDD8; --text: #1A1208;
            --text-dim: rgba(30,20,5,0.55); --text-muted: rgba(30,20,5,0.35);
            --border: rgba(139,105,20,0.25); --bar-bg: rgba(232,222,200,0.97);
            --spine-bg: linear-gradient(90deg,#6b4c1e,#a0742e,#6b4c1e);
            --page-bg: #fffdf7; --shadow: rgba(80,50,10,0.25); --input-bg: #f0e8d0;
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            background: var(--bg); color: var(--text); font-family: 'Lato', sans-serif;
            height: 100vh; overflow: hidden; user-select: none; transition: 0.3s;
        }

        [data-lang="ar"] .lbl, [data-lang="ar"] .mushaf-label, [data-lang="ar"] .page-info,
        [data-lang="ar"] .hint, [data-lang="ar"] .nav-btn, [data-lang="ar"] #ayah-tooltip { font-family: 'Tajawal', sans-serif; }

        body::before {
            content:''; position:fixed; inset:0; opacity:0.04;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cg fill='none' stroke='%23C9A84C' stroke-width='0.6'%3E%3Cpolygon points='40,2 76,21 76,59 40,78 4,59 4,21'/%3E%3Cpolygon points='40,14 66,28 66,52 40,66 14,52 14,28'/%3E%3C/g%3E%3C/svg%3E");
            pointer-events:none; z-index:0;
        }

        .app { display:flex; flex-direction:column; height:100vh; position:relative; z-index:1; }

        /* ─── Top Bar ─── */
        .topbar {
            display:flex; align-items:center; justify-content:space-between;
            padding:0 16px; height:52px; border-bottom:1px solid var(--border);
            background: var(--bar-bg); backdrop-filter:blur(10px); flex-shrink:0; gap:10px;
        }
        .topbar-left { display:flex; align-items:center; gap:10px; flex:1; min-width:0; }
        .topbar-right { display:flex; align-items:center; gap:6px; flex:1; justify-content:flex-end; }

        .back-btn { display:flex; align-items:center; gap:5px; color:var(--text-dim); font-size:11px; text-decoration:none; }
        .mushaf-label { font-size:11px; color:var(--accent); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        [data-lang="ar"] .mushaf-label { font-size:13px; }

        .btn-icon {
            width:32px; height:32px; display:flex; align-items:center; justify-content:center;
            border:1px solid var(--border); border-radius:3px; background:transparent;
            color:var(--text-dim); cursor:pointer; transition:0.2s;
        }
        .btn-icon:hover { border-color:var(--gold); color:var(--gold); background:rgba(201,168,76,0.08); }

        /* ─── Mushaf Switcher ─── */
        .mushaf-switcher { position:relative; }
        .switcher-dropdown {
            position:absolute; top:calc(100% + 8px); right:0; background:var(--bg2);
            border:1px solid var(--border); border-radius:4px; min-width:210px; z-index:100;
            display:none; box-shadow:0 8px 32px var(--shadow);
        }
        .switcher-dropdown.open { display:block; }
        .switcher-item {
            display:flex; align-items:center; gap:10px; padding:10px 14px;
            text-decoration:none; color:var(--text-dim); font-size:12px;
            border-bottom:1px solid rgba(201,168,76,0.08);
        }
        .switcher-item.current { color:var(--accent); background:rgba(201,168,76,0.08); }

        /* ─── Reader & Book ─── */
        .reader { flex:1; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; }
        .book-wrap {
            display:flex; align-items:center; justify-content:center;
            height:calc(100vh - 120px); filter:drop-shadow(0 30px 80px var(--shadow));
        }

        .page-slot {
            position:relative; height:100%; aspect-ratio:340/480;
            background: var(--page-bg); overflow:hidden;
        }

        /* طلبك الخاص بالهوامش */
        #svg-right { padding: 50px; width:100%; height:100%; display:block; }
        #svg-left { padding: 50px; width:100%; height:100%; display:block; }

        .book-spine { width:4px; height:100%; background:var(--spine-bg); z-index:10; }

        /* ─── Responsive (Mobile) ─── */
        @media (max-width: 768px) {
            .page-slot.left, .book-spine { display: none !important; }
            .page-slot.right { width: 95vw; border-radius: 8px; }
            #svg-right { padding: 0px; margin-top: -15px;width:100%; height:100%; display:block; }
            #svg-left { padding: 0px; margin-top: -15px;width:100%; height:100%; display:block; }
            .navbar {
                position: absolute;
                bottom: 50px;
                left: 0;
                width: 100%;
            }
        }
        @media (min-width: 760px) {
            #svg-right { padding:  90px}
            #svg-left { padding:  90px }


        }
        /* ─── Tooltip & Navbar ─── */
        #ayah-tooltip {
            position:fixed; bottom:80px; left:50%; transform:translateX(-50%);
            background:var(--bg2); border:1px solid var(--accent); border-radius:4px;
            padding:8px 18px; font-family:'Amiri',serif; font-size:14px; color:var(--text);
            z-index:50; opacity:0; transition:0.3s; pointer-events:none;
        }
        #ayah-tooltip.show { opacity:1; }

        .navbar {
            height:64px; display:flex; align-items:center; justify-content:center; gap:10px;
            border-top:1px solid var(--border); background:var(--bar-bg); flex-shrink:0;
        }
        .nav-btn {
            width:38px; height:38px; display:flex; align-items:center; justify-content:center;
            border:1px solid var(--border); border-radius:3px; background:transparent;
            color:var(--text-dim); cursor:pointer;
        }
        .nav-btn:disabled { opacity:0.2; }

        .page-input { width:56px; height:36px; background:var(--input-bg); border:1px solid var(--border); color:var(--text); text-align:center; outline:none; }

        .zoom-overlay {
            position:fixed; inset:0; background:rgba(8,6,4,0.93); z-index:200;
            display:none; align-items:center; justify-content:center; cursor:zoom-out;
        }
        .zoom-overlay.open { display:flex; }
        #zoom-svg { max-width:95vw; max-height:95vh; border:1px solid rgba(201,168,76,0.3); }

        .lang-en { display:inline; } .lang-ar { display:none; }
        [data-lang="ar"] .lang-en { display:none; } [data-lang="ar"] .lang-ar { display:inline; }
    </style>
</head>
<body>

<div class="app">
    <!-- TOP BAR -->
    <div class="topbar">
        <div class="topbar-left">
            <a href="{{ route('home') }}" class="back-btn">
                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor"><path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"/></svg>
                <span class="lang-en">Mushafs</span><span class="lang-ar">المصاحف</span>
            </a>
            <div class="mushaf-label">{{ $info['name'] }} <span style="opacity:0.5"> — </span> {{ $info['qiraa'] }}</div>
        </div>

        <div class="topbar-center">
            <div class="page-info" id="page-display"></div>
        </div>

        <div class="topbar-right">
            <button class="btn-icon" title="Zoom" onclick="zoomPage()">🔍</button>
            <button class="btn-icon" onclick="toggleTheme()" title="Theme">🌓</button>
            <button class="btn-icon" onclick="toggleLang()" style="width:auto;padding:0 8px;">
                <span class="lang-en">ع</span><span class="lang-ar">EN</span>
            </button>

            <div class="mushaf-switcher">
                <button class="btn-icon" onclick="toggleSwitcher()">📚</button>
                <div class="switcher-dropdown" id="switcher-dropdown">
                    @foreach($mushafs as $key => $m)
                        <a href="{{ route('reader', $key) }}" class="switcher-item {{ $key === $mushaf ? 'current' : '' }}">
                            <span class="lang-en">{{ $m['name'] }}</span>
                            <span class="lang-ar">{{ $m['name'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- READER -->
    <div class="reader">
        <div class="book-wrap" id="book-container">
            <div class="page-slot left" id="page-left-container">
                <object id="svg-left" type="image/svg+xml" data=""></object>
            </div>
            <div class="book-spine"></div>
            <div class="page-slot right" id="page-right-container">
                <object id="svg-right" type="image/svg+xml" data=""></object>
            </div>
        </div>
    </div>

    <!-- BOTTOM NAV -->
    <div class="navbar">
        <button class="nav-btn" onclick="goToPage(604)">«</button>
        <button class="nav-btn" id="btn-next" onclick="nextSpread()">◀</button>
        <div class="page-jump">
            <input class="page-input" id="page-input" type="number" min="1" max="604">
            <span style="font-size:11px; color:var(--text-muted);"> / 604</span>
        </div>
        <button class="nav-btn" id="btn-prev" onclick="prevSpread()">▶</button>
        <button class="nav-btn" onclick="goToPage(1)">»</button>
    </div>
</div>

<!-- AYAH TOOLTIP -->
<div id="ayah-tooltip">
    <span class="lang-en">Surah <span id="tt-surah"></span>, Ayah <span id="tt-ayah"></span></span>
    <span class="lang-ar">سورة <span id="tt-surah-ar"></span>، آية <span id="tt-ayah-ar"></span></span>
</div>

<!-- ZOOM OVERLAY -->
<div class="zoom-overlay" id="zoom-overlay" onclick="closeZoom()">
    <object id="zoom-svg" type="image/svg+xml" data=""></object>
</div>

<script>
    const MUSHAF = '{{ $mushaf }}';
    const TOTAL = 604;
    let currentPage = 1;
    let tooltipTimer = null;

    function isMobile() { return window.innerWidth <= 768; }

    function getSpread(p) {
        if (isMobile()) return { right: p, left: null };
        if (p === 1) return { right: 1, left: null };
        const right = (p % 2 !== 0) ? p : p + 1;
        const left = right - 1;
        return { right: right <= TOTAL ? right : null, left: left >= 1 ? left : null };
    }

    function renderSpread(page) {
        currentPage = Math.max(1, Math.min(TOTAL, page));
        const spread = getSpread(currentPage);

        loadSlot('svg-right', spread.right);
        if (!isMobile()) {
            loadSlot('svg-left', spread.left);
            document.getElementById('page-left-container').style.visibility = spread.left ? 'visible' : 'hidden';
            document.querySelector('.book-spine').style.display = spread.left ? 'block' : 'none';
        }

        const lang = document.documentElement.getAttribute('data-lang');
        document.getElementById('page-display').innerHTML = lang === 'ar' ? `الصفحة ${currentPage}` : `Page ${currentPage}`;
        document.getElementById('page-input').value = currentPage;
    }

    function loadSlot(id, p) {
        const obj = document.getElementById(id);
        if (!p) { obj.data = ""; return; }
        obj.data = `/api/mushaf/${MUSHAF}/page/${p}`;
        obj.onload = () => injectInteraction(obj.contentDocument);
    }

    function injectInteraction(svgDoc) {
        if (!svgDoc) return;
        const style = svgDoc.createElementNS('http://www.w3.org/2000/svg', 'style');
        style.textContent = `.ayahPolygon { fill: transparent; cursor: pointer; transition: 0.2s; } .ayahPolygon:hover { fill: var(--gold); fill-opacity: 0.2; } .ayahPolygon.active { fill: var(--gold); fill-opacity: 0.4; }`;
        svgDoc.querySelector('svg').prepend(style);

        svgDoc.querySelectorAll('.ayahPolygon').forEach(poly => {
            poly.onclick = () => {
                svgDoc.querySelectorAll('.ayahPolygon.active').forEach(a => a.classList.remove('active'));
                poly.classList.add('active');
                const s = poly.getAttribute('surah'), a = poly.getAttribute('ayah');
                showTooltip(s, a);
            };
        });
    }

    function showTooltip(s, a) {
        document.getElementById('tt-surah').innerText = s; document.getElementById('tt-ayah').innerText = a;
        document.getElementById('tt-surah-ar').innerText = s; document.getElementById('tt-ayah-ar').innerText = a;
        const tt = document.getElementById('ayah-tooltip');
        tt.classList.add('show');
        clearTimeout(tooltipTimer);
        tooltipTimer = setTimeout(() => tt.classList.remove('show'), 3000);
    }

    function nextSpread() { renderSpread(currentPage + (isMobile() ? 1 : (currentPage === 1 ? 1 : 2))); }
    function prevSpread() { renderSpread(currentPage - (isMobile() ? 1 : (currentPage <= 2 ? 1 : 2))); }
    function goToPage(p) { renderSpread(parseInt(p)); }

    function zoomPage() {
        document.getElementById('zoom-svg').data = `/api/mushaf/${MUSHAF}/page/${currentPage}`;
        document.getElementById('zoom-overlay').classList.add('open');
    }
    function closeZoom() { document.getElementById('zoom-overlay').classList.remove('open'); }

    function toggleTheme() {
        const t = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', t);
    }
    function toggleLang() {
        const l = document.documentElement.getAttribute('data-lang') === 'ar' ? 'en' : 'ar';
        document.documentElement.setAttribute('data-lang', l);
        document.documentElement.dir = l === 'ar' ? 'rtl' : 'ltr';
        renderSpread(currentPage);
    }
    function toggleSwitcher() { document.getElementById('switcher-dropdown').classList.toggle('open'); }

    window.addEventListener('resize', () => renderSpread(currentPage));
    document.getElementById('page-input').onchange = (e) => goToPage(e.target.value);

    renderSpread(1);
</script>
</body>
</html>
