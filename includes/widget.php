<?php

/**
 * Adds Foo_Widget widget.
 */
class TP_YTW_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'tp_ytw_widget', // Base ID
            __( 'TomParisDE YouTube Widget', 'tp-ytw' ), // Name
            array( 'description' => __( 'Your TomParisDE YouTube Widget for Statistics, Banner and More!', 'tp-ytw' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }


        if ( ! empty( $instance['channel_id'] ) && ! empty( $instance['api_key'] ) ) {

            $youtube_channel_id = $instance['channel_id'];
            $youtube_api_key = $instance['api_key'];
            $banner_postion = $instance['banner_postion'];
            $header_background = $instance['header_background'];
            $header_color = $instance['header_color'];
            $statistics_background = $instance['statistics_background'];
            $statistics_color = $instance['statistics_color'];
            $latest_videos_max = $instance['latest_videos'];
            $latest_videos_title_color = $instance['latest_videos_title_color'];
            $latest_videos_view_color = $instance['latest_videos_view_color'];

            // Get cache
            $cache = get_transient( TP_YTW_CACHE . $youtube_channel_id );

            // If cache is empty, fetch data from API
            if ( false === $cache || WP_DEBUG) {
                $youtube_statistics = tp_ytw_get_youtube_statistics($youtube_channel_id, $youtube_api_key);
                $youtube_snippet = tp_ytw_get_youtube_snippet($youtube_channel_id, $youtube_api_key);
                $youtube_brandingsettings = tp_ytw_get_youtube_brandingsettings($youtube_channel_id, $youtube_api_key);
                $youtube_latest_videos = tp_ytw_get_youtube_latest_videos($youtube_channel_id, $youtube_api_key);
                $youtube_video_counts = tp_ytw_get_youtube_video_counts($youtube_channel_id, $youtube_api_key);

                // Prepare data
                $cache = array(
                    'youtube_statistics' => $youtube_statistics,
                    'youtube_snippet' => $youtube_snippet,
                    'youtube_brandingsettings' => $youtube_brandingsettings,
                    'youtube_latest_videos' => $youtube_latest_videos,
                    'youtube_video_counts' => $youtube_video_counts
                );

                // Save cache
                set_transient( TP_YTW_CACHE . $youtube_channel_id, $cache, 60 * 60 * 1 );

            } else {
                // Otherwise use cache
                $youtube_statistics = $cache['youtube_statistics'];
                $youtube_snippet = $cache['youtube_snippet'];
                $youtube_brandingsettings = $cache['youtube_brandingsettings'];
                $youtube_latest_videos = $cache['youtube_latest_videos'];
                $youtube_video_counts = $cache['youtube_video_counts'];
            }

            $youtube_channel_name = (strlen($youtube_snippet['youtube_name']) > 15) ? substr($youtube_snippet['youtube_name'],0,13).'...' : $youtube_snippet['youtube_name'];

            include TP_YTW_DIR . 'views/youtube.php';

        }

        else {
            echo __('Wrong Channel-ID or API-Key', 'tp-ytw' );
        }

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $channel_id = ! empty( $instance['channel_id'] ) ? $instance['channel_id'] : '';
        $api_key = ! empty( $instance['api_key'] ) ? $instance['api_key'] : '';
        $banner_postion = ! empty( $instance['banner_postion'] ) ? $instance['banner_postion'] : 'bottom';
        $header_background = ! empty( $instance['header_background'] ) ? $instance['header_background'] : '';
        $header_color = ! empty( $instance['header_color'] ) ? $instance['header_color'] : '';
        $statistics_background = ! empty( $instance['statistics_background'] ) ? $instance['statistics_background'] : '';
        $statistics_color = ! empty( $instance['statistics_color'] ) ? $instance['statistics_color'] : '';
        $latest_videos_max = ! empty( $instance['latest_videos'] ) ? $instance['latest_videos'] : '';
        $latest_videos_title_color = ! empty( $instance['latest_videos_title_color'] ) ? $instance['latest_videos_title_color'] : '';
        $latest_videos_view_color = ! empty( $instance['latest_videos_view_color'] ) ? $instance['latest_videos_view_color'] : '';

        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'channel_id' ); ?>"><?php _e( 'Channel ID:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'channel_id' ); ?>" name="<?php echo $this->get_field_name( 'channel_id' ); ?>" type="text" value="<?php echo esc_attr( $channel_id ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'api_key' ); ?>"><?php _e( 'YouTube Data API Key:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'api_key' ); ?>" name="<?php echo $this->get_field_name( 'api_key' ); ?>" type="text" value="<?php echo esc_attr( $api_key ); ?>">
        </p>

        <br />

        <p>
            <label for="<?php echo $this->get_field_id( 'banner_postion' ); ?>"><?php _e( 'YouTube Banner Position:', 'tp-ytw' ); ?></label>

            <select class="widefat" id="<?php echo $this->get_field_id( 'banner_postion' ); ?>" name="<?php echo $this->get_field_name( 'banner_postion' ); ?>">
                <option value="top"<?php selected( $banner_postion, "top" ); ?>><?php _e( 'Top', 'tp-ytw' ); ?></option>
                <option value="middle"<?php selected( $banner_postion, "middle" ); ?>><?php _e( 'Middle', 'tp-ytw' ); ?></option>
                <option value="bottom"<?php selected( $banner_postion, "bottom" ); ?>><?php _e( 'Bottom', 'tp-ytw' ); ?></option>
                <option value="hidden"<?php selected( $banner_postion, "hidden" ); ?>><?php _e( 'Hidden', 'tp-ytw' ); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'header_background' ); ?>"><?php _e( 'YouTube Box - Background Color:', 'tp-ytw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'header_background' ); ?>" name="<?php echo $this->get_field_name( 'header_background' ); ?>" placeholder="#B31217" type="text" value="<?php echo esc_attr( $header_background ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'header_color' ); ?>"><?php _e( 'YouTube Box - Font Color:', 'tp-ytw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'header_color' ); ?>" name="<?php echo $this->get_field_name( 'header_color' ); ?>" placeholder="#ffffff" type="text" value="<?php echo esc_attr( $header_color ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'statistics_color' ); ?>"><?php _e( 'Statistics Box - Font Color:', 'tp-ytw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'statistics_color' ); ?>" name="<?php echo $this->get_field_name( 'statistics_color' ); ?>" placeholder="#222222" type="text" value="<?php echo esc_attr( $statistics_color ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'statistics_background' ); ?>"><?php _e( 'Body Background Color:', 'tp-ytw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'statistics_background' ); ?>" name="<?php echo $this->get_field_name( 'statistics_background' ); ?>" placeholder="#F1F1F1" type="text" value="<?php echo esc_attr( $statistics_background ); ?>">
        </p>

        <br />

        <p>
            <label for="<?php echo $this->get_field_id( 'latest_videos' ); ?>"><?php _e( 'Latest Videos', 'tp-ytw' ); ?></label>

            <select class="widefat" id="<?php echo $this->get_field_id( 'latest_videos' ); ?>" name="<?php echo $this->get_field_name( 'latest_videos' ); ?>">
                <option value="0"<?php selected( $latest_videos_max, "0" ); ?>><?php _e( 'Hide latest Videos', 'tp-ytw' ); ?></option>
                <option value="1"<?php selected( $latest_videos_max, "1" ); ?>>1 Video</option>
                <option value="2"<?php selected( $latest_videos_max, "2" ); ?>>2 Videos</option>
                <option value="3"<?php selected( $latest_videos_max, "3" ); ?>>3 Videos</option>
                <option value="4"<?php selected( $latest_videos_max, "4" ); ?>>4 Videos</option>
                <option value="5"<?php selected( $latest_videos_max, "5" ); ?>>5 Videos</option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'latest_videos_title_color' ); ?>"><?php _e( 'Latest Videos - Title Color:', 'tp-ytw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'latest_videos_title_color' ); ?>" name="<?php echo $this->get_field_name( 'latest_videos_title_color' ); ?>" placeholder="#222222" type="text" value="<?php echo esc_attr( $latest_videos_title_color ); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'latest_videos_view_color' ); ?>"><?php _e( 'Latest Videos - Views Color:', 'tp-ytw' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'latest_videos_view_color' ); ?>" name="<?php echo $this->get_field_name( 'latest_videos_view_color' ); ?>" placeholder="#767676" type="text" value="<?php echo esc_attr( $latest_videos_view_color ); ?>">
        </p>

        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['channel_id'] = ( ! empty( $new_instance['channel_id'] ) ) ? strip_tags( $new_instance['channel_id'] ) : '';
        $instance['api_key'] = ( ! empty( $new_instance['api_key'] ) ) ? strip_tags( $new_instance['api_key'] ) : '';
        $instance['banner_postion'] = ( ! empty( $new_instance['banner_postion'] ) ) ? strip_tags( $new_instance['banner_postion'] ) : 'bottom';
        $instance['header_background'] = ( ! empty( $new_instance['header_background'] ) ) ? strip_tags( $new_instance['header_background'] ) : '';
        $instance['header_color'] = ( ! empty( $new_instance['header_color'] ) ) ? strip_tags( $new_instance['header_color'] ) : '';
        $instance['statistics_background'] = ( ! empty( $new_instance['statistics_background'] ) ) ? strip_tags( $new_instance['statistics_background'] ) : '';
        $instance['statistics_color'] = ( ! empty( $new_instance['statistics_color'] ) ) ? strip_tags( $new_instance['statistics_color'] ) : '';
        $instance['latest_videos'] = ( ! empty( $new_instance['latest_videos'] ) ) ? strip_tags( $new_instance['latest_videos'] ) : '';
        $instance['latest_videos_title_color'] = ( ! empty( $new_instance['latest_videos_title_color'] ) ) ? strip_tags( $new_instance['latest_videos_title_color'] ) : '';
        $instance['latest_videos_view_color'] = ( ! empty( $new_instance['latest_videos_view_color'] ) ) ? strip_tags( $new_instance['latest_videos_view_color'] ) : '';

        return $instance;
    }

} // class Foo_Widget


// register TP YouTube widget
function tp_ytw_register_widget() {
    register_widget( 'TP_YTW_Widget' );
}
add_action( 'widgets_init', 'tp_ytw_register_widget' );