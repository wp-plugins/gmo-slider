<?php

$attachment = get_post($properties['postId']);

if (isset($attachment)):

	$title = $titleElementTagID = $description = $descriptionElementTagID = $url = $target = $alternativeText = '';

    $noFollow = false;

    if (isset($properties['title']))
	{
		$title = GMOsliderSecurity::htmlspecialchars_allow_exceptions($properties['title']);
	}

	if (isset($properties['titleElementTagID']))
	{
		$titleElementTagID = $properties['titleElementTagID'];
	}

	if (isset($properties['description']))
	{
		$description = GMOsliderSecurity::htmlspecialchars_allow_exceptions($properties['description']);
	}

	if (isset($properties['descriptionElementTagID']))
	{
		$descriptionElementTagID = $properties['descriptionElementTagID'];
	}

	if (isset($properties['url']))
	{
		$url = $properties['url'];
	}

	if (isset($properties['urlTarget']))
	{
		$target = $properties['urlTarget'];
	}

    if (isset($properties['noFollow']))
    {
        $noFollow = true;
    }

	if (isset($properties['alternativeText']))
	{
		$alternativeText = $properties['alternativeText'];
	}
	else
	{
		$alternativeText = $title;
	}

	$image        = wp_get_attachment_image_src($attachment->ID);
	$imageSrc     = '';
	$displaySlide = true;

	if (!is_array($image) ||
		!$image)
	{
		if (!empty($attachment->guid))
		{
			$imageSrc = $attachment->guid;
		}
		else
		{
			$displaySlide = false;
		}
	}
	else
	{
		$imageSrc = $image[0];
	}

	if (!$imageSrc ||
		empty($imageSrc))
	{
		$imageSrc = GMOsliderMain::getPluginUrl() . '/images/' . __CLASS__ . '/no-img.png';
	}

	$editUrl = admin_url() . '/media.php?attachment_id=' . $attachment->ID . '&amp;action=edit';

	if ($displaySlide): ?>

		<div id="" class="widefat sortable-slides-list-item postbox">

			<div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br></div>

			<div class="hndle">
				<div class="slide-icon image-slide-icon"></div>
				<div class="slide-title">
					<?php if (strlen($title) > 0) : ?>

						<?php echo $title; ?>

					<?php else : ?>

						<?php _e('Image slide', 'gmoslider'); ?>

					<?php endif; ?>
				</div>
				<div class="clear"></div>
			</div>

			<div class="inside">

				<div class="gmoslider-group">

					<a href="<?php echo $editUrl; ?>" title="<?php _e('Edit', 'gmoslider'); ?> &#34;<?php echo $attachment->post_title; ?>&#34;">
						<img width="80" height="60" src="<?php echo $imageSrc; ?>" class="attachment-80x60" alt="<?php echo $attachment->post_title; ?>" title="<?php echo $attachment->post_title; ?>" />
					</a>

				</div>

				<div class="gmoslider-group">

					<div class="gmoslider-left gmoslider-label"><?php _e('Title', 'gmoslider'); ?></div>
					<div class="gmoslider-right">
						<select name="<?php echo $name; ?>[titleElementTagID]">
							<?php foreach (GMOsliderSlideInserter::getElementTags() as $elementTagID => $elementTag): ?>
								<option value="<?php echo $elementTagID; ?>" <?php selected($titleElementTagID, $elementTagID); ?>><?php echo $elementTag; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="clear"></div>
					<input type="text" name="<?php echo $name; ?>[title]" value="<?php echo $title; ?>" style="width: 100%;" />

				</div>

				<div class="gmoslider-group">

					<div class="gmoslider-left gmoslider-label"><?php _e('Description', 'gmoslider'); ?></div>
					<div class="gmoslider-right">
						<select name="<?php echo $name; ?>[descriptionElementTagID]">
							<?php foreach (GMOsliderSlideInserter::getElementTags() as $elementTagID => $elementTag): ?>
								<option value="<?php echo $elementTagID; ?>" <?php selected($descriptionElementTagID, $elementTagID); ?>><?php echo $elementTag; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div clear="clear"></div>
					<textarea name="<?php echo $name; ?>[description]" rows="3" cols="" style="width: 100%;"><?php echo $description; ?></textarea>

				</div>

				<div class="gmoslider-group">

					<div class="gmoslider-label"><?php _e('URL', 'gmoslider'); ?></div>
					<input type="text" name="<?php echo $name; ?>[url]" value="<?php echo $url; ?>" style="width: 100%;" />

					<div class="gmoslider-label gmoslider-left"><?php _e('Open URL in', 'gmoslider'); ?></div>
					<select name="<?php echo $name; ?>[urlTarget]" class="gmoslider-right">
						<option value="_self" <?php selected('_self', $target); ?>><?php _e('Same window', 'gmoslider'); ?></option>
						<option value="_blank" <?php selected('_blank', $target); ?>><?php _e('New window', 'gmoslider'); ?></option>
					</select>
					<div class="clear"></div>

					<div class="gmoslider-label gmoslider-left"><?php _e('Don\'t let search engines follow link', 'gmoslider'); ?></div>
	                <input type="checkbox" name="<?php echo $name; ?>[noFollow]" value="" <?php checked($noFollow); ?> class="gmoslider-right" />
					<div class="clear"></div>

	            </div>

				<div class="gmoslider-group">

					<div class="gmoslider-label"><?php _e('Alternative text', 'gmoslider'); ?></div>
					<input type="text" name="<?php echo $name; ?>[alternativeText]" value="<?php echo $alternativeText; ?>" style="width: 100%;" />

				</div>

				<button type="button" class="gmoslider-group gmoslider-delete-slide button button-secondary">
					<span><?php _e('Delete slide', 'gmoslider'); ?></span>
				</button>

				<input type="hidden" name="<?php echo $name; ?>[type]" value="attachment" />
				<input type="hidden" name="<?php echo $name; ?>[postId]" value="<?php echo $attachment->ID; ?>" />

			</div>

		</div>

	<?php endif; ?>
<?php endif; ?>