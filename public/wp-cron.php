<?php
ignore_user_abort(true);

if ( !empty($_POST) || defined('DOING_AJAX') || defined('DOING_CRON') )
	die();

define('DOING_CRON', true);

if ( !defined('ABSPATH') ) {
	require_once( dirname( __FILE__ ) . '/wp-load.php' );
}

function _get_cron_lock() {
	global $wpdb;

	$value = 0;
	if ( wp_using_ext_object_cache() ) {
		$value = wp_cache_get( 'doing_cron', 'transient', true );
	} else {
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", '_transient_doing_cron' ) );
		if ( is_object( $row ) )
			$value = $row->option_value;
	}

	return $value;
}

if ( false === $crons = _get_cron_array() )
	die();

$keys = array_keys( $crons );
$gmt_time = microtime( true );

if ( isset($keys[0]) && $keys[0] > $gmt_time )
	die();


$doing_cron_transient = get_transient( 'doing_cron' );

if ( empty( $doing_wp_cron ) ) {
	if ( empty( $_GET[ 'doing_wp_cron' ] ) ) {
		if ( $doing_cron_transient && ( $doing_cron_transient + WP_CRON_LOCK_TIMEOUT > $gmt_time ) )
			return;
		$doing_cron_transient = $doing_wp_cron = sprintf( '%.22F', microtime( true ) );
		set_transient( 'doing_cron', $doing_wp_cron );
	} else {
		$doing_wp_cron = $_GET[ 'doing_wp_cron' ];
	}
}
if ( $doing_cron_transient != $doing_wp_cron )
	return;

foreach ( $crons as $timestamp => $cronhooks ) {
	if ( $timestamp > $gmt_time )
		break;

	foreach ( $cronhooks as $hook => $keys ) {

		foreach ( $keys as $k => $v ) {

			$schedule = $v['schedule'];

			if ( $schedule != false ) {
				$new_args = array($timestamp, $schedule, $hook, $v['args']);
				call_user_func_array('wp_reschedule_event', $new_args);
			}

			wp_unschedule_event( $timestamp, $hook, $v['args'] );
            do_action_ref_array( $hook, $v['args'] );

			if ( _get_cron_lock() != $doing_wp_cron )
				return;
		}
	}
}

if ( _get_cron_lock() == $doing_wp_cron )
	delete_transient( 'doing_cron' );

die();
