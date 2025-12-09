<?php
/**
 * Run Shipping System Migration
 * Execute this file to setup shipping & tracking features
 */

// Include database connection
require_once '../config/database.php';

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Shipping System Migration</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', -apple-system, sans-serif; 
            background: #F9FAFB; 
            padding: 40px 20px;
            color: #1F2937;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header { 
            background: #1F2937; 
            color: white; 
            padding: 24px; 
        }
        .header h1 { 
            font-size: 24px; 
            font-weight: 700; 
            margin-bottom: 8px;
        }
        .header p { 
            color: #D1D5DB; 
            font-size: 14px;
        }
        .content { 
            padding: 24px; 
        }
        .step { 
            padding: 16px; 
            margin-bottom: 12px; 
            border-radius: 8px; 
            border: 1px solid #E5E7EB;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .step-icon { 
            width: 32px; 
            height: 32px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: 700; 
            font-size: 14px;
            flex-shrink: 0;
        }
        .step-content { 
            flex: 1; 
        }
        .step-title { 
            font-weight: 600; 
            margin-bottom: 4px;
            font-size: 15px;
        }
        .step-desc { 
            color: #6B7280; 
            font-size: 13px;
            line-height: 1.5;
        }
        .success { 
            background: #D1FAE5; 
            border-color: #10B981;
        }
        .success .step-icon { 
            background: #10B981; 
            color: white;
        }
        .error { 
            background: #FEE2E2; 
            border-color: #EF4444;
        }
        .error .step-icon { 
            background: #EF4444; 
            color: white;
        }
        .pending { 
            background: #FEF3C7; 
            border-color: #F59E0B;
        }
        .pending .step-icon { 
            background: #F59E0B; 
            color: white;
        }
        .info { 
            background: #DBEAFE; 
            border: 1px solid #3B82F6; 
            padding: 16px; 
            border-radius: 8px; 
            margin-bottom: 20px;
        }
        .info-title { 
            font-weight: 600; 
            color: #1E40AF; 
            margin-bottom: 8px;
        }
        .info-text { 
            color: #1E40AF; 
            font-size: 14px; 
            line-height: 1.6;
        }
        .summary { 
            margin-top: 24px; 
            padding: 20px; 
            background: #F9FAFB; 
            border-radius: 8px;
        }
        .summary h3 { 
            font-size: 16px; 
            margin-bottom: 12px;
        }
        .summary-item { 
            display: flex; 
            justify-content: space-between; 
            padding: 8px 0; 
            border-bottom: 1px solid #E5E7EB;
        }
        .summary-item:last-child { 
            border-bottom: none;
        }
        .btn { 
            display: inline-block; 
            padding: 12px 24px; 
            background: #D97706; 
            color: white; 
            text-decoration: none; 
            border-radius: 8px; 
            font-weight: 600; 
            margin-top: 20px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover { 
            background: #B45309;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🚚 Shipping System Migration</h1>
            <p>Setting up shipping services and order tracking features</p>
        </div>
        <div class='content'>";

// Track results
$results = [
    'success' => 0,
    'errors' => 0,
    'warnings' => 0
];

// Read SQL file
$sql_file = 'add_shipping_system.sql';
if (!file_exists($sql_file)) {
    echo "<div class='step error'>
            <div class='step-icon'>✗</div>
            <div class='step-content'>
                <div class='step-title'>SQL File Not Found</div>
                <div class='step-desc'>File 'add_shipping_system.sql' tidak ditemukan!</div>
            </div>
          </div>";
    $results['errors']++;
} else {
    echo "<div class='step success'>
            <div class='step-icon'>✓</div>
            <div class='step-content'>
                <div class='step-title'>SQL File Found</div>
                <div class='step-desc'>File migration berhasil ditemukan</div>
            </div>
          </div>";
    $results['success']++;
    
    // Read and execute SQL
    $sql_content = file_get_contents($sql_file);
    
    // Remove BOM if present
    $sql_content = str_replace("\xEF\xBB\xBF", '', $sql_content);
    
    $statements = explode(';', $sql_content);
    
    $step_num = 1;
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement)) continue;
        
        // Get statement type
        $type = 'Query';
        if (stripos($statement, 'CREATE TABLE') !== false) {
            preg_match('/CREATE TABLE.*(\w+)/i', $statement, $matches);
            $type = 'Create Table: ' . ($matches[1] ?? 'Unknown');
        } elseif (stripos($statement, 'ALTER TABLE') !== false) {
            preg_match('/ALTER TABLE (\w+)/i', $statement, $matches);
            $type = 'Alter Table: ' . ($matches[1] ?? 'Unknown');
        } elseif (stripos($statement, 'INSERT INTO') !== false) {
            preg_match('/INSERT INTO ?(\w+)?/i', $statement, $matches);
            $type = 'Insert Data: ' . ($matches[1] ?? 'Unknown');
        } elseif (stripos($statement, 'CREATE INDEX') !== false) {
            $type = 'Create Index';
        }
        
        // Execute
        $result = @mysqli_query($GLOBALS['conn'], $statement);
        
        if ($result) {
            echo "<div class='step success'>
                    <div class='step-icon'>{$step_num}</div>
                    <div class='step-content'>
                        <div class='step-title'>{$type}</div>
                        <div class='step-desc'>Berhasil dieksekusi</div>
                    </div>
                  </div>";
            $results['success']++;
        } else {
            $error = mysqli_error($GLOBALS['conn']);
            // Check if it's just a duplicate/already exists error
            if (stripos($error, 'Duplicate') !== false || stripos($error, 'already exists') !== false) {
                echo "<div class='step pending'>
                        <div class='step-icon'>!</div>
                        <div class='step-content'>
                            <div class='step-title'>{$type}</div>
                            <div class='step-desc'>Sudah ada (skipped)</div>
                        </div>
                      </div>";
                $results['warnings']++;
            } else {
                echo "<div class='step error'>
                        <div class='step-icon'>✗</div>
                        <div class='step-content'>
                            <div class='step-title'>{$type}</div>
                            <div class='step-desc'>{$error}</div>
                        </div>
                      </div>";
                $results['errors']++;
            }
        }
        
        $step_num++;
    }
}

// Summary
echo "<div class='summary'>
        <h3>📊 Migration Summary</h3>
        <div class='summary-item'>
            <span>✅ Successful Operations</span>
            <strong>{$results['success']}</strong>
        </div>
        <div class='summary-item'>
            <span>⚠️ Warnings (Already Exists)</span>
            <strong>{$results['warnings']}</strong>
        </div>
        <div class='summary-item'>
            <span>❌ Errors</span>
            <strong>{$results['errors']}</strong>
        </div>
      </div>";

if ($results['errors'] == 0) {
    echo "<div class='info' style='margin-top: 20px; background: #D1FAE5; border-color: #10B981;'>
            <div class='info-title' style='color: #065F46;'>✓ Migration Completed Successfully!</div>
            <div class='info-text' style='color: #065F46;'>
                Database telah berhasil diupdate dengan fitur shipping & tracking. 
                Anda sekarang bisa:
                <ul style='margin-top: 8px; margin-left: 20px;'>
                    <li>Menambahkan layanan ekspedisi di checkout</li>
                    <li>Tracking order dengan timeline visual</li>
                    <li>Update status pengiriman dari admin panel</li>
                </ul>
            </div>
          </div>";
    echo "<a href='../admin/orders.php' class='btn'>Go to Admin Orders →</a>";
} else {
    echo "<div class='info' style='margin-top: 20px;'>
            <div class='info-title'>⚠️ Migration Completed with Errors</div>
            <div class='info-text'>
                Beberapa query gagal dieksekusi. Mohon periksa error di atas dan perbaiki secara manual.
            </div>
          </div>";
}

echo "      </div>
    </div>
</body>
</html>";
?>
