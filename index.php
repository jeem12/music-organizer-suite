<?php
/**
 * AUDIO ARCHIVE MANAGER // ENTERPRISE EDITION
 * Engineering Lead: Automated Metadata Organization & Filename Refactoring
 * Developed by: JM ESCOBAR
 */

require_once('vendor/autoload.php');

$logs = [];
$isProcessing = false;

$userProfile = getenv('USERPROFILE'); 
$defaultSource = $userProfile ? $userProfile . '\Desktop\Unsorted' : 'D:\Music\Unsorted';
$defaultTarget = $userProfile ? $userProfile . '\Desktop\Organized' : 'D:\Music\Organized';

$currentSource = isset($_POST['source_path']) ? $_POST['source_path'] : $defaultSource;
$currentTarget = isset($_POST['target_path']) ? $_POST['target_path'] : $defaultTarget;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_process'])) {
    $isProcessing = true;
    $getID3 = new getID3;
    
    $sourceDir = $currentSource;
    $targetDir = $currentTarget;

    if (is_dir($sourceDir)) {
        if (!is_dir($targetDir)) {
            if (!@mkdir($targetDir, 0755, true)) {
                $logs[] = "CRITICAL: Could not create target directory.";
            }
        }

        try {
            $directory = new RecursiveDirectoryIterator($sourceDir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($directory);
            
            foreach ($files as $file) {
                if ($file->isDir()) continue;
                
                $filePath = $file->getRealPath();
                $fileInfo = @$getID3->analyze($filePath);

                $artist = !empty($fileInfo['tags']['id3v2']['artist'][0]) 
                          ? $fileInfo['tags']['id3v2']['artist'][0] 
                          : (!empty($fileInfo['tags']['id3v1']['artist'][0]) ? $fileInfo['tags']['id3v1']['artist'][0] : 'Unknown Artist');

                $safeArtistName = preg_replace('#[\\\/:\*\?"<>\|]#', '-', $artist);
                $safeArtistName = trim($safeArtistName) ?: "Unknown Artist";

                $artistPath = $targetDir . DIRECTORY_SEPARATOR . $safeArtistName;
                if (!is_dir($artistPath)) {
                    @mkdir($artistPath, 0755, true);
                }

                $originalFileName = $file->getFilename();
                
                if (stripos($originalFileName, $safeArtistName) === 0) {
                    $newFileName = $originalFileName;
                } else {
                    $newFileName = $safeArtistName . " - " . $originalFileName;
                }

                $destination = $artistPath . DIRECTORY_SEPARATOR . $newFileName;

                if (@rename($filePath, $destination)) {
                    $logs[] = "Processed: " . $newFileName;
                } else {
                    $logs[] = "IO Error: File '" . $originalFileName . "' is locked or in use.";
                }
            }
        } catch (Exception $e) {
            $logs[] = "Runtime Error: " . $e->getMessage();
        }
    } else {
        $logs[] = "Path Error: Directory not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>AUTOMATED MUSIC ORGANIZER</title>
    <style>
        :root { --primary:rgb(50, 156, 73); --primary-hover: #2e7d32; --bg: #f3f4f6; --card: #ffffff; --text-dark: #111827; --text-light: #6b7280; --border: #e5e7eb; }
        body { background-color: var(--bg); color: var(--text-dark); font-family: 'Inter', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .dashboard { width: 100%; max-width: 600px; background: var(--card); border-radius: 8px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); border: 1px solid var(--border); overflow: hidden; }
        .header { padding: 20px 32px; border-bottom: 1px solid var(--border); background: #fdfdfd; display: flex; align-items: center; gap: 12px; }
        .header img { width: 32px; height: 32px; border-radius: 50%; }
        .header h1 { font-size: 1.125rem; margin: 0; font-weight: 700; color: var(--text-dark); letter-spacing: -0.025em; }
        form { padding: 20px 32px; }
        .input-box { margin-bottom: 15px; }
        label { display: block; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; color: var(--text-light); margin-bottom: 8px; letter-spacing: 0.05em; }
        input[type="text"] { width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 4px; font-size: 0.875rem; box-sizing: border-box; background: #f9fafb; transition: border 0.2s; }
        input[type="text"]:focus { outline: none; border-color: var(--primary); background: #fff; }
        button { width: 100%; background: var(--primary); color: #fff; border: none; padding: 12px; border-radius: 4px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        button:hover { background: var(--primary-hover); }
        .terminal { background: #111827; margin: 0 32px 20px 32px; border-radius: 6px; padding: 16px; max-height: 180px; overflow-y: auto; border: 1px solid #374151; }
        .log { font-family: 'Consolas', monospace; font-size: 0.75rem; color: #d1d5db; margin-bottom: 4px; border-bottom: 1px solid #1f2937; padding-bottom: 2px; }
        .success-footer { text-align: center; font-size: 0.75rem; color: #059669; padding-bottom: 10px; font-weight: 600; }
        .credits-footer { background: #f9fafb; padding: 15px; border-top: 1px solid var(--border); text-align: center; }
        .credits-footer p { margin: 0; font-size: 0.7rem; color: var(--text-light); }
        .credits-footer strong { color: var(--text-dark); }
    </style>
</head>
<body>

<div class="dashboard">
    <div class="header">
        <img src="logo.png" alt="Logo">
        <h1>Automated Music Organizer</h1>
    </div>
    
    <form method="POST">
        <div class="input-box">
            <label>Source Path</label>
            <input type="text" name="source_path" value="<?= htmlspecialchars($currentSource) ?>" required>
        </div>
        <div class="input-box">
            <label>Target Path</label>
            <input type="text" name="target_path" value="<?= htmlspecialchars($currentTarget) ?>" required>
        </div>
        <button type="submit" name="run_process">Run Organizer Engine</button>
    </form>

    <?php if ($isProcessing): ?>
        <div class="terminal">
            <?php if (empty($logs)): ?>
                <div class="log">No action required or directory empty.</div>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                    <div class="log">> <?= htmlspecialchars($log) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="success-footer">ENGINE CYCLE COMPLETE</div>
    <?php endif; ?>

    <div class="credits-footer">
        <p>System developed by <strong>JM ESCOBAR</strong></p>
        <p>© <?= date('Y') ?> Music Management Suite v1.0.5</p>
    </div>
</div>

</body>
</html>