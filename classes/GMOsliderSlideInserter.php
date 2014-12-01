<?php
class GMOsliderSlideInserter
{
	static function getImageSlideInsertButton()
	{
		add_action('admin_footer', array(__CLASS__, 'includePopup'));

		ob_start();
		include(GMOsliderMain::getPluginPath() . '/views/' . __CLASS__ . '/insert-image-button.php');
		return ob_get_clean();
	}

	static function getTextSlideInsertButton()
	{
		ob_start();
		include(GMOsliderMain::getPluginPath() . '/views/' . __CLASS__ . '/insert-text-button.php');
		return ob_get_clean();
	}

	static function getVideoSlideInsertButton()
	{
		ob_start();
		include(GMOsliderMain::getPluginPath() . '/views/' . __CLASS__ . '/insert-video-button.php');
		return ob_get_clean();
	}

	static function getElementTags()
	{
		return array(
			0 => 'div',
			1 => 'p',
			2 => 'h1',
			3 => 'h2',
			4 => 'h3',
			5 => 'h4',
			6 => 'h5',
			7 => 'h6',
		);
	}

	static function getElementTag($id = null)
	{
		$elementTags = self::getElementTags();

		if (isset($elementTags[$id]))
		{
			return $elementTags[$id];
		}

		return reset($elementTags);
	}

	static function printSearchResults()
	{
		global $wpdb;

		$numberPosts = 10;
		$offset      = 0;

		if (isset($_POST['offset']) &&
			is_numeric($_POST['offset']))
		{
			$offset = $_POST['offset'];
		}

		$attachmentIDs = array();

		if (isset($_POST['attachmentIDs']))
		{
			$attachmentIDs = array_filter($_POST['attachmentIDs'], 'ctype_digit');
		}

		add_filter('posts_where', array(__CLASS__, 'printSearchResultsWhereFilter'));
		$query = new WP_Query(array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'offset'         => $offset,
			'posts_per_page' => $numberPosts + 1,
			'orderby'        => 'date',
			'order'          => 'DESC'
		));
		$attachments = $query->get_posts();
		remove_filter('posts_where', array(__CLASS__, 'printSearchResultsWhereFilter'));

		if (count($attachments) < $numberPosts)
		{
			$searchString = esc_sql($_POST['search']);

			foreach ($attachments as $attachment)
			{
				$attachmentIDs[] = $attachment->ID;
			}

			$fileNameQuery = new WP_Query(array(
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'posts_per_page' => $numberPosts - count($attachments),
				'post__not_in'   => $attachmentIDs,
				'meta_query'     => array(
					array(
						'key'     => '_wp_attached_file',
						'value'   => $searchString,
						'compare' => 'LIKE'
					)
				)
			));

			$fileNameQueryAttachments = $fileNameQuery->get_posts();

			if (is_array($fileNameQueryAttachments) &&
				count($fileNameQueryAttachments) > 0)
			{
				foreach ($fileNameQueryAttachments as $fileNameQueryAttachment)
				{
					$attachments[] = $fileNameQueryAttachment;
				}
			}
		}

		$loadMoreResults = false;

		if (count($attachments) > $numberPosts)
		{
			array_pop($attachments);

			$loadMoreResults = true;
		}

		if (count($attachments) > 0)
		{
			if ($offset > 0)
			{
				echo '<tr valign="top">
					<td colspan="3" style="text-align: center;">
						<b>' . count($attachments) . ' ' . __('More results loaded', 'gmoslider') . '<b>
					</td>
				</tr>';
			}

			foreach ($attachments as $attachment)
			{
				$image = wp_get_attachment_image_src($attachment->ID);

				if (!is_array($image) ||
					!$image)
				{
					if (!empty($attachment->guid))
					{
						$imageSrc = $attachment->guid;
					}
					else
					{
						continue;
					}
				}
				else
				{
					$imageSrc = $image[0];
				}

				if (!$imageSrc ||
					empty($imageSrc))
				{
					$imageSrc = GMOsliderMain::getPluginUrl() . '/images/GMOsliderPostType/no-img.png';
				}

				echo '<tr valign="top" data-attachment-Id="' . $attachment->ID . '" class="result-table-row">
					<td class="image">
						<img width="60" height="60" src="' . $imageSrc . '" class="attachment" alt="' . $attachment->post_title . '" title="' . $attachment->post_title . '">
					</td>
					<td class="column-title">
						<strong class="title">' . $attachment->post_title . '</strong>
						<p class="description">' . $attachment->post_content . '</p>
					</td>
					<td class="insert-button">
						<input
							type="button"
							class="insert-attachment button-secondary"
							value="' . __('Insert', 'gmoslider') . '"
						/>
					</td>
				</tr>';
			}

			if ($loadMoreResults)
			{
				echo '<tr>
					<td colspan="3" style="text-align: center;">
						<button class="button-secondary load-more-results" data-offset="' . ($offset + $numberPosts) . '">
							' . __('Load more results', 'gmoslider') . '
						</button>
					</td>
				</tr>';
			}
		}
		else
		{
			echo '<tr>
				<td colspan="3" style="text-align: center;">
					<a href="' . admin_url() . 'media-new.php" target="_blank">
						' . __('No images were found, click here to upload some.', 'gmoslider') . '
					</a>
				</td>
			</tr>';
		}

		die;
	}

	static function printSearchResultsWhereFilter($where)
	{
		global $wpdb;

		$searchString = $_POST['search'];
		$searchString = esc_sql($searchString);

		if (isset($_POST['search']))
		{
			$where .= $wpdb->prepare(
				" AND (post_title LIKE '%%%s%%' OR ID LIKE '%%%s%%') ",
				$searchString,
				$searchString
			);
		}

		return $where;
	}

	static function includePopup()
	{
		include(GMOsliderMain::getPluginPath() . '/views/' . __CLASS__ . '/search-popup.php');
	}

	static function localizeScript()
	{
		if (!function_exists('get_current_screen'))
		{
			return;
		}

        $currentScreen = get_current_screen();

        if ($currentScreen->post_type != GMOsliderPostType::$postType)
        {
            return;
        }

		wp_localize_script(
			'gmoslider-admin-script',
			'gmoslider_admin_script_editGMOslider',
			array(
				'data' => array(),
				'localization' => array(
					'confirm'       => __('Are you sure you want to delete this slide?', 'gmoslider'),
					'uploaderTitle' => __('Insert image slide', 'gmoslider')
				)
			)
		);
	}
}