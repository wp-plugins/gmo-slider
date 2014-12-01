<?php if (function_exists('wp_enqueue_media')): ?>
	<input type="button" class="button button-primary button-large gmoslider-insert-image-slide" value="<?php _e('Image slide', 'gmoslider'); ?>" />
<?php else: ?>
	<input type="button" id="gmoslider-insert-image-slide" class="button button-primary button-large" value="<?php _e('Image slide', 'gmoslider'); ?>" />
<?php endif; ?>