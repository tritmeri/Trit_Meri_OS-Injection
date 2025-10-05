<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Ping Console — retro</title>
<style>
  :root{
    --bg:#071013;
    --panel:#041018;
    --text:#7CFF7C;
    --muted:#4ea74e;
    --accent:#3be0ff;
  }
  html,body{height:100%;margin:0;background:var(--bg);font-family: "Courier New", Courier, monospace;color:var(--text);}
  .wrap{display:flex;flex-direction:column;align-items:center;justify-content:flex-start;padding:28px 18px;gap:18px;min-height:100%;}
  .banner{
    font-weight:700;
    letter-spacing:2px;
    color:var(--muted);
    text-shadow:0 0 12px rgba(124,255,124,0.08), 0 0 6px rgba(59,224,255,0.02);
    white-space:pre;
    line-height:1;
    font-size:16px;
  }
  .panel{
    width:100%;
    max-width:920px;
    background:linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.00));
    border:1px solid rgba(124,255,124,0.06);
    padding:18px;
    box-shadow: 0 6px 30px rgba(0,0,0,0.6);
    border-radius:8px;
    position:relative;
    overflow:hidden;
  }

  /* scanlines */
  .panel::after{
    content:"";
    position:absolute;left:0;right:0;top:0;bottom:0;
    background:linear-gradient(rgba(255,255,255,0.00) 49%, rgba(0,0,0,0.02) 50%);
    background-size:100% 4px;
    mix-blend-mode:overlay;
    pointer-events:none;
    opacity:0.45;
  }

  .toolbar{display:flex;gap:10px;align-items:center;margin-bottom:8px;}
  .led{width:10px;height:10px;border-radius:50%;background:#052;box-shadow:0 0 6px rgba(0,0,0,0.6) inset;}
  .led.red{background:#ff5c5c;}
  .led.yellow{background:#ffd86b;}
  .led.green{background:#3bff7b;}
  .title{flex:1;color:var(--muted);font-size:13px}

  form{display:flex;gap:12px;align-items:center}
  input[type="text"]{
    background:transparent;border:1px dashed rgba(124,255,124,0.12);padding:10px 12px;border-radius:6px;color:var(--text);
    outline:none;font-family:inherit;font-size:14px;min-width:360px;
  }
  input[type="submit"]{
    background:transparent;border:1px solid rgba(59,224,255,0.1);color:var(--accent);padding:10px 14px;border-radius:6px;cursor:pointer;
    transition:all .15s ease;
  }
  input[type="submit"]:hover{transform:translateY(-2px);box-shadow:0 6px 18px rgba(59,224,255,0.03)}
  .hint{color:rgba(124,255,124,0.6);font-size:12px;margin-top:8px}
  .flags{display:flex;gap:12px;margin-top:10px;flex-wrap:wrap}
  .flag{background:rgba(255,255,255,0.01);padding:6px 10px;border-radius:6px;border:1px solid rgba(124,255,124,0.04);font-size:12px;color:var(--muted)}

  .output{
    margin-top:12px;padding:12px;border-radius:6px;
    background:#000000; color:var(--text); font-family:monospace; font-size:13px;
    min-height:160px; white-space:pre-wrap; overflow:auto; border:1px solid rgba(0,255,0,0.03);
    box-shadow: inset 0 2px 12px rgba(0,0,0,0.6);
  }

  /* typing effect cursor */
  .typing { display:inline-block; border-right:2px solid var(--text); padding-right:6px; animation: blink 1s steps(2,start) infinite;}
  @keyframes blink { to { border-color: transparent; } }

  /* small footer */
  .footer{color:rgba(124,255,124,0.18);font-size:12px;margin-top:10px}
  a { color: var(--accent); text-decoration: none; }
  a:hover { text-decoration: underline; }

</style>
</head>
<body>
<div class="wrap">
  <div class="banner">
<?php
echo "          _____                    _____                    _____                _____                    _____                _____ \n";
echo "         /\    \                  /\    \                  /\    \              /\    \                  /\    \              |\    \ \n";
echo "        /::\    \                /::\    \                /::\____\            /::\    \                /::\    \             |:\____\ \n";
echo "       /::::\    \              /::::\    \              /::::|   |            \:::\    \              /::::\    \            |::|   | \n";
echo "      /::::::\    \            /::::::\    \            /:::::|   |             \:::\    \            /::::::\    \           |::|   | \n";
echo "     /:::/\:::\    \          /:::/\:::\    \          /::::::|   |              \:::\    \          /:::/\:::\    \          |::|   | \n";
echo "    /:::/__\:::\    \        /:::/__\:::\    \        /:::/|::|   |               \:::\    \        /:::/__\:::\    \         |::|   | \n";
echo "    \:::\   \:::\    \      /::::\   \:::\    \      /:::/ |::|   |               /::::\    \      /::::\   \:::\    \        |::|   | \n";
echo "  ___\:::\   \:::\    \    /::::::\   \:::\    \    /:::/  |::|   | _____        /::::::\    \    /::::::\   \:::\    \       |::|___|______  \n";
echo " /\   \:::\   \:::\    \  /:::/\:::\   \:::\    \  /:::/   |::|   |/\    \      /:::/\:::\    \  /:::/\:::\   \:::\____\      /::::::::\    \ \n";
echo "/::\   \:::\   \:::\____\/:::/__\:::\   \:::\____\/:: /    |::|   /::\____\    /:::/  \:::\____\/:::/  \:::\   \:::|    |    /::::::::::\____\\ n";
echo "\:::\   \:::\   \::/    /\:::\   \:::\   \::/    /\::/    /|::|  /:::/    /   /:::/    \::/    /\::/   |::::\  /:::|____|   /:::/~~~~/~~ \n";
echo " \:::\   \:::\   \/____/  \:::\   \:::\   \/____/  \/____/ |::| /:::/    /   /:::/    / \/____/  \/____|:::::\/:::/    /   /:::/    / \n";
echo "  \:::\   \:::\    \       \:::\   \:::\    \              |::|/:::/    /   /:::/    /                 |:::::::::/    /   /:::/    / \n";
echo "   \:::\   \:::\____\       \:::\   \:::\____\             |::::::/    /   /:::/    /                  |::|\::::/    /   /:::/    / \n";
echo "    \:::\  /:::/    /        \:::\   \::/    /             |:::::/    /    \::/    /                   |::| \::/____/    \::/    / \n";
echo "     \:::\/:::/    /          \:::\   \/____/              |::::/    /      \/____/                    |::|  ~|           \/____/ \n";
echo "      \::::::/    /            \:::\    \                  /:::/    /                                  |::|   | \n";
echo "       \::::/    /              \:::\____\                /:::/    /                                   \::|   | \n";
echo "        \::/    /                \::/    /                \::/    /                                     \:|   | \n";
echo "         \/____/                  \/____/                  \/____/                                       \|___| \n";
?>
  </div>
  <div class="panel" id="panel">
    <div class="toolbar">
      <div style="display:flex;gap:6px;align-items:center">
        <span class="led red"></span>
        <span class="led yellow"></span>
        <span class="led green"></span>
      </div>
      <div class="title">Ping Console — live</div>
      <div style="font-size:12px;color:rgba(124,255,124,0.25)">PID: <?php echo getmypid(); ?> • <?php echo date('Y-m-d H:i:s'); ?> UTC</div>
    </div>

    <form method="get" action="ping.php" id="pingForm" onsubmit="saveRecent();">
      <input id="host" name="host" type="text" autocomplete="off" value="127.0.0.1" />
      <input type="submit" value="Execute" />
    </form>


  </div>
</div>

<script>
/* small UI niceties: load last host, animate placeholder */
(function(){
  const host = document.getElementById('host');
  const out = document.getElementById('output');

  try {
    const last = localStorage.getItem('last_host_v1');
    if (last) host.value = last;
  } catch(e){}

  function q(name){ const params = new URLSearchParams(location.search); return params.get(name); }
  const qhost = q('host');
  if(qhost){
    fetch('ping.php?host=' + encodeURIComponent(qhost))
      .then(r => r.text())
      .then(text => {
        const match = text.match(/<pre[^>]*>([\\s\\S]*?)<\\/pre>/i);
        out.textContent = match ? match[1].replace(/&nbsp;/g,' ') : text;
        out.scrollTop = out.scrollHeight;
      }).catch(e => { out.textContent = 'Fetch failed: ' + e; });
  }

  host.addEventListener('focus', function(){ host.classList.add('typing'); });
  host.addEventListener('blur', function(){ host.classList.remove('typing'); });

  window.saveRecent = function(){
    try { localStorage.setItem('last_host_v1', host.value); } catch(e){}
    out.textContent = '> executing...';
  }
})();
</script>
</body>
</html>
