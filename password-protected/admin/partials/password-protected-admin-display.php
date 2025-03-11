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
$table_name = $wpdb->prefix . 'password_protected';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['postId']) && isset($_POST['password'])) {
    $postId = intval($_POST['postId']);
    $password = sanitize_text_field($_POST['password']);
    $wpdb->insert($table_name, array(
        'post_id' => $postId,
        'password' => $password,
        'created_at' => current_time('mysql')
    ));
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $wpdb->delete($table_name, array('id' => $delete_id));
}

// Fetch passwords
$passwords = $wpdb->get_results("SELECT * FROM $table_name");

?>

<form method="post" action="">
    <label for="postId">Post:</label>
    <select id="postId" name="postId" required>
        <?php
        $posts = get_posts(array(
            'numberposts' => -1,
            'post_type' => array('post', 'page'),
            'post_status' => array('publish', 'private', 'password')
        ));
        foreach ($posts as $post) {
            echo '<option value="' . esc_attr($post->ID) . '">' . esc_html($post->post_title) . '</option>';
        }
        ?>
    </select>
    <label for="password">Password:</label>
    <input type="text" id="password" name="password" required>
    <input type="submit" value="Add Password">
</form>

<?php
if (!empty($passwords)) {
    echo '<table>';
    echo '<tr><th>ID</th><th>Post ID</th><th>Post Title</th><th>Password</th><th>Created At</th><th>Action</th></tr>';
    foreach ($passwords as $password) {
        echo '<tr>';
        echo '<td>' . esc_html($password->id) . '</td>';
        echo '<td>' . esc_html($password->post_id) . '</td>';
        $post_title = get_the_title($password->post_id);
        echo '<td>' . esc_html($post_title) . '</td>';
        echo '<td>' . esc_html($password->password) . '</td>';
        echo '<td>' . esc_html($password->created_at) . '</td>';
        echo '<td>
                <form method="post" action="" style="display:inline;">
                    <input type="hidden" name="delete_id" value="' . esc_attr($password->id) . '">
                    <button type="submit" style="background:none;border:none;color:red;cursor:pointer;">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                </form>
              </td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>No passwords found.</p>';
}
?>