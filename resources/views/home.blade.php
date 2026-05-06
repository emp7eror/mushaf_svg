<!DOCTYPE html>
<html lang="en" dir="ltr" data-theme="dark" data-lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>القرآن الكريم — Quran Mushafs</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Cinzel+Decorative:wght@400;700&family=Lato:wght@300;400&family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        /* ─── Theme Tokens ─── */
        :root {
            --gold:    #C9A84C;
            --gold-lt: #E8D5A0;
            --gold-dk: #8B6914;
            --transition: background 0.3s, color 0.3s, border-color 0.3s;
        }

        [data-theme="dark"] {
            --bg:           #1A1208;
            --surface:      rgba(250,246,238,0.04);
            --text:         #FAF6EE;
            --text-dim:     rgba(250,246,238,0.5);
            --text-muted:   rgba(250,246,238,0.3);
            --card-bg:      rgba(250,246,238,0.04);
            --stat-bg:      rgba(0,0,0,0.2);
            --border:       rgba(201,168,76,0.22);
            --footer-border:rgba(201,168,76,0.1);
        }

        [data-theme="light"] {
            --bg:           #F5EDD8;
            --surface:      rgba(60,40,10,0.04);
            --text:         #1A1208;
            --text-dim:     rgba(30,20,5,0.55);
            --text-muted:   rgba(30,20,5,0.35);
            --card-bg:      rgba(255,252,244,0.9);
            --stat-bg:      rgba(255,255,255,0.4);
            --border:       rgba(139,105,20,0.28);
            --footer-border:rgba(139,105,20,0.18);
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Lato', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            transition: background 0.3s, color 0.3s;
        }

        [data-lang="ar"] body,
        [data-lang="ar"] .subtitle,
        [data-lang="ar"] .card-meta,
        [data-lang="ar"] .card-desc,
        [data-lang="ar"] .card-cta,
        [data-lang="ar"] footer { font-family: 'Tajawal', sans-serif; }

        /* ─── BG pattern ─── */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                radial-gradient(ellipse at 20% 10%, rgba(201,168,76,0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 90%, rgba(30,70,32,0.1) 0%, transparent 50%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cg fill='none' stroke='rgba(201,168,76,0.07)' stroke-width='0.5'%3E%3Cpolygon points='30,2 56,15 56,45 30,58 4,45 4,15'/%3E%3Cpolygon points='30,10 50,20 50,40 30,50 10,40 10,20'/%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
        }

        .wrap { position: relative; z-index: 1; }

        /* ─── Controls Bar ─── */
        .controls-bar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: flex-end;
            gap: 8px; padding: 12px 24px;
        }

        .ctrl-btn {
            display: flex; align-items: center; gap: 6px;
            height: 32px; padding: 0 12px;
            border: 1px solid var(--border); border-radius: 3px;
            background: var(--card-bg); color: var(--text-dim);
            font-size: 11px; letter-spacing: 0.1em;
            cursor: pointer; transition: all 0.2s;
            backdrop-filter: blur(8px); font-family: inherit;
        }
        .ctrl-btn:hover { border-color: var(--gold); color: var(--gold); }
        .ctrl-btn svg { width: 14px; height: 14px; fill: currentColor; }

        /* lang/theme label visibility */
        .lang-en { display: inline; }
        .lang-ar { display: none; }
        [data-lang="ar"] .lang-en { display: none; }
        [data-lang="ar"] .lang-ar { display: inline; }

        /* ─── Header ─── */
        header { text-align: center; padding: 90px 20px 50px; }

        .arch {
            width: 320px; max-width: 90vw; margin: 0 auto 10px;
            opacity: 0.18; animation: fadeIn 2s ease both;
        }
        [data-theme="light"] .arch { opacity: 0.28; }

        .bismillah {
            font-family: 'Amiri', serif;
            font-size: clamp(24px, 5vw, 44px);
            color: var(--gold); letter-spacing: 0.05em; margin-bottom: 8px;
            text-shadow: 0 0 40px rgba(201,168,76,0.3);
            animation: fadeDown 1s ease both;
        }

        .site-title {
            font-family: 'Cinzel Decorative', cursive;
            font-size: clamp(12px, 2.2vw, 18px);
            letter-spacing: 0.25em; color: var(--gold-lt); text-transform: uppercase;
            animation: fadeDown 1s 0.15s ease both;
        }
        [data-theme="light"] .site-title { color: var(--gold-dk); }
        [data-lang="ar"] .site-title {
            font-family: 'Tajawal', sans-serif;
            font-size: clamp(15px, 2.5vw, 22px); letter-spacing: 0;
        }

        .divider {
            width: 240px; height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            margin: 22px auto; animation: fadeIn 1.2s 0.3s ease both;
        }

        .subtitle {
            font-size: 13px; color: var(--text-dim);
            letter-spacing: 0.12em; font-weight: 300;
            animation: fadeIn 1s 0.4s ease both;
        }
        [data-lang="ar"] .subtitle { letter-spacing: 0; font-size: 15px; }

        /* ─── Grid ─── */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 28px; max-width: 1200px;
            margin: 0 auto; padding: 20px 30px 80px;
        }

        /* ─── Card ─── */
        .card {
            position: relative;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 4px; overflow: hidden; cursor: pointer;
            transition: transform 0.35s cubic-bezier(.22,.68,0,1.2), border-color 0.3s, box-shadow 0.3s, background 0.3s;
            animation: fadeUp 0.7s calc(var(--i,0) * 0.1s + 0.5s) ease both;
            text-decoration: none; display: block;
        }
        [data-theme="light"] .card { box-shadow: 0 2px 16px rgba(100,70,10,0.08); }

        .card::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, var(--card-color, #2C5F2E) 0%, transparent 60%);
            opacity: 0.07; transition: opacity 0.3s;
        }
        .card:hover {
            transform: translateY(-6px) scale(1.01);
            border-color: var(--card-accent, var(--gold));
            box-shadow: 0 20px 60px rgba(0,0,0,0.25), inset 0 1px 0 rgba(201,168,76,0.1);
        }
        .card:hover::before { opacity: 0.14; }

        .card-top {
            padding: 28px 28px 0;
            display: flex; justify-content: space-between; align-items: flex-start;
        }

        .card-badge {
            font-family: 'Cinzel Decorative', cursive;
            font-size: 9px; letter-spacing: 0.2em;
            padding: 4px 10px; border-radius: 2px;
            text-transform: uppercase; border: 1px solid;
        }
        [data-lang="ar"] .card-badge { font-family: 'Tajawal', sans-serif; font-size: 11px; letter-spacing: 0; }

        .card-pages { font-size: 11px; color: var(--text-muted); letter-spacing: 0.1em; }

        .card-body { padding: 18px 28px 24px; }

        .card-arabic {
            font-family: 'Amiri', serif; font-size: 28px;
            margin-bottom: 4px; line-height: 1.2;
        }

        .card-name {
            font-family: 'Cinzel Decorative', cursive;
            font-size: 15px; color: var(--text); margin-bottom: 3px; letter-spacing: 0.05em;
        }
        [data-lang="ar"] .card-name { font-family: 'Tajawal', sans-serif; font-size: 16px; letter-spacing: 0; }

        .card-meta { font-size: 11px; color: var(--text-dim); letter-spacing: 0.08em; margin-bottom: 14px; }
        [data-lang="ar"] .card-meta { letter-spacing: 0; font-size: 13px; }

        .card-desc {
            font-size: 12.5px; color: var(--text-dim); line-height: 1.7;
            border-top: 1px solid rgba(201,168,76,0.12);
            padding-top: 14px; margin-bottom: 18px;
        }
        [data-lang="ar"] .card-desc { font-size: 14px; line-height: 1.9; }

        .card-stats { display: flex; gap: 16px; }
        .stat {
            text-align: center; flex: 1;
            background: var(--stat-bg); border: 1px solid rgba(201,168,76,0.1);
            border-radius: 3px; padding: 8px 6px;
        }
        .stat-val { display: block; font-family: 'Cinzel Decorative', cursive; font-size: 11px; }
        .stat-lbl {
            display: block; font-size: 9px; color: var(--text-muted);
            letter-spacing: 0.1em; margin-top: 2px;
        }
        [data-lang="ar"] .stat-lbl { font-family: 'Tajawal', sans-serif; font-size: 11px; letter-spacing: 0; }

        .card-cta {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin: 20px 28px 24px; padding: 11px;
            border: 1px solid; border-radius: 3px;
            font-family: 'Cinzel Decorative', cursive;
            font-size: 10px; letter-spacing: 0.2em; transition: background 0.25s;
        }
        [data-lang="ar"] .card-cta { font-family: 'Tajawal', sans-serif; font-size: 13px; letter-spacing: 0; }
        .card:hover .card-cta { background: rgba(201,168,76,0.08); }
        .card-cta svg { width: 14px; height: 14px; fill: currentColor; }

        footer {
            text-align: center; padding: 30px;
            border-top: 1px solid var(--footer-border);
            color: var(--text-muted); font-size: 11px; letter-spacing: 0.1em;
        }
        [data-lang="ar"] footer { letter-spacing: 0; font-size: 13px; }

        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-18px); } to { opacity: 1; transform: none; }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: none; }
        }
        @keyframes fadeIn {
            from { opacity: 0; } to { opacity: 1; }
        }
    </style>
</head>
<body>
<div class="wrap">

    <div class="controls-bar">
        <button class="ctrl-btn" onclick="toggleLang()">
            <svg viewBox="0 0 20 20"><path d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2h-.092a7.003 7.003 0 01-1.674 3.445l.23.218a1 1 0 11-1.368 1.46l-.25-.236A6.98 6.98 0 015 13H4a1 1 0 110-2h1a5.002 5.002 0 002.196-1.086l-.26-.246a1 1 0 011.368-1.46l.25.236A5.002 5.002 0 009.908 7H6a1 1 0 01-1-1V3a1 1 0 011-1zm6 9a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"/></svg>
            <span class="lang-en">العربية</span><span class="lang-ar">English</span>
        </button>
        <button class="ctrl-btn" onclick="toggleTheme()">
            <svg id="icon-moon" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
            <svg id="icon-sun" viewBox="0 0 20 20" style="display:none"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"/></svg>
            <span id="theme-label-en" class="lang-en">Light</span>
            <span id="theme-label-ar" class="lang-ar">فاتح</span>
        </button>
    </div>

    <header>
        <svg class="arch" viewBox="0 0 320 80" xmlns="http://www.w3.org/2000/svg">
            <path d="M20,80 L20,40 Q20,5 160,5 Q300,5 300,40 L300,80" fill="none" stroke="#C9A84C" stroke-width="1.5"/>
            <path d="M35,80 L35,42 Q35,18 160,18 Q285,18 285,42 L285,80" fill="none" stroke="#C9A84C" stroke-width="0.5"/>
            <circle cx="160" cy="5" r="3" fill="#C9A84C"/>
            <polygon points="160,0 163,8 157,8" fill="#C9A84C"/>
        </svg>
        <div class="bismillah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</div>
        <div class="site-title">
            <span class="lang-en">The Holy Quran — Digital Mushafs</span>
            <span class="lang-ar">القرآن الكريم — المصاحف الرقمية</span>
        </div>
        <div class="divider"></div>
        <div class="subtitle">
            <span class="lang-en">Select a Mushaf to begin your journey through the Sacred Text</span>
            <span class="lang-ar">اختر مصحفاً لتبدأ رحلتك مع كتاب الله</span>
        </div>
    </header>

    <div class="grid">
        @php
            $arabicNames  = ['hafs'=>'حَفْص','warsh'=>'وَرْش','qalon'=>'قَالُون','douri'=>'الدُّورِي','shubah'=>'شُعْبَة'];
            $arabicDesc   = [
              'hafs'  =>'أكثر المصاحف انتشاراً في العالم، يُستخدم في معظم الدول الإسلامية.',
              'warsh' =>'السائد في شمال وغرب أفريقيا، برواية ورش عن الإمام نافع.',
              'qalon' =>'يُستخدم في ليبيا وتونس، إحدى الروايتين الكبريين عن الإمام نافع.',
              'douri' =>'رواية الدوري عن أبي عمرو البصري، بالعدّ البصري.',
              'shubah'=>'الرواية الثانية عن الإمام عاصم إلى جانب حفص، عبر شعبة.',
            ];
            $arabicQiraa  = ['hafs'=>'عاصم','warsh'=>'نافع','qalon'=>'نافع','douri'=>'أبو عمرو','shubah'=>'عاصم'];
            $arabicRawi   = ['hafs'=>'حفص','warsh'=>'ورش','qalon'=>'قالون','douri'=>'الدوري','shubah'=>'شعبة'];
            $arabicSystem = ['hafs'=>'كوفي','warsh'=>'مدني','qalon'=>'مدني','douri'=>'بصري','shubah'=>'كوفي'];
        @endphp

        @foreach($mushafs as $key => $info)
            <a href="{{ route('reader', $key) }}" class="card"
               style="--card-color:{{ $info['color'] }};--card-accent:{{ $info['accent'] }};--i:{{ $loop->index }}">
                <div class="card-top">
      <span class="card-badge" style="color:{{ $info['accent'] }};border-color:{{ $info['accent'] }}44;background:{{ $info['accent'] }}18">
        <span class="lang-en">{{ $info['qiraa'] }}</span>
        <span class="lang-ar">{{ $arabicQiraa[$key] }}</span>
      </span>
                    <span class="card-pages">
        <span class="lang-en">604 pages</span>
        <span class="lang-ar">٦٠٤ صفحة</span>
      </span>
                </div>
                <div class="card-body">
                    <div class="card-arabic" style="color:{{ $info['accent'] }}">{{ $arabicNames[$key] }}</div>
                    <div class="card-name">
                        <span class="lang-en">{{ $info['name'] }}</span>
                        <span class="lang-ar">{{ $arabicNames[$key] }}</span>
                    </div>
                    <div class="card-meta">
                        <span class="lang-en">Rawi: {{ $info['rawi'] }} · {{ $info['system'] }} Count</span>
                        <span class="lang-ar">الراوي: {{ $arabicRawi[$key] }} · العدّ {{ $arabicSystem[$key] }}</span>
                    </div>
                    <p class="card-desc">
                        <span class="lang-en">{{ $info['desc'] }}</span>
                        <span class="lang-ar">{{ $arabicDesc[$key] }}</span>
                    </p>
                    <div class="card-stats">
                        <div class="stat">
                            <span class="stat-val" style="color:{{ $info['accent'] }}">{{ $info['count'] }}</span>
                            <span class="stat-lbl"><span class="lang-en">Ayaat</span><span class="lang-ar">آيات</span></span>
                        </div>
                        <div class="stat">
                            <span class="stat-val" style="color:{{ $info['accent'] }}">114</span>
                            <span class="stat-lbl"><span class="lang-en">Surahs</span><span class="lang-ar">سورة</span></span>
                        </div>
                        <div class="stat">
                            <span class="stat-val" style="color:{{ $info['accent'] }}">30</span>
                            <span class="stat-lbl"><span class="lang-en">Juz'</span><span class="lang-ar">جزء</span></span>
                        </div>
                    </div>
                </div>
                <div class="card-cta" style="border-color:{{ $info['accent'] }};color:{{ $info['accent'] }}">
                    <svg viewBox="0 0 20 20"><path d="M4 3h12a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1zm1 2v10h4V5H5zm6 0v10h4V5h-4z"/></svg>
                    <span class="lang-en">Open Mushaf</span>
                    <span class="lang-ar">افتح المصحف</span>
                </div>
            </a>
        @endforeach
    </div>

    <footer>
        <span class="lang-en">القرآن الكريم · All Qira'at · 5 Mushafs · Interactive SVG</span>
        <span class="lang-ar">القرآن الكريم · جميع القراءات · ٥ مصاحف · SVG تفاعلي</span>
    </footer>

</div>
<script>
    const html = document.documentElement;

    function applyTheme(t) {
        html.setAttribute('data-theme', t);
        localStorage.setItem('qtheme', t);
        document.getElementById('icon-moon').style.display = t === 'dark'  ? '' : 'none';
        document.getElementById('icon-sun').style.display  = t === 'light' ? '' : 'none';
        document.getElementById('theme-label-en').textContent = t === 'dark' ? 'Light' : 'Dark';
        document.getElementById('theme-label-ar').textContent = t === 'dark' ? 'فاتح'  : 'داكن';
    }

    function applyLang(l) {
        html.setAttribute('data-lang', l);
        html.setAttribute('lang', l);
        html.setAttribute('dir', l === 'ar' ? 'rtl' : 'ltr');
        localStorage.setItem('qlang', l);
    }

    function toggleTheme() { applyTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark'); }
    function toggleLang()  { applyLang(html.getAttribute('data-lang') === 'en' ? 'ar' : 'en'); }

    applyTheme(localStorage.getItem('qtheme') || 'dark');
    applyLang(localStorage.getItem('qlang')   || 'en');
</script>
</body>
</html>
