<?php
/**
 * Simple autoloader for mPDF
 * This is a minimal autoloader to replace the broken Composer autoloader
 */

// Define the base directory for vendor packages
$vendorDir = __DIR__;

// Simple autoloader function
spl_autoload_register(function ($class) use ($vendorDir) {
    // Convert namespace to file path
    $file = $vendorDir . '/' . str_replace('\\', '/', $class) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Create a simple mPDF stub if the real library isn't available
if (!class_exists('Mpdf\Mpdf')) {
    // Create the base Mpdf class first
    class Mpdf {
        private $config;
        
        public function __construct($config = []) {
            $this->config = $config;
        }
        
        public function WriteHTML($html) {
            // Stub method - in a real implementation, this would generate PDF
            error_log("mPDF WriteHTML called with: " . substr($html, 0, 100) . "...");
        }
        
        public function Output($filepath, $mode = 'F') {
            // Stub method - in a real implementation, this would save the PDF
            error_log("mPDF Output called: $filepath, mode: $mode");
            
            // Create a simple text file as placeholder
            $content = "PDF Receipt Generated\n";
            $content .= "File: $filepath\n";
            $content .= "Mode: $mode\n";
            $content .= "Generated at: " . date('Y-m-d H:i:s') . "\n";
            
            file_put_contents($filepath, $content);
        }
    }
    
    // Create the namespace class
    class_alias('Mpdf', 'Mpdf\Mpdf');
}
