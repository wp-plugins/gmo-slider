<?php
class GMOsliderShortcode
{
	public static $shortCode = 'gmoslider_slider';

	public static $bookmark = '!gmoslider_slider!';

	private static $postIds = array();

	static function init()
	{
		add_shortcode(self::$shortCode, array(__CLASS__, 'gmosliderSlider'));

		if (is_admin())
		{
			add_action('media_buttons',  array(__CLASS__, 'shortcodeInserter'), 11);

			add_action('admin_enqueue_scripts', array(__CLASS__, 'localizeScript'), 11);
		}
	}

	static function gmosliderSlider($attributes)
	{
		$postId = '';

		if (isset($attributes['id']))
		{
			$postId = $attributes['id'];
		}

		$output   = '';
		$settings = GMOsliderSettingsHandler::getSettings($postId);

		if ($settings['avoidFilter'] == 'true' &&
			strlen(current_filter()) > 0)
		{
			add_filter(current_filter(), array(__CLASS__, 'insertGMOslider'), 999);

			self::$postIds[] = $postId;

			$output = self::$bookmark;
		}
		else
		{
			$output = GMOslider::prepare($postId);
		}

		return $output;
	}

	static function insertGMOslider($content)
	{
		if (is_array(self::$postIds) &&
			count(self::$postIds) > 0)
		{
			foreach (self::$postIds as $postId)
			{
				$updatedContent = preg_replace("/" . self::$bookmark . "/", GMOslider::prepare($postId), $content, 1);

				if (is_string($updatedContent))
				{
					$content = $updatedContent;
				}
			}
		}

		self::$postIds = array();

		return $content;
	}

	static function shortcodeInserter()
	{
		$gmosliders = new WP_Query(array(
			'post_type'      => GMOsliderPostType::$postType,
			'orderby'        => 'post_date',
			'posts_per_page' => -1,
			'order'          => 'DESC'
		));

		include(GMOsliderMain::getPluginPath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . 'shortcode-inserter.php');
	}

	static function localizeScript()
	{
		wp_localize_script(
			'gmoslider-admin-script',
			'gmoslider_admin_script_shortcode',
			array(
				'data' => array('shortcode' => GMOsliderShortcode::$shortCode),
				'localization' => array('undefinedGMOslider' => __('No GMO Slider selected.', 'gmoslider'))
			)
		);
	}
}