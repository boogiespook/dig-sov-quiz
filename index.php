<?php
session_start();
/**
 * Viewfinder - Digital Sovereignty Quiz
 * Features: Knowledge-focused text, Animated CTA, Technical Labels, Wide Results, Certification Note
 */

// Reset Logic
if (isset($_GET['reset'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$domains = [
    "data" => [
        "title" => "Data Sovereignty",
        "questions" => [
            "d1" => ["s" => "A foreign cloud provider's home government can legally demand access to your data, even if it is stored locally.", "a" => "true", "h" => "Consider extraterritorial laws like the US CLOUD Act.", "e" => "Laws like the US CLOUD Act allow governments to compel access to data held by their companies regardless of where the server sits."],
            "d2" => ["s" => "Provider-managed keys guarantee 100% data sovereignty.", "a" => "false", "h" => "Who really holds the master key in this scenario?", "e" => "Sole key-tenancy is required; if they have the keys, they have the power."],
            "d3" => ["s" => "Metadata (usage patterns, timestamps) is less sensitive than the data content itself.", "a" => "false", "h" => "Can someone reconstruct your behaviour just from timestamps?", "e" => "Metadata can reveal as much about an organisation's operations as the content."]
        ]
    ],
    "tech" => [
        "title" => "Technical Sovereignty",
        "questions" => [
            "t1" => ["s" => "Open standards can help reduce vendor lock-in.", "a" => "true", "h" => "Think about how easily you could move this data to another host.", "e" => "Standards enable easier workload and data portability between providers."],
            "t2" => ["s" => "Proprietary 'Serverless' functions are easily portable.", "a" => "false", "h" => "Do these functions exist outside of the provider's specific ecosystem?", "e" => "These functions usually rely on provider-specific APIs that don't exist elsewhere."],
            "t3" => ["s" => "Using a 'Single-Pane-of-Glass' tool from a vendor reduces technical lock-in.", "a" => "false", "h" => "Does this tool make it easier or harder to leave the ecosystem?", "e" => "Proprietary management layers often become the most difficult thing to replace."]
        ]
    ],
    "ops" => [
        "title" => "Operational Sovereignty",
        "questions" => [
            "o1" => ["s" => "Sovereignty exists even if a vendor can unilaterally cancel your account.", "a" => "false", "h" => "If someone else holds the kill-switch, do you have autonomy?", "e" => "You must have control over your own service availability and kill-switches."],
            "o2" => ["s" => "Local support staff are a requirement for many sovereign clouds.", "a" => "true", "h" => "Consider whose laws the actual human workers must follow.", "e" => "This ensures the operators are subject to local, rather than foreign, laws."],
            "o3" => ["s" => "Remote 'Emergency Access' by a vendor is acceptable in a sovereign environment.", "a" => "false", "h" => "Could this access be used without your supervision?", "e" => "Unsupervised remote access creates a massive 'backdoor' risk to sovereignty."]
        ]
    ],
    "assu" => [
        "title" => "Assurance Sovereignty",
        "questions" => [
            "a1" => ["s" => "Third-party audits are more reliable than vendor claims.", "a" => "true", "h" => "Is it better to take their word or see independent proof?", "e" => "Independent verification is the gold standard for verifying security promises."],
            "a2" => ["s" => "You can verify integrity without source code access.", "a" => "false", "h" => "Can you be 100% sure what is 'under the bonnet' if you can't see it?", "e" => "True assurance requires code-level transparency or reproducible binary builds."],
            "a3" => ["s" => "Regular penetration testing by an independent firm is a form of assurance sovereignty.", "a" => "true", "h" => "Does this provide neutral evidence of security?", "e" => "It provides neutral proof that the sovereignty controls actually work."]
        ]
    ],
    "oss" => [
        "title" => "Open Source",
        "questions" => [
            "os1" => ["s" => "OSS can be 'forked' if a vendor disappears.", "a" => "true", "h" => "Can you take the code and run it yourself if the company dies?", "e" => "This is the ultimate safety net; you can maintain the code yourself if needed."],
            "os2" => ["s" => "OSS software is 100% secure by default.", "a" => "false", "h" => "Does having the code mean it was written perfectly?", "e" => "OSS provides the tools for security, but you must still patch and audit it."],
            "os3" => ["s" => "The ability to audit the source code is the primary sovereignty benefit of OSS.", "a" => "true", "h" => "Think about finding hidden 'backdoors'.", "e" => "Transparency prevents hidden 'telemetry' or backdoors from being installed."]
        ]
    ],
    "exec" => [
        "title" => "Executive Oversight",
        "questions" => [
            "e1" => ["s" => "Sovereignty risk belongs on the Corporate Risk Register.", "a" => "true", "h" => "Is this a technical problem or a business continuity problem?", "e" => "It is a strategic business continuity and legal compliance risk."],
            "e2" => ["s" => "CEOs should worry about single-provider concentration.", "a" => "true", "h" => "What happens if your only provider has a major outage?", "e" => "Over-dependence on one vendor is a critical board-level vulnerability."],
            "e3" => ["s" => "Digital Sovereignty is primarily a 'cost-saving' initiative.", "a" => "false", "h" => "Is the goal spending less money or having more control?", "e" => "It is about risk management and autonomy; it may sometimes even increase costs."]
        ]
    ],
    "mng" => [
        "title" => "Managed Services",
        "questions" => [
            "m1" => ["s" => "Total outsourcing increases internal expertise.", "a" => "false", "h" => "If you pay others to do the work, do your staff learn how to do it?", "e" => "It usually leads to 'brain drain,' making it harder to migrate later."],
            "m2" => ["s" => "Managed services can be used sovereignly.", "a" => "true", "h" => "Can a managed service be sovereign if the contract is right?", "e" => "Requires strong exit strategies, data escrow, and localised contracts."],
            "m3" => ["s" => "Managed services often obscure the 'Supply Chain' risk of underlying components.", "a" => "true", "h" => "Do you always know which 'sub-providers' your vendor uses?", "e" => "Sovereignty requires knowing which sub-processors are being used by your provider."]
        ]
    ]
];

// Randomisation logic
if (!isset($_POST['submit_quiz'])) {
    foreach ($domains as $key => $domain) {
        $q_keys = array_keys($domain['questions']); shuffle($q_keys);
        $randQ = []; foreach ($q_keys as $k) { $randQ[$k] = $domain['questions'][$k]; }
        $domains[$key]['questions'] = $randQ;
    }
    $d_keys = array_keys($domains); shuffle($d_keys);
    $randD = []; foreach ($d_keys as $dk) { $randD[$dk] = $domains[$dk]; }
    $domains = $randD;
}

$results = null; $best_domains = [];
if (isset($_POST['submit_quiz'])) {
    $results = []; $total_q = 0; $total_correct = 0; $max_score = -1;
    foreach ($domains as $dKey => $dData) {
        $d_correct = 0; $d_total = 0;
        foreach ($dData['questions'] as $qKey => $qData) {
            $uAns = $_POST[$qKey] ?? ''; $isCorrect = ($uAns === $qData['a']);
            if ($isCorrect) { $total_correct++; $d_correct++; }
            $total_q++; $d_total++;
            $results[$dData['title']]['items'][] = ["is_correct" => $isCorrect, "exp" => $qData['e'], "s" => $qData['s']];
        }
        $d_perc = round(($d_correct / $d_total) * 100); $results[$dData['title']]['score'] = $d_perc;
        if ($d_perc > $max_score) { $max_score = $d_perc; $best_domains = [$dData['title']]; }
        elseif ($d_perc == $max_score && $max_score > 0) { $best_domains[] = $dData['title']; }
    }
    $final_score = round(($total_correct / $total_q) * 100);
    if ($final_score <= 33) { $readiness = ["Level" => "Foundation", "Color" => "#ef4444", "Icon" => "🏗️"]; }
    elseif ($final_score <= 66) { $readiness = ["Level" => "Strategic", "Color" => "#f59e0b", "Icon" => "📈"]; }
    else { $readiness = ["Level" => "Advanced", "Color" => "#10b981", "Icon" => "🚀"]; }
}
?>
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viewfinder | Sovereignty Quiz</title>
    <style>
        :root { --bg: #1b1d21; --card: #f1f1f1; --primary: #4f46e5; --text-main: #111827; --text-muted: #4b5563; --success: #10b981; --error: #ef4444; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg); height: 100vh; margin: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; overflow: hidden; }
        .external-logo { margin-bottom: 10px; max-width: 220px; }
        .external-logo img { width: 100%; height: auto; display: block; }
        
        .app-container { width: 95%; max-width: 650px; background-color: var(--card); border-radius: 24px; padding: 30px; color: var(--text-main); box-sizing: border-box; box-shadow: 0 20px 50px rgba(0,0,0,0.5); position: relative; overflow: hidden; min-height: 550px; transition: max-width 0.5s ease-in-out; }
        .results-view { max-width: 850px; }

        .view-content { width: 100%; transition: 0.4s ease-in-out; }
        .hidden-view { display: none; opacity: 0; transform: translateX(50px); }
        .slide-left-out { opacity: 0; transform: translateX(-100px); }
        .slide-right-out { opacity: 0; transform: translateX(100px); }

        .landing-view h1 { color: var(--primary); font-size: 1.6rem; margin: 0 0 10px 0; }
        .landing-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; text-align: left; margin: 15px 0; }
        .landing-item { background: white; padding: 10px; border-radius: 12px; font-size: 0.78rem; border: 1px solid #e5e7eb; }
        .landing-item b { color: var(--primary); display: block; margin-bottom: 4px; }
        .domain-label { background: #ffffff; color: var(--text-main); padding: 5px 10px; border-radius: 4px; font-size: 0.72rem; font-weight: 600; border: 1px solid #e5e7eb; border-left: 4px solid var(--primary); white-space: nowrap; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }

        .btn-primary { background: var(--primary); color: white; padding: 12px 30px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; text-align: center; position: relative; transition: padding-right 0.3s; }
        .btn-primary:hover { padding-right: 45px; }
        .btn-primary:hover::after { content: "→"; position: absolute; right: 15px; opacity: 1; animation: arrowBounce 0.5s infinite alternate; }
        @keyframes arrowBounce { from { transform: translateX(0); } to { transform: translateX(5px); } }

        /* Quiz UI */
        .step { display: none; opacity: 0; transition: 0.4s ease-in-out; }
        .step.active { display: block; opacity: 1; transform: translateX(0); }
        .progress-dots { display: flex; gap: 8px; justify-content: center; margin-bottom: 15px; }
        .dot { width: 8px; height: 8px; border-radius: 50%; background: #d1d5db; transition: 0.3s; }
        .dot.active { background: var(--primary); transform: scale(1.3); }

        .q-row { position: relative; margin-bottom: 12px; }
        .q-text { font-weight: 600; font-size: 0.9rem; color: var(--text-main); }
        .hint-trigger { display: inline-block; font-size: 0.75rem; color: var(--primary); cursor: help; font-weight: 700; margin-left: 5px; text-decoration: underline; }
        .q-hint-box { visibility: hidden; width: 220px; background-color: #333; color: #fff; text-align: center; border-radius: 6px; padding: 8px; position: absolute; z-index: 10; bottom: 110%; left: 50%; transform: translateX(-50%); opacity: 0; transition: 0.2s; font-size: 0.75rem; pointer-events: none; }
        .hint-trigger:hover + .q-hint-box { visibility: visible; opacity: 1; }

        .btn-group { display: flex; gap: 8px; margin-top: 6px; }
        .btn-group input { display: none; }
        .btn-group label { flex: 1; text-align: center; padding: 10px; border: 2px solid #e5e7eb; border-radius: 10px; cursor: pointer; font-weight: 700; background: white; font-size: 0.85rem; transition: 0.2s; }
        .btn-group input:checked + label { background: var(--primary); color: white; border-color: var(--primary); }
        
        .nav-btns { display: flex; justify-content: space-between; margin-top: 15px; }
    </style>
</head>
<body>

<div class="external-logo"><img src="images/viewfinder-logo.png" alt="Viewfinder"></div>

<div id="main-container" class="app-container <?php echo isset($_POST['submit_quiz']) ? 'results-view' : ''; ?>">
    
    <?php if (!isset($_POST['submit_quiz'])): ?>
    <div id="view-landing" class="view-content" style="text-align: center;">
        <h1>Sovereignty Readiness Assessment</h1>
        <p style="color:var(--text-muted); line-height:1.5; font-size:0.9rem;">Evaluate your knowledge on digital independence. This 7-domain assessment identifies your current knowledge on the reliance on external providers and your understanding of technical autonomy.</p>
        
        <div class="landing-grid">
            <div class="landing-item" style="grid-column: span 2;">
                <b>7 Critical Domains</b>
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; justify-content: center;">
                    <span class="domain-label">Managed Services</span>
                    <span class="domain-label">Data Sovereignty</span>
                    <span class="domain-label">Operational Sovereignty</span>
                    <span class="domain-label">Technical Sovereignty</span>
                    <span class="domain-label">Assurance Sovereignty</span>
                    <span class="domain-label">Executive Oversight</span>
                    <span class="domain-label">Open Source Software</span>
                </div>
            </div>
            <div class="landing-item"><b>21 Indicators</b> Precise indicators designed to test strategic resilience.</div>
            <div class="landing-item"><b>Instant Profile</b> Real-time competency breakdown and rank categorisation.</div>
            <div class="landing-item" style="grid-column: span 2;">
                <b>Certification</b>
                When completed, you can download and share your official certificate of proficiency.
            </div>
        </div>

        <div style="margin-top: 20px;">
            <button type="button" class="btn-primary" onclick="startQuiz()">Begin Assessment</button>
        </div>
        <p style="font-size: 0.7rem; color: #9ca3af; margin-top: 12px; font-style: italic;">Privacy Note: Your responses are processed locally. No personal data is stored.</p>
    </div>

    <div id="view-quiz" class="view-content hidden-view">
        <form id="quizForm" method="POST">
            <div class="progress-dots"><?php $i=0; foreach($domains as $d): ?><div class="dot <?php echo $i==0?'active':''; ?>" id="dot_<?php echo $i++; ?>"></div><?php endforeach; ?></div>
            <?php $step=0; foreach ($domains as $dKey => $dData): ?>
                <div class="step <?php echo $step==0?'active':''; ?>" id="step_<?php echo $step; ?>">
                    <h2 style="margin:0 0 10px 0; color:var(--primary); text-align:center; font-size:1.1rem; text-transform:uppercase;"><?php echo $dData['title']; ?></h2>
                    <?php foreach ($dData['questions'] as $qKey => $qData): ?>
                        <div class="q-row">
                            <span class="q-text"><?php echo $qData['s']; ?></span>
                            <span class="hint-trigger">(Hint)</span>
                            <span class="q-hint-box"><?php echo $qData['h']; ?></span>
                            <div class="btn-group">
                                <input type="radio" id="<?php echo $qKey; ?>_t" name="<?php echo $qKey; ?>" value="true"><label for="<?php echo $qKey; ?>_t">TRUE</label>
                                <input type="radio" id="<?php echo $qKey; ?>_f" name="<?php echo $qKey; ?>" value="false"><label for="<?php echo $qKey; ?>_f">FALSE</label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="nav-btns">
                        <?php if($step > 0): ?>
                            <button type="button" onclick="changeStep(<?php echo $step-1; ?>, 'back')" style="background:#e5e7eb; color:var(--text-muted); border-radius:8px; padding:10px 20px; border:none; cursor:pointer; font-weight:700;">Back</button>
                        <?php else: ?><div></div><?php endif; ?>
                        
                        <?php if($step < 6): ?>
                            <button type="button" class="btn-primary" onclick="changeStep(<?php echo $step+1; ?>, 'next')" style="padding:10px 24px;">Next</button>
                        <?php else: ?>
                            <button type="submit" name="submit_quiz" class="btn-primary" style="background:var(--success); padding:10px 24px;">Get Results</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php $step++; endforeach; ?>
        </form>
    </div>
    <?php endif; ?>

    <?php if (isset($_POST['submit_quiz'])): ?>
    <div class="view-content" style="opacity:1; text-align:center;">
        <div style="font-size:2rem; font-weight:800; color:var(--primary)"><?php echo $final_score; ?>%</div>
        <div style="background:<?php echo $readiness['Color']; ?>; color:white; display:inline-block; padding:5px 15px; border-radius:10px; font-weight:800; margin-top:5px;"><?php echo $readiness['Icon']; ?> <?php echo $readiness['Level']; ?></div>
        
        <?php if(!empty($best_domains)): ?><p style="font-size:0.8rem; margin:10px 0; color: #4338ca; background: #e0e7ff; padding: 5px; border-radius: 8px;">⭐ <strong>Core Strength:</strong> <?php echo implode(", ", $best_domains); ?></p><?php endif; ?>

        <div style="max-height:220px; overflow-y:auto; margin-top:15px; border-top:1px solid #e5e7eb; text-align:left; padding-right:5px;">
            <?php foreach ($results as $title => $data): ?>
                <div style="font-weight:800; font-size:0.75rem; color:var(--primary); margin-top:10px; text-transform:uppercase;"><?php echo $title; ?></div>
                <?php foreach ($data['items'] as $item): ?>
                    <div style="padding:10px; border-radius:10px; margin-top:8px; font-size:0.75rem; border-left:5px solid <?php echo $item['is_correct']?'var(--success)':'var(--error)';?>; background:white;">
                        <div style="font-style:italic; color:#666;">"<?php echo $item['s']; ?>"</div>
                        <strong><?php echo $item['is_correct']?'✓':'✗'; ?></strong> <?php echo $item['exp']; ?>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>

        <div style="margin-top:15px; padding:15px; background:white; border-radius:12px; border:1px dashed var(--primary); text-align:center;">
            <form action="certificate.php" method="GET" target="_blank">
                <input type="hidden" name="score" value="<?php echo $final_score; ?>"><input type="hidden" name="level" value="<?php echo $readiness['Level']; ?>"><input type="hidden" name="ts" value="<?php echo time(); ?>">
                <input type="hidden" name="domain_data" value="<?php echo base64_encode(json_encode($results)); ?>">
                <input type="text" name="name" placeholder="Full Name for Certificate" required style="width:55%; padding:8px; border-radius:6px; border:1px solid #ddd;">
                <button type="submit" class="btn-primary" style="padding:8px 15px; font-size:0.85rem;">Certificate</button>
            </form>
        </div>
        <button onclick="window.location.href='index.php?reset=1'" style="width:100%; margin-top:10px; background:transparent; color:#6b7280; border:2px solid #d1d5db; padding:10px; border-radius:8px; cursor:pointer; font-weight:700;">Restart Quiz</button>
    <?php endif; ?>
</div>

<script>
function startQuiz() {
    const landing = document.getElementById('view-landing');
    const quiz = document.getElementById('view-quiz');
    landing.classList.add('slide-left-out');
    setTimeout(() => {
        landing.style.display = 'none';
        quiz.style.display = 'block';
        setTimeout(() => quiz.classList.remove('hidden-view'), 50);
    }, 400);
}

function changeStep(s, direction) {
    const active = document.querySelector('.step.active');
    if(direction === 'next') {
        const inputs = active.querySelectorAll('input[type="radio"]');
        const names = [...new Set([...inputs].map(i => i.name))];
        let ok = true;
        names.forEach(n => { if(!document.querySelector(`input[name="${n}"]:checked`)) ok = false; });
        if(!ok) { alert("Please answer all questions before proceeding!"); return; }
        active.classList.add('slide-left-out');
    } else {
        active.classList.add('slide-right-out');
    }
    setTimeout(() => {
        document.querySelectorAll('.step').forEach(el => { el.classList.remove('active', 'slide-left-out', 'slide-right-out'); el.style.opacity = '0'; });
        document.querySelectorAll('.dot').forEach(el => el.classList.remove('active'));
        const nextStep = document.getElementById('step_' + s);
        nextStep.classList.add('active');
        nextStep.style.opacity = '1';
        document.getElementById('dot_' + s).classList.add('active');
    }, 300);
}
</script>
</body>
</html>