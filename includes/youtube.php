<?php

function tp_ytw_get_youtube_statistics ($channel_id, $api_key) {
    $data = tp_ytw_get_data('https://www.googleapis.com/youtube/v3/channels?part=statistics&id=' . $channel_id . '&key=' . $api_key);

    $statistics = array(
        'sub_count' => $data['items']['0']['statistics']['subscriberCount'],
        'video_count' => $data['items']['0']['statistics']['videoCount'],
        'views_count' => $data['items']['0']['statistics']['viewCount'],
    );

    return $statistics;
}


function tp_ytw_get_youtube_snippet ($channel_id, $api_key) {
    $data = tp_ytw_get_data('https://www.googleapis.com/youtube/v3/channels?part=snippet&id=' . $channel_id . '&key=' . $api_key);

    $snippet = array(
        'youtube_name' => $data['items']['0']['snippet']['title'],
        'youtube_logo' => $data['items']['0']['snippet']['thumbnails']['default']['url'],
    );

    return $snippet;
}

function tp_ytw_get_youtube_brandingsettings ($channel_id, $api_key) {
    $data = tp_ytw_get_data('https://www.googleapis.com/youtube/v3/channels?part=brandingSettings&id=' . $channel_id . '&key=' . $api_key);

    $brandingsettings = array(
        'youtube_banner' => $data['items']['0']['brandingSettings']['image']['bannerMobileLowImageUrl'],
    );

    return $brandingsettings;
}

function tp_ytw_get_youtube_latest_videos ($channel_id, $api_key) {
    $data = tp_ytw_get_data('https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId=' . $channel_id . '&key=' . $api_key);

    $latest_videos = array();
    $video_ids = '';

    foreach($data['items'] as $item) {

        $latest_videos[] = array(
            'id' => $item['id']['videoId'],
            'thumbnails' => $item['snippet']['thumbnails']['medium']['url'],
            'title' => (strlen($item['snippet']['title']) > 70) ? substr($item['snippet']['title'],0,68).'...' : $item['snippet']['title'],
        );

        $video_ids = (empty($video_ids)) ? $item['id']['videoId'] : $video_ids . ',' . $item['id']['videoId'];
    }

    // Video Counts
    $video_counts = tp_ytw_get_youtube_video_counts ($video_ids, $api_key);

    foreach($latest_videos as $key => $video){
        $latest_videos[$key]['views'] = $video_counts[$key]['statistics']['viewCount'];
    }

    return $latest_videos;
}

function tp_ytw_get_youtube_video_counts ($video_ids, $api_key) {
    $data = tp_ytw_get_data('https://www.googleapis.com/youtube/v3/videos?part=statistics&id=' . $video_ids . '&key=' . $api_key);

    return $data['items'];
}