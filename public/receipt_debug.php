<?php
require_once '../app/core/init.php';

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = null;
    $receipt_no = null;
    $method_used = "Unknown";

    // ✅ Preferred: resend using log ID
    if (!empty($_POST['id'])) {
        $log_id = $_POST['id'];
        $log = $db->query("SELECT phone, receipt_no FROM sms_logs WHERE id = ?", [$log_id]);
        if ($log && count($log) > 0) {
            $phone = $log[0]['phone'];
            $receipt_no = $log[0]['receipt_no'];
            $method_used = "ID-based lookup";
        } else {
            $error = "Log not found for ID: " . htmlspecialchars($log_id);
        }
    } 
    // ✅ Fallback: direct POST of phone + receipt_no
    elseif (!empty($_POST['phone']) && !empty($_POST['receipt_no'])) {
        $phone = $_POST['phone'];
        $receipt_no = $_POST['receipt_no'];
        $method_used = "Direct parameters";
    } else {
        $error = "Invalid request: missing data";
    }

    if ($phone && $receipt_no) {
        // Fetch sales data
        $sales = $db->query("SELECT * FROM sales WHERE receipt_no = ?", [$receipt_no]);
        if (!$sales || count($sales) === 0) {
            $error = "Transaction not found for receipt: " . htmlspecialchars($receipt_no);
        } else {
            // Build SMS message
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
            $message .= "----\nTOTAL: K" . number_format($grand_total, 2) . "\nThank you!";

            // Send SMS
            require_once 'send_receipt.php';
            $sent = send_sms($phone, $message);

            // Log attempt
            $response_text = $sent ? 'Success' : 'Failed';
            $db->query(
                "INSERT INTO sms_logs (phone, message, receipt_no, date_sent, response) VALUES (?, ?, ?, NOW(), ?)",
                [$phone, $message, $receipt_no, $response_text]
            );

            if ($sent) {
                $success = "Receipt SMS resent successfully to $phone (Method: $method_used)";
            } else {
                $error = "Failed to resend SMS to $phone (Method: $method_used)";
            }
        }
    }
}

// Fetch logs
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
        /* Improved Table and Card Styling */
        body {
            background: #f4f8fb;
        }
        .card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(44, 62, 80, 0.10), 0 1.5px 4px rgba(44,62,80,0.04);
            margin: 40px auto;
            max-width: 1050px;
            padding: 0;
            border: none;
        }
        .card-header {
            background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%);
            border-radius: 18px 18px 0 0;
            border-bottom: none;
            padding: 24px 36px;
            font-size: 1.45rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 1px;
        }
        .table-container {
            padding: 32px 32px 24px 32px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            font-size: 1.08rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(44,62,80,0.04);
        }
        th, td {
            padding: 16px 18px;
            border-bottom: 1px solid #e3eaf1;
            text-align: left;
        }
        th {
            background: #f7fbff;
            color: #1a2942;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e3eaf1;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:nth-child(even) {
            background: #fafdff;
        }
        tr:hover {
            background: #eaf6ff;
            transition: background 0.2s;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 16px;
            border-radius: 16px;
            font-size: 1em;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.5px;
            box-shadow: 0 1px 4px rgba(44,62,80,0.07);
        }
        .status-success {
            background: linear-gradient(90deg, #28a745 60%, #43e97b 100%);
        }
        .status-failed {
            background: linear-gradient(90deg, #dc3545 60%, #ff758c 100%);
        }
        .page-header {
            margin: 40px auto 24px auto;
            max-width: 1050px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .page-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #1a2942;
            margin: 0;
            letter-spacing: 1px;
        }
        .back-link {
            font-size: 1.15rem;
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
            background: #eaf6ff;
            padding: 8px 18px;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
            box-shadow: 0 1px 4px rgba(44,62,80,0.04);
        }
        .back-link:hover {
            color: #fff;
            background: #007bff;
            text-decoration: none;
        }
        .alert {
            max-width: 1050px;
            margin: 22px auto;
            padding: 18px 28px;
            border-radius: 8px;
            font-size: 1.13rem;
            box-shadow: 0 1px 4px rgba(44,62,80,0.04);
        }
        .alert-success {
            background: linear-gradient(90deg, #e6f9ed 60%, #d2f7e5 100%);
            color: #207544;
            border: 1px solid #b7e4c7;
        }
        .alert-error {
            background: linear-gradient(90deg, #fdeaea 60%, #ffe3e3 100%);
            color: #a94442;
            border: 1px solid #f5c6cb;
        }
        .btn-resend {
            background: linear-gradient(90deg, #007bff 60%, #00c6ff 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 7px 18px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 4px rgba(44,62,80,0.07);
        }
        .btn-resend:hover {
            background: linear-gradient(90deg, #0056b3 60%, #007bff 100%);
            color: #fff;
        }
        .empty-state {
            text-align: center;
            color: #b0b8c1;
            font-size: 1.2rem;
            padding: 32px 0;
        }
        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 8px;
            color: #cfd8dc;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-sms"></i> Receipt SMS Sending Logs</h1>
        <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to POS Dashboard</a>
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
                                    <form method="POST" action="receipt_debug.php" onsubmit="return confirm('Resend this receipt SMS to <?= htmlspecialchars($log['phone']) ?>?');">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($log['id']) ?>">
                                        <!-- ✅ Fallback values -->
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
