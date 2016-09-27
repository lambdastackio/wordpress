<?php

function wp_rp_update_tags($post_id) {
	global $wpdb;

	$post = get_post($post_id);
	if ($post->post_parent) {	// Handling revisions
		$post = get_post($post->post_parent);
	}

	$options = wp_rp_get_options();

	delete_post_meta($post->ID, '_wp_rp_related_posts_query_result_cache_expiration');
	delete_post_meta($post->ID, '_wp_rp_related_posts_query_result_cache_'.$options['max_related_posts']);

	$wpdb->query(
		$wpdb->prepare('DELETE from ' . $wpdb->prefix . 'wp_rp_tags WHERE post_id=%d', $post->ID)
	);

	if ($post->post_type !== 'post' ||  $post->post_status !== 'publish') {
		return;
	}

	wp_rp_generate_tags($post);
}
add_action('save_post', 'wp_rp_update_tags');

function wp_rp_get_exclude_ids_list_string($exclude_ids = array()) {
	global $post;

	array_push($exclude_ids, $post->ID);
	$exclude_ids = array_map('intval', $exclude_ids);
	$exclude_ids_str = implode(', ', $exclude_ids);

	return $exclude_ids_str;
}

global $wp_rp_unigrams;
function wp_rp_get_unigrams() {
	global $wp_rp_unigrams;
	if ($wp_rp_unigrams) {
		return $wp_rp_unigrams;
	}

	$wp_rp_unigrams = array();

	$unigrams_file = dirname(__FILE__) . '/lib/unigrams.csv';
	$handle = fopen($unigrams_file, 'r');
	while (($data = fgets($handle, 50)) !== FALSE) {
		$un = explode("\t", $data);
		if (count($un) == 5) {
			$wp_rp_unigrams[$un[0]] = floatval($un[4]);
		}
	}
	fclose($handle);

	return $wp_rp_unigrams;
}

function wp_rp_generate_auto_tags($post) {
	$suitable_words = wp_rp_get_unigrams();

	$words = array_slice(array_merge(explode(' ', $post->post_title), explode(' ', $post->post_content)), 0, WP_RP_RECOMMENDATIONS_AUTO_TAGS_MAX_WORDS);

	$bag_of_words = array();
	foreach ($words as $word) {
		$word = strtolower($word);
		$word = preg_replace('/[\W_]+/', '', $word);
		$stem = PorterStemmer::Stem($word);
		if ($stem) {
			if (!isset($bag_of_words[$stem])) {
				$bag_of_words[$stem] = 1;
			} else {
				$bag_of_words[$stem] += 1;
			}
		}
	}

	$selected_words = array();
	foreach ($bag_of_words as $word => $freq) {
		if (isset($suitable_words[$word])) {
			$selected_words[$word] = $suitable_words[$word] * sqrt($freq);
		}
	}

	asort($selected_words);
	$selected_words = array_reverse($selected_words, true);

	$auto_tags = array_slice($selected_words, 0, WP_RP_RECOMMENDATIONS_AUTO_TAGS_MAX_TAGS, true);

	return array_keys($auto_tags);
}

function wp_rp_get_post_term_tags($post, $term, $skip=array()) {
	$tags = wp_get_post_terms($post->ID, $term);

	$skip = array_flip($skip);

	$selected_tags = array();
	foreach ($tags as $tag) {
		if (!isset($skip[$tag->name])) {
			array_push($selected_tags, $tag->name);
		}
	}
	return $selected_tags;
}

function wp_rp_generate_tags($post) {
	global $wpdb;

	$tag_groups = array(
		'tags' => array(
			'labels' => wp_rp_get_post_term_tags($post, 'post_tag'),
			'weight' => WP_RP_RECOMMENDATIONS_TAGS_SCORE,
			'prefix' => 'P_',
		),
		'categories' => array(
			'labels' => wp_rp_get_post_term_tags($post, 'category', array('Uncategorized')),
			'weight' => WP_RP_RECOMMENDATIONS_CATEGORIES_SCORE,
			'prefix' => 'C_',
		),
		'auto_tags' => array(
			'labels' => wp_rp_generate_auto_tags($post),
			'weight' => WP_RP_RECOMMENDATIONS_AUTO_TAGS_SCORE,
			'prefix' => 'A_',
		)
	);

	$all_tags = array();
	$sql_tag_values = array();
	foreach ($tag_groups as $type => $tag_obj) {
		$weight = $tag_obj['weight'];

		foreach ($tag_obj['labels'] as $label) {
			$label = $tag_obj['prefix'] . strtolower($label);
			$label = substr($label, 0, WP_RP_MAX_LABEL_LENGTH);

			array_push($all_tags, $label);

			array_push($sql_tag_values, $post->ID);
			array_push($sql_tag_values, $post->post_date);
			array_push($sql_tag_values, $label);
			array_push($sql_tag_values, $weight);
		}
	}

	if (count($all_tags) > 0 && $post->post_status == 'publish') {
		$sql_tag_format_line = '(%d, %s, %s, %f)';
		$tags_insert_query = $wpdb->prepare('INSERT INTO ' . $wpdb->prefix . 'wp_rp_tags (post_id, post_date, label, weight)
				VALUES ' . implode(', ', array_fill(0, count($all_tags), $sql_tag_format_line)) . ';',
			$sql_tag_values);

		$wpdb->query($tags_insert_query);
	}

	return $all_tags;
}

function wp_rp_fetch_related_posts_v2($limit = 10, $exclude_ids = array()) {
	global $wpdb, $post;
	$options = wp_rp_get_options();

	$timestamp = time();
	$related_posts_query_result_cache_expiration = (int) get_post_meta($post->ID, '_wp_rp_related_posts_query_result_cache_expiration', true);
	$related_posts_query_result_cache = get_post_meta($post->ID, '_wp_rp_related_posts_query_result_cache_'.$options['max_related_posts'], true);

	if (!$related_posts_query_result_cache || !$related_posts_query_result_cache_expiration || $related_posts_query_result_cache_expiration < $timestamp) { // Cache empty or never cached or cache expired
		$related_post_ids = null;
		$exclude_ids_str = wp_rp_get_exclude_ids_list_string($exclude_ids);

		$tags_query = "SELECT label FROM " . $wpdb->prefix . "wp_rp_tags WHERE post_id=$post->ID;";
		$tags = $wpdb->get_col($tags_query, 0);
		if (empty($tags)) {
			$tags = wp_rp_generate_tags($post);
			if (empty($tags)) {
				return array();
			}
		}

		if($options['exclude_categories']) {
			$exclude_categories = get_categories(array('include' => $options['exclude_categories']));
			$exclude_categories_labels = array_map(create_function('$c', 'return "C_" . $c->name;'), $exclude_categories);
		} else {
			$exclude_categories_labels = array();
		}

		$total_number_of_posts = $wpdb->get_col("SELECT count(post_id) from (SELECT post_id FROM " . $wpdb->prefix . "wp_rp_tags group by post_id) t;", 0);
		if (empty($total_number_of_posts)) {
			return array();
		}
		$total_number_of_posts = $total_number_of_posts[0];

		$post_id_query = $wpdb->prepare("
			SELECT
				target.post_id, sum(target.weight * log(%d / least(%d, freqs.freq))) as score
			FROM
				" . $wpdb->prefix . "wp_rp_tags as target,
				(SELECT label, count(1) as freq FROM " . $wpdb->prefix . "wp_rp_tags
					WHERE label IN (" . implode(', ', array_fill(0, count($tags), "%s"))  . ")
					GROUP BY label
				) as freqs
			WHERE
				target.post_id NOT IN (%s) AND
				" . ($options['max_related_post_age_in_days'] > 0 ? "target.post_date > DATE_SUB(CURDATE(), INTERVAL %s DAY) AND" : "") . "
				target.label=freqs.label AND
				target.label IN (" . implode(', ', array_fill(0, count($tags), "%s"))  . ")" .
				(empty($exclude_categories_labels) ? "" : " AND
					target.post_id NOT IN (
						SELECT post_id FROM " . $wpdb->prefix . "wp_rp_tags
						WHERE label IN (" . implode(', ', array_fill(0, count($exclude_categories_labels), "%s")) . ")
					)") . "
			GROUP BY target.post_id
			ORDER BY score desc, target.post_id desc
			LIMIT %d;",
			array_merge(
				array($total_number_of_posts, $total_number_of_posts),
				$tags,
				array($exclude_ids_str),
				$options['max_related_post_age_in_days'] > 0 ? array($options['max_related_post_age_in_days']) : array(),
				$tags,
				$exclude_categories_labels,
				array($limit * 2)
			)
		);	// limit * 2 just in case

		$related_posts_query_result_cache = $wpdb->get_results($post_id_query, 0);
		if (empty($related_posts_query_result_cache)) {
			return array();
		}

		// Update the cache
		if ($timestamp - strtotime($post->post_date) > 30 * 24 * 60 * 60) { // Post is older than one month
			update_post_meta($post->ID, '_wp_rp_related_posts_query_result_cache_expiration', $timestamp + 30 * 24 * 60 * 60); // Cache for one month
		} else {
			update_post_meta($post->ID, '_wp_rp_related_posts_query_result_cache_expiration', $timestamp + 24 * 60 * 60); // Cache for one day
		}

		update_post_meta($post->ID, '_wp_rp_related_posts_query_result_cache_'.$options['max_related_posts'], $related_posts_query_result_cache);
	}

	$related_posts_with_score_map = array();
	foreach ($related_posts_query_result_cache as $rp) {
		$related_posts_with_score_map[$rp->post_id] = $rp->score;
	}

	$related_post_ids = array_keys($related_posts_with_score_map);

	$now = current_time('mysql', 1);
	$post_query = $wpdb->prepare("
		SELECT post.ID, post.post_title, post.post_excerpt, post.post_content, post.post_date, post.comment_count
		FROM $wpdb->posts as post
		WHERE post.ID IN (" . implode(', ', array_fill(0, count($related_post_ids), '%d')) . ")
			AND post.post_type = 'post'
			AND post.post_status = 'publish'
			AND post.post_date_gmt < %s",
		array_merge($related_post_ids, array($now)));

	$related_posts = $wpdb->get_results($post_query);

	foreach ($related_posts as $rp) {
		$rp->wp_rp_score = $related_posts_with_score_map[$rp->ID];
	}

	usort($related_posts, create_function('$a,$b', 'return $b->wp_rp_score < $a->wp_rp_score ? -1 : 1;'));

	$related_posts = array_slice($related_posts, 0, $limit);

	return $related_posts;
}

function wp_rp_fetch_related_posts($limit = 10, $exclude_ids = array()) {
	global $wpdb, $post;
	$options = wp_rp_get_options();

	$exclude_ids_str = wp_rp_get_exclude_ids_list_string($exclude_ids);

	if(!$post->ID){return;}
	$now = current_time('mysql', 1);
	$tags = wp_get_post_tags($post->ID);

	$tagcount = count($tags);
	$taglist = false;
	if ($tagcount > 0) {
		$taglist = "'" . $tags[0]->term_id. "'";
		for ($i = 1; $i < $tagcount; $i++) {
			$taglist = $taglist . ", '" . $tags[$i]->term_id . "'";
		}
	}

	$related_posts = false;
	if ($taglist) {
		$q = "SELECT p.ID, p.post_title, p.post_content,p.post_excerpt, p.post_date, p.comment_count, count(t_r.object_id) as cnt 
				FROM $wpdb->term_taxonomy t_t, $wpdb->term_relationships t_r, $wpdb->posts p
				WHERE t_t.taxonomy ='post_tag'
					AND t_t.term_taxonomy_id = t_r.term_taxonomy_id
					AND t_r.object_id = p.ID
					AND (t_t.term_id IN ($taglist))
					AND p.ID NOT IN ($exclude_ids_str)
					AND " . (!$options['exclude_categories'] ? "" : "p.ID NOT IN (
						SELECT tr.object_id FROM $wpdb->term_taxonomy tt, $wpdb->term_relationships tr
							WHERE tt.taxonomy = 'category'
							AND tt.term_taxonomy_id = tr.term_taxonomy_id
							AND tt.term_id IN (" . $options['exclude_categories'] . "))
							AND "
						) .
						"p.post_status = 'publish'
					AND p.post_type = 'post'
					AND " . ($options['max_related_post_age_in_days'] == 0 ? ""
						: "p.post_date > DATE_SUB(CURDATE(), INTERVAL " . $options['max_related_post_age_in_days'] . " DAY) AND ") .
					    "p.post_date_gmt < '$now' GROUP BY t_r.object_id
				ORDER BY cnt DESC, p.post_date_gmt DESC LIMIT $limit;";

		$related_posts = $wpdb->get_results($q);
	}

	return $related_posts;
}

function wp_rp_fetch_random_posts($limit = 10, $exclude_ids = array()) {
	global $wpdb, $post;
	$options = wp_rp_get_options();

	$exclude_ids_str = wp_rp_get_exclude_ids_list_string($exclude_ids);

	$q1 = "SELECT ID FROM $wpdb->posts posts WHERE post_status = 'publish' AND post_type = 'post' AND ID NOT IN ($exclude_ids_str)";
	if($options['exclude_categories']) {
		$q1 .= " AND ID NOT IN (SELECT tr.object_id FROM $wpdb->term_taxonomy tt, $wpdb->term_relationships tr WHERE tt.taxonomy = 'category' AND tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.term_id IN (" . $options['exclude_categories'] . "))";
	}
	if($options['max_related_post_age_in_days'] > 0) {
		$q1 .= " AND post_date > DATE_SUB(CURDATE(), INTERVAL " . $options['max_related_post_age_in_days'] . " DAY)";
	}
	$ids = $wpdb->get_col($q1, 0);
	$count = count($ids);
	if($count === 0) {
		return false;
	/*} else if($count === 1) {
		$rnd = $ids;*/
	} else if($count > 1) {
		$display_number = min($limit, $count);

		$next_seed = rand();
		$t = time();
		$seed = $t - $t % 300 + $post->ID << 4;		// We keep the same seed for 5 minutes, so MySQL can cache the `q2` query.
		srand($seed);

		shuffle($ids);

		$ids = array_slice($ids, 0, $display_number);

		srand($next_seed);
	}
	$q2 = "SELECT ID, post_title, post_content, post_excerpt, post_date, comment_count FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND ID IN (" . implode(',', $ids) . ")";
	$results = $wpdb->get_results($q2);
	asort($ids);
	$ids_keys = array_keys($ids);
	array_multisort($ids_keys, $results);
	return $results;
}

function wp_rp_get_post_categories() {
	global $wpdb;
	
	$post_query = "SELECT p.ID as pid, te.name as cat_name, te.term_id as cat_id
		FROM $wpdb->posts p, $wpdb->terms te, $wpdb->term_taxonomy tt, $wpdb->term_relationships tr
		WHERE p.post_status = 'publish' AND p.post_type = 'post'
			AND tt.taxonomy='category' AND tt.term_id = te.term_id
			AND tr.object_id = p.ID AND tt.term_taxonomy_id = tr.term_taxonomy_id";
	$posts = $wpdb->get_results($post_query);

	$result = array();
	foreach($posts as $row) {
		if (!isset($result[$row->pid])) {
			$result[$row->pid] = array();
		}
		$result[$row->pid][$row->cat_id] = $row->cat_name;
	}
	return $result;
}

