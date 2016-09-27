<?php

function wp_rp_get_all_attachments() {
    global $wpdb;
    $images = $wpdb->get_results("select p1.*, m.meta_value as meta
        FROM {$wpdb->posts} p1, {$wpdb->posts} p2, {$wpdb->postmeta} m
        WHERE m.post_id = p1.ID
           AND p1.post_parent = p2.ID 
           AND p1.post_mime_type LIKE 'image%'
           AND p2.post_type = 'post'
           AND m.meta_key = '_wp_attachment_metadata'
        ORDER BY p2.post_date;"
    );
    return $images;
}

function wp_rp_article_count($api_key) {
	$http_response = wp_remote_get(WP_RP_ZEMANTA_ARTICLE_COUNT_URL . $api_key . '/');
	if (!is_wp_error($http_response) && wp_remote_retrieve_response_code($http_response) == 200) {
		$response = json_decode(wp_remote_retrieve_body($http_response));
		if ($response->status == 'ok') {
			return $response->articles;
		}
	} 
	return __('Sorry, could not connect to server', 'wp_related_posts');
}

function wp_rp_upload_articles($api_key) {
	$media = array();
	foreach(wp_rp_get_all_attachments() as $image) {
		if (empty($media[$image->post_parent])) {
			$media[$image->post_parent] = array();
		}
		$meta = unserialize($image->meta);
		$media["$image->post_parent"][] = array(
			"URL" => $image->guid,
			"width" => $meta['width'],
			"height" => $meta['height']
		);
	}

	$payload = array(
		"found" => 0,
		"posts" => array(),
	);
	foreach(get_posts() as $post) {
		if ($post->post_status !== "publish" || $post->post_type !== "post") { continue; }
		$obj = array(
			"ID" => $post->ID,
			"URL" => get_permalink($post->ID), 
			"attachment_count" => 0,
			"attachments" => array(),
			"content" => $post->post_content,
			"date" => $post->post_date,
			"excerpt" => $post->post_excerpt,
			"featured_image" => "",
			"modified" => $post->post_modified,
			"post_thumbnail" => null,
			"slug" => $post->post_name,
			"status" => $post->post_status,
			"title" => $post->post_title,
			"type" => $post->post_type,
		);
		if (has_post_thumbnail( $post->ID ) ) {
			$thumb_id = get_post_thumbnail_id( $post->ID );
			$meta = wp_get_attachment_metadata($thumb_id);
			$obj["post_thumbnail"] = array(
				"URL" => wp_get_attachment_url ($thumb_id),
				"width" => $meta["width"],
				"height" => $meta["height"]
			);
		}
		if (!empty($media[$post->ID])) {
			$obj["attachments"] = $media[$post->ID];
		}

		$obj["attachment_count"] = sizeof($obj["attachments"]);
		
		$payload["posts"][] = $obj;
	}
	$payload["found"] = sizeof($payload["posts"]);
	$payload["posts"] = $payload["posts"];

	$http_response = wp_remote_post(WP_RP_ZEMANTA_UPLOAD_URL, array(
		"body" => array(
			"payload" => json_encode($payload),
			"blog_name" => get_bloginfo('name'),
			"api_key" => $api_key,
			"feed_url" => get_bloginfo('rss2_url'),
			"blog_url" => get_bloginfo('url'),
			"platform" => 'wordpress-wprp',
		)
	));
	if (!is_wp_error($http_response) && wp_remote_retrieve_response_code($http_response) == 200) {
		$response = json_decode(wp_remote_retrieve_body($http_response));
		return $response->status === 'ok';
	}
	return false;
}
