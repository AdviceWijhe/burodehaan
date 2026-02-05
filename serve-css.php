<?php
/**
 * CSS Server Script
 * Alternative way to serve CSS if direct file access fails
 */

// Security check
if (!defined('ABSPATH')) {
    // If not in WordPress context, define basic security
    if (!isset($_SERVER['HTTP_HOST']) || empty($_SERVER['HTTP_HOST'])) {
        exit('Access denied');
    }
}

// Set proper headers
header('Content-Type: text/css');
header('Cache-Control: public, max-age=31536000'); // 1 year cache

// Get the CSS file
$css_file = __DIR__ . '/assets/css/style.css';

if (file_exists($css_file) && is_readable($css_file)) {
    // Set last modified header
    $last_modified = filemtime($css_file);
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $last_modified) . ' GMT');
    
    // Check if client has cached version
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
        $if_modified_since = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
        if ($if_modified_since >= $last_modified) {
            header('HTTP/1.1 304 Not Modified');
            exit();
        }
    }
    
    // Output the CSS
    readfile($css_file);
} else {
    // Fallback: basic CSS
    header('HTTP/1.1 200 OK');
    echo '/* CSS file not found - using fallback */';
    echo 'body { font-family: system-ui, -apple-system, sans-serif; line-height: 1.6; }';
    echo '.container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }';
    echo '.text-center { text-align: center; }';
    echo '.mb-4 { margin-bottom: 1rem; }';
    echo '.p-4 { padding: 1rem; }';
    echo '.bg-white { background-color: white; }';
    echo '.shadow { box-shadow: 0 1px 3px rgba(0,0,0,0.1); }';
}
?>
