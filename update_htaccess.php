<?php
// Script to update .htaccess file to increase execution time limit

// Backup the original .htaccess file
$htaccess_file = '.htaccess';
$backup_file = '.htaccess.backup.' . date('YmdHis');

if (file_exists($htaccess_file)) {
    echo "Creating backup of .htaccess file as $backup_file\n";
    copy($htaccess_file, $backup_file);
}

// Read the current .htaccess content
$htaccess_content = file_get_contents($htaccess_file);

// Check if PHP settings section already exists
if (strpos($htaccess_content, '# PHP Settings') !== false) {
    echo "PHP settings section already exists in .htaccess file.\n";
} else {
    // Add PHP settings section to increase execution time limit
    $php_settings = "\n# PHP Settings\n<IfModule mod_php.c>\n    php_value max_execution_time 300\n    php_value memory_limit 256M\n</IfModule>\n";

    // Find the position to insert the PHP settings (after the RewriteEngine section)
    $insert_pos = strpos($htaccess_content, '</IfModule>');
    if ($insert_pos !== false) {
        $insert_pos = strpos($htaccess_content, "\n", $insert_pos);

        // Insert the PHP settings
        $new_htaccess_content = substr($htaccess_content, 0, $insert_pos) . $php_settings . substr($htaccess_content, $insert_pos);

        // Write the updated content back to the .htaccess file
        file_put_contents($htaccess_file, $new_htaccess_content);

        echo "Updated .htaccess file to increase execution time limit to 300 seconds.\n";
    } else {
        echo "Could not find appropriate position to insert PHP settings in .htaccess file.\n";
    }
}

echo "Done.\n";
?>
