<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'gmoslider'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo htmlspecialchars($instance['title']); ?>" style="width:100%" />
</p>

<p>
	<label for="<?php echo $this->get_field_id('gmosliderId'); ?>"><?php _e('GMO Slider', 'gmoslider'); ?></label>
	<select class="widefat" id="<?php echo $this->get_field_id('gmosliderId'); ?>" name="<?php echo $this->get_field_name('gmosliderId'); ?>" value="<?php echo (is_numeric($instance['gmosliderId']))? $instance['gmosliderId'] : ''; ?>" style="width:100%">
		<option value="-1" <?php selected($instance['gmosliderId'], -1); ?>><?php _e('Random GMO Slider', 'gmoslider'); ?></option>
		<?php if(count($gmosliders) > 0): ?>
		<?php foreach($gmosliders as $gmoslider): ?>
			<option value="<?php echo $gmoslider->ID ?>" <?php selected($instance['gmosliderId'], $gmoslider->ID); ?>><?php echo !empty($gmoslider->post_title) ? $gmoslider->post_title : __('Untitled GMO Slider', 'gmoslider'); ?></option>
		<?php endforeach; ?>
		<?php endif; ?>
	</select>
</p>