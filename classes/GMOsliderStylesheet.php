<?php
class GMOsliderStylesheet
{
	public static $allStylesheetsRegistered = false;

	public static function init()
	{
		add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueueStylesheets'));
	}

	public static function enqueueStylesheets()
	{
		if (GMOsliderGeneralSettings::getStylesheetLocation() === 'head')
		{
			wp_enqueue_style(
				'gmoslider-stylesheet-basic',
				GMOsliderMain::getPluginUrl() . '/style/GMOslider/functional.css',
				array(),
				GMOsliderMain::$version
			);

			$stylesheets        = GMOsliderGeneralSettings::getStylesheets(true, true);
			$defaultStylesheets = $stylesheets['default'];
			$customStylesheets  = $stylesheets['custom'];

			foreach ($defaultStylesheets as $defaultStylesheetKey => $defaultStylesheetValue)
			{
				$newDefaultStylesheetKey = str_replace('.css', '', $defaultStylesheetKey);

				$defaultStylesheets[$newDefaultStylesheetKey] = $defaultStylesheetValue;

				if ($defaultStylesheetKey !== $newDefaultStylesheetKey)
				{
					unset($defaultStylesheets[$defaultStylesheetKey]);
				}
			}

			foreach (array_merge($defaultStylesheets, $customStylesheets) as $stylesheetKey => $stylesheetValue)
			{
				wp_enqueue_style(
					'gmoslider-ajax-stylesheet_' . $stylesheetKey,
					admin_url('admin-ajax.php?action=gmoslider_jquery_image_gallery_load_stylesheet&style=' . $stylesheetKey),
					array(),
					$stylesheetValue['version']
				);
			}

			self::$allStylesheetsRegistered = true;
		}
	}

	public static function enqueueStylesheet($name = null)
	{
		if (isset($name))
		{
			$customStyle        = get_option($name, false);
			$customStyleVersion = false;

			if ($customStyle)
			{
				$customStyleVersion = get_option($name . '_version', false);
			}

			if ($customStyle && $customStyleVersion)
			{
				$version = $customStyleVersion;
			}
			else
			{
				$name    = str_replace('.css', '', $name);
				$version = GMOsliderMain::$version;
			}
		}
		else
		{
			$name    = 'style-light';
			$version = GMOsliderMain::$version;
		}

		wp_enqueue_style(
			'gmoslider-ajax-stylesheet_' . $name,
			admin_url('admin-ajax.php?action=gmoslider_jquery_image_gallery_load_stylesheet&style=' . $name),
			array(),
			$version
		);

		return array($name, $version);
	}

	public static function loadStylesheetByAJAX()
	{
		$styleName = filter_input(INPUT_GET, 'style', FILTER_SANITIZE_SPECIAL_CHARS);

		if (isset($styleName) &&
			!empty($styleName) &&
			strlen($styleName) > 0)
		{
			$stylesheet = self::getStylesheet($styleName);
		}
		else
		{
			return;
		}

		if (headers_sent())
		{
			return;
		}

		header('Content-Type: text/css; charset=UTF-8');
		header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 31556926) . ' GMT');
		header('Pragma: cache');
		header("Cache-Control: public, max-age=31556926");

		echo $stylesheet;

		die;
	}

	public static function getStylesheet($styleName)
	{
		$stylesheet = get_option($styleName, '');

		if (strlen($stylesheet) <= 0)
		{
			$stylesheetFile = GMOsliderMain::getPluginPath() . DIRECTORY_SEPARATOR . 'style' . DIRECTORY_SEPARATOR . 'GMOslider' . DIRECTORY_SEPARATOR . $styleName . '.css';

			if (!file_exists($stylesheetFile))
			{
				$stylesheetFile = GMOsliderMain::getPluginPath() . DIRECTORY_SEPARATOR . 'style' . DIRECTORY_SEPARATOR . 'GMOslider' . DIRECTORY_SEPARATOR . 'style-light.css';
			}

			ob_start();
			include($stylesheetFile);
			$stylesheet .= ob_get_clean();
		}

		$stylesheet = str_replace('%plugin-url%', GMOsliderMain::getPluginUrl(), $stylesheet);
		$stylesheet = str_replace('%site-url%', get_bloginfo('url'), $stylesheet);
		$stylesheet = str_replace('%stylesheet-url%', get_stylesheet_directory_uri(), $stylesheet);
		$stylesheet = str_replace('%template-url%', get_template_directory_uri(), $stylesheet);
		$stylesheet = str_replace('.gmoslider_container', '.gmoslider_container_' . $styleName, $stylesheet);

		return $stylesheet;
	}
}