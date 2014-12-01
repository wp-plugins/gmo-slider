<?php
class GMOslider
{
	private static $sessionCounter = 0;

	static function slider($postId = null)
	{
		echo self::prepare($postId);
	}
	static function prepare($postId = null)
	{
		$post = null;
		if (is_numeric($postId) &&
			$postId >= 0)
		{
			$post = get_post($postId);
		}
		if ($post === null &&
			is_string($postId) &&
			!is_numeric($postId) &&
			!empty($postId))
		{
			$query = new WP_Query(array(
				'post_type'        => GMOsliderPostType::$postType,
				'name'             => $postId,
				'orderby'          => 'post_date',
				'order'            => 'DESC',
				'suppress_filters' => true
			));

			if($query->have_posts())
			{
				$post = $query->next_post();
			}
		}
		if ($post === null)
		{
			$post = get_posts(array(
				'numberposts'      => 1,
				'offset'           => 0,
				'orderby'          => 'rand',
				'post_type'        => GMOsliderPostType::$postType,
				'suppress_filters' => true
			));

			if(is_array($post))
			{
				$post = $post[0];
			}
		}

		if($post === null)
		{
			return '<!-- WordPress GMO Slider - No GMO Slider available -->';
		}

		$log = array();

		$views = GMOsliderSettingsHandler::getViews($post->ID);

		if (!is_array($views) ||
			count($views) <= 0)
		{
			$log[] = 'No views were found';
		}

		$settings      = GMOsliderSettingsHandler::getSettings($post->ID);
		$styleSettings = GMOsliderSettingsHandler::getStyleSettings($post->ID);

		$sessionID = self::$sessionCounter++;

		if (!GMOsliderStylesheet::$allStylesheetsRegistered)
		{
				
			wp_enqueue_style(
				'gmoslider-stylesheet-basic',
				GMOsliderMain::getPluginUrl() . '/style/' . __CLASS__ . '/basic.css',
				array(),
				GMOsliderMain::$version
			);
		}

		list($styleName, $styleVersion) = GMOsliderStylesheet::enqueueStylesheet($styleSettings['style']);

		$output = '';
		ob_start();
		include(GMOsliderMain::getPluginPath() . '/views/' . __CLASS__ . '/gmoslider.php');
		$output .= ob_get_clean();

		wp_enqueue_style(
			'gmoslider-stylesheet-flexslider',
			GMOsliderMain::getPluginUrl() . '/flexslider/flexslider.css',
			array(),
			GMOsliderMain::$version
		);
		
		wp_enqueue_style(
			'gmoslider-stylesheet',
			GMOsliderMain::getPluginUrl() . '/css/gmoslider.css',
			array(),
			GMOsliderMain::$version
		);
		
		
		
		wp_enqueue_script(
			'gmoslider-script-youtube-api',
			'//www.youtube.com/iframe_api'
		);
		
		wp_enqueue_script(
			'gmoslider-script-easing',
			GMOsliderMain::getPluginUrl() . '/js/jquery.easing.1.3.min.js',
			array('jquery'),
			GMOsliderMain::$version
		);

		wp_enqueue_script(
			'gmoslider-script-flexslider',
			GMOsliderMain::getPluginUrl() . '/flexslider/jquery.flexslider.min.js',
			array('jquery'),
			GMOsliderMain::$version
		);
		
		wp_enqueue_script(
			'gmoslider-script',
			GMOsliderMain::getPluginUrl() . '/js/gmoslider.js',
			array('jquery'),
			GMOsliderMain::$version
		);
		if(isset($settings['textPosition'])) {
			unset($settings['textPosition']);
		}
		wp_localize_script(
			'gmoslider-script',
			'GMOsliderSettings_' . $sessionID,
			$settings
		);

		wp_localize_script(
			'gmoslider-script',
			'gmoslider_jquery_image_gallery_script_adminURL',
			admin_url()
		);

		return $output;
	}
}