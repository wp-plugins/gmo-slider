<p style="text-align: center;" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
	<i><?php _e('Insert', 'gmoslider'); ?>:</i><br/>

	<?php echo GMOsliderSlideInserter::getImageSlideInsertButton(); ?>
	<?php echo GMOsliderSlideInserter::getTextSlideInsertButton(); ?>
	<?php echo GMOsliderSlideInserter::getVideoSlideInsertButton(); ?>
</p>

<?php if(count($slides) <= 0): ?>
	<p><?php _e('Add slides to this GMO Slider by using one of the buttons above.', 'gmoslider'); ?></p>
<?php endif; ?>

<style type="text/css">
	.sortable li {
		cursor: pointer;
	}

	.sortable-slide-placeholder {
		border: 1px solid #f00;
	}
</style>
dsadasd
<ul class="sortable-slides-list">
	<?php if(count($slides) > 0): ?>
	<?php foreach($slides as $key => $slide):
		$url = $target = $order = '';
		if(isset($slide['url']))
			$url = $slide['url'];
		if(isset($slide['urlTarget']))
			$target = $slide['urlTarget'];
		if(isset($slide['order']))
			$order = $slide['order'];

		$name = GMOsliderSettingsHandler::$slidesKey . '[' . $key . ']';
		?>

		<li class="widefat sortable-slides-list-item" style="margin: 10px 0; width: auto; background-color: #fafafa;">
			<?php if($slide['type'] == 'text'):

				$title = $description = $color = '';
				if(isset($slide['title']))
					$title = $slide['title'];
				if(isset($slide['description']))
					$description = $slide['description'];
				if(isset($slide['color']))
					$color = $slide['color'];
				?>

				<h3 class="hndle">
					<span style="font-size: 0.8em;">
						<?php _e('Text slide', 'gmoslider'); ?>
					</span>
				</h3>

				<p style="margin: 5px 15px 5px 5px;">
					<i><?php _e('Title', 'gmoslider'); ?></i><br />
					<input type="text" name="<?php echo $name; ?>[title]" value="<?php echo $title; ?>" /><br />
					<i><?php _e('Description', 'gmoslider'); ?></i><br />
					<textarea name="<?php echo $name; ?>[description]" rows="7" cols="" style="width: 100%;"><?php echo $description; ?></textarea><br />
					<i><?php _e('Background color', 'gmoslider'); ?></i><br />
					<input type="text" name="<?php echo $name; ?>[color]" value="<?php echo $color; ?>" class="color {required:false}" />
				</p>

				<p style="margin: 5px 15px 5px 5px;">
					<i><?php _e('URL', 'gmoslider'); ?></i><br />
					<input type="text" name="<?php echo $name; ?>[url]" value="<?php echo $url; ?>" /><br />
					<i><?php _e('Open URL in', 'gmoslider'); ?></i>
					<select name="<?php echo $name; ?>[urlTarget]">
						<option value="_self" <?php selected('_self', $target); ?>><?php _e('Same window', 'gmoslider'); ?></option>
						<option value="_blank" <?php selected('_blank', $target); ?>><?php _e('New window', 'gmoslider'); ?></option>
					</select>
				</p>

				<input type="hidden" name="<?php echo $name; ?>[type]" value="text" />
				<input type="hidden" name="<?php echo $name; ?>[order]" value="<?php echo $order; ?>" class="slide_order" />

			<?php elseif($slide['type'] == 'video'):

				$videoId = '';
				if(isset($slide['videoId']))
					$videoId = $slide['videoId'];
				?>

				<h3 class="hndle">
					<span style="font-size: 0.8em;">
						<?php _e('Video slide', 'gmoslider'); ?>
					</span>
				</h3>

				<p style="margin: 5px 15px 5px 5px;">
					<i><?php _e('Youtube Video ID', 'gmoslider'); ?></i><br />
					<input type="text" name="<?php echo $name; ?>[videoId]" value="<?php echo $videoId; ?>" />
				</p>

				<input type="hidden" name="<?php echo $name; ?>[type]" value="video" />
				<input type="hidden" name="<?php echo $name; ?>[order]" value="<?php echo $order; ?>" class="slide_order" />

			<?php elseif($slide['type'] == 'attachment'):

				$attachment = get_post($slide['postId']);
				if(!isset($attachment))
					continue;

				$title = $description = '';
				if(isset($slide['title']))
					$title = $slide['title'];
				if(isset($slide['description']))
					$description = $slide['description'];

				$image = wp_get_attachment_image_src($attachment->ID);
				$imageSrc = '';
				if(!is_array($image) || !$image){
					if(!empty($attachment->guid))
						$imageSrc = $attachment->guid;
					else
						continue;
				}else{
					$imageSrc = $image[0];
				}
				if(!$imageSrc || empty($imageSrc)) $imageSrc = $noPreviewIcon;

				$editUrl = admin_url() . '/media.php?attachment_id=' . $attachment->ID . '&amp;action=edit'; ?>

				<h3 class="hndle">
					<span style="font-size: 0.8em;">
						<?php _e('Image slide', 'gmoslider'); ?>
					</span>
				</h3>

				<p style="float: left; margin: 5px;">
					<a href="<?php echo $editUrl; ?>" title="<?php _e('Edit', 'gmoslider'); ?> &#34;<?php echo $attachment->post_title; ?>&#34;">
						<img width="80" height="60" src="<?php echo $imageSrc; ?>" class="attachment-80x60" alt="<?php echo $attachment->post_title; ?>" title="<?php echo $attachment->post_title; ?>" />
					</a>
				</p>

				<p style="float: left; margin: 5px 15px 5px 5px;">
					<i><?php _e('Title', 'gmoslider'); ?></i><br />
					<input type="text" name="<?php echo $name; ?>[title]" value="<?php echo $title; ?>" />
				</p>
				<p style="clear: both"></p>

				<p style="margin: 5px 15px 5px 5px;">
					<i><?php _e('Description', 'gmoslider'); ?></i><br />
					<textarea name="<?php echo $name; ?>[description]" rows="3" cols="" style="width: 100%;"><?php echo $description; ?></textarea><br />
				</p>

				<p style="margin: 5px 15px 5px 5px;">
					<i><?php _e('URL', 'gmoslider'); ?></i><br />
					<input type="text" name="<?php echo $name; ?>[url]" value="<?php echo $url; ?>" /><br />
					<i><?php _e('Open URL in', 'gmoslider'); ?></i>
					<select name="<?php echo $name; ?>[urlTarget]">
						<option value="_self" <?php selected('_self', $target); ?>><?php _e('Same window', 'gmoslider'); ?></option>
						<option value="_blank" <?php selected('_blank', $target); ?>><?php _e('New window', 'gmoslider'); ?></option>
					</select>
				</p>

				<input type="hidden" name="<?php echo $name; ?>[type]" value="attachment" />
				<input type="hidden" name="<?php echo $name; ?>[postId]" value="<?php echo $attachment->ID; ?>" />
				<input type="hidden" name="<?php echo $name; ?>[order]" value="<?php echo $order; ?>" class="slide_order" />

			<?php else: ?>

				<p style="margin: 5px 15px 5px 5px;">
					<?php _e('An error occurred while loading this slide, and it will not be present in the GMO Slider', 'gmoslider'); ?>
				</p>

			<?php endif; ?>

			<p style="margin: 5px 15px 5px 5px; color: red; cursor: pointer;" class="gmoslider-delete-slide">
				<span><?php _e('Delete slide', 'gmoslider'); ?></span>
				<span style="display: none;" class="<?php echo $id; ?>"></span>
			</p>

		</li>
	<?php endforeach; ?>
	<?php endif; ?>
</ul>

<div class="text-slide-template" style="display: none;">
	<li class="widefat sortable-slides-list-item" style="margin: 10px 0; width: auto; background-color: #fafafa;">

		<h3 class="hndle">
			<span style="font-size: 0.8em;">
				<?php _e('Text slide', 'gmoslider'); ?>
			</span>
		</h3>

		<p style="margin: 5px 15px 5px 5px;">
			<i><?php _e('Title', 'gmoslider'); ?></i><br />
			<input type="text" class="title" /><br />
			<i><?php _e('Description', 'gmoslider'); ?></i><br />
			<textarea class="description" cols="" rows="7" style="width: 100%;"></textarea><br />
			<i><?php _e('Background color', 'gmoslider'); ?></i><br />
			<input type="text" class="color {required:false}" />
		</p>

		<p style="margin: 5px 15px 5px 5px;">
			<i><?php _e('URL', 'gmoslider'); ?></i><br />
			<input type="text" class="url" value="" /><br />
			<i><?php _e('Open URL in', 'gmoslider'); ?></i>
			<select class="urlTarget">
				<option value="_self"><?php _e('Same window', 'gmoslider'); ?></option>
				<option value="_blank"><?php _e('New window', 'gmoslider'); ?></option>
			</select>
		</p>

		<input type="hidden" class="type" value="text" />
		<input type="hidden" class="slide_order" />

		<p style="margin: 5px 15px 5px 5px; color: red; cursor: pointer;" class="gmoslider-delete-new-slide">
			<span><?php _e('Delete slide', 'gmoslider'); ?></span>
			<span style="display: none;" class="<?php echo $id; ?>"></span>
		</p>

	</li>
</div>

<div class="video-slide-template" style="display: none;">
	<li class="widefat sortable-slides-list-item" style="margin: 10px 0; width: auto; background-color: #fafafa;">

		<h3 class="hndle">
			<span style="font-size: 0.8em;">
				<?php _e('Video slide', 'gmoslider'); ?>
			</span>
		</h3>

		<p style="margin: 5px 15px 5px 5px;">
			<i><?php _e('Youtube Video ID', 'gmoslider'); ?></i><br />
			<input type="text" class="videoId" />
		</p>

		<input type="hidden" class="type" value="video" />
		<input type="hidden" class="slide_order" />

		<p style="margin: 5px 15px 5px 5px; color: red; cursor: pointer;" class="gmoslider-delete-new-slide">
			<span><?php _e('Delete slide', 'gmoslider'); ?></span>
			<span style="display: none;" class="<?php echo $id; ?>"></span>
		</p>

	</li>
</div>

<div class="image-slide-template" style="display: none;">
	<li class="widefat sortable-slides-list-item" style="margin: 10px 0; width: auto; background-color: #fafafa;">

		<h3 class="hndle">
			<span style="font-size: 0.8em;">
				<?php _e('Image slide', 'gmoslider'); ?>
			</span>
		</h3>

		<p style="float: left; margin: 5px;">
			<img width="80" height="60" src="" class="attachment attachment-80x60" alt="" title="" />
		</p>

		<p style="float: left; margin: 5px 15px 5px 5px;">
			<i><?php _e('Title', 'gmoslider'); ?></i><br />
			<input type="text" class="title" />
		</p>
		<p style="clear: both"></p>

		<p style="margin: 5px 15px 5px 5px;">
			<i><?php _e('Description', 'gmoslider'); ?></i><br />
			<textarea class="description" rows="3" cols="" style="width: 100%;"></textarea><br />
		</p>

		<p style="margin: 5px 15px 5px 5px;">
			<i><?php _e('URL', 'gmoslider'); ?></i><br />
			<input type="text" class="url" value="" /><br />
			<i><?php _e('Open URL in', 'gmoslider'); ?></i>
			<select class="urlTarget">
				<option value="_self"><?php _e('Same window', 'gmoslider'); ?></option>
				<option value="_blank"><?php _e('New window', 'gmoslider'); ?></option>
			</select>
		</p>

		<input type="hidden" class="type" value="attachment" />
		<input type="hidden" class="postId" value="" />
		<input type="hidden" value="" class="slide_order" />

		<p style="margin: 5px 15px 5px 5px; color: red; cursor: pointer;" class="gmoslider-delete-new-slide">
			<span><?php _e('Delete slide', 'gmoslider'); ?></span>
			<span style="display: none;" class="<?php echo $id; ?>"></span>
		</p>

	</li>
</div>