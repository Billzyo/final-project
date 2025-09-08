<?php
require_once '../app/core/init.php';

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['phone'], $_POST['receipt_no'])) {
    $phone = $_POST['phone'];
    $receipt_no = $_POST['receipt_no'];

    // Fetch transaction details by receipt_no
    $sales = $db->query("SELECT * FROM sales WHERE receipt_no = ?", [$receipt_no]);
    if (!$sales || count($sales) === 0) {
        $error = "Transaction not found";
    } else {
        // Build SMS message with item details
        $message = "POS RECEIPT\n";
        $message .= "Receipt #: " . $receipt_no . "\n";
        $message .= "Date: " . date("jS M, Y H:i", strtotime($sales[0]['date'])) . "\n";
        $grand_total = 0;
        foreach ($sales as $item) {
            $item_total = $item['qty'] * $item['amount'];
            $grand_total += $item_total;
            $message .= "• " . substr($item['description'], 0, 20) . " x" . $item['qty'] .
                        " @ K" . number_format($item['amount'], 2) .
                        " = K" . number_format($item_total, 2) . "\n";
        }
        $message .= "----\n";
        $message .= "TOTAL: K" . number_format($grand_total, 2) . "\n";
        $message .= "Thank you!";

        // Send SMS
        require_once 'send_receipt.php'; // Include the send_sms function
        $sent = send_sms($phone, $message);

        // Log SMS attempt
        $response_text = $sent ? 'Success' : 'Failed';
        $db->query("INSERT INTO sms_logs (phone, message, receipt_no, date_sent, response) VALUES (?, ?, ?, NOW(), ?)", [$phone, $message, $receipt_no, $response_text]);

        if ($sent) {
            $success = "Receipt SMS resent successfully";
        } else {
            $error = "Failed to resend receipt SMS";
        }
    }
}

$logs = $db->query("SELECT id, phone, receipt_no, date_sent, response, LEFT(message, 50) AS message_preview FROM sms_logs ORDER BY date_sent DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt SMS Debugging</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/main.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --success: #2E7D32;
            --danger: #C62828;
            --light-bg: #f8f9fa;
            --dark-text: #343a40;
            --border-radius: 12px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        body {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: var(--dark-text);
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .page-title {
            font-weight: 700;
            color: var(--primary);
            margin: 0;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: var(--primary);
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .back-link:hover {
            color: #2a4dca;
            transform: translateX(-3px);
        }
        
        .back-link i {
            margin-right: 8px;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .alert-success {
            background-color: #e8f5e9;
            color: var(--success);
            border-left: 4px solid var(--success);
        }
        
        .alert-error {
            background-color: #ffebee;
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        .alert i {
            margin-right: 10px;
            font-size: 18px;
        }
        
        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 25px;
        }
        
        .card-header {
            background-color: var(--primary);
            color: white;
            padding: 16px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .card-header i {
            margin-right: 10px;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background-color: var(--light-bg);
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        
        tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-success {
            background-color: rgba(46, 125, 50, 0.15);
            color: var(--success);
        }
        
        .status-failed {
            background-color: rgba(198, 40, 40, 0.15);
            color: var(--danger);
        }
        
        .btn-resend {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-resend:hover {
            background-color: #2a4dca;
            transform: translateY(-2px);
        }
        
        .btn-resend i {
            margin-right: 6px;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        
        @media (max-width: 768px) {
            th, td {
                padding: 12px 8px;
            }
            
            .card-header {
                padding: 12px 15px;
            }
            
            .btn-resend {
                padding: 6px 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-sms"></i> Receipt SMS Sending Logs</h1>
        <a href="index.php?pg=admin" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header">
            <i class="fas fa-history"></i> SMS History
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Phone</th>
                        <th>Receipt No</th>
                        <th>Date Sent</th>
                        <th>Status</th>
                        <th>Message Preview</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($logs && count($logs) > 0): ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['id']) ?></td>
                                <td><?= htmlspecialchars($log['phone']) ?></td>
                                <td><?= htmlspecialchars($log['receipt_no']) ?></td>
                                <td><?= htmlspecialchars($log['date_sent']) ?></td>
                                <td>
                                    <span class="status-badge <?= strtolower($log['response']) === 'success' ? 'status-success' : 'status-failed' ?>">
                                        <?= htmlspecialchars($log['response']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($log['message_preview']) ?>...</td>
                                <td>
                                    <form method="POST" action="receipt_debug.php" onsubmit="return confirm('Resend this receipt SMS?');">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($log['id']) ?>">
                                        <input type="hidden" name="phone" value="<?= htmlspecialchars($log['phone']) ?>">
                                        <input type="hidden" name="receipt_no" value="<?= htmlspecialchars($log['receipt_no']) ?>">
                                        <button type="submit" class="btn-resend"><i class="fas fa-paper-plane"></i> Send</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>No SMS logs found</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>