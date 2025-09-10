<?php
require_once '../app/core/init.php';

// Check if user is logged in
if (!Auth::logged_in()) {
    header('Location: ../index.php');
    exit;
}

// Get date range for analytics (default to last 30 days)
$date_from = $_GET['date_from'] ?? date('Y-m-d', strtotime('-30 days'));
$date_to = $_GET['date_to'] ?? date('Y-m-d');

// Get analytics data
$analytics_data = getAnalyticsData($date_from, $date_to);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Analytics Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #2a4dca;
            --secondary: #6c757d;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
            --light: #f8f9fa;
            --dark: #343a40;
            --dark-text: #2c3e50;
            --border-radius: 8px;
            --box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
            color: var(--dark-text);
            line-height: 1.6;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1e3a8a 100%);
            color: white;
            padding: 20px 0;
            position: relative;
            min-height: 50px;
        }

        .page-title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
        }

        .back-link {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            background-color: rgba(255, 255, 255, 0.1);
            transition: background-color 0.2s;
            white-space: nowrap;
        }

        .back-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .date-filter {
            background: white;
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 25px;
        }

        .date-filter form {
            display: flex;
            gap: 15px;
            align-items: end;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            min-width: 150px;
        }

        .filter-group label {
            font-weight: 500;
            margin-bottom: 5px;
            color: var(--dark-text);
        }

        .filter-group input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .btn-filter {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }

        .btn-filter:hover {
            background-color: #2a4dca;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            text-align: center;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--secondary);
            font-size: 0.9rem;
        }

        .stat-card.success .stat-icon { color: var(--success); }
        .stat-card.danger .stat-icon { color: var(--danger); }
        .stat-card.info .stat-icon { color: var(--info); }
        .stat-card.warning .stat-icon { color: var(--warning); }

        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark-text);
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .table-card {
            background: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--dark-text);
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-success {
            background-color: #d4edda;
            color: #155724;
        }

        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: center;
                gap: 15px;
                padding-top: 15px;
            }

            .back-link {
                position: static;
                transform: none;
                margin-bottom: 10px;
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

            .date-filter form {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-chart-line"></i> SMS Analytics Dashboard</h1>
        <a href="receipt_debug.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to SMS Logs</a>
    </div>

    <div class="container">
        <!-- Date Filter -->
        <div class="date-filter">
            <form method="GET" action="sms_analytics.php">
                <div class="filter-group">
                    <label for="date_from">Date From</label>
                    <input type="date" id="date_from" name="date_from" value="<?= htmlspecialchars($date_from) ?>">
                </div>
                <div class="filter-group">
                    <label for="date_to">Date To</label>
                    <input type="date" id="date_to" name="date_to" value="<?= htmlspecialchars($date_to) ?>">
                </div>
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i> Update Analytics
                </button>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card success">
                <div class="stat-icon"><i class="fas fa-paper-plane"></i></div>
                <div class="stat-number"><?= $analytics_data['total_sms'] ?></div>
                <div class="stat-label">Total SMS Sent</div>
            </div>
            <div class="stat-card success">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-number"><?= $analytics_data['successful_sms'] ?></div>
                <div class="stat-label">Successful SMS</div>
            </div>
            <div class="stat-card danger">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-number"><?= $analytics_data['failed_sms'] ?></div>
                <div class="stat-label">Failed SMS</div>
            </div>
            <div class="stat-card info">
                <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-number">K<?= number_format($analytics_data['total_cost'], 2) ?></div>
                <div class="stat-label">Total SMS Cost</div>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <div class="chart-card">
                <h3 class="chart-title">SMS Sent Per Day</h3>
                <div class="chart-container">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <h3 class="chart-title">Success vs Failed Rate</h3>
                <div class="chart-container">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Failed SMS -->
        <div class="table-card">
            <h3 class="chart-title">Recent Failed SMS (Last 10)</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Phone</th>
                            <th>Receipt No</th>
                            <th>Retry Count</th>
                            <th>Sent By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($analytics_data['recent_failed'] as $failed): ?>
                        <tr>
                            <td><?= htmlspecialchars($failed['date_sent']) ?></td>
                            <td><?= htmlspecialchars($failed['phone']) ?></td>
                            <td><?= htmlspecialchars($failed['receipt_no']) ?></td>
                            <td><?= isset($failed['retry_count']) ? $failed['retry_count'] : 0 ?></td>
                            <td><?= htmlspecialchars(isset($failed['sent_by_user']) ? $failed['sent_by_user'] : 'System') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Daily SMS Chart
        const dailyCtx = document.getElementById('dailyChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($analytics_data['daily_labels']) ?>,
                datasets: [{
                    label: 'SMS Sent',
                    data: <?= json_encode($analytics_data['daily_data']) ?>,
                    borderColor: '#2a4dca',
                    backgroundColor: 'rgba(42, 77, 202, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Success vs Failed Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Success', 'Failed'],
                datasets: [{
                    data: [<?= $analytics_data['successful_sms'] ?>, <?= $analytics_data['failed_sms'] ?>],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php
function getAnalyticsData($date_from, $date_to) {
    global $db;
    
    // Total SMS count
    $total_sms = $db->query("SELECT COUNT(*) as count FROM sms_logs WHERE DATE(date_sent) BETWEEN ? AND ?", [$date_from, $date_to])[0]['count'];
    
    // Successful SMS count
    $successful_sms = $db->query("SELECT COUNT(*) as count FROM sms_logs WHERE response = 'Success' AND DATE(date_sent) BETWEEN ? AND ?", [$date_from, $date_to])[0]['count'];
    
    // Failed SMS count
    $failed_sms = $db->query("SELECT COUNT(*) as count FROM sms_logs WHERE response = 'Failed' AND DATE(date_sent) BETWEEN ? AND ?", [$date_from, $date_to])[0]['count'];
    
    // Total cost
    $total_cost = $db->query("SELECT COALESCE(SUM(sms_cost), 0) as total FROM sms_logs WHERE DATE(date_sent) BETWEEN ? AND ?", [$date_from, $date_to])[0]['total'];
    
    // Daily SMS data
    $daily_data = $db->query("
        SELECT DATE(date_sent) as date, COUNT(*) as count 
        FROM sms_logs 
        WHERE DATE(date_sent) BETWEEN ? AND ? 
        GROUP BY DATE(date_sent) 
        ORDER BY DATE(date_sent)
    ", [$date_from, $date_to]);
    
    // Process daily data for chart
    $daily_labels = [];
    $daily_counts = [];
    $current_date = new DateTime($date_from);
    $end_date = new DateTime($date_to);
    
    while ($current_date <= $end_date) {
        $date_str = $current_date->format('Y-m-d');
        $daily_labels[] = $current_date->format('M d');
        
        $found = false;
        foreach ($daily_data as $day) {
            if ($day['date'] === $date_str) {
                $daily_counts[] = $day['count'];
                $found = true;
                break;
            }
        }
        if (!$found) {
            $daily_counts[] = 0;
        }
        
        $current_date->add(new DateInterval('P1D'));
    }
    
    // Recent failed SMS
    try {
        $recent_failed = $db->query("
            SELECT phone, receipt_no, date_sent, retry_count, sent_by_user 
            FROM sms_logs 
            WHERE response = 'Failed' 
            ORDER BY date_sent DESC 
            LIMIT 10
        ");
    } catch (Exception $e) {
        // Fallback to basic query if new columns don't exist
        $recent_failed = $db->query("
            SELECT phone, receipt_no, date_sent 
            FROM sms_logs 
            WHERE response = 'Failed' 
            ORDER BY date_sent DESC 
            LIMIT 10
        ");
    }
    
    return [
        'total_sms' => $total_sms,
        'successful_sms' => $successful_sms,
        'failed_sms' => $failed_sms,
        'total_cost' => $total_cost,
        'daily_labels' => $daily_labels,
        'daily_data' => $daily_counts,
        'recent_failed' => $recent_failed
    ];
}
?>
