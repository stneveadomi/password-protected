<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    PPPTNSE
 * @subpackage PPPTNSE/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
global $wpdb;
$table_name = $wpdb->prefix . 'passwords';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['path']) && isset($_POST['password'])) {
    $path = sanitize_text_field($_POST['path']);
    $password = sanitize_text_field($_POST['password']);
    $wpdb->insert($table_name, array(
        'path' => $path,
        'password' => $password,
        'created_at' => current_time('mysql')
    ));
}

// Fetch passwords
$passwords = $wpdb->get_results("SELECT * FROM $table_name");

?>

<form method="post" action="">
    <label for="path">Path:</label>
    <input type="text" id="path" name="path" required>
    <label for="password">Password:</label>
    <input type="text" id="password" name="password" required>
    <input type="submit" value="Add Password">
</form>

<?php
if (!empty($passwords)) {
    echo '<table>';
    echo '<tr><th>ID</th><th>Path</th><th>Password</th><th>Created At</th></tr>';
    foreach ($passwords as $password) {
        echo '<tr>';
        echo '<td>' . esc_html($password->id) . '</td>';
        echo '<td>' . esc_html($password->path) . '</td>';
        echo '<td>' . esc_html($password->password) . '</td>';
        echo '<td>' . esc_html($password->created_at) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>No passwords found.</p>';
}
?>