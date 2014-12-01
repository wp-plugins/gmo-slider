<?php

$generalSettingsViewsPath = GMOsliderMain::getPluginPath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'GMOsliderGeneralSettings' . DIRECTORY_SEPARATOR;

?>
<div class="wrapper general-settings">
<div class="wrap">
	<div class="wrap-inner">
	<form method="post" action="options.php">
		<?php settings_fields(GMOsliderGeneralSettings::$settingsGroup); ?>

		<div class="icon32" style="background: url('<?php echo GMOsliderMain::getPluginUrl() . '/images/GMOsliderPostType/adminIcon32.png'; ?>');"></div>
		<h2 class="nav-tab-wrapper">
			<a href="#general-settings-tab" class="nav-tab nav-tab-active"><?php _e('General Settings', 'gmoslider'); ?></a>
			<a href="#default-gmoslider-settings-tab" class="nav-tab"><?php _e('Default GMO Slider Settings', 'gmoslider'); ?></a>
			<a href="#custom-styles-tab" class="nav-tab"><?php _e('Custom Styles', 'gmoslider'); ?></a>

			<?php submit_button(null, 'primary', null, false, 'style="float: right;"'); ?>
		</h2>

		<?php

		include $generalSettingsViewsPath . 'general-settings-tab.php';

		include $generalSettingsViewsPath . 'default-gmoslider-settings-tab.php';

		include $generalSettingsViewsPath . 'custom-styles-tab.php';

		?>

		<p>
			<?php submit_button(null, 'primary', null, false); ?>
		</p>
	</form>
	</div>
</div>
<?php include GMOsliderMain::getPluginPath() . '/views/GMOsliderPostType/gmosidebar.php'; ?>
<br class="clear" />
</div>
