<?php

$videoId           = '';
$showRelatedVideos = 0;

if (isset($properties['videoId']))
{
	$videoId = htmlspecialchars($properties['videoId']);
}

if (isset($properties['showRelatedVideos']) && $properties['showRelatedVideos'] === 'true')
{
	$showRelatedVideos = 1;
}

$idPosition = null;

if (($idPosition = stripos($videoId, 'v=')) !== false)
{
	$videoId = substr($videoId, $idPosition + 2);

	$videoId = explode('&', $videoId);

	if (is_array($videoId) && isset($videoId[0]))
	{
		$videoId = $videoId[0];
	}
}

?>

<div class="gmoslider_slide gmoslider_slide_video">
	<div class="gmoslider_slide_video_id" style="display: none;" data-show-related-videos="<?php echo $showRelatedVideos; ?>"><?php echo $videoId; ?></div>
</div>