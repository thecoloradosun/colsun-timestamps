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
 * Only affect whatever is at /.
 *
 * We would use is_front_page(), but it's not available early enough for us to
 * still replace the `newspack_posted_on` function defined by the Newspack
 * theme.
 */
if ( '/' !== sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) {
    return;
}

if ( ! function_exists( 'newspack_posted_on' ) ) :
    function newspack_posted_on() {

        if ( true === apply_filters( 'newspack_listings_hide_publish_date', false ) ) {
            return;
        }

        // Get publish date as unix timestamp.
        $published_timestamp = get_the_time( 'U' );

        // Amount of seconds that have passed since the post was published.
        $elapsed_time_in_seconds = time() - $published_timestamp; // Seconds since the post was published.

        // Default display.
        $display_date = get_the_date();

        // Within 24 hours.
        if ( $elapsed_time_in_seconds <= 24 * HOUR_IN_SECONDS ) {

            // Same day.
            if ( get_the_time( 'Y-m-d' ) === date( 'Y-m-d', time() ) ) {
                $display_date = gmdate( 'g:i A', $published_timestamp );
            } else {
                $display_date = gmdate( 'g:i A D j, Y', $published_timestamp );
            }
        }

        // Within 1 hour.
        if ( $elapsed_time_in_seconds <= HOUR_IN_SECONDS ) {
            $display_date = ceil( $elapsed_time_in_seconds / 60 ) . ' Minutes ago';
            $additional_class = 'timestamp-red';
        }

        printf(
            '<time class="entry-date published updated %3$s" datetime="%1$s">%2$s</time>',
            esc_attr( get_the_date( DATE_W3C ) ),
            esc_html( $display_date ),
            esc_attr( $additional_class )
        );
    }
endif;