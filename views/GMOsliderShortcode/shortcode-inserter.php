<a
	href="#TB_inline?width=450&inlineId=insertGMOsliderShortcode"
	class="button thickbox"
	title="<?php _e('Insert a GMO Slider', 'gmoslider'); ?>"
    style="padding-left: .4em;"
>
	<span class="dashicons dashicons-format-gallery" style="margin-top:3px;"></span> <?php _e('Insert GMO Slider', 'gmoslider'); ?>
</a>

<div id="insertGMOsliderShortcode" style="display: none;">

	<h3 style="padding: 10px 0; color: #5a5a5a;">
		<?php _e('Insert a GMOslider', 'gmoslider'); ?>
	</h3>

	<div style="border: 1px solid #ddd; padding: 10px; color: #5a5a5a;">

		<?php if($gmosliders instanceof WP_Query && count($gmosliders->get_posts()) > 0): ?>
		<table>
			<tr>

				<td><?php _e('Select a GMO Slider', 'gmoslider'); ?></td>
				<td>
					<select id="insertGMOsliderShortcodeGMOsliderSelect">

						<?php foreach($gmosliders->get_posts() as $gmoslider): ?>

						<?php if(!is_numeric($gmoslider->ID)) continue; ?>

						<option value="<?php echo $gmoslider->ID; ?>">
							<?php echo (!empty($gmoslider->post_title)) ? htmlspecialchars($gmoslider->post_title) : __('Untitled GMO Slider', 'gmoslider'); ?>
						</option>

						<?php endforeach; ?>

					</select>
				</td>

			</tr>
			<tr>

				<td>
					<input
						type="button"
						class="button-primary insertGMOsliderShortcodeGMOsliderInsertButton"
						value="<?php _e('Insert GMO Slider', 'gmoslider'); ?>"
					/>
					<input
						type="button"
						class="button insertGMOsliderShortcodeCancelButton"
					    value="<?php _e('Cancel', 'gmoslider'); ?>"
					/>
				</td>

			</tr>
		</table>

		<?php else: ?>

		<p>
			<?php echo sprintf(
				__('It seems you haven\'t created any GMO Sliders yet. %sYou can create a GMO Slider here!%s', 'gmoslider'),
				'<a href="' . admin_url('post-new.php?post_type=' . GMOsliderPostType::$postType) . '" target="_blank">',
				'</a>'
			); ?>
		</p>

		<?php endif; ?>

    </div>
</div>