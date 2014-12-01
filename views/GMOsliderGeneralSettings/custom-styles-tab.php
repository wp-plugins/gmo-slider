<?php

$defaultStyles      = array();
$defaultStylesheets = array(
	'style-light.css' => __('Light', 'gmoslider'),
	'style-dark.css' => __('Dark', 'gmoslider')
);

$stylesheetsFilePath = GMOsliderMain::getPluginPath() . DIRECTORY_SEPARATOR . 'style' . DIRECTORY_SEPARATOR . 'GMOslider';

foreach ($defaultStylesheets as $fileName => $name)
{
	if (file_exists($stylesheetsFilePath . DIRECTORY_SEPARATOR . $fileName))
	{
		ob_start();

		include $stylesheetsFilePath . DIRECTORY_SEPARATOR . $fileName;

		$defaultStyles[$fileName] = array(
			'name' => $name,
			'style' => ob_get_clean()
		);
	}
}

$customStyleValues = array();
$customStyleKeys   = get_option(GMOsliderGeneralSettings::$customStyles, array());

if (is_array($customStyleKeys))
{
	foreach ($customStyleKeys as $customStyleKey => $customStyleKeyName)
	{
		$customStyleValues[$customStyleKey] = get_option($customStyleKey);
	}
}

?>

<div class="custom-styles-tab feature-filter" style="float: left; display: none;">
	<div class="styles-list">

		<p>
			<b><?php _e('Default stylesheets', 'gmoslider'); ?></b>
		</p>

		<ul class="default-styles-list">

			<?php if(is_array($defaultStyles)): ?>
			<?php foreach($defaultStyles as $defaultStyleKey => $defaultStyleValues): ?>

				<?php if(!isset($defaultStyleValues['style']) || empty($defaultStyleValues['style'])) continue; // Continue if style is not set or empty ?>

				<li>
					<span class="style-title"><?php echo (isset($defaultStyleValues['name'])) ? htmlspecialchars($defaultStyleValues['name']) : __('Untitled'); ?></span>
						<span
							class="style-action style-default <?php htmlspecialchars($defaultStyleKey); ?>"
							title="<?php _e('Create a new custom style from this style', 'gmoslider'); ?>"
							>
							<?php _e('Customize', 'gmoslider'); ?> &raquo;
						</span>

					<p style="clear: both;"></p>

					<span class="style-content" style="display: none;"><?php echo htmlspecialchars($defaultStyleValues['style']); ?></span>
				</li>

				<?php endforeach; ?>
			<?php endif; ?>

		</ul>

		<p>
			<b><?php _e('Custom stylesheets', 'gmoslider'); ?></b>
		</p>

		<ul class="custom-styles-list">

			<?php if(is_array($customStyleKeys) && count($customStyleKeys) > 0): ?>
			<?php foreach($customStyleKeys as $customStyleKey => $customStyleKeyName): ?>

				<li>
					<span class="style-title"><?php echo htmlspecialchars($customStyleKeyName); ?></span>

						<span
							class="style-action <?php echo htmlspecialchars($customStyleKey); ?>"
							title="<?php _e('Edit this style', 'gmoslider'); ?>"
							>
							<?php _e('Edit', 'gmoslider'); ?> &raquo;
						</span>

					<span style="float: right;">&#124;</span>

						<span
							class="style-delete <?php echo htmlspecialchars($customStyleKey); ?>"
							title="<?php _e('Delete this style', 'gmoslider'); ?>"
							>
							<?php _e('Delete', 'gmoslider'); ?>
						</span>

					<p style="clear: both;"></p>
				</li>

				<?php endforeach; ?>
			<?php else: ?>

			<li class="no-custom-styles-found">
				<?php _e("Click 'Customize' to create a new custom stylesheet."); ?>
			</li>

			<?php endif; ?>

		</ul>

	</div>
</div>

<div style="clear: both;"></div>

<div class="custom-styles-tab feature-filter" style="display: none;">
	<div class="style-editors">

		<p>
			<b><?php _e('Custom style editor', 'gmoslider'); ?></b>
		</p>

		<p class="style-editor">
			<?php _e('Select a stylesheet to start customizing it.', 'gmoslider'); ?>
		</p>

		<?php if(is_array($customStyleValues)): ?>
		<?php foreach($customStyleValues as $customStyleKey => $customStyleValue): ?>

			<div class="style-editor <?php echo htmlspecialchars($customStyleKey); ?>" style="display: none;">

				<p>
					<i><?php _e('Title', 'gmoslider'); ?></i><br />
					<input
						type="text"
						name="<?php echo GMOsliderGeneralSettings::$customStyles; ?>[<?php echo htmlspecialchars($customStyleKey); ?>][title]"
						value="<?php echo (isset($customStyleKeys[$customStyleKey]) && !empty($customStyleKeys[$customStyleKey])) ? $customStyleKeys[$customStyleKey] : __('Untitled', 'gmoslider'); ?>"
					/>
				</p>

				<p>
					<i><?php _e('Style', 'gmoslider'); ?></i><br />
					<textarea
						name="<?php echo GMOsliderGeneralSettings::$customStyles; ?>[<?php echo htmlspecialchars($customStyleKey); ?>][style]"
						rows="25"
						cols=""
					><?php echo isset($customStyleValue) ? htmlspecialchars($customStyleValue) : ''; ?></textarea>
				</p>

			</div>

			<?php endforeach; ?>
		<?php endif; ?>

	</div>

	<div style="clear: both;"></div>

	<div class="custom-style-templates" style="display: none;">

		<li class="custom-styles-list-item">
			<span class="style-title"></span>

					<span
						class="style-action"
						title="<?php _e('Edit this style', 'gmoslider'); ?>"
						>
						<?php _e('Edit', 'gmoslider'); ?> &raquo;
					</span>

			<span style="float: right;">&#124;</span>

					<span
						class="style-delete"
						title="<?php _e('Delete this style', 'gmoslider'); ?>"
						>
						<?php _e('Delete', 'gmoslider'); ?>
					</span>

			<p style="clear: both;"></p>
		</li>

		<div class="style-editor" style="display: none;">

			<p>
				<i><?php _e('Title', 'gmoslider'); ?></i><br />
				<input
					type="text"
					class="new-custom-style-title"
					/>
			</p>

			<p>
				<i><?php _e('Style', 'gmoslider'); ?></i><br />
				<textarea
					class="new-custom-style-content"
					rows="25"
					cols=""
					></textarea>
			</p>

		</div>
	</div>
</div>