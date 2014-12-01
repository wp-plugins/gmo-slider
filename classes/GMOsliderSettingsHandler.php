<?php
class GMOsliderSettingsHandler
{
	static $nonceAction = 'gmoslider-nonceAction';
	static $nonceName = 'gmoslider-nonceName';

	static $settingsKey = 'settings';
	static $styleSettingsKey = 'styleSettings';
	static $slidesKey = 'slides';

	static $settings = array();
	static $styleSettings = array();
	static $slides = array();

	static function getAllSettings($gmosliderId, $fullDefinition = false, $enableCache = true, $mergeDefaults = true)
	{
		$settings                          = array();
		$settings[self::$settingsKey]      = self::getSettings($gmosliderId, $fullDefinition, $enableCache,  $mergeDefaults);
		$settings[self::$styleSettingsKey] = self::getStyleSettings($gmosliderId, $fullDefinition, $enableCache,  $mergeDefaults);
		$settings[self::$slidesKey]        = self::getSlides($gmosliderId, $enableCache);

		return $settings;
	}

	static function getSettings($gmosliderId, $fullDefinition = false, $enableCache = true, $mergeDefaults = true)
	{
		if (!is_numeric($gmosliderId) ||
			empty($gmosliderId))
		{
			return array();
		}

		if ($fullDefinition)
		{
			$enableCache   = false;
			$mergeDefaults = true;
		}

		if (!isset(self::$settings[$gmosliderId]) ||
			empty(self::$settings[$gmosliderId]) ||
			!$enableCache)
		{
			$settingsMeta = get_post_meta(
				$gmosliderId,
				self::$settingsKey,
				true
			);

			if (!$settingsMeta ||
				!is_array($settingsMeta))
			{
				$settingsMeta = array();
			}

			if ($fullDefinition)
			{
				foreach ($settingsMeta as $key => $value)
				{
					$settingsMeta[$key] = array('value' => $value);
				}
			}

			$defaults = array();

			if ($mergeDefaults)
			{
				$defaults = self::getDefaultSettings($fullDefinition);
			}

			if ($fullDefinition)
			{
				$settings = array_merge_recursive(
					$defaults,
					$settingsMeta
				);
			}
			else
			{
				$settings = array_merge(
					$defaults,
					$settingsMeta
				);
			}

			if ($enableCache)
			{
				self::$settings[$gmosliderId] = $settings;
			}
		}
		else
		{
			$settings = self::$settings[$gmosliderId];
		}

		return $settings;
	}

	static function getStyleSettings($gmosliderId, $fullDefinition = false, $enableCache = true, $mergeDefaults = true)
	{
		if (!is_numeric($gmosliderId) ||
			empty($gmosliderId))
		{
			return array();
		}

		if ($fullDefinition)
		{
			$enableCache   = false;
			$mergeDefaults = true;
		}

		if (!isset(self::$styleSettings[$gmosliderId]) ||
			empty(self::$styleSettings[$gmosliderId]) ||
			!$enableCache)
		{
			$styleSettingsMeta = get_post_meta(
				$gmosliderId,
				self::$styleSettingsKey,
				true
			);

			if (!$styleSettingsMeta ||
				!is_array($styleSettingsMeta))
			{
				$styleSettingsMeta = array();
			}

			if ($fullDefinition)
			{
				foreach ($styleSettingsMeta as $key => $value)
				{
					$styleSettingsMeta[$key] = array('value' => $value);
				}
			}

			$defaults = array();

			if ($mergeDefaults)
			{
				$defaults = self::getDefaultStyleSettings($fullDefinition);
			}

			if ($fullDefinition)
			{
				$styleSettings = array_merge_recursive(
					$defaults,
					$styleSettingsMeta
				);
			}
			else
			{
				$styleSettings = array_merge(
					$defaults,
					$styleSettingsMeta
				);
			}

			if ($enableCache)
			{
				self::$styleSettings[$gmosliderId] = $styleSettings;
			}
		}
		else
		{
			$styleSettings = self::$styleSettings[$gmosliderId];
		}

		return $styleSettings;
	}

	static function getSlides($gmosliderId, $enableCache = true)
	{
		if (!is_numeric($gmosliderId) ||
			empty($gmosliderId))
		{
			return array();
		}

		if (!isset(self::$slides[$gmosliderId]) ||
			empty(self::$slides[$gmosliderId]) ||
			!$enableCache)
		{
			$slides = get_post_meta(
				$gmosliderId,
				self::$slidesKey,
				true
			);
		}
		else
		{
			$slides = self::$slides[$gmosliderId];
		}

		if (is_array($slides))
		{
			ksort($slides);
		}
		else
		{
			$slides = array();
		}

		return $slides;
	}

	static function getViews($gmosliderId, $returnAsObjects = true, $enableCache = true)
	{
		$slides = self::getSlides($gmosliderId, $enableCache);

		$settings = GMOsliderSettingsHandler::getSettings($gmosliderId, false, $enableCache);
		$slidesPerView = 1;

		if(isset($settings['slidesPerView']))
		{
			$slidesPerView = $settings['slidesPerView'];
		}

		$i      = 0;
		$viewId = -1;
		$views  = array();

		if (is_array($slides))
		{
			foreach ($slides as $slide)
			{
				if ($i % $slidesPerView == 0)
				{
					$viewId++;

					if ($returnAsObjects)
					{
						$views[$viewId] = new GMOsliderView();
					}
					else
					{
						$views[$viewId] = array();
					}
				}

				if ($returnAsObjects)
				{
					$views[$viewId]->addSlide($slide);
				}
				else
				{
					$views[$viewId][] = $slide;
				}

				$i++;
			}
		}

		return $views;
	}

	static function save($postId)
	{
		if (get_post_type($postId) != GMOsliderPostType::$postType ||
			(!isset($_POST[self::$nonceName]) || !wp_verify_nonce($_POST[self::$nonceName], self::$nonceAction)) ||
			!current_user_can('gmoslider-edit-gmosliders', $postId) ||
			(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE))
		{
			return $postId;
		}
		$oldSettings      = self::getSettings($postId);
		$oldStyleSettings = self::getStyleSettings($postId);

		$newPostSettings = $newPostStyleSettings = $newPostSlides = array();

		if (isset($_POST[self::$settingsKey]) &&
			is_array($_POST[self::$settingsKey]))
		{
			$newPostSettings = $_POST[self::$settingsKey];
		}

		if (isset($_POST[self::$styleSettingsKey]) &&
			is_array($_POST[self::$styleSettingsKey]))
		{
			$newPostStyleSettings = $_POST[self::$styleSettingsKey];
		}

		if (isset($_POST[self::$slidesKey]) &&
			is_array($_POST[self::$slidesKey]))
		{
			$newPostSlides = $_POST[self::$slidesKey];
		}

		$newSettings = array_merge(
			$oldSettings,
			$newPostSettings
		);

		$newStyleSettings = array_merge(
			$oldStyleSettings,
			$newPostStyleSettings
		);

		update_post_meta($postId, self::$settingsKey, $newSettings);
		update_post_meta($postId, self::$styleSettingsKey, $newStyleSettings);
		update_post_meta($postId, self::$slidesKey, $newPostSlides);

		return $postId;
	}

	static function getAllDefaults($key = null, $fullDefinition = false, $fromDatabase = true)
	{
		$data                          = array();
		$data[self::$settingsKey]      = self::getDefaultSettings($fullDefinition, $fromDatabase);
		$data[self::$styleSettingsKey] = self::getDefaultStyleSettings($fullDefinition, $fromDatabase);

		return $data;
	}

	static function getDefaultSettings($fullDefinition = false, $fromDatabase = true)
	{
		$yes = __('Yes', 'gmoslider');
		$no  = __('No', 'gmoslider');

		$data = array(
			'animation' => 'fade',
			'direction' => 'horizontal',
			'animationLoop' => 'true',
			'easing' => 'swing',
			'smoothHeight' => 'false',
			'slideshow' => 'true',
			'slideshowSpeed' => '7000',
			'animationSpeed' => '600',
			'initDelay' => '0',
			'controlNav' => 'true',
			'directionNav' => 'true',
			'pauseOnHover' => 'true',
			'textPosition' => 'leftBottom'
		);

		if ($fromDatabase)
		{
			$data = array_merge(
				$data,
				$customData = get_option(GMOsliderGeneralSettings::$defaultSettings, array())
			);
		}

		if ($fullDefinition)
		{
			$descriptions = array(
				
				'animation' => __('Select your animation type', 'gmoslider'),
				'direction' => __('Select the sliding direction', 'gmoslider'),
				'animationLoop' => __('Should the animation loop?', 'gmoslider'),
				'easing' => __('Determines the easing method used in jQuery transitions.', 'gmoslider'),
				'smoothHeight' => __('Allow height of the slider to animate smoothly in horizontal mode', 'gmoslider'),
				'slideshow' => __('Animate slider automatically', 'gmoslider'),
				'slideshowSpeed' => __('Set the speed of the slideshow cycling, in milliseconds', 'gmoslider'),
				'animationSpeed' => __('Set the speed of animations, in milliseconds', 'gmoslider'),
				'initDelay' => __('Set an initialization delay, in milliseconds', 'gmoslider'),
				'controlNav' => __('Create navigation for paging control of each slide?', 'gmoslider'),
				'directionNav' => __('Create navigation for previous/next navigation?', 'gmoslider'),
				'pauseOnHover' => __('Pause the slideshow when hovering over slider, then resume when no longer hovering', 'gmoslider'),
				'textPosition' => __('Position of the text', 'gmoslider')
			);

			$data = array(
				'animation'                   => array('type' => 'select', 'default' => $data['animation']            , 'description' => $descriptions['animation']            , 'group' => __('Animation', 'gmoslider'), 'options' => array('slide' => __('Slide', 'gmoslider'), 'fade' => __('Fade', 'gmoslider'))),
				'direction'                  => array('type' => 'select'  , 'default' => $data['direction']           , 'description' => $descriptions['direction']           , 'group' => __('Animation', 'gmoslider'), 'options' => array('horizontal' => __('Horizontal', 'gmoslider'), 'vertical' => __('Vertical', 'gmoslider'))),
				'animationLoop'            => array('type' => 'radio'  , 'default' => $data['animationLoop']     , 'description' => $descriptions['animationLoop']     , 'group' => __('Animation', 'gmoslider'), 'options' => array('true' => $yes, 'false' => $no)),
				'easing'                   => array('type' => 'select', 'default' => $data['easing']            , 'description' => $descriptions['easing']            , 'group' => __('Animation', 'gmoslider'), 'options' => array('swing' => 'swing', 'linear' => 'linear', 'jswing' => 'jswing', 'easeInQuad' => 'easeInQuad', 'easeOutQuad' => 'easeOutQuad', 'easeInOutQuad' => 'easeInOutQuad', 'easeInCubic' => 'easeInCubic', 'easeOutCubic' => 'easeOutCubic', 'easeInOutCubic' => 'easeInOutCubic', 'easeInQuart' => 'easeInQuart', 'easeOutQuart' => 'easeOutQuart', 'easeInOutQuart' => 'easeInOutQuart', 'easeInQuint' => 'easeInQuint', 'easeOutQuint' => 'easeOutQuint', 'easeInOutQuint' => 'easeInOutQuint', 'easeInSine' => 'easeInSine', 'easeOutSine' => 'easeOutSine', 'easeInOutSine' => 'easeInOutSine', 'easeInExpo' => 'easeInExpo', 'easeOutExpo' => 'easeOutExpo', 'easeInOutExpo' => 'easeInOutExpo', 'easeInCirc' => 'easeInCirc', 'easeOutCirc' => 'easeOutCirc', 'easeInOutCirc' => 'easeInOutCirc', 'easeInElastic' => 'easeInElastic', 'easeOutElastic' => 'easeOutElastic', 'easeInOutElastic' => 'easeInOutElastic', 'easeInBack' => 'easeInBack', 'easeOutBack' => 'easeOutBack', 'easeInOutBack' => 'easeInOutBack', 'easeInBounce' => 'easeInBounce', 'easeOutBounce' => 'easeOutBounce', 'easeInOutBounce' => 'easeInOutBounce')),
				'slideshowSpeed'                    => array('type' => 'text'  , 'default' => $data['slideshowSpeed']             , 'description' => $descriptions['slideshowSpeed']             , 'group' => __('Animation', 'gmoslider')),
				'animationSpeed'                 => array('type' => 'text'  , 'default' => $data['animationSpeed']          , 'description' => $descriptions['animationSpeed']          , 'group' => __('Animation', 'gmoslider')),
				'initDelay'                      => array('type' => 'text'  , 'default' => $data['initDelay']               , 'description' => $descriptions['initDelay']               , 'group' => __('Animation', 'gmoslider')),
				'slideshow'               => array('type' => 'radio'  , 'default' => $data['slideshow']        , 'description' => $descriptions['slideshow']        , 'group' => __('Animation', 'gmoslider'), 'options' => array('true' => $yes, 'false' => $no)),
				'smoothHeight'               => array('type' => 'radio'  , 'default' => $data['smoothHeight']        , 'description' => $descriptions['smoothHeight']        , 'group' => __('Animation', 'gmoslider'), 'options' => array('true' => $yes, 'false' => $no)),
				'controlNav'              => array('type' => 'radio', 'default' => $data['controlNav']       , 'description' => $descriptions['controlNav']       , 'group' => __('Display', 'gmoslider'), 'options' => array('true' => $yes, 'false' => $no)),
				'directionNav' => array('type' => 'radio' , 'default' => $data['directionNav'], 'description' => $descriptions['directionNav'], 'group' => __('Display', 'gmoslider'), 'options' => array('true' => $yes, 'false' => $no)),
				'pauseOnHover' => array('type' => 'radio' , 'default' => $data['pauseOnHover'], 'description' => $descriptions['pauseOnHover'], 'group' => __('Display', 'gmoslider'), 'options' => array('true' => $yes, 'false' => $no) , 'dependsOn' => array('settings[pauseOnHover]', 'true')),
				'textPosition'                   => array('type' => 'select', 'default' => $data['textPosition']            , 'description' => $descriptions['textPosition']            , 'group' => __('Display', 'gmoslider'), 'options' => array('leftBottom' => __('Left Bottom', 'gmoslider'), 'leftTop' => __('Left Top', 'gmoslider'), 'rightBottom' => __('Right Bottom', 'gmoslider'), 'rightTop' => __('Right Top', 'gmoslider')))
			);
		}

		return $data;
	}

	static function getDefaultStyleSettings($fullDefinition = false, $fromDatabase = true)
	{
		$data = array(
			'style' => 'style-light.css'
		);

		if ($fromDatabase)
		{
			$data = array_merge(
				$data,
				$customData = get_option(GMOsliderGeneralSettings::$defaultStyleSettings, array())
			);
		}

		if ($fullDefinition)
		{
			$data = array(
				'style' => array('type' => 'select', 'default' => $data['style'], 'description' => __('The style used for this GMO Slider', 'gmoslider'), 'options' => GMOsliderGeneralSettings::getStylesheets()),
			);
		}

		return $data;
	}

	static function getInputField($settingsKey, $settingsName, $settings, $hideDependentValues = true)
	{
		if (!is_array($settings) ||
			empty($settings) ||
			empty($settingsName))
		{
			return null;
		}

		$inputField   = '';
		$name         = $settingsKey . '[' . $settingsName . ']';
		$displayValue = (!isset($settings['value']) || (empty($settings['value']) && !is_numeric($settings['value'])) ? $settings['default'] : $settings['value']);
		$class        = ((isset($settings['dependsOn']) && $hideDependentValues)? 'depends-on-field-value ' . $settings['dependsOn'][0] . ' ' . $settings['dependsOn'][1] . ' ': '') . $settingsKey . '-' . $settingsName;

		switch($settings['type'])
		{
			case 'text':

				$inputField .= '<input
					type="text"
					name="' . $name . '"
					class="' . $class . '"
					value="' . $displayValue . '"
				/>';

				break;

			case 'textarea':

				$inputField .= '<textarea
					name="' . $name . '"
					class="' . $class . '"
					rows="20"
					cols="60"
				>' . $displayValue . '</textarea>';

				break;

			case 'select':

				$inputField .= '<select name="' . $name . '" class="' . $class . '">';

				foreach ($settings['options'] as $optionKey => $optionValue)
				{
					$inputField .= '<option value="' . $optionKey . '" ' . selected($displayValue, $optionKey, false) . '>
						' . $optionValue . '
					</option>';
				}

				$inputField .= '</select>';

				break;

			case 'radio':

				foreach ($settings['options'] as $radioKey => $radioValue)
				{
					$inputField .= '<label style="padding-right: 10px;"><input
						type="radio"
						name="' . $name . '"
						class="' . $class . '"
						value="' . $radioKey . '" ' .
						checked($displayValue, $radioKey, false) .
						' />' . $radioValue . '</label>';
				}

				break;

			default:

				$inputField = null;

				break;
		};

		return $inputField;
	}
}