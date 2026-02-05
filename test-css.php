<?php
/**
 * CSS Test Script
 * Run this to test if CSS is accessible: /wp-content/themes/advice2025/test-css.php
 */

echo '<h1>CSS Accessibility Test</h1>';

$css_file = __DIR__ . '/assets/css/style.css';
$css_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', $css_file);

echo '<h2>File System Check:</h2>';
echo '<p><strong>CSS File Path:</strong> ' . $css_file . '</p>';
echo '<p><strong>File Exists:</strong> ' . (file_exists($css_file) ? '✅ Yes' : '❌ No') . '</p>';

if (file_exists($css_file)) {
    echo '<p><strong>File Size:</strong> ' . number_format(filesize($css_file)) . ' bytes</p>';
    echo '<p><strong>Last Modified:</strong> ' . date('Y-m-d H:i:s', filemtime($css_file)) . '</p>';
    echo '<p><strong>Permissions:</strong> ' . substr(sprintf('%o', fileperms($css_file)), -4) . '</p>';
    echo '<p><strong>Readable:</strong> ' . (is_readable($css_file) ? '✅ Yes' : '❌ No') . '</p>';
}

echo '<h2>URL Test:</h2>';
echo '<p><strong>CSS URL:</strong> <a href="' . $css_url . '" target="_blank">' . $css_url . '</a></p>';

// Test URL accessibility
$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'method' => 'HEAD'
    ]
]);

$headers = @get_headers($css_url, 1, $context);
if ($headers) {
    echo '<p><strong>HTTP Status:</strong> ' . $headers[0] . '</p>';
    if (isset($headers['Content-Type'])) {
        echo '<p><strong>Content-Type:</strong> ' . $headers['Content-Type'] . '</p>';
    }
} else {
    echo '<p><strong>HTTP Status:</strong> ❌ Failed to connect</p>';
}

echo '<h2>WordPress Context Test:</h2>';
if (function_exists('get_template_directory')) {
    echo '<p><strong>WP Template Dir:</strong> ' . get_template_directory() . '</p>';
    echo '<p><strong>WP Template URI:</strong> ' . get_template_directory_uri() . '</p>';
} else {
    echo '<p>❌ WordPress functions not available</p>';
}

echo '<h2>Server Info:</h2>';
echo '<p><strong>Document Root:</strong> ' . $_SERVER['DOCUMENT_ROOT'] . '</p>';
echo '<p><strong>Script Path:</strong> ' . __DIR__ . '</p>';
echo '<p><strong>Web Server:</strong> ' . $_SERVER['SERVER_SOFTWARE'] . '</p>';

if (file_exists($css_file)) {
    echo '<h2>CSS Preview (first 500 characters):</h2>';
    echo '<pre style="background: #f5f5f5; padding: 10px; border: 1px solid #ddd; max-height: 200px; overflow-y: auto;">';
    echo htmlspecialchars(substr(file_get_contents($css_file), 0, 500));
    echo '</pre>';
}
?>
