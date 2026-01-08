<?php
/**
 * Viewfinder - Digital Sovereignty Quiz
 * Features: 21 Questions, Randomized Domains & Questions, Certificate Generation
 */

$domains = [
    "data" => [
        "title" => "Data Sovereignty",
        "questions" => [
            "d1" => ["s" => "A parent company's jurisdiction can override physical data location.", "a" => "true", "e" => "The US CLOUD Act is a prime example of extraterritorial legal reach."],
            "d2" => ["s" => "Provider-managed keys guarantee 100% data sovereignty.", "a" => "false", "e" => "Sole key-tenancy is required; if they have the keys, they have the power."],
            "d3" => ["s" => "Metadata (usage patterns, timestamps) is less sensitive than the data content itself.", "a" => "false", "e" => "Metadata can reveal as much about an organization's operations as the content."]
        ]
    ],
    "tech" => [
        "title" => "Technical Sovereignty",
        "questions" => [
            "t1" => ["s" => "Open standards can help reduce vendor lock-in.", "a" => "true", "e" => "Standards enable easier workload and data portability between providers."],
            "t2" => ["s" => "Proprietary 'Serverless' functions are easily portable.", "a" => "false", "e" => "These functions usually rely on provider-specific APIs that don't exist elsewhere."],
            "t3" => ["s" => "Using a 'Single-Pane-of-Glass' tool from a vendor reduces technical lock-in.", "a" => "false", "e" => "Proprietary management layers often become the most difficult thing to replace."]
        ]
    ],
    "ops" => [
        "title" => "Operational Sovereignty",
        "questions" => [
            "o1" => ["s" => "Sovereignty exists even if a vendor can unilaterally kill your account.", "a" => "false", "e" => "You must have control over your own service availability and kill-switches."],
            "o2" => ["s" => "Local support staff are a requirement for many sovereign clouds.", "a" => "true", "e" => "This ensures the operators are subject to local, rather than foreign, laws."],
            "o3" => ["s" => "Remote 'Emergency Access' by a vendor is acceptable in a sovereign environment.", "a" => "false", "e" => "Unsupervised remote access creates a massive 'backdoor' risk to sovereignty."]
        ]
    ],
    "assu" => [
        "title" => "Assurance Sovereignty",
        "questions" => [
            "a1" => ["s" => "Third-party audits are more reliable than vendor claims.", "a" => "true", "e" => "Independent verification is the gold standard for verifying security promises."],
            "a2" => ["s" => "You can verify integrity without source code access.", "a" => "false", "e" => "True assurance requires code-level transparency or reproducible binary builds."],
            "a3" => ["s" => "Regular penetration testing by an independent firm is a form of assurance sovereignty.", "a" => "true", "e" => "It provides neutral proof that the sovereignty controls actually work."]
        ]
    ],
    "oss" => [
        "title" => "Open Source",
        "questions" => [
            "os1" => ["s" => "OSS can be 'forked' if a vendor disappears.", "a" => "true", "e" => "This is the ultimate safety net; you can maintain the code yourself if needed."],
            "os2" => ["s" => "OSS software is 100% secure by default.", "a" => "false", "e" => "OSS provides the tools for security, but you must still patch and audit it."],
            "os3" => ["s" => "The ability to audit the source code is the primary sovereignty benefit of OSS.", "a" => "true", "e" => "Transparency prevents hidden 'telemetry' or backdoors from being installed."]
        ]
    ],
    "exec" => [
        "title" => "Executive Oversight",
        "questions" => [
            "e1" => ["s" => "Sovereignty risk belongs on the Corporate Risk Register.", "a" => "true", "e" => "It is a strategic business continuity and legal compliance risk."],
            "e2" => ["s" => "CEOs should worry about single-provider concentration.", "a" => "true", "e" => "Over-dependence on one vendor is a critical board-level vulnerability."],
            "e3" => ["s" => "Digital Sovereignty is primarily a 'cost-saving' initiative.", "a" => "false", "e" => "It is about risk management and autonomy; it may sometimes even increase costs."]
        ]
    ],
    "mng" => [
        "title" => "Managed Services",
        "questions" => [
            "m1" => ["s" => "Total outsourcing increases internal expertise.", "a" => "false", "e" => "It usually leads to 'brain drain,' making it harder to migrate later."],
            "m2" => ["s" => "Managed services can be used sovereignly.", "a" => "true", "e" => "Requires strong exit strategies, data escrow, and localized contracts."],
            "m3" => ["s" => "Managed services often obscure the 'Supply Chain' risk of underlying components.", "a" => "true", "e" => "Sovereignty requires knowing which sub-processors are being used by your provider."]
        ]
    ]
];

/** * RANDOMIZATION LOGIC 
 */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // 1. Shuffle the Questions within each domain
    foreach ($domains as $key => $domain) {
        $q_keys = array_keys($domain['questions']);
        shuffle($q_keys);
        $randomizedQ = [];
        foreach ($q_keys as $k) {
            $randomizedQ[$k] = $domain['questions'][$k];
        }
        $domains[$key]['questions'] = $randomizedQ;
    }
    
    // 2. Shuffle the Domains themselves
    $d_keys = array_keys($domains);
    shuffle($d_keys);
    $randomizedD = [];
    foreach ($d_keys as $dk) {
        $randomizedD[$dk] = $domains[$dk];
    }
    $domains = $randomizedD;
}

$results = null;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $results = []; $total_q = 0; $total_correct = 0;
    foreach ($domains as $dKey => $dData) {
        $d_correct = 0; $d_total = 0;
        foreach ($dData['questions'] as $qKey => $qData) {
            $uAns = $_POST[$qKey] ?? '';
            $isCorrect = ($uAns === $qData['a']);
            if ($isCorrect) { $total_correct++; $d_correct++; }
            $total_q++; $d_total++;
            $results[$dData['title']]['items'][] = ["is_correct" => $isCorrect, "exp" => $qData['e'], "s" => $qData['s']];
        }
        $results[$dData['title']]['score'] = round(($d_correct / $d_total) * 100);
    }
    $final_score = round(($total_correct / $total_q) * 100);

    if ($final_score <= 33) { $readiness = ["Level" => "Foundation", "Icon" => "🏗️", "Color" => "#ef4444"]; }
    elseif ($final_score <= 66) { $readiness = ["Level" => "Strategic", "Icon" => "📈", "Color" => "#f59e0b"]; }
    else { $readiness = ["Level" => "Advanced", "Icon" => "🚀", "Color" => "#10b981"]; }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viewfinder | Sovereignty Quiz</title>
    <style>
        :root { --bg: #1b1d21; --card: #f1f1f1; --primary: #4f46e5; --text-main: #111827; --text-muted: #4b5563; --success: #10b981; --error: #ef4444; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg); height: 100vh; margin: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; overflow: hidden; }
        .external-logo { margin-bottom: 20px; max-width: 200px; }
        .external-logo img { width: 100%; height: auto; display: block; }
        .app-container { width: 95%; max-width: 650px; background-color: var(--card); border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); padding: 30px; color: var(--text-main); box-sizing: border-box; }
        .step { display: none; }
        .step.active { display: block; animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .progress-dots { display: flex; gap: 8px; justify-content: center; margin-bottom: 15px; }
        .dot { width: 8px; height: 8px; border-radius: 50%; background: #d1d5db; transition: all 0.3s; }
        .dot.active { background: var(--primary); transform: scale(1.3); }
        .q-text { font-weight: 600; margin-bottom: 8px; font-size: 0.95rem; color: var(--text-main); }
        .btn-group { display: flex; gap: 10px; margin-bottom: 15px; }
        .btn-group input { display: none; }
        .btn-group label { flex: 1; text-align: center; padding: 12px; border: 2px solid #e5e7eb; border-radius: 10px; cursor: pointer; font-weight: 700; background: white; color: var(--text-muted); transition: 0.2s; }
        .btn-group input:checked + label { background: var(--primary); color: white; border-color: var(--primary); }
        .nav-btns { display: flex; justify-content: space-between; margin-top: 5px; }
        button { padding: 10px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: 700; }
        .btn-next { background: var(--primary); color: white; }
        .btn-prev { background: #e5e7eb; color: var(--text-muted); }
        .results-header { text-align: center; margin-bottom: 10px; }
        .score-row { display: flex; align-items: center; justify-content: center; gap: 15px; }
        .score-circle { font-size: 2.5rem; font-weight: 800; color: var(--primary); }
        .readiness-badge { padding: 6px 14px; border-radius: 10px; color: white; font-weight: 800; }
        .results-scroll { max-height: 180px; overflow-y: auto; padding-right: 10px; margin-top: 10px; border-top: 1px solid #e5e7eb; }
        .res-card { padding: 8px; border-radius: 8px; margin-top: 8px; font-size: 0.75rem; border-left: 4px solid; background: white; }
        .cert-section { margin-top: 15px; padding: 15px; background: white; border-radius: 12px; border: 1px dashed var(--primary); text-align: center; }
    </style>
</head>
<body>

<div class="external-logo">
    <img src="images/viewfinder-logo.png" alt="Viewfinder Logo">
</div>

<div class="app-container">
    <?php if ($results === null): ?>
        <form id="quizForm" method="POST">
            <div class="progress-dots"><?php $i=0; foreach($domains as $d): ?><div class="dot <?php echo $i==0?'active':''; ?>" id="dot_<?php echo $i++; ?>"></div><?php endforeach; ?></div>
            <?php $step=0; foreach ($domains as $dKey => $dData): ?>
                <div class="step <?php echo $step==0?'active':''; ?>" id="step_<?php echo $step; ?>">
                    <h2 style="margin:0; color:var(--primary); text-align:center; font-size: 1.2rem;"><?php echo $dData['title']; ?></h2>
                    <p style="font-size: 0.75rem; color:var(--text-muted); text-align:center; margin-bottom: 15px;">Domain <?php echo $step+1; ?> of 7</p>
                    <?php foreach ($dData['questions'] as $qKey => $qData): ?>
                        <div class="q-text"><?php echo $qData['s']; ?></div>
                        <div class="btn-group">
                            <input type="radio" id="<?php echo $qKey; ?>_t" name="<?php echo $qKey; ?>" value="true"><label for="<?php echo $qKey; ?>_t">TRUE</label>
                            <input type="radio" id="<?php echo $qKey; ?>_f" name="<?php echo $qKey; ?>" value="false"><label for="<?php echo $qKey; ?>_f">FALSE</label>
                        </div>
                    <?php endforeach; ?>
                    <div class="nav-btns"><?php if($step > 0): ?><button type="button" class="btn-prev" onclick="changeStep(<?php echo $step-1; ?>)">Back</button><?php else: ?><div></div><?php endif; ?>
                    <?php if($step < 6): ?><button type="button" class="btn-next" onclick="changeStep(<?php echo $step+1; ?>)">Next</button><?php else: ?><button type="submit" class="btn-next" style="background: var(--success);">Get Results</button><?php endif; ?></div>
                </div>
            <?php $step++; endforeach; ?>
        </form>
    <?php else: ?>
        <div class="results-header">
            <div class="score-row">
                <div class="score-circle"><?php echo $final_score; ?>%</div>
                <div class="readiness-badge" style="background: <?php echo $readiness['Color']; ?>;"><?php echo $readiness['Icon']; ?> <?php echo $readiness['Level']; ?></div>
            </div>
        </div>
        <div class="results-scroll">
            <?php foreach ($results as $title => $data): ?>
                <div style="margin-top: 10px;"><h4 style="margin:0; font-size: 0.8rem; color: var(--primary);"><?php echo $title; ?> (<?php echo $data['score']; ?>%)</h4>
                <?php foreach ($data['items'] as $item): ?><div class="res-card <?php echo $item['is_correct']?'':'wrong'; ?>" style="border-color:<?php echo $item['is_correct']?'var(--success)':'var(--error)';?>"><strong><?php echo $item['is_correct']?'✓':'✗'; ?></strong> <?php echo $item['exp']; ?></div><?php endforeach; ?></div>
            <?php endforeach; ?>
        </div>
        <div class="cert-section">
            <form action="certificate.php" method="GET" target="_blank">
                <input type="hidden" name="score" value="<?php echo $final_score; ?>"><input type="hidden" name="level" value="<?php echo $readiness['Level']; ?>"><input type="hidden" name="ts" value="<?php echo time(); ?>">
                <input type="text" name="name" placeholder="Enter name for certificate" required style="padding: 8px; border: 1px solid #ddd; border-radius: 6px; width: 60%; font-size: 0.8rem;">
                <button type="submit" style="background: var(--primary); color: white; padding: 8px 15px; font-size: 0.8rem;">Get Certificate</button>
            </form>
        </div>
        <button onclick="window.location.href='index.php'" class="btn-next" style="width:100%; margin-top: 10px; font-size: 0.8rem;">Restart Quiz</button>
    <?php endif; ?>
</div>

<script>
function changeStep(s) {
    const currentStep = document.querySelector('.step.active');
    const radios = currentStep.querySelectorAll('input[type="radio"]');
    const names = [...new Set([...radios].map(r => r.name))];
    let ok = true;
    if(s > parseInt(currentStep.id.split('_')[1])) { names.forEach(n => { if(!document.querySelector(`input[name="${n}"]:checked`)) ok = false; }); }
    if(!ok) { alert("Please answer all questions first!"); return; }
    document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.dot').forEach(el => el.classList.remove('active'));
    document.getElementById('step_' + s).classList.add('active');
    document.getElementById('dot_' + s).classList.add('active');
}
</script>
</body>
</html>