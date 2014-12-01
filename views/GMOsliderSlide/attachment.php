<?php

$title = $description = $url = $urlTarget = $alternativeText = $noFollow = $postId = '';

$titleElementTag = $descriptionElementTag = GMOsliderSlideInserter::getElementTag();

if (isset($properties['title']))
{
	$title = trim(GMOsliderSecurity::htmlspecialchars_allow_exceptions($properties['title']));
}

if (isset($properties['titleElementTagID']))
{
	$titleElementTag = GMOsliderSlideInserter::getElementTag($properties['titleElementTagID']);
}

if (isset($properties['description']))
{
	$description = trim(GMOsliderSecurity::htmlspecialchars_allow_exceptions($properties['description']));
}

if (isset($properties['descriptionElementTagID']))
{
	$descriptionElementTag = GMOsliderSlideInserter::getElementTag($properties['descriptionElementTagID']);
}

if (isset($properties['url']))
{
	$url = htmlspecialchars($properties['url']);
}

if (isset($properties['urlTarget']))
{
	$urlTarget = htmlspecialchars($properties['urlTarget']);
}

if (isset($properties['alternativeText']))
{
	$alternativeText = htmlspecialchars($properties['alternativeText']);
}

if (isset($properties['noFollow']))
{
	$noFollow = ' rel="nofollow" ';
}

if (isset($properties['postId']))
{
	$postId = $properties['postId'];
}

if (is_numeric($postId)):

	$anchorTag = $endAnchorTag = $anchorTagAttributes = '';

	if (strlen($url) > 0)
	{
		$anchorTagAttributes =
			'href="' . $url . '" ' .
			(strlen($urlTarget) > 0 ? 'target="' . $urlTarget . '" ' : '') .
			$noFollow;

		$anchorTag    = '<a ' . $anchorTagAttributes . '>';
		$endAnchorTag = '</a>';
	}

	$attachment = get_post($postId);
	if (!empty($attachment)):

		if (empty($alternativeText))
		{
			$alternativeText = $title;

			if (empty($alternativeText))
			{
				$alternativeText = htmlspecialchars($attachment->post_title);
			}

			if (empty($alternativeText))
			{
				$alternativeText = htmlspecialchars($attachment->post_content);
			}
		}

		$image          = wp_get_attachment_image_src($attachment->ID, 'full');
		$imageSrc       = '';
		$imageWidth     = 0;
		$imageHeight    = 0;
		$imageAvailable = true;

		if (!is_array($image) ||
			!$image ||
			!isset($image[0]))
		{
			if (!empty($attachment->guid))
			{
				$imageSrc = $attachment->guid;
			}
			else
			{
				$imageAvailable = false;
			}
		}
		else
		{
			$imageSrc = $image[0];

			if (isset($image[1], $image[2]))
			{
				$imageWidth  = $image[1];
				$imageHeight = $image[2];
			}
		}

		if ($imageAvailable): ?>

			<div class="gmoslider_slide gmoslider_slide_image">
				<?php echo $anchorTag; ?>
					<img src="<?php echo htmlspecialchars($imageSrc); ?>" alt="<?php echo $alternativeText; ?>" <?php echo ($imageWidth > 0) ? 'width="' . $imageWidth . '"' : ''; ?> <?php echo ($imageHeight > 0) ? 'height="' . $imageHeight . '"' : ''; ?> />
				<?php echo $endAnchorTag; ?>
				<div class="gmoslider_description_box gmoslider_transparent">
					<div class="gmoslider_description_box_inner">
					<?php echo !empty($title) ? '<' . $titleElementTag . ' class="gmoslider_title">' . $anchorTag . $title . $endAnchorTag . '</' . $titleElementTag . '>' : ''; ?>
					<?php echo !empty($description) ? '<' . $descriptionElementTag . ' class="gmoslider_description">' . $anchorTag . $description . $endAnchorTag . '</' . $descriptionElementTag . '>' : ''; ?>
					</div>
				</div>
			</div>

		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>