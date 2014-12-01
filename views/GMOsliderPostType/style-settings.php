<table>
	<?php if(count($settings) > 0): $i = 0; ?>

	<?php foreach($settings as $key => $value): ?>

	<?php if( !isset($value, $value['type'], $value['default'], $value['description']) || !is_array($value)) continue; ?>

	<tr <?php if(isset($value['dependsOn'])) echo 'style="display:none;"'; ?>>
		<td><?php echo $value['description']; ?></td>
		<td><?php echo GMOsliderSettingsHandler::getInputField(htmlspecialchars(GMOsliderSettingsHandler::$styleSettingsKey), $key, $value); ?></td>
		<td><?php _e('Default', 'gmoslider'); ?>: &#39;<?php echo (isset($value['options']))? $value['options'][$value['default']]: $value['default']; ?>&#39;</td>
	</tr>

	<?php endforeach; ?>

	<?php endif; ?>
</table>

<p>
	<?php
		echo sprintf(__(
				'Custom styles can be created and customized %shere%s.',
				'gmoslider'
			),
			'<a href="' . admin_url() . '/edit.php?post_type=gmoslider&page=general_settings#custom-styles" target="_blank">',
			'</a>'
		);
	?>
</p>