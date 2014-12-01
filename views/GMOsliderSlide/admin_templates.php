<div class="text-slide-template" style="display: none;">
	<div class="widefat sortable-slides-list-item postbox">

		<div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br></div>

		<div class="hndle">
			<div class="slide-icon text-slide-icon"></div>
			<div class="slide-title">
				<?php _e('Text slide', 'gmoslider'); ?>
			</div>
			<div class="clear"></div>
		</div>

		<div class="inside">

			<div class="gmoslider-group">

				<div class="gmoslider-left gmoslider-label"><?php _e('Title', 'gmoslider'); ?></div>
				<div class="gmoslider-right">
					<select class="titleElementTagID">
						<?php foreach (GMOsliderSlideInserter::getElementTags() as $elementTagID => $elementTag): ?>
							<option value="<?php echo $elementTagID; ?>"><?php echo $elementTag; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="clear"></div>
				<input type="text" class="title" style="width: 100%;" />

			</div>

			<div class="gmoslider-group">

				<div class="gmoslider-left gmoslider-label"><?php _e('Description', 'gmoslider'); ?></div>
				<div class="gmoslider-right">
					<select class="descriptionElementTagID">
						<?php foreach (GMOsliderSlideInserter::getElementTags() as $elementTagID => $elementTag): ?>
							<option value="<?php echo $elementTagID; ?>"><?php echo $elementTag; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div clear="clear"></div>
				<textarea class="description" cols="" rows="7" style="width: 100%;"></textarea>

			</div>

			<div class="gmoslider-group">

				<div class="gmoslider-label"><?php _e('Text color', 'gmoslider'); ?></div>
				<input type="text" class="textColor" value="000000" />

				<div class="gmoslider-label"><?php _e('Background color', 'gmoslider'); ?></div>
				<input type="text" class="color" value="FFFFFF" />
				<div style="font-style: italic;"><?php _e('(Leave empty for a transparent background)', 'gmoslider'); ?></div>

			</div>

			<div class="gmoslider-group">

				<div class="gmoslider-label"><?php _e('URL', 'gmoslider'); ?></div>
				<input type="text" class="url" value="" style="width: 100%;" />

				<div class="gmoslider-label gmoslider-left"><?php _e('Open URL in', 'gmoslider'); ?></div>
				<select class="urlTarget gmoslider-right">
					<option value="_self"><?php _e('Same window', 'gmoslider'); ?></option>
					<option value="_blank"><?php _e('New window', 'gmoslider'); ?></option>
				</select>
				<div class="clear"></div>

				<div class="gmoslider-label gmoslider-left"><?php _e('Don\'t let search engines follow link', 'gmoslider'); ?></div>
	            <input type="checkbox" class="noFollow gmoslider-right" />
				<div class="clear"></div>

	        </div>

			<button type="button" class="gmoslider-group gmoslider-delete-slide button button-secondary">
				<span><?php _e('Delete slide', 'gmoslider'); ?></span>
			</button>

			<input type="hidden" class="type" value="text" />

		</div>

	</div>
</div>

<div class="video-slide-template" style="display: none;">
	<div class="widefat sortable-slides-list-item postbox">

		<div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br></div>

		<div class="hndle">
			<div class="slide-icon video-slide-icon"></div>
			<div class="slide-title">
				<?php _e('Video slide', 'gmoslider'); ?>
			</div>
			<div class="clear"></div>
		</div>

		<div class="inside">

			<div class="gmoslider-group">

				<div class="gmoslider-label"><?php _e('Youtube Video ID', 'gmoslider'); ?></div>
				<input type="text" class="videoId" style="width: 100%;" />

			</div>

			<div class="gmoslider-group">

				<div class="gmoslider-label"><?php _e('Show related videos', 'gmoslider'); ?></div>
				<label><input type="radio" class="showRelatedVideos" value="true"><?php _e('Yes', 'gmoslider'); ?></label>
				<label><input type="radio" class="showRelatedVideos" value="false" checked="checked""><?php _e('No', 'gmoslider'); ?></label>

			</div>

			<button type="button" class="gmoslider-group gmoslider-delete-slide button button-secondary">
				<span><?php _e('Delete slide', 'gmoslider'); ?></span>
			</button>

			<input type="hidden" class="type" value="video" />

		</div>

	</div>
</div>

<div class="image-slide-template" style="display: none;">
	<div class="widefat sortable-slides-list-item postbox">

		<div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br></div>

		<div class="hndle">
			<div class="slide-icon image-slide-icon"></div>
			<div class="slide-title">
				<?php _e('Image slide', 'gmoslider'); ?>
			</div>
			<div class="clear"></div>
		</div>

		<div class="inside">

			<div class="gmoslider-group">

				<img width="80" height="60" src="" class="attachment attachment-80x60" alt="" title="" style="float: none; margin: 0; padding: 0;" />

			</div>

			<div class="gmoslider-group">

				<div class="gmoslider-left gmoslider-label"><?php _e('Title', 'gmoslider'); ?></div>
				<div class="gmoslider-right">
					<select class="titleElementTagID">
						<?php foreach (GMOsliderSlideInserter::getElementTags() as $elementTagID => $elementTag): ?>
							<option value="<?php echo $elementTagID; ?>"><?php echo $elementTag; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="clear"></div>
				<input type="text" class="title" style="width: 100%;" />

			</div>

			<div class="gmoslider-group">

				<div class="gmoslider-left gmoslider-label"><?php _e('Description', 'gmoslider'); ?></div>
				<div class="gmoslider-right">
					<select class="descriptionElementTagID">
						<?php foreach (GMOsliderSlideInserter::getElementTags() as $elementTagID => $elementTag): ?>
							<option value="<?php echo $elementTagID; ?>"><?php echo $elementTag; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="clear"></div>
				<textarea class="description" rows="3" cols="" style="width: 100%;"></textarea><br />

			</div>

			<div class="gmoslider-group">

				<div class="gmoslider-label"><?php _e('URL', 'gmoslider'); ?></div>
				<input type="text" class="url" value="" style="width: 100%;" /><br />

				<div class="gmoslider-label gmoslider-left"><?php _e('Open URL in', 'gmoslider'); ?></div>
				<select class="urlTarget gmoslider-right">
					<option value="_self"><?php _e('Same window', 'gmoslider'); ?></option>
					<option value="_blank"><?php _e('New window', 'gmoslider'); ?></option>
				</select>
				<div class="clear"></div>

				<div class="gmoslider-label gmoslider-left"><?php _e('Don\'t let search engines follow link', 'gmoslider'); ?></div>
	            <input type="checkbox" class="noFollow gmoslider-right" />

	        </div>

			<div class="gmoslider-group">

				<div class="gmoslider-label"><?php _e('Alternative text', 'gmoslider'); ?></div>
				<input type="text" class="alternativeText" style="width: 100%;" />

			</div>

			<button type="button" class="gmoslider-group gmoslider-delete-slide button button-secondary">
				<span><?php _e('Delete slide', 'gmoslider'); ?></span>
			</button>

			<input type="hidden" class="type" value="attachment" />
			<input type="hidden" class="postId" value="" />

		</div>

	</div>
</div>