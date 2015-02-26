<?php
/**
 * Plugin Name: GMO Slider
 * Plugin URI:  
 * Description: GMO Slider plugin let you insert sliders in posts and pages. The control screen is simple, for anyone to easily use. GMO Slider supports images as well as text and video.
 * Version:     1.1
 * Author:      WP Shop byGMO
 * Author URI:  https://www.wpcloud.jp/en/
 * License:     GPLv2
 * Text Domain: gmoslider
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2015 GMO WP Cloud (https://www.wpcloud.jp/en/)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
 
class GMOsliderMain
{
	static $version = '1.0';

	static function init()
	{
		
		
		self::autoInclude();
		
		add_action('init', array(__CLASS__, 'localize'));

		add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueueAdminScripts'));

		GMOsliderAJAX::init();

		GMOsliderPostType::init();

		GMOsliderGeneralSettings::init();

		GMOsliderStylesheet::init();

		add_action('gmoslider_slider', array('GMOslider', 'slider'));
		
		GMOsliderShortcode::init();

		add_action('widgets_init', array('GMOsliderWidget', 'registerWidget'));

		GMOsliderInstaller::init();
	}

	static function enqueueAdminScripts()
	{
		if (!function_exists('get_current_screen'))
		{
			return;
		}

		$currentScreen = get_current_screen();

		if ($currentScreen->post_type === 'gmoslider' &&
			function_exists('wp_enqueue_media'))
		{
			wp_enqueue_media();
		}

		wp_enqueue_script(
			'gmoslider-admin-script',
			self::getPluginUrl() . '/js/gmoslider-admin.js',
			array(
				'jquery',
				'jquery-ui-sortable',
				'wp-color-picker'
			),
			GMOsliderMain::$version
		);

		wp_enqueue_style(
			'gmoslider-admin-style',
			self::getPluginUrl() . '/css/gmoslider-admin.css',
			array(
				'wp-color-picker'
			),
			GMOsliderMain::$version
		);
	}

	static function localize()
	{
		load_plugin_textdomain(
			'gmoslider',
			false,
			dirname(plugin_basename(__FILE__)) . '/languages/'
		);
	}

	static function getPluginUrl()
	{
		return plugins_url('', __FILE__);
	}

	static function getPluginPath()
	{
		return dirname(__FILE__);
	}

	static function autoInclude()
	{
		if (!function_exists('spl_autoload_register'))
		{
			return;
		}
		function GMOsliderAutoLoader($name)
		{
			$name = str_replace('\\', DIRECTORY_SEPARATOR, $name);
			$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $name . '.php';

			if (is_file($file))
			{
				require_once $file;
			}
		}

		spl_autoload_register('GMOsliderAutoLoader');
	}
}

GMOsliderMain::init();
?>
