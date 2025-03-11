<?php
function submit_password_shortcode() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_password'])) {
        $post_password = sanitize_text_field($_POST['post_password']);

        setcookie('password_protected_password', $post_password, time() + 864000, '/');
        exit;
    }

    ob_start();
    ?>
    <?php if (isset($_COOKIE['password_protected_password'])): ?>
        <p>Password: <?php echo htmlspecialchars($_COOKIE['password_protected_password']); ?></p>
        <p>Expires: <?php echo date('Y-m-d H:i:s', $_COOKIE['password_protected_password'] + 864000); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="post_password">Enter Password:</label>
        <input type="password" name="post_password" id="post_password" required>
        <input type="submit" value="Submit">
    </form>
    <?php
    return ob_get_clean();
}

add_shortcode('pp_submit_password', 'submit_password_shortcode');
?>