<?php
$name = htmlspecialchars($_GET['name'] ?? 'Participant');
$score = htmlspecialchars($_GET['score'] ?? '0');
$level = htmlspecialchars($_GET['level'] ?? 'Foundation');
$ts = $_GET['ts'] ?? time();
$date = date('F d, Y');
$hash = strtoupper(substr(md5($name . $ts), 0, 8));
$cert_id = "VF-" . substr($hash, 0, 4) . "-" . substr($hash, 4, 4);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate - <?php echo $cert_id; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&family=Libre+Baskerville:ital@1&display=swap" rel="stylesheet">
    <style>
        body { background: #1b1d21; font-family: 'Inter', sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .cert-container { background: white; padding: 50px; width: 800px; height: 500px; border: 15px solid #1b1d21; outline: 4px solid #4f46e5; position: relative; text-align: center; color: #1b1d21; }
        .cert-id { position: absolute; top: 20px; right: 30px; font-family: monospace; font-size: 11px; color: #991b1b; }
        .logo { max-width: 140px; margin-bottom: 20px; }
        h1 { font-size: 40px; font-weight: 800; text-transform: uppercase; margin: 0; }
        .award-line { font-family: 'Libre+Baskerville', serif; font-style: italic; font-size: 18px; margin: 15px 0; color: #4b5563; }
        .participant-name { font-size: 38px; font-weight: 800; color: #4f46e5; border-bottom: 2px solid #e5e7eb; display: inline-block; padding: 0 30px; margin: 15px 0; }
        .rank-box { margin-top: 25px; font-size: 24px; font-weight: 800; color: #10b981; text-transform: uppercase; }
        .footer { position: absolute; bottom: 30px; width: calc(100% - 100px); display: flex; justify-content: space-between; font-size: 12px; color: #6b7280; }
        .download-btn { margin-top: 20px; background: #4f46e5; color: white; padding: 12px 25px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; display: inline-block; }
        @media print { .download-btn { display: none; } body { background: white; } .cert-container { border: 15px solid #eee; box-shadow: none; outline: none; } }
    </style>
</head>
<body>
    <div class="cert-container">
        <div class="cert-id">ID: <?php echo $cert_id; ?></div>
        <img src="images/viewfinder-logo.png" class="logo" alt="Viewfinder">
        <h1>Certificate</h1>
        <div class="award-line">of Digital Sovereignty Readiness</div>
        <p style="margin:0; font-size: 12px; text-transform: uppercase;">This is to certify that</p>
        <div class="participant-name"><?php echo $name; ?></div>
        <p style="font-size: 16px; line-height: 1.4; max-width: 80%; margin: 0 auto;">Demonstrated strategic understanding of digital independence and vendor autonomy across 7 infrastructure domains.</p>
        <div class="rank-box">Rank: <?php echo $level; ?> (<?php echo $score; ?>%)</div>
        <div class="footer"><div>Date: <?php echo $date; ?></div><div>Verified by Viewfinder Audit</div></div>
    </div>
    <button class="download-btn" onclick="window.print()">Download as PDF / Print</button>
</body>
</html>