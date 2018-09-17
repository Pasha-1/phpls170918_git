<?php
require(dirname(__FILE__) . '/wp-load.php');

if (!apply_filters('enable_post_by_email_configuration', true))
    wp_die(__('This action has been disabled by the administrator.'), 403);

$mailserver_url = get_option('mailserver_url');

if ('mail.example.com' === $mailserver_url || empty($mailserver_url)) {
    wp_die(__('This action has been disabled by the administrator.'), 403);
}

do_action('wp-mail.php');

require_once(ABSPATH . WPINC . '/class-pop3.php');

if (!defined('WP_MAIL_INTERVAL'))
    define('WP_MAIL_INTERVAL', 300);

$last_checked = get_transient('mailserver_last_checked');

if ($last_checked)
    wp_die(__('Slow down cowboy, no need to check for new mails so often!'));

set_transient('mailserver_last_checked', true, WP_MAIL_INTERVAL);

$time_difference = get_option('gmt_offset') * HOUR_IN_SECONDS;

$phone_delim = '::';

$pop3 = new POP3();

if (!$pop3->connect(get_option('mailserver_url'), get_option('mailserver_port')) || !$pop3->user(get_option('mailserver_login')))
    wp_die(esc_html($pop3->ERROR));

$count = $pop3->pass(get_option('mailserver_pass'));

if (false === $count)
    wp_die(esc_html($pop3->ERROR));

if (0 === $count) {
    $pop3->quit();
    wp_die(__('There doesn&#8217;t seem to be any new mail.'));
}

for ($i = 1; $i <= $count; $i++) {

    $message = $pop3->get($i);

    $bodysignal = false;
    $boundary = '';
    $charset = '';
    $content = '';
    $content_type = '';
    $content_transfer_encoding = '';
    $post_author = 1;
    $author_found = false;
    foreach ($message as $line) {
        if (strlen($line) < 3)
            $bodysignal = true;
        if ($bodysignal) {
            $content .= $line;
        } else {
            if (preg_match('/Content-Type: /i', $line)) {
                $content_type = trim($line);
                $content_type = substr($content_type, 14, strlen($content_type) - 14);
                $content_type = explode(';', $content_type);
                if (!empty($content_type[1])) {
                    $charset = explode('=', $content_type[1]);
                    $charset = (!empty($charset[1])) ? trim($charset[1]) : '';
                }
                $content_type = $content_type[0];
            }
            if (preg_match('/Content-Transfer-Encoding: /i', $line)) {
                $content_transfer_encoding = trim($line);
                $content_transfer_encoding = substr($content_transfer_encoding, 27, strlen($content_transfer_encoding) - 27);
                $content_transfer_encoding = explode(';', $content_transfer_encoding);
                $content_transfer_encoding = $content_transfer_encoding[0];
            }
            if (($content_type == 'multipart/alternative') && (false !== strpos($line, 'boundary="')) && ('' == $boundary)) {
                $boundary = trim($line);
                $boundary = explode('"', $boundary);
                $boundary = $boundary[1];
            }
            if (preg_match('/Subject: /i', $line)) {
                $subject = trim($line);
                $subject = substr($subject, 9, strlen($subject) - 9);
                if (function_exists('iconv_mime_decode')) {
                    $subject = iconv_mime_decode($subject, 2, get_option('blog_charset'));
                } else {
                    $subject = wp_iso_descrambler($subject);
                }
                $subject = explode($phone_delim, $subject);
                $subject = $subject[0];
            }
            if (!$author_found && preg_match('/^(From|Reply-To): /', $line)) {
                if (preg_match('|[a-z0-9_.-]+@[a-z0-9_.-]+(?!.*<)|i', $line, $matches))
                    $author = $matches[0];
                else
                    $author = trim($line);
                $author = sanitize_email($author);
                if (is_email($author)) {
                    echo '<p>' . sprintf(__('Author is %s'), $author) . '</p>';
                    $userdata = get_user_by('email', $author);
                    if (!empty($userdata)) {
                        $post_author = $userdata->ID;
                        $author_found = true;
                    }
                }
            }

            if (preg_match('/Date: /i', $line)) {
                $ddate = str_replace('Date: ', '', trim($line));
                $ddate = preg_replace('!\s*\(.+\)\s*$!', '', $ddate);
                $ddate_U = strtotime($ddate);
                $post_date = gmdate('Y-m-d H:i:s', $ddate_U + $time_difference);
                $post_date_gmt = gmdate('Y-m-d H:i:s', $ddate_U);
            }
        }
    }

    if ($author_found) {
        $user = new WP_User($post_author);
        $post_status = ($user->has_cap('publish_posts')) ? 'publish' : 'pending';
    } else {
        $post_status = 'pending';
    }

    $subject = trim($subject);

    if ($content_type == 'multipart/alternative') {
        $content = explode('--' . $boundary, $content);
        $content = $content[2];

        if (preg_match('/Content-Transfer-Encoding: quoted-printable/i', $content, $delim)) {
            $content = explode($delim[0], $content);
            $content = $content[1];
        }
        $content = strip_tags($content, '<img><p><br><i><b><u><em><strong><strike><font><span><div>');
    }
    $content = trim($content);

    $content = apply_filters('wp_mail_original_content', $content);

    if (false !== stripos($content_transfer_encoding, "quoted-printable")) {
        $content = quoted_printable_decode($content);
    }

    if (function_exists('iconv') && !empty($charset)) {
        $content = iconv($charset, get_option('blog_charset'), $content);
    }

    $content = explode($phone_delim, $content);
    $content = empty($content[1]) ? $content[0] : $content[1];

    $content = trim($content);

    $post_content = apply_filters('phone_content', $content);

    $post_title = xmlrpc_getposttitle($content);

    if ($post_title == '') $post_title = $subject;

    $post_category = array(get_option('default_email_category'));

    $post_data = compact('post_content', 'post_title', 'post_date', 'post_date_gmt', 'post_author', 'post_category', 'post_status');
    $post_data = wp_slash($post_data);

    $post_ID = wp_insert_post($post_data);
    if (is_wp_error($post_ID))
        echo "\n" . $post_ID->get_error_message();

    if (empty($post_ID))
        continue;

    do_action('publish_phone', $post_ID);

    echo "\n<p><strong>" . __('Author:') . '</strong> ' . esc_html($post_author) . '</p>';
    echo "\n<p><strong>" . __('Posted title:') . '</strong> ' . esc_html($post_title) . '</p>';

    if (!$pop3->delete($i)) {
        echo '<p>' . sprintf(
                __('Oops: %s'),
                esc_html($pop3->ERROR)
            ) . '</p>';
        $pop3->reset();
        exit;
    } else {
        echo '<p>' . sprintf(
                __('Mission complete. Message %s deleted.'),
                '<strong>' . $i . '</strong>'
            ) . '</p>';
    }

}

$pop3->quit();
