<!doctype html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Giriş • AVC Bridal Dress</title>
  <style>
    :root{--bg1:#0b0b0f;--glass:#15151a;--glass2:#1a1a22;--text:#e7e7ee;--muted:#cdd0d7;--radius:22px;}
    *{box-sizing:border-box} html,body{height:100%}
    body{margin:0;color:var(--text);
      background: radial-gradient(1200px 600px at -10% 0%, #4c1d95 0%, transparent 60%),
                  radial-gradient(1000px 500px at 110% 40%, #f59e0b22 0%, transparent 65%),
                  radial-gradient(800px 600px at 50% 115%, #ef444422 0%, transparent 65%),
                  var(--bg1);
      font:16px/1.4 system-ui,-apple-system,'Segoe UI',Roboto,Ubuntu,'Helvetica Neue',Arial;
    }
    .wrap{max-width:1200px;margin:40px auto;padding:20px}
    .card{background:linear-gradient(180deg,var(--glass),var(--glass2));border-radius:var(--radius);
      box-shadow:0 20px 80px #0008,inset 0 1px 0 #ffffff0f;overflow:hidden}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:0;align-items:center;min-height:560px;position:relative}
    .divider{position:absolute;left:50%;top:24px;bottom:24px;width:4px;background:linear-gradient(to bottom,#fff2,#fff5,#fff2);border-radius:2px;transform:translateX(-50%)}
    .left,.right{padding:48px} .brand{display:flex;align-items:center;gap:16px;margin-bottom:24px}
    .brand img{width:72px;height:72px} h1{font-size:42px;margin:0 0 12px} p{color:var(--muted);margin:8px 0}
    .muted{font-size:14px;color:#aeb2be} .center{text-align:center}
    .avatar{width:160px;height:160px;border-radius:50%;background:#4443;display:inline-grid;place-items:center;margin:6px auto 20px;border:6px solid #ffffff14}
    .avatar img{width:70%;opacity:.9;filter:drop-shadow(0 6px 20px #000a)}
    .input{width:100%;padding:14px 16px;background:#e9efff;border:none;border-radius:16px;font-size:16px;outline:none;color:#0f172a;box-shadow:inset 0 0 0 1px #0000,0 6px 18px #0005}
    .input+.input{margin-top:14px}
    .btn{display:block;width:100%;padding:12px 18px;margin:14px 0 0;border:none;cursor:pointer;border-radius:18px;font-weight:700;font-size:16px;color:#111;background:linear-gradient(180deg,#dbeafe,#bfdbfe);box-shadow:0 10px 26px #0007}
    .btn.secondary{background:linear-gradient(180deg,#e5e7eb,#d1d5db)}
    .footer-note{margin-top:16px;font-size:12px;color:#c084fc}
    @media (max-width:980px){.grid{grid-template-columns:1fr}.divider{display:none}.right{padding-top:0}.left{padding-bottom:0}}
    a{color:#93c5fd;text-decoration:none} form .error{background:#7f1d1d;color:#fff;padding:8px 12px;border-radius:12px;margin-bottom:10px}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="grid">
        <div class="divider"></div>
        <div class="left">
          <div class="brand">
            <img src="/assets/img/gp-logo.svg" alt="GP">
            <div style="font-size:18px;letter-spacing:.5px;opacity:.9">AVC Bridal Dress</div>
          </div>
          <h1>AVC Bridal Dress</h1>
          <p>Türkiye'nin öncü gelinlik mağaza yazılımı.</p>
          <p>AVCSoft Bilişim Yazılım ve Otomasyon Teknolojileri Lisanslı olarak hizmet vermektedir.</p>
          <p class="muted">AVC Bridal Dress bir AVCSoft® markasıdır.</p>
          <p class="muted"><a href="https://www.avcsoftbilisim.com" target="_blank" rel="noreferrer">www.avcsoftbilisim.com</a></p>
        </div>
        <div class="right center">
          <div class="avatar"><img src="/assets/img/fingerprint.svg" alt="fingerprint"></div>
          <?php echo $content; ?>
        </div>
      </div>
    </div>
    <div class="footer-note">#AVCBridalDress</div>
  </div>
</body>
</html>
