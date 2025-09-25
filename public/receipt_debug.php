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
            $message = "Pos System\n";
            $message .= "Receipt No: " . $receipt_no . "\n";
            $message .= "Date: " . date("jS M, Y", strtotime($sales[0]['date'])) . "\n";
            $message .= "Items:\n";
            $grand_total = 0;
            foreach ($sales as $item) {
                $item_total = $item['qty'] * $item['amount'];
                $grand_total += $item_total;
                $message .= "- " . $item['description'] . " x" . $item['qty'] .
                            " @ K" . number_format($item['amount'], 2) .
                            " = K" . number_format($item_total, 2) . "\n";
            }
            $message .= "TOTAL: K" . number_format($grand_total, 2) . "\n";
            $message .= "Thank you for your purchase!";

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

// Handle bulk actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action']) && isset($_POST['selected_logs'])) {
    $selected_logs = $_POST['selected_logs'];
    $bulk_action = $_POST['bulk_action'];
    
    if ($bulk_action === 'resend_selected' && !empty($selected_logs)) {
        $resend_count = 0;
        $error_count = 0;
        
        foreach ($selected_logs as $log_id) {
            // Look up log data
            $log_data = $db->query("SELECT phone, receipt_no FROM sms_logs WHERE id = ?", [$log_id]);
            
            if ($log_data && count($log_data) > 0) {
                $phone = $log_data[0]['phone'];
                $receipt_no = $log_data[0]['receipt_no'];
                
                // Fetch transaction details
                $sales = $db->query("SELECT * FROM sales WHERE receipt_no = ?", [$receipt_no]);
                if ($sales && count($sales) > 0) {
                    // Build SMS message
        $message = "Pos System\n";
        $message .= "Receipt No: " . $receipt_no . "\n";
        $message .= "Date: " . date("jS M, Y", strtotime($sales[0]['date'])) . "\n";
        $message .= "Items:\n";
        $grand_total = 0;
        foreach ($sales as $item) {
            $item_total = $item['qty'] * $item['amount'];
            $grand_total += $item_total;
            $message .= "- " . $item['description'] . " x" . $item['qty'] .
                        " @ K" . number_format($item['amount'], 2) .
                        " = K" . number_format($item_total, 2) . "\n";
        }
        $message .= "TOTAL: K" . number_format($grand_total, 2) . "\n";
        $message .= "Thank you for your purchase!";

        // Send SMS
                    require_once 'send_receipt.php';
        $sent = send_sms($phone, $message);

        // Log SMS attempt
        $response_text = $sent ? 'Success' : 'Failed';
        $db->query("INSERT INTO sms_logs (phone, message, receipt_no, date_sent, response) VALUES (?, ?, ?, NOW(), ?)", [$phone, $message, $receipt_no, $response_text]);

        if ($sent) {
                        $resend_count++;
                    } else {
                        $error_count++;
                    }
                } else {
                    $error_count++;
                }
            } else {
                $error_count++;
            }
        }
        
        if ($resend_count > 0) {
            $success = "Bulk resend completed: {$resend_count} SMS sent successfully";
            if ($error_count > 0) {
                $success .= ", {$error_count} failed";
            }
        } else {
            $error = "Bulk resend failed: All {$error_count} SMS failed to send";
        }
    }
}

// Handle search and filters
$search_phone = $_GET['phone'] ?? '';
$search_receipt = $_GET['receipt_no'] ?? '';
$filter_status = $_GET['status'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
$show_failed_only = isset($_GET['failed_only']) ? true : false;
$show_success_only = isset($_GET['success_only']) ? true : false;
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = intval($_GET['per_page'] ?? 20);

// Build WHERE clause
$where_conditions = [];
$params = [];

if (!empty($search_phone)) {
    $where_conditions[] = "phone LIKE ?";
    $params[] = "%{$search_phone}%";
}

if (!empty($search_receipt)) {
    $where_conditions[] = "receipt_no LIKE ?";
    $params[] = "%{$search_receipt}%";
}

if (!empty($filter_status)) {
    $where_conditions[] = "response = ?";
    $params[] = $filter_status;
}

if (!empty($date_from)) {
    $where_conditions[] = "DATE(date_sent) >= ?";
    $params[] = $date_from;
}

if (!empty($date_to)) {
    $where_conditions[] = "DATE(date_sent) <= ?";
    $params[] = $date_to;
}

if ($show_failed_only) {
    $where_conditions[] = "response = 'Failed'";
}

if ($show_success_only) {
    $where_conditions[] = "response = 'Success'";
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM sms_logs {$where_clause}";
$total_result = $db->query($count_query, $params);
$total_logs = $total_result[0]['total'] ?? 0;
$total_pages = ceil($total_logs / $per_page);

// Calculate offset
$offset = ($page - 1) * $per_page;

// Fetch logs with pagination
$logs_query = "SELECT id, phone, receipt_no, date_sent, response, LEFT(message, 50) AS message_preview 
               FROM sms_logs 
               {$where_clause} 
               ORDER BY date_sent DESC 
               LIMIT {$per_page} OFFSET {$offset}";
$logs = $db->query($logs_query, $params);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt SMS Debugging</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/main.css" rel="stylesheet" />
    <link href="assets/css/receipt-debug.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    
    <!-- Search and Filters -->
    <div class="search-filters">
        <form method="GET" action="receipt_debug.php">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($search_phone) ?>" placeholder="Search by phone...">
                </div>
                <div class="filter-group">
                    <label for="receipt_no">Receipt Number</label>
                    <input type="text" id="receipt_no" name="receipt_no" value="<?= htmlspecialchars($search_receipt) ?>" placeholder="Search by receipt...">
                </div>
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All Status</option>
                        <option value="Success" <?= $filter_status === 'Success' ? 'selected' : '' ?>>Success</option>
                        <option value="Failed" <?= $filter_status === 'Failed' ? 'selected' : '' ?>>Failed</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="date_from">Date From</label>
                    <input type="date" id="date_from" name="date_from" value="<?= htmlspecialchars($date_from) ?>">
                </div>
                <div class="filter-group">
                    <label for="date_to">Date To</label>
                    <input type="date" id="date_to" name="date_to" value="<?= htmlspecialchars($date_to) ?>">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="receipt_debug.php" class="btn-clear">
                        <i class="fas fa-times"></i> Clear
                    </a>
                    <a href="receipt_debug.php?success_only=1" class="btn-success-only">
                        <i class="fas fa-check-circle"></i> Show Success Only
                    </a>
                    <a href="receipt_debug.php?failed_only=1" class="btn-failed-only">
                        <i class="fas fa-exclamation-triangle"></i> Show Failed Only
                    </a>
                </div>
            </div>
            <input type="hidden" name="per_page" value="<?= $per_page ?>">
        </form>
    </div>
    
    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulk-actions" style="display: none;">
        <form method="POST" action="receipt_debug.php" onsubmit="return confirm('Resend selected SMS messages?');">
            <input type="checkbox" id="select-all">
            <label for="select-all">Select All</label>
            <span id="selected-count">0 selected</span>
            <button type="submit" name="bulk_action" value="resend_selected" class="btn-bulk" id="bulk-resend-btn" disabled>
                <i class="fas fa-paper-plane"></i> Resend Selected
            </button>
            <div id="selected-logs"></div>
        </form>
    </div>
    
    <div class="card">
        <div class="card-header">
            <i class="fas fa-history"></i> SMS History
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="header-checkbox"></th>
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
                                <td><input type="checkbox" class="log-checkbox" value="<?= htmlspecialchars($log['id']) ?>"></td>
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
    
    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <div class="pagination-info">
            Showing <?= $offset + 1 ?>-<?= min($offset + $per_page, $total_logs) ?> of <?= $total_logs ?> logs
        </div>
        <div class="pagination-controls">
            <?php if ($page > 1): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" class="btn-page">
                    <i class="fas fa-chevron-left"></i> Previous
                </a>
            <?php else: ?>
                <span class="btn-page" disabled>Previous</span>
            <?php endif; ?>
            
            <?php
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $page + 2);
            
            if ($start_page > 1): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>" class="btn-page">1</a>
                <?php if ($start_page > 2): ?>
                    <span class="btn-page">...</span>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                   class="btn-page <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            
            <?php if ($end_page < $total_pages): ?>
                <?php if ($end_page < $total_pages - 1): ?>
                    <span class="btn-page">...</span>
                <?php endif; ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>" class="btn-page"><?= $total_pages ?></a>
            <?php endif; ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" class="btn-page">
                    Next <i class="fas fa-chevron-right"></i>
                </a>
            <?php else: ?>
                <span class="btn-page" disabled>Next</span>
            <?php endif; ?>
        </div>
        <div class="per-page-selector">
            <label for="per_page">Per page:</label>
            <select id="per_page">
                <option value="20" <?= $per_page === 20 ? 'selected' : '' ?>>20</option>
                <option value="50" <?= $per_page === 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= $per_page === 100 ? 'selected' : '' ?>>100</option>
            </select>
        </div>
    </div>
    <?php endif; ?>
    
    <script src="assets/js/receipt-debug.js"></script>
</body>
</html>
