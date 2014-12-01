<?php
class GMOsliderWidget extends WP_Widget
{
	static $widgetName = 'GMOslider';

	function GMOsliderWidget()
	{
		$options = array(
			'classname'   => 'GMOsliderWidget',
			'description' => __('Enables you to show your GMO Slider in the widget area of your website.', 'gmoslider')
		);

		$this->WP_Widget(
			'GMOsliderWidget',
			__('GMOslider Widget', 'gmoslider'),
			$options
		);
	}

	function widget($args, $instance)
	{
		$gmosliderId = '';
		if (isset($instance['gmosliderId']))
		{
			$gmosliderId = $instance['gmosliderId'];
		}

		$title = '';
		if (isset($instance['title']))
		{
			$title = $instance['title'];
		}

		$output = GMOslider::prepare($gmosliderId);

		$beforeWidget = $afterWidget = $beforeTitle = $afterTitle = '';
		if (isset($args['before_widget']))
		{
			$beforeWidget = $args['before_widget'];
		}

		if (isset($args['after_widget']))
		{
			$afterWidget = $args['after_widget'];
		}

		if (isset($args['before_title']))
		{
			$beforeTitle = $args['before_title'];
		}

		if (isset($args['after_title']))
		{
			$afterTitle = $args['after_title'];
		}

		echo $beforeWidget . (!empty($title) ? $beforeTitle . $title . $afterTitle : '') . $output . $afterWidget;
	}

	function form($instance)
	{
		$defaults = array(
			'title'       => __(self::$widgetName, 'gmoslider'),
			'gmosliderId' => -1
		);

		$instance = wp_parse_args((array) $instance, $defaults);

		$gmosliders = get_posts(array(
			'numberposts' => -1,
			'offset'      => 0,
			'post_type'   => GMOsliderPostType::$postType
		));

		include(GMOsliderMain::getPluginPath() . '/views/' . __CLASS__ . '/form.php');
	}

	function update($newInstance, $instance)
	{
		if (isset($newInstance['title']))
		{
			$instance['title'] = $newInstance['title'];
		}

		if (isset($newInstance['gmosliderId']) &&
			!empty($newInstance['gmosliderId']))
		{
			$instance['gmosliderId'] = $newInstance['gmosliderId'];
		}

		return $instance;
	}

	static function registerWidget()
	{
		register_widget(__CLASS__);
	}
}