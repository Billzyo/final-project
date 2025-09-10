<?php
// Debug: Add error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../app/core/init.php';

// Check if user is logged in
if (!Auth::logged_in()) {
    header('Location: ../index.php');
    exit;
}

// Get export parameters
$export_format = $_GET['format'] ?? 'csv';
$search_phone = $_GET['phone'] ?? '';
$search_receipt = $_GET['receipt_no'] ?? '';
$filter_status = $_GET['status'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
$show_failed_only = isset($_GET['failed_only']) ? true : false;
$show_success_only = isset($_GET['success_only']) ? true : false;

// Debug output (remove this in production)
if (isset($_GET['debug'])) {
    echo "<h1>Export Debug Info</h1>";
    echo "<p>Format: " . $export_format . "</p>";
    echo "<p>Phone: " . $search_phone . "</p>";
    echo "<p>Receipt: " . $search_receipt . "</p>";
    echo "<p>Status: " . $filter_status . "</p>";
    echo "<p>Date From: " . $date_from . "</p>";
    echo "<p>Date To: " . $date_to . "</p>";
    echo "<p>Failed Only: " . ($show_failed_only ? 'Yes' : 'No') . "</p>";
    echo "<p>Success Only: " . ($show_success_only ? 'Yes' : 'No') . "</p>";
    exit;
}

// Build WHERE clause (same logic as receipt_debug.php)
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

// Fetch all logs (no pagination for export)
// Check if new columns exist, if not use basic query
try {
    $logs_query = "SELECT id, phone, receipt_no, date_sent, response, message, retry_count, sent_by_user, sms_cost 
                   FROM sms_logs 
                   {$where_clause} 
                   ORDER BY date_sent DESC";
    $logs = $db->query($logs_query, $params);
} catch (Exception $e) {
    // Fallback to basic query if new columns don't exist
    $logs_query = "SELECT id, phone, receipt_no, date_sent, response, message 
                   FROM sms_logs 
                   {$where_clause} 
                   ORDER BY date_sent DESC";
    $logs = $db->query($logs_query, $params);
}

// Check if we have any logs to export
if (!$logs || count($logs) === 0) {
    header('HTTP/1.1 404 Not Found');
    echo "No SMS logs found to export with the current filters.";
    exit;
}

// Generate filename with timestamp
$timestamp = date('Y-m-d_H-i-s');
$filename = "sms_logs_export_{$timestamp}";

switch ($export_format) {
    case 'csv':
        exportCSV($logs, $filename);
        break;
    case 'excel':
        exportExcel($logs, $filename);
        break;
    case 'pdf':
        exportPDF($logs, $filename);
        break;
    default:
        header('HTTP/1.1 400 Bad Request');
        echo "Invalid export format";
        exit;
}

function exportCSV($logs, $filename) {
    header('Content-Type: text/csv');
    header("Content-Disposition: attachment; filename=\"{$filename}.csv\"");
    
    $output = fopen('php://output', 'w');
    
    // CSV Headers
    fputcsv($output, [
        'ID', 'Phone', 'Receipt No', 'Date Sent', 'Status', 
        'Retry Count', 'Sent By User', 'SMS Cost', 'Message Preview'
    ]);
    
    // CSV Data
    foreach ($logs as $log) {
        fputcsv($output, [
            $log['id'],
            $log['phone'],
            $log['receipt_no'],
            $log['date_sent'],
            $log['response'],
            isset($log['retry_count']) ? $log['retry_count'] : 0,
            isset($log['sent_by_user']) ? $log['sent_by_user'] : 'System',
            isset($log['sms_cost']) ? $log['sms_cost'] : 0.0000,
            substr($log['message'], 0, 100) . (strlen($log['message']) > 100 ? '...' : '')
        ]);
    }
    
    fclose($output);
}

function exportExcel($logs, $filename) {
    // Simple Excel-like CSV with UTF-8 BOM for Excel compatibility
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=\"{$filename}.xlsx\"");
    
    // Add UTF-8 BOM for Excel
    echo "\xEF\xBB\xBF";
    
    $output = fopen('php://output', 'w');
    
    // Excel Headers
    fputcsv($output, [
        'ID', 'Phone', 'Receipt No', 'Date Sent', 'Status', 
        'Retry Count', 'Sent By User', 'SMS Cost', 'Message Preview'
    ]);
    
    // Excel Data
    foreach ($logs as $log) {
        fputcsv($output, [
            $log['id'],
            $log['phone'],
            $log['receipt_no'],
            $log['date_sent'],
            $log['response'],
            isset($log['retry_count']) ? $log['retry_count'] : 0,
            isset($log['sent_by_user']) ? $log['sent_by_user'] : 'System',
            isset($log['sms_cost']) ? $log['sms_cost'] : 0.0000,
            substr($log['message'], 0, 100) . (strlen($log['message']) > 100 ? '...' : '')
        ]);
    }
    
    fclose($output);
}

function exportPDF($logs, $filename) {
    // Generate HTML that can be easily printed as PDF
    header('Content-Type: text/html; charset=utf-8');
    header("Content-Disposition: attachment; filename=\"{$filename}.html\"");
    
    $html = generatePDFHTML($logs);
    
    // Add print styles and JavaScript for easy PDF conversion
    $html = str_replace('</head>', '
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .print-break { page-break-before: always; }
        }
        .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
        }
    </style>
    <script>
        function printToPDF() {
            window.print();
        }
    </script>
    </head>', $html);
    
    echo $html;
}

function generatePDFHTML($logs) {
    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SMS Logs Export</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #2a4dca; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .status-success { color: #28a745; font-weight: bold; }
        .status-failed { color: #dc3545; font-weight: bold; }
        .summary { margin-bottom: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; }
        .print-button { 
            position: fixed; 
            top: 10px; 
            right: 10px; 
            background: #007bff; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 5px; 
            cursor: pointer; 
            z-index: 1000; 
        }
        @media print {
            .print-button { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">Print to PDF</button>
    <h1>SMS Logs Export</h1>
    <div class="summary">
        <strong>Export Date:</strong> ' . date('Y-m-d H:i:s') . '<br>
        <strong>Total Records:</strong> ' . count($logs) . '<br>
        <strong>Generated By:</strong> ' . (Auth::get('firstname') ?? 'System') . '
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Phone</th>
                <th>Receipt No</th>
                <th>Date Sent</th>
                <th>Status</th>
                <th>Retry Count</th>
                <th>Sent By</th>
                <th>Cost</th>
                <th>Message Preview</th>
            </tr>
        </thead>
        <tbody>';
    
    foreach ($logs as $log) {
        $status_class = strtolower($log['response']) === 'success' ? 'status-success' : 'status-failed';
        $html .= '<tr>
            <td>' . htmlspecialchars($log['id']) . '</td>
            <td>' . htmlspecialchars($log['phone']) . '</td>
            <td>' . htmlspecialchars($log['receipt_no']) . '</td>
            <td>' . htmlspecialchars($log['date_sent']) . '</td>
            <td class="' . $status_class . '">' . htmlspecialchars($log['response']) . '</td>
            <td>' . (isset($log['retry_count']) ? $log['retry_count'] : 0) . '</td>
            <td>' . htmlspecialchars(isset($log['sent_by_user']) ? $log['sent_by_user'] : 'System') . '</td>
            <td>K' . number_format(isset($log['sms_cost']) ? $log['sms_cost'] : 0.0000, 4) . '</td>
            <td>' . htmlspecialchars(substr($log['message'], 0, 50) . (strlen($log['message']) > 50 ? '...' : '')) . '</td>
        </tr>';
    }
    
    $html .= '</tbody>
    </table>
</body>
</html>';
    
    return $html;
}
?>
