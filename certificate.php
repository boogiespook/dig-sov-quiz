<?php
$name = htmlspecialchars($_GET['name'] ?? 'Participant');
$score = htmlspecialchars($_GET['score'] ?? '0');
$level = htmlspecialchars($_GET['level'] ?? 'Foundation');
$ts = $_GET['ts'] ?? time();
$date = date('F d, Y');

// Handle Domain Data
$domain_ratings = [];
if (isset($_GET['domain_data'])) {
    $decoded = json_decode(base64_decode($_GET['domain_data']), true);
    if ($decoded) {
        foreach ($decoded as $title => $data) {
            $domain_ratings[$title] = $data['score'];
        }
        arsort($domain_ratings);
    }
}

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
        body { background: #1b1d21; font-family: 'Inter', sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px; }
        
        .cert-container { 
            background: white; padding: 40px; width: 850px; height: auto; min-height: 650px; 
            border: 15px solid #1b1d21; outline: 4px solid #4f46e5; position: relative; 
            text-align: center; color: #1b1d21; box-sizing: border-box;
        }

        .cert-id { position: absolute; top: 20px; right: 30px; font-family: monospace; font-size: 11px; color: #991b1b; }
        .logo { max-width: 110px; margin-bottom: 10px; }
        h1 { font-size: 32px; font-weight: 800; text-transform: uppercase; margin: 0; letter-spacing: 1px; }
        .award-line { font-family: 'Libre+Baskerville', serif; font-style: italic; font-size: 16px; margin: 5px 0; color: #4b5563; }
        .participant-name { font-size: 30px; font-weight: 800; color: #4f46e5; border-bottom: 2px solid #e5e7eb; display: inline-block; padding: 0 30px; margin: 10px 0; }
        
        /* New Stacked Layout */
        .profile-section { margin-top: 20px; text-align: left; background: #f9fafb; padding: 25px; border-radius: 12px; }
        
        .score-summary-box { display: flex; justify-content: space-around; align-items: center; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb; margin-bottom: 20px; }
        .score-stat { text-align: center; }
        .score-label { font-size: 10px; text-transform: uppercase; color: #6b7280; letter-spacing: 1px; margin-bottom: 5px; }
        .score-val { font-size: 28px; font-weight: 800; color: #1b1d21; line-height: 1; }
        .level-val { color: #10b981; }

        .chart-container { width: 100%; }
        .bar-row { display: flex; align-items: center; margin-bottom: 8px; font-size: 11px; }
        
        /* Width increased to 200px to prevent truncation */
        .bar-label { width: 200px; font-weight: 700; color: #374151; }
        
        .bar-bg { flex-grow: 1; background: #e5e7eb; height: 12px; border-radius: 6px; overflow: hidden; }
        .bar-fill { height: 100%; background: #4f46e5; border-radius: 6px; }
        .bar-val { width: 40px; text-align: right; font-weight: 800; color: #4f46e5; }

        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #eee; display: flex; justify-content: space-between; font-size: 11px; color: #6b7280; }
        .download-btn { margin-top: 25px; background: #4f46e5; color: white; padding: 12px 25px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; }
        
        @media print { .download-btn { display: none; } body { background: white; padding: 0; } .cert-container { border: 15px solid #eee; outline: none; box-shadow: none; } }
    </style>
</head>
<body>
    <div class="cert-container">
        <div class="cert-id">VERIFICATION ID: <?php echo $cert_id; ?></div>
        <img src="images/viewfinder-logo.png" class="logo" alt="Viewfinder">
        <h1>Certificate of Achievement</h1>
        <div class="award-line">Digital Sovereignty Proficiency</div>
        
        <div class="participant-name"><?php echo $name; ?></div>
        
        <div class="profile-section">
            <div class="score-summary-box">
                <div class="score-stat">
                    <div class="score-label">Readiness Level</div>
                    <div class="score-val level-val"><?php echo $level; ?></div>
                </div>
                <div class="score-stat">
                    <div class="score-label">Overall Proficiency</div>
                    <div class="score-val"><?php echo $score; ?>%</div>
                </div>
            </div>
            
            <div class="chart-container">
                <p class="score-label" style="text-align: center; margin-bottom: 15px;">Competency Profile Breakdown</p>
                <?php foreach ($domain_ratings as $title => $val): ?>
                <div class="bar-row">
                    <div class="bar-label"><?php echo strtoupper($title); ?></div>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width: <?php echo $val; ?>%;"></div>
                    </div>
                    <div class="bar-val"><?php echo $val; ?>%</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="footer">
            <div>Issue Date: <strong><?php echo $date; ?></strong></div>
            <div>Verified by <strong>Viewfinder Audit Division</strong></div>
        </div>
    </div>
    <button class="download-btn" onclick="window.print()">Download / Print Certificate</button>
</body>
</html>