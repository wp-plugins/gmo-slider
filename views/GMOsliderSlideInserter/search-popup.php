<div id="gmoslider-slide-inserter-popup-background"></div>
<div id="gmoslider-slide-inserter-popup">
	<div id="close"></div>
	<div>
		<input type="text" id="search" />
		<?php submit_button(__('Search', 'gmoslider'), 'primary', 'search-submit', false); ?>
		<i><?php _e('Search images by title or ID', 'gmoslider'); ?></i>
	</div>
	<div style="clear: both;"></div>

	<div id="search-results">
		<table id="results" class="widefat"></table>
	</div>
</div>