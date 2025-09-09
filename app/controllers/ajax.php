<?php 

defined("ABSPATH") ? "" : die();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php-error.log');

// Set the correct timezone
date_default_timezone_set('Africa/Lusaka');

// Capture AJAX data
$raw_data = file_get_contents("php://input", true);
if (!empty($raw_data)) {
    $OBJ = json_decode($raw_data, true);
    if (is_array($OBJ)) {
        // Check if the data type is "search"
if ($OBJ['data_type'] == "search") {
            try {
                error_log("AJAX Search: Start");
                $productClass = new Product();
                error_log("AJAX Search: Product instance created");
                $limit = 20;

                // Capture the phone number and category
                $phone = isset($OBJ['phone']) ? $OBJ['phone'] : ''; 
                $category_id = isset($OBJ['category_id']) ? $OBJ['category_id'] : null;
                error_log("AJAX Search: Phone and category captured - Category ID: " . $category_id . " (Type: " . gettype($category_id) . ")");

                if (!empty($OBJ['text'])) {
                    $search_text = $OBJ['text'];
                    error_log("AJAX Search: Search text: " . $OBJ['text']);
                    $rows = $productClass->searchProductsWithCategory($search_text, $category_id, $limit);
                    error_log("AJAX Search: Search query executed");
                } else {
                    error_log("AJAX Search: No search text, checking category filter");
                    error_log("AJAX Search: category_id = '$category_id', category_id !== 'all' = " . ($category_id !== 'all' ? 'true' : 'false'));
                    if ($category_id && $category_id !== 'all') {
                        $rows = $productClass->getProductsByCategory($category_id, $limit, 0, 'desc', 'views');
                        error_log("AJAX Search: Category query executed for category: $category_id");
                    } else {
                        $rows = $productClass->getProductsWithCategories($limit, 0, 'desc', 'views');
                        error_log("AJAX Search: All products query executed");
                    }
                }

                if ($rows) {
                    foreach ($rows as $key => $row) {
                        $rows[$key]['description'] = strtoupper($row['description']);
                        $rows[$key]['image'] = crop($row['image']);
                    }
                    error_log("AJAX Search: Rows processed");

                    $info['data_type'] = "search";
                    $info['data'] = $rows;
                    $info['phone'] = $phone;

                    echo json_encode($info);
                    error_log("AJAX Search: Response sent");
                }
            } catch (Exception $e) {
                http_response_code(500);
                $error_info = [
                    'data_type' => 'error',
                    'message' => $e->getMessage()
                ];
                error_log("AJAX Search Error: " . $e->getMessage());
                echo json_encode($error_info);
            }
        } 
        // Check if the data type is "checkout"
        elseif ($OBJ['data_type'] == "checkout") {

            // Capture checkout data, including phone number
            $data = $OBJ['text'];
            $phone = isset($OBJ['phone']) ? $OBJ['phone'] : '';  
            $receipt_no = get_receipt_no();
            $user_id = auth("id");
            $now = new DateTime();
            $date = $now->format('Y-m-d H:i:s');

            $db = new Database();

            // Insert each item from the checkout data into the database
            foreach ($data as $row) {
                $arr = [];
                $arr['id'] = $row['id'];
                $phone = isset($row['phone']) ? $row['phone'] : '';
                $query = "select * from products where id = :id limit 1";
                $check = $db->query($query, $arr);

                if (is_array($check)) {
                    $check = $check[0];

                    // Prepare data for insertion, including phone number
                    $arr = [];
                    $arr['barcode'] = $check['barcode'];
                    $arr['description'] = $check['description'];
                    $arr['amount'] = $check['amount'];
                    $arr['qty'] = $row['qty'];
                    $arr['total'] = $row['qty'] * $check['amount'];
                    $arr['receipt_no'] = $receipt_no;
                    $arr['date'] = $date;
                    $arr['user_id'] = $user_id;
                    $arr['phone'] = $phone; 

                    // Insert into sales table
                    $query = "insert into sales (barcode, receipt_no, description, qty, amount, total, date, user_id, phone) values (:barcode, :receipt_no, :description, :qty, :amount, :total, :date, :user_id, :phone)";
                    $db->query($query, $arr);

                    // Update product view count
                    $query = "update products set views = views + 1 where id = :id limit 1";
                    $db->query($query, ['id' => $check['id']]);
                    
                    // SMS Section: Send SMS to the user via Arduino GSM module on COM6
                    $company = "Ink Groceries Receipt";
                    $code = "+26";
                    $phoneNumber = $code . $phone;

                    // Create the SMS message
                    $message = $company . ', ' . 'Receipt #: ' . $receipt_no . ', ' . 'Date: ' . $date . ', ' . 
                               'Description: ' . $check['description'] . ', ' . 'Amount K: ' . $check['amount'];

                    // Prepare the data to send to Arduino (phone number and message separated by a delimiter)
                    $dataToSend = $phoneNumber . "|" . $message . "\n";

                    // Open COM6 serial port for writing
                    $serialPort = "COM12";
                    $response = "";
                    $fp = @fopen($serialPort, "w+");
                    if ($fp) {
                        fwrite($fp, $dataToSend);
                        fflush($fp);
                        fclose($fp);
                        $response = "SMS data sent to Arduino GSM module on $serialPort.";
                    } else {
                        $response = "Failed to open serial port $serialPort.";
                    }

                    // Store the SMS response for debugging or confirmation
                    $info['sms_response'] = $response;
                }

                // Log SMS to database
                $logData = [
                    'phone' => $phoneNumber,
                    'message' => $message,
                    'receipt_no' => $receipt_no,
                    'date_sent' => $date,
                    'response' => $response
                ];
                $logQuery = "INSERT INTO sms_logs (phone, message, receipt_no, date_sent, response) VALUES (:phone, :message, :receipt_no, :date_sent, :response)";
                $db->query($logQuery, $logData);
            }

            // Return success message
            $info['data_type'] = "checkout";
            $info['data'] = "Items saved successfully!";
            $info['phone'] = $phone;
            $info['receipt_no'] = $receipt_no;

            echo json_encode($info);
        }
    }
}
?>
