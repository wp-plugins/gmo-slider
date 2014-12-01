<?php
class GMOsliderGeneralSettings
{
	static $isCurrentPage = false;

	static $settingsGroup = 'gmoslider-general-settings';

	static $stylesheetLocation = 'gmoslider-stylesheet-location';
	static $capabilities = array(
		'addGMOsliders'    => 'gmoslider-add-gmosliders',
		'editGMOsliders'   => 'gmoslider-edit-gmosliders',
		'deleteGMOsliders' => 'gmoslider-delete-gmosliders'
	);

	static $defaultSettings = 'gmoslider-default-settings';
	static $defaultStyleSettings = 'gmoslider-default-style-settings';

	static $customStyles = 'gmoslider-custom-styles';

	static function init()
	{
		if (!is_admin())
		{
			return;
		}

		if (isset($_GET['post_type']) &&
			$_GET['post_type'] == 'gmoslider' &&
			isset($_GET['page']) &&
			$_GET['page'] == 'general_settings')
		{
			self::$isCurrentPage = true;
		}

		add_action('admin_init', array(__CLASS__, 'registerSettings'));

		add_action('admin_menu', array(__CLASS__, 'addSubMenuPage'));

		add_action('admin_enqueue_scripts', array(__CLASS__, 'localizeScript'), 11);
	}
	static function addSubMenuPage()
	{
		if(!post_type_exists(GMOsliderPostType::$postType))
		{
			return;
		}

		add_submenu_page(
			'edit.php?post_type=' . GMOsliderPostType::$postType,
			__('General Settings', 'gmoslider'),
			__('General Settings', 'gmoslider'),
			'manage_options',
			'general_settings',
			array(__CLASS__, 'generalSettings')
		);
	}
	static function generalSettings()
	{
		include GMOsliderMain::getPluginPath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . 'general-settings.php';
	}

	static function registerSettings()
	{
		$urlParts = explode('/', $_SERVER['PHP_SELF']);

		if (array_pop($urlParts) != 'options.php')
		{
			return;
		}

		register_setting(self::$settingsGroup, self::$stylesheetLocation);

		register_setting(self::$settingsGroup, self::$capabilities['addGMOsliders']);
		register_setting(self::$settingsGroup, self::$capabilities['editGMOsliders']);
		register_setting(self::$settingsGroup, self::$capabilities['deleteGMOsliders'], array(__CLASS__, 'saveCapabilities'));

		register_setting(self::$settingsGroup, self::$defaultSettings);
		register_setting(self::$settingsGroup, self::$defaultStyleSettings);

		register_setting(self::$settingsGroup, self::$customStyles, array(__CLASS__, 'saveCustomStyles'));
	}

	static function localizeScript()
	{
		if (!self::$isCurrentPage)
		{
			return;
		}

		wp_localize_script(
			'gmoslider-admin-script',
			'gmoslider_admin_script_generalSettings',
			array(
				'data'         => array('customStylesKey' => self::$customStyles),
				'localization' => array(
					'newCustomizationPrefix' => __('New', 'gmoslider'),
					'confirmDeleteMessage'   => __('Are you sure you want to delete this custom style?', 'gmoslider')
				)
			)
		);
	}

	public static function getStylesheetLocation()
	{
		return get_option(GMOsliderGeneralSettings::$stylesheetLocation, 'footer');
	}

	static function getStylesheets($withVersion = false, $separateDefaultFromCustom = false)
	{
		$defaultStyles = array(
			'style-light.css' => __('Light', 'gmoslider'),
			'style-dark.css'  => __('Dark', 'gmoslider')
		);

		$stylesheetsFilePath = GMOsliderMain::getPluginPath() . DIRECTORY_SEPARATOR . 'style' . DIRECTORY_SEPARATOR . 'GMOslider';

		foreach ($defaultStyles as $fileName => $name)
		{
			if (!file_exists($stylesheetsFilePath . DIRECTORY_SEPARATOR . $fileName))
			{
				unset($defaultStyles[$fileName]);

				continue;
			}

			if($withVersion)
			{
				$defaultStyles[$fileName] = array('name' => $name, 'version' => GMOsliderMain::$version);
			}
		}

		$customStyles = get_option(GMOsliderGeneralSettings::$customStyles, array());

		if ($withVersion)
		{
			foreach ($customStyles as $customStylesKey => $customStylesName)
			{
				$customStylesVersion = get_option($customStylesKey . '_version', false);

				if (!$customStylesVersion)
				{
					$customStylesVersion = time();
				}

				$customStyles[$customStylesKey] = array('name' => $customStylesName, 'version' => $customStylesVersion);
			}
		}

		if ($separateDefaultFromCustom)
		{
			return array(
				'default' => $defaultStyles,
				'custom' => $customStyles
			);
		}

		return array_merge(
			$defaultStyles,
			$customStyles
		);
	}

	static function saveCapabilities($capability)
	{
		$nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '';

		if (!wp_verify_nonce($nonce, self::$settingsGroup . '-options'))
		{
			return $capability;
		}

		global $wp_roles;

		foreach ($wp_roles->roles as $roleSlug => $roleValues)
		{
			if (!is_array($roleValues) ||
				!isset($roleValues['capabilities']) ||
				!is_array($roleValues['capabilities']))
			{
				continue;
			}

			$role = get_role($roleSlug);

			if ($role == null)
			{
				continue;
			}

			foreach (self::$capabilities as $capabilitySlug)
			{
				if ((isset($_POST[$capabilitySlug]) && is_array($_POST[$capabilitySlug]) && array_key_exists($roleSlug, $_POST[$capabilitySlug])) ||
					$roleSlug == 'administrator')
				{
					$role->add_cap($capabilitySlug);
				}
				else
				{
					$role->remove_cap($capabilitySlug);
				}
			}
		}

		return $capability;
	}

	static function saveCustomStyles($customStyles)
	{
		$nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '';

		if (!wp_verify_nonce($nonce, self::$settingsGroup . '-options'))
		{
			return $customStyles;
		}

		$oldCustomStyles = get_option(self::$customStyles, array());

		if (is_array($oldCustomStyles))
		{
			foreach ($oldCustomStyles as $oldCustomStyleKey => $oldCustomStyleValue)
			{
				if (!array_key_exists($oldCustomStyleKey, $customStyles))
				{
					delete_option($oldCustomStyleKey);
				}
			}
		}

		$newCustomStyles = array();

		if (is_array($customStyles))
		{
			foreach ($customStyles as $customStyleKey => $customStyleValue)
			{
				$newCustomStyles[$customStyleKey] = isset($customStyleValue['title']) ? $customStyleValue['title'] : __('Untitled', 'gmoslider');

				$newStyle = isset($customStyleValue['style']) ? $customStyleValue['style'] : '';

				$oldStyle = get_option($customStyleKey, false);

				if ($oldStyle)
				{
					if ($oldStyle !== $newStyle)
					{
						update_option($customStyleKey, $newStyle);
						update_option($customStyleKey . '_version', time());
					}
				}
				else
				{
					add_option($customStyleKey, $newStyle, '', 'no');
					add_option($customStyleKey . '_version', time());
				}
			}
		}

		return $newCustomStyles;
	}
}