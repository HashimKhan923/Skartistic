<?php
/**
 * Run this ONCE from your browser: https://yourdomain.com/storage-link.php
 * Then DELETE this file immediately after!
 */

$target = __DIR__ . '/../storage/app/public';
$link   = __DIR__ . '/storage';

if (file_exists($link) || is_link($link)) {
    echo '⚠️ Link already exists at: ' . $link . '<br>';
    echo 'Target points to: ' . readlink($link);
    exit;
}

if (symlink($target, $link)) {
    echo '✅ Storage symlink created successfully!<br>';
    echo 'Link: ' . $link . '<br>';
    echo 'Target: ' . $target . '<br>';
    echo '<br><strong style="color:red">DELETE this file now!</strong>';
} else {
    echo '❌ symlink() failed. Try the FTP method below.<br><br>';
    echo '<strong>Manual FTP method:</strong><br>';
    echo 'In your hosting file manager or FTP client,<br>';
    echo 'create a symlink inside <code>public_html/</code> named <code>storage</code><br>';
    echo 'pointing to: <code>../storage/app/public</code>';
}