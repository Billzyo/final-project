<?php
// Set the default timezone to ensure correct time is captured
date_default_timezone_set('Africa/Lusaka');

// Require composer autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $WshShell = new COM("WScript.Shell");
    $obj = $WshShell->Run("cmd /c wscript.exe " . ABSPATH . "/file.vbs", 0, true);
}

$vars = $_GET['vars'] ?? "";
$obj = json_decode($vars, true);

// Ensure receipt_no is set
if (!isset($obj['receipt_no'])) {
    $obj['receipt_no'] = get_receipt_no();
}

if (is_array($obj)) {
    // Use the consistent receipt number
    $receipt_no = $obj['receipt_no'];

    // Create receipts directory if it doesn't exist
    $receiptDir = __DIR__ . '/receipts';
    if (!file_exists($receiptDir)) {
        mkdir($receiptDir, 0755, true);
    }

    // Use receipt number for filename
    $filename = $receipt_no . ".pdf";
    $filepath = $receiptDir . '/' . $filename;

    // Create new mPDF instance
    $mpdf = new \Mpdf\Mpdf([
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 15,
        'margin_bottom' => 15
    ]);

    // Build HTML content for PDF
    $html = '
    <style>
        body { font-family: arial; }
        .header { text-align: center; margin-bottom: 20px; }
        .logo { width: 110px; height: 70px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 8px; border: 1px solid #ddd; }
        th { background-color: #f8f9fa; }
        .amount { text-align: right; }
        .center { text-align: center; }
        .total-row { font-weight: bold; }
    </style>
    
    <div class="header">
        <img src="assets/images/inkTech.png" class="logo">
        <h1>' . htmlspecialchars($obj['company'], ENT_QUOTES, 'UTF-8') . '</h1>
        <h2>Receipt</h2>
        <i>' . (new DateTime())->format("jS F, Y H:i a") . '</i>
    </div>

    <table>
        <thead>
            <tr>
                <th>Qty</th>
                <th>Description</th>
                <th>@</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($obj['data'] as $row) {
        $html .= '<tr>
            <td class="center">' . htmlspecialchars($row['qty'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') . '</td>
            <td class="amount">K' . htmlspecialchars($row['amount'], ENT_QUOTES, 'UTF-8') . '</td>
            <td class="amount">K' . number_format($row['qty'] * $row['amount'], 2) . '</td>
        </tr>';
    }

    $html .= '</tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2"></td>
                <td>Total:</td>
                <td class="amount">' . htmlspecialchars($obj['gtotal'], ENT_QUOTES, 'UTF-8') . '</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>Amount paid:</td>
                <td class="amount">' . htmlspecialchars($obj['amount'], ENT_QUOTES, 'UTF-8') . '</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>Change:</td>
                <td class="amount">' . htmlspecialchars($obj['change'], ENT_QUOTES, 'UTF-8') . '</td>
            </tr>
        </tfoot>
    </table>

    <div class="center">
        <i>Thanks for shopping with us!</i>
    </div>';

    // Write PDF
    $mpdf->WriteHTML($html);

    // Save PDF to file
    $mpdf->Output($filepath, 'F');
}
?>

<!DOCTYPE html>
<!-- Rest of your HTML code remains the same -->