<?php
require_once '../app/core/init.php';
require_once '../app/core/functions.php';

function send_sms($phone, $message) {
    $url = 'http://192.168.211.145/send';
    $data = [
        'phone' => $phone,
        'message' => $message
    ];
    
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
            'timeout' => 15  // Increased timeout
        ]
    ];
    
    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);
    
    // Enhanced logging
    $logData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'phone' => $phone,
        'message' => $message,
        'response' => $result,
        'url' => $url
    ];
    file_put_contents('../app/logs/sms_debug.log', print_r($logData, true), FILE_APPEND);
    
    return $result !== false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receipt_no = $_POST['receipt_no'] ?? null;
    $phone = $_POST['phone'] ?? null;

    if (!$receipt_no || !$phone) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing receipt_no or phone parameter']);
        exit;
    }

    $db = new Database();
    $sales = $db->query("SELECT * FROM sales WHERE receipt_no = ?", [$receipt_no]);

    if (!$sales || count($sales) === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Transaction not found']);
        exit;
    }

    // Build SMS message with proper formatting
    $message = "Pos System\n";
    $message .= "Receipt No: $receipt_no\n";
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
    $sent = send_sms($phone, $message);

    // Log to database
    $response_text = $sent ? 'Success' : 'Failed';
    $db->query(
        "INSERT INTO sms_logs (phone, message, receipt_no, date_sent, response) 
         VALUES (:phone, :message, :receipt_no, NOW(), :response)",
        [
            'phone' => $phone,
            'message' => $message,
            'receipt_no' => $receipt_no,
            'response' => $response_text
        ]
    );

    if ($sent) {
        echo json_encode(['success' => 'Receipt sent successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to send receipt SMS']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}