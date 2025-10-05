<?php
// vulnerable: still intentionally uses shell_exec()
$host = isset($_GET['host']) ? $_GET['host'] : '127.0.0.1';
$cmd = 'ping -c 1 ' . $host;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Ping — output</title>
<style>
  body{background:#071013;color:#7CFF7C;font-family: "Courier New", monospace;padding:18px;}
  .out{white-space:pre-wrap;background:#000;border:1px solid rgba(124,255,124,0.06);padding:12px;border-radius:6px;box-shadow:inset 0 6px 40px rgba(0,0,0,0.6); max-width:1100px; margin:0 auto;}
  a{color:#3be0ff}
</style>
</head>
<body>
<div class="out">
<?php
echo "Running: " . htmlspecialchars($cmd) . "\n\n";
$output = shell_exec($cmd . " 2>&1");
echo htmlspecialchars($output);
?>
</div>
<div style="max-width:1100px;margin:18px auto;color:rgba(124,255,124,0.5);font-family:monospace">
  <a href="index.php">← back</a>  •  Tip: Try injection like <code>127.0.0.1;id</code>
</div>
</body>
</html>
