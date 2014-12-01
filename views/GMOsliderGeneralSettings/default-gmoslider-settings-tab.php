<?php

$defaultSettings      = GMOsliderSettingsHandler::getDefaultSettings(true);
$defaultStyleSettings = GMOsliderSettingsHandler::getDefaultStyleSettings(true);

?>
<div class="default-gmoslider-settings-tab feature-filter" style="display: none;">

	<h4><?php _e('Default GMO Slider Settings', 'gmoslider'); ?></h4>

	<table>

		<?php $groups = array(); ?>
		<?php foreach($defaultSettings as $defaultSettingKey => $defaultSettingValue): ?>

		<?php if(!empty($defaultSettingValue['group']) && !isset($groups[$defaultSettingValue['group']])): $groups[$defaultSettingValue['group']] = true; ?>

		<tr>
			<td colspan="3" style="border-bottom: 1px solid #dfdfdf; text-align: center;">
				<span style="display: inline-block; position: relative; top: 14px; padding: 0 12px; background: #fff;">
					<?php echo $defaultSettingValue['group']; ?> <?php _e('settings', 'gmoslider'); ?>
				</span>
			</td>
		</tr>
		<tr>
			<td colspan="3"></td>
		</tr>

		<?php endif; ?>

		<tr>
			<td>
				<?php echo $defaultSettingValue['description']; ?>
			</td>
			<td>
				<?php

				echo GMOsliderSettingsHandler::getInputField(
					GMOsliderGeneralSettings::$defaultSettings,
					$defaultSettingKey,
					$defaultSettingValue,
					false
				);

				?>
			</td>
		</tr>

		<?php endforeach; ?>
		<?php unset($groups); ?>

	</table>
</div>

<div class="default-gmoslider-settings-tab feature-filter" style="display: none;">

	<h4><?php _e('Default GMO Slider Stylesheet', 'gmoslider'); ?></h4>

	<table>

		<?php foreach($defaultStyleSettings as $defaultStyleSettingKey => $defaultStyleSettingValue): ?>

		<tr>
			<td>
				<?php echo $defaultStyleSettingValue['description']; ?>
			</td>
			<td>
				<?php

				echo GMOsliderSettingsHandler::getInputField(
					GMOsliderGeneralSettings::$defaultStyleSettings,
					$defaultStyleSettingKey,
					$defaultStyleSettingValue,
					false
				);

				?>
			</td>
		</tr>

		<?php endforeach; ?>

	</table>
</div>

<div style="clear: both;"></div>