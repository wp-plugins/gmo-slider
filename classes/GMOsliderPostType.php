<?php
class GMOsliderPostType
{
	static $postType = 'gmoslider';

	static function init()
	{
		add_action('init'                 , array(__CLASS__, 'registerGMOsliderPostType'));
		add_action('save_post'            , array('GMOsliderSettingsHandler', 'save'));
		add_action('admin_enqueue_scripts', array('GMOsliderSlideInserter', 'localizeScript'), 11);

		add_action('admin_action_gmoslider_jquery_image_gallery_duplicate_gmoslider', array(__CLASS__, 'duplicateGMOslider'), 11);

		add_filter('post_updated_messages', array(__CLASS__, 'alterGMOsliderMessages'));
		add_filter('post_row_actions'     , array(__CLASS__, 'duplicateGMOsliderActionLink'), 10, 2);
	}

	static function registerGMOsliderPostType()
	{
		global $wp_version;
		register_post_type(
			self::$postType,
			array(
				'labels'               => array(
					'name'               => __('GMO Slider', 'gmoslider'),
					'singular_name'      => __('GMO Slider', 'gmoslider'),
					'add_new_item'       => __('Add New GMO Slider', 'gmoslider'),
					'edit_item'          => __('Edit GMO Slider', 'gmoslider'),
					'new_item'           => __('New GMO Slider', 'gmoslider'),
					'view_item'          => __('View GMO Slider', 'gmoslider'),
					'search_items'       => __('Search GMO Slider', 'gmoslider'),
					'not_found'          => __('No GMO Slider found', 'gmoslider'),
					'not_found_in_trash' => __('No GMO Slider found', 'gmoslider')
				),
				'public'               => false,
				'publicly_queryable'   => false,
				'show_ui'              => true,
				'show_in_menu'         => true,
				'query_var'            => true,
				'rewrite'              => true,
				'capability_type'      => 'post',
				'capabilities'         => array(
					'edit_post'              => GMOsliderGeneralSettings::$capabilities['editGMOsliders'],
					'read_post'              => GMOsliderGeneralSettings::$capabilities['addGMOsliders'],
					'delete_post'            => GMOsliderGeneralSettings::$capabilities['deleteGMOsliders'],
					'edit_posts'             => GMOsliderGeneralSettings::$capabilities['editGMOsliders'],
					'edit_others_posts'      => GMOsliderGeneralSettings::$capabilities['editGMOsliders'],
					'publish_posts'          => GMOsliderGeneralSettings::$capabilities['addGMOsliders'],
					'read_private_posts'     => GMOsliderGeneralSettings::$capabilities['editGMOsliders'],

					'read'                   => GMOsliderGeneralSettings::$capabilities['addGMOsliders'],
					'delete_posts'           => GMOsliderGeneralSettings::$capabilities['deleteGMOsliders'],
					'delete_private_posts'   => GMOsliderGeneralSettings::$capabilities['deleteGMOsliders'],
					'delete_published_posts' => GMOsliderGeneralSettings::$capabilities['deleteGMOsliders'],
					'delete_others_posts'    => GMOsliderGeneralSettings::$capabilities['deleteGMOsliders'],
					'edit_private_posts'     => GMOsliderGeneralSettings::$capabilities['editGMOsliders'],
					'edit_published_posts'   => GMOsliderGeneralSettings::$capabilities['editGMOsliders'],
				),
				'has_archive'          => true,
				'hierarchical'         => false,
				'menu_position'        => null,
				'menu_icon'            => version_compare($wp_version, '3.8', '<') ? GMOsliderMain::getPluginUrl() . '/images/' . __CLASS__ . '/adminIcon.png' : 'dashicons-format-gallery',
				'supports'             => array('title'),
				'register_meta_box_cb' => array(__CLASS__, 'registerMetaBoxes')
			)
		);
	}

	static function registerMetaBoxes()
	{
		add_meta_box(
			'information',
			__('Information', 'gmoslider'),
			array(__CLASS__, 'informationMetaBox'),
			self::$postType,
			'normal',
			'high'
		);

		add_meta_box(
			'slideslist',
			__('GMO Slider List', 'gmoslider'),
			array(__CLASS__, 'gmoslidersMetaBox'),
			self::$postType,
			'normal',
			'high'
		);

		add_meta_box(
			'style',
			__('GMO Slider Style', 'gmoslider'),
			array(__CLASS__, 'styleMetaBox'),
			self::$postType,
			'normal',
			'low'
		);

		add_meta_box(
			'settings',
			__('GMO Slider Settings', 'gmoslider'),
			array(__CLASS__, 'settingsMetaBox'),
			self::$postType,
			'normal',
			'low'
		);
		
		add_meta_box(
			'gmosidebar',
			__('How To Use', 'gmoslider'),
			array(__CLASS__, 'gmoSidebar'),
			self::$postType,
			'side'
		);
		
	}
	
	
	static function gmoSidebar()
	{
		include GMOsliderMain::getPluginPath() . '/views/' . __CLASS__ . '/gmosidebar.php';
	}

	static function alterGMOsliderMessages($messages)
	{
		if (!function_exists('get_current_screen'))
		{
			return $messages;
		}

		$currentScreen = get_current_screen();

		if ($currentScreen->post_type != GMOsliderPostType::$postType)
		{
			return $messages;
		}

		$messageID = filter_input(INPUT_GET, 'message', FILTER_VALIDATE_INT);

		if (!$messageID)
		{
			return $messages;
		}

		switch ($messageID)
		{
			case 6:
				$messages[$currentScreen->base][$messageID] = __('GMO Slider created', 'gmoslider');
				break;

			default:
				$messages[$currentScreen->base][$messageID] = __('GMO Slider updated', 'gmoslider');
		}

		return $messages;
	}

	static function informationMetaBox()
	{
		global $post;

		$snippet   = htmlentities(sprintf('<?php do_action(\'gmoslider_slider\', \'%s\'); ?>', $post->ID));
		$shortCode = htmlentities(sprintf('[' . GMOsliderShortcode::$shortCode . ' id=\'%s\']', $post->ID));

		include GMOsliderMain::getPluginPath() . '/views/' . __CLASS__ . '/information.php';
	}

	static function gmoslidersMetaBox()
	{
		global $post;

		$views = GMOsliderSettingsHandler::getViews($post->ID);

		echo '<ul class="gmoslider-list-button">' .
			'<li>' . GMOsliderSlideInserter::getImageSlideInsertButton() . '</li><!--' .
			'--><li>' . GMOsliderSlideInserter::getTextSlideInsertButton() . '</li><!--' .
			'--><li>' . GMOsliderSlideInserter::getVideoSlideInsertButton() . '</li>' .
		'</ul>';

		echo '<ul class="gmoslider-list-button">
			<li><a href="#" class="open-slides-button button button-secondary">' . __( 'Open all', 'gmoslider' ) . '</a></li><!--
			
			--><li><a href="#" class="close-slides-button button button-secondary">' . __( 'Close all', 'gmoslider' ) . '</a></li>
		</ul>';

		if (count($views) <= 0)
		{
			echo '<p>' . __('Add slides to this GMO Slider by using one of the buttons above.', 'gmoslider') . '</p>';
		}

		echo '<div class="sortable-slides-list">';

		if (is_array($views))
		{
			foreach($views as $view)
			{
				if (!($view instanceof GMOsliderView))
				{
					continue;
				}

				echo $view->toAdminHTML();
			}
		}

		echo '</div>';

		GMOsliderSlide::getAdminTemplates(false);
	}

	static function showGMOsidebar()
	{
		
	}
	static function styleMetaBox()
	{
		global $post;

		$settings = GMOsliderSettingsHandler::getStyleSettings($post->ID, true);

		include GMOsliderMain::getPluginPath() . '/views/' . __CLASS__ . '/style-settings.php';
	}

	static function settingsMetaBox()
	{
		global $post;

		wp_nonce_field(GMOsliderSettingsHandler::$nonceAction, GMOsliderSettingsHandler::$nonceName);

		$settings = GMOsliderSettingsHandler::getSettings($post->ID, true);

		include GMOsliderMain::getPluginPath() . '/views/' . __CLASS__ . '/settings.php';
	}

	static function duplicateGMOsliderActionLink($actions, $post)
	{
		if (current_user_can('gmoslider-add-gmosliders') &&
			$post->post_type === self::$postType)
		{
			$url = add_query_arg(array(
				'action' => 'gmoslider_jquery_image_gallery_duplicate_gmoslider',
				'post'   => $post->ID,
			));

			$actions['duplicate'] = '<a href="' . wp_nonce_url($url, 'duplicate-gmoslider_' . $post->ID, 'nonce') . '">' . __('Duplicate', 'gmoslider') . '</a>';
		}

		return $actions;
	}

	static function duplicateGMOslider()
	{
		$postID           = filter_input(INPUT_GET, 'post'     , FILTER_VALIDATE_INT);
		$nonce            = filter_input(INPUT_GET, 'nonce'    , FILTER_SANITIZE_STRING);
		$postType         = filter_input(INPUT_GET, 'post_type', FILTER_SANITIZE_STRING);
		$errorRedirectURL = remove_query_arg(array('action', 'post', 'nonce'));

		if (!wp_verify_nonce($nonce, 'duplicate-gmoslider_' . $postID) ||
			!current_user_can('gmoslider-add-gmosliders') ||
			$postType !== self::$postType)
		{
			wp_redirect($errorRedirectURL);

			die();
		}

		$post = get_post($postID);

		if (!$post instanceof WP_Post ||
			$post->post_type !== self::$postType)
		{
			wp_redirect($errorRedirectURL);

			die();
		}

		$current_user = wp_get_current_user();

		$newPostID = wp_insert_post(array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $current_user->ID,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title . (strlen($post->post_title) > 0 ? ' - ' : '') . __('Copy', 'gmoslider'),
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order,
		));

		if (is_wp_error($newPostID))
		{
			wp_redirect($errorRedirectURL);

			die();
		}

		$taxonomies = get_object_taxonomies($post->post_type);

		foreach ($taxonomies as $taxonomy)
		{
			$postTerms = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'slugs'));

			wp_set_object_terms($newPostID, $postTerms, $taxonomy, false);
		}

		$postMetaRecords = get_post_meta($post->ID);

		foreach ($postMetaRecords as $postMetaKey => $postMetaValues)
		{
			foreach ($postMetaValues as $postMetaValue)
			{
				update_post_meta($newPostID, $postMetaKey, maybe_unserialize($postMetaValue));
			}
		}

		wp_redirect(admin_url('post.php?action=edit&post=' . $newPostID));

		die();
	}
}