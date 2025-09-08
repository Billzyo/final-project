CREATE TABLE sms_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(20) NOT NULL,
    message TEXT NOT NULL,
    receipt_no VARCHAR(50) NOT NULL,
    date_sent DATETIME NOT NULL,
    response TEXT,
    INDEX (phone),
    INDEX (receipt_no)
);
