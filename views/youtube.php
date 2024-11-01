<script src="https://apis.google.com/js/platform.js"></script>

<div class="tp-ytw-wrapper" <?php if (!empty($statistics_background)) echo ' style="background-color:' . $statistics_background . ';"'; ?>>


    <!-- YouTube Banner -->
    <?php if ($banner_postion == 'top') { ?>
        <a target="blank"
           href="https://www.youtube.com/channel/<?php echo $youtube_channel_id ?>" title="<?php echo $youtube_channel_name; ?>"><img class="tp-ytw-banner" src="<?php echo $youtube_brandingsettings['youtube_banner']; ?>" alt="<?php echo $youtube_channel_name; ?>"></a>
    <?php } ?>


    <!-- Red Box -->
    <div
        class="tp-ytw-redbox"<?php if (!empty($header_background)) echo ' style="background-color:' . $header_background . ';"'; ?>>
        <ul class="tp-ytw-redbox-ul tp-ytw-clearfix">
            <li class="tp-ytw-redbox-li"><a target="blank"
                                        href="https://www.youtube.com/channel/<?php echo $youtube_channel_id ?>" title="<?php echo $youtube_channel_name; ?>">
                    <img class="tp-ytw-logo-kreis" src="<?php echo $youtube_snippet['youtube_logo']; ?>" alt="YouTube" width="30px"
                         height="30px"> </a></li>
            <li class="tp-ytw-name"><a target="blank"
                                             href="https://www.youtube.com/channel/<?php echo $youtube_channel_id ?>" title="<?php echo $youtube_channel_name; ?>"<?php if (!empty($header_color)) echo ' style="color:' . $header_color . ';"'; ?>><?php echo $youtube_channel_name; ?></a>
            </li>
            <li class="tp-ytw-subscriber-button">
                <div class="g-ytsubscribe" data-channelid="<?php echo $youtube_channel_id ?>" data-layout="default"
                     data-count="hidden"></div>
            </li>
        </ul>
    </div>


    <!-- YouTube Banner -->
    <?php if ($banner_postion == 'middle') { ?>
    <a target="blank"
       href="https://www.youtube.com/channel/<?php echo $youtube_channel_id ?>" title="<?php echo $youtube_channel_name; ?>"><img class="tp-ytw-banner" src="<?php echo $youtube_brandingsettings['youtube_banner']; ?>" alt="<?php echo $youtube_channel_name; ?>"></a>
    <?php } ?>


    <!-- Statistics Part -->
    <div class="tp-ytw-body tp-ytw-clearfix">
        <ul <?php if (!empty($statistics_color)) echo ' style="color:' . $statistics_color . ';"'; ?>>
            <li class="tp-ytw-stats"><?php _e('Videos', 'tp-ytw'); ?><span
                    class="tp-ytw-statistics"><?php echo tp_ytw_format_numbers($youtube_statistics['video_count']); ?></span>
            </li>
            <li class="tp-ytw-stats"><?php _e('Subscribers', 'tp-ytw'); ?><span
                    class="tp-ytw-statistics"><?php echo tp_ytw_format_numbers($youtube_statistics['sub_count']); ?></span></li>
            <li class="tp-ytw-stats"><?php _e('Views', 'tp-ytw'); ?><span
                    class="tp-ytw-statistics"><?php echo tp_ytw_format_numbers($youtube_statistics['views_count']); ?></span>
            </li>
        </ul>
    </div>


    <!-- YouTube Banner -->
    <?php if ($banner_postion == 'bottom') { ?>
        <a target="blank"
           href="https://www.youtube.com/channel/<?php echo $youtube_channel_id ?>" title="<?php echo $youtube_channel_name; ?>"><img class="tp-ytw-banner" src="<?php echo $youtube_brandingsettings['youtube_banner']; ?>" alt="<?php echo $youtube_channel_name; ?>"></a>
    <?php } ?>

    <!-- YouTube Last X Videos -->
    <?php if ($latest_videos_max != 0) {?>

        <ul class="tp-ytw-latest-video-ul<?php if ($banner_postion == 'bottom') echo ' tp-ytw-banner-bottom'; ?>">
            <?php foreach($youtube_latest_videos as $key => $video) { ?>
                <li class="tp-ytw-latest-video-li">

                    <a class="latest-video-link tp-ytw-clearfix" target="blank"
                       href="https://www.youtube.com/watch?v=<?php echo $video['id'] ?>" title="<?php echo $video['title']; ?>"><img class="tp-ytw-latest_video_thumb" src="<?php echo $video['thumbnails']; ?>" alt="<?php echo $video['title']; ?>" width="120" height="68">
                        <span <?php if (!empty($latest_videos_title_color)) echo ' style="color:' . $latest_videos_title_color . ';"'; ?> class="tp-ytw-latest-video-title"><?php echo $video['title']; ?></span> <span <?php if (!empty($latest_videos_view_color)) echo ' style="color:' . $latest_videos_view_color . ';"'; ?> class="tp-ytw-latest-video-views"><?php echo $video['views']; ?> <?php  _e('Views', 'tp-ytw');?></span>
                    </a>

                </li>

                <?php if($key == ($latest_videos_max -1)) break; ?>
            <?php } ?>
        </ul>

    <?php } ?>

</div>