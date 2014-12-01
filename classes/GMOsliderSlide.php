<?php
class GMOsliderSlide
{
	private $properties;

	function __construct($properties)
	{
		if (is_array($properties))
		{
			$this->properties = $properties;
		}
	}

	function toHTML($return = true)
	{
		if (!isset($this->properties['type']) ||
			empty($this->properties['type']))
		{
			return '';
		}

		$properties = $this->properties;

		$file = GMOsliderMain::getPluginPath() . DIRECTORY_SEPARATOR .
			'views' . DIRECTORY_SEPARATOR .
			__CLASS__ . DIRECTORY_SEPARATOR .
			$this->properties['type'] . '.php';

		if (!file_exists($file))
		{
			return '';
		}

		if ($return)
		{
			ob_start();
		}

		include $file;

		if ($return)
		{
			return ob_get_clean();
		}

		return '';
	}

	function toAdminHTML($return = true)
	{
		if (!isset($this->properties['type']) ||
			empty($this->properties['type']))
		{
			return '';
		}

		$properties = $this->properties;

		$name = GMOsliderSettingsHandler::$slidesKey . '[' . rand() . ']';

		$file = GMOsliderMain::getPluginPath() . DIRECTORY_SEPARATOR .
			'views' . DIRECTORY_SEPARATOR .
			__CLASS__ . DIRECTORY_SEPARATOR .
			'admin_' . $this->properties['type'] . '.php';

		if (!file_exists($file))
		{
			return '';
		}

		if ($return)
		{
			ob_start();
		}

		include $file;

		if ($return)
		{
			return ob_get_clean();
		}

		return '';
	}

	static function getAdminTemplates($return = true)
	{
		if ($return)
		{
			ob_start();
		}

		include GMOsliderMain::getPluginPath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . 'admin_templates.php';

		if($return)
		{
			return ob_get_clean();
		}

		return '';
	}
}