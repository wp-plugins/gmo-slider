<?php
class GMOsliderInstaller
{
	private static $versionKey = 'gmoslider-plugin-version';
	static function init()
	{
		if (!is_admin())
		{
			return;
		}

		$currentVersion = get_option(self::$versionKey, null);
		
		if ($currentVersion == null)
		{
			self::setCapabilities();
		}
	}

	private static function update($currentVersion)
	{
		add_option('gmoslider-updated', 'updated', '', false);
	}

	private static function setCapabilities()
	{
		if (get_option('gmoslider-installed') !== false)
		{
			return;
		}

		$addGMOsliders   = 'gmoslider-add-gmosliders';
		$editGMOsliders  = 'gmoslider-edit-gmosliders';
		$deleteGMOslider = 'gmoslider-delete-gmosliders';

		$roles = array('administrator', 'editor', 'author');

		foreach ($roles as $roleName)
		{
			$role = get_role($roleName);

			if ($role == null)
			{
				continue;
			}

			$role->add_cap($addGMOsliders);
			$role->add_cap($editGMOsliders);
			$role->add_cap($deleteGMOslider);
		}

		add_option('gmoslider-installed', 'updated', '', false);
	}
}