<?php

$stylesheetLocation = GMOsliderGeneralSettings::getStylesheetLocation();

global $wp_roles;

$capabilities = array(
	GMOsliderGeneralSettings::$capabilities['addGMOsliders']    => __('Add GMO Sliders', 'gmoslider'),
	GMOsliderGeneralSettings::$capabilities['editGMOsliders']   => __('Edit GMO Sliders', 'gmoslider'),
	GMOsliderGeneralSettings::$capabilities['deleteGMOsliders'] => __('Delete GMO Sliders', 'gmoslider')
);

?>

<div class="general-settings-tab feature-filter">

	<h4><?php _e('User Capabilities', 'gmoslider'); ?></h4>

	<p><?php _e('Select the user roles that will able to perform certain actions.', 'gmoslider');  ?></p>

	<table>

		<?php foreach($capabilities as $capability => $capabilityName): ?>

		<tr valign="top">
			<th><?php echo $capabilityName; ?></th>
			<td>
				<?php

				if(isset($wp_roles->roles) && is_array($wp_roles->roles)):
					foreach($wp_roles->roles as $roleSlug => $values):

						$disabled = ($roleSlug == 'administrator') ? 'disabled="disabled"' : '';
						$checked = ((isset($values['capabilities']) && array_key_exists($capability, $values['capabilities']) && $values['capabilities'][$capability] == true) || $roleSlug == 'administrator') ? 'checked="checked"' : '';
						$name = (isset($values['name'])) ? htmlspecialchars($values['name']) : __('Untitled role', 'gmoslider');

						?>

						<input
							type="checkbox"
							name="<?php echo htmlspecialchars($capability); ?>[<?php echo htmlspecialchars($roleSlug); ?>]"
							id="<?php echo htmlspecialchars($capability . '_' . $roleSlug); ?>"
							<?php echo $disabled; ?>
							<?php echo $checked; ?>
						/>
						<label for="<?php echo htmlspecialchars($capability . '_' . $roleSlug); ?>"><?php echo $name; ?></label>
						<br />

						<?php endforeach; ?>
					<?php endif; ?>

			</td>
		</tr>

		<?php endforeach; ?>

	</table>
</div>

<div class="general-settings-tab feature-filter">

	<h4><?php _e('Settings', 'gmoslider'); ?></h4>

	<table>
		<tr>
			<td><?php _e('Stylesheet location', 'gmoslider'); ?></td>
			<td>
				<select name="<?php echo GMOsliderGeneralSettings::$stylesheetLocation; ?>">
					<option value="head" <?php selected('head', $stylesheetLocation); ?>>Head (<?php _e('top', 'gmoslider'); ?>)</option>
					<option value="footer" <?php selected('footer', $stylesheetLocation); ?>>Footer (<?php _e('bottom', 'gmoslider'); ?>)</option>
				</select>
			</td>
		</tr>
	</table>

</div>