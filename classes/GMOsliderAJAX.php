<?php
class GMOsliderAJAX
{
	static function init()
	{
		add_action('wp_ajax_gmoslider_slide_inserter_search_query', array('GMOsliderSlideInserter', 'printSearchResults'));

		add_action('wp_ajax_gmoslider_jquery_image_gallery_load_stylesheet', array('GMOsliderStylesheet', 'loadStylesheetByAJAX'));
		add_action('wp_ajax_nopriv_gmoslider_jquery_image_gallery_load_stylesheet', array('GMOsliderStylesheet', 'loadStylesheetByAJAX'));
	}
}