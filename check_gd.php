<?php
echo "Checking GD library support...\n";

if (extension_loaded('gd')) {
    echo "✓ GD library is enabled\n";
    
    // Check specific GD functions
    $functions = ['imagecreatefromjpeg', 'imagecreatefrompng', 'imagecreatefromgif', 'imagejpeg', 'imagepng', 'imagegif'];
    foreach ($functions as $function) {
        if (function_exists($function)) {
            echo "✓ $function is available\n";
        } else {
            echo "✗ $function is NOT available\n";
        }
    }
    
    // Get GD version info
    $gd_info = gd_info();
    echo "GD Version: " . $gd_info['GD Version'] . "\n";
    echo "FreeType Support: " . ($gd_info['FreeType Support'] ? 'Yes' : 'No') . "\n";
    echo "JPEG Support: " . ($gd_info['JPEG Support'] ? 'Yes' : 'No') . "\n";
    echo "PNG Support: " . ($gd_info['PNG Support'] ? 'Yes' : 'No') . "\n";
    echo "GIF Support: " . ($gd_info['GIF Support'] ? 'Yes' : 'No') . "\n";
    
} else {
    echo "✗ GD library is NOT enabled\n";
    echo "Please enable the GD extension in your php.ini file\n";
}
?>
