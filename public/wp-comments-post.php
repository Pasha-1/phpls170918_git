<?php
if ('POST' != $_SERVER['REQUEST_METHOD']) {
    $protocol = $_SERVER['SERVER_PROTOCOL'];
    if (!in_array($protocol, array('HTTP/1.1', 'HTTP/2', 'HTTP/2.0'))) {
        $protocol = 'HTTP/1.0';
    }

    header('Allow: POST');
    header("$protocol 405 Method Not Allowed");
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');

nocache_headers();

$comment = wp_handle_comment_submission(wp_unslash($_POST));
if (is_wp_error($comment)) {
    $data = intval($comment->get_error_data());
    if (!empty($data)) {
        wp_die('<p>' . $comment->get_error_message() . '</p>', __('Comment Submission Failure'), array('response' => $data, 'back_link' => true));
    } else {
        exit;
    }
}

$user = wp_get_current_user();
$cookies_consent = (isset($_POST['wp-comment-cookies-consent']));

do_action('set_comment_cookies', $comment, $user, $cookies_consent);

$location = empty($_POST['redirect_to']) ? get_comment_link($comment) : $_POST['redirect_to'] . '#comment-' . $comment->comment_ID;

$location = apply_filters('comment_post_redirect', $location, $comment);

wp_safe_redirect($location);
exit;
