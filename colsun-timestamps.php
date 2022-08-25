<?php
/**
 * Plugin Name: Colsun Timestamps
 * Description: Modify the timestamp formatting and display.
 * Version:     1.0.0
 * Author:      thecoloradosun, jameswalterburke
 * Text Domain: colsun-timestamps
 *
 * @package     colsun-timestamps
 */


/**
 * Modify the result of get_the_date().
 *
 * @param string|int $the_date Formatted date string or Unix timestamp if
 *                             $format is 'U' or 'G'.
 * @param string $format       PHP date format.
 * @param WP_Post $post        The post object.
 *
 * @return string New format.
 */
function modify_date( $the_date, $format, $post ) {

	// Only apply these changes to the homepage.
	if ( ! is_front_page() ) {
		return $the_date;
	}

	// Get publish date as unix timestamp.
	$published_timestamp = get_post_time( 'U', true, $post );

	// Amount of seconds that have passed since the post was published.
	$elapsed_time_in_seconds = time() - $published_timestamp; // Seconds since the post was published.

	// Within 1 hour.
	if ( $elapsed_time_in_seconds <= HOUR_IN_SECONDS ) {
		return sprintf(
			esc_html__( '%1$s minutes ago', 'colsun-timestamps' ),
			absint( ceil( $elapsed_time_in_seconds / 60 ) )
		);
	}

	// Within 24 hours.
	if ( $elapsed_time_in_seconds <= 24 * HOUR_IN_SECONDS ) {

		// Same day.
		if ( get_the_time( 'Y-m-d', $post ) === date( 'Y-m-d', time() ) ) {
			return gmdate( 'g:i A', $published_timestamp );
		} else {
			return gmdate( 'g:i A D j, Y', $published_timestamp );
		}
	}
}
add_filter( 'get_the_date', __NAMESPACE__ . '\\modify_date', 10, 3 );
