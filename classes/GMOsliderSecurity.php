<?php
class GMOsliderSecurity
{
	private static $allowedElements = array(
		'b'      => array('endTag' => true, 'attributes' => 'default'),
		'br'     => array('endTag' => false),
		'div'    => array('endTag' => true, 'attributes' => 'default'),
		'h1'     => array('endTag' => true, 'attributes' => 'default'),
		'h2'     => array('endTag' => true, 'attributes' => 'default'),
		'h3'     => array('endTag' => true, 'attributes' => 'default'),
		'h4'     => array('endTag' => true, 'attributes' => 'default'),
		'h5'     => array('endTag' => true, 'attributes' => 'default'),
		'h6'     => array('endTag' => true, 'attributes' => 'default'),
		'i'      => array('endTag' => true, 'attributes' => 'default'),
		'li'     => array('endTag' => true, 'attributes' => 'default'),
		'ol'     => array('endTag' => true, 'attributes' => 'default'),
		'p'      => array('endTag' => true, 'attributes' => 'default'),
		'span'   => array('endTag' => true, 'attributes' => 'default'),
		'strong' => array('endTag' => true, 'attributes' => 'default'),
		'sub'    => array('endTag' => true, 'attributes' => 'default'),
		'sup'    => array('endTag' => true, 'attributes' => 'default'),
		'table'  => array('endTag' => true, 'attributes' => 'default'),
		'tbody'  => array('endTag' => true, 'attributes' => 'default'),
		'td'     => array('endTag' => true, 'attributes' => 'default'),
		'tfoot'  => array('endTag' => true, 'attributes' => 'default'),
		'th'     => array('endTag' => true, 'attributes' => 'default'),
		'thead'  => array('endTag' => true, 'attributes' => 'default'),
		'tr'     => array('endTag' => true, 'attributes' => 'default'),
		'ul'     => array('endTag' => true, 'attributes' => 'default')
	);

	private static $defaultAllowedAttributes = array(
		'class',
		'id',
		'style'
	);

	static function htmlspecialchars_allow_exceptions($text)
	{
		$text = htmlspecialchars(htmlspecialchars_decode($text));

		$allowedElements = self::$allowedElements;

		if (is_array($allowedElements) &&
			count($allowedElements) > 0)
		{
			foreach ($allowedElements as $element => $attributes)
			{
				$position = 0;

				while (($position = stripos($text, $element, $position)) !== false)
				{
					$openingTag        = '<';
					$encodedOpeningTag = htmlspecialchars($openingTag);

					if (substr($text, $position - strlen($encodedOpeningTag), strlen($encodedOpeningTag)) == $encodedOpeningTag)
					{
						$text      = substr_replace($text, '<', $position - strlen($encodedOpeningTag), strlen($encodedOpeningTag));
						$position -= strlen($encodedOpeningTag) - strlen($openingTag);

						$closingTag         = '>';
						$encodedClosingTag  = htmlspecialchars($closingTag);
						$closingTagPosition = stripos($text, $encodedClosingTag, $position);

						if ($closingTagPosition !== false)
						{
							$text = substr_replace($text, '>', $closingTagPosition, strlen($encodedClosingTag));
						}

						$elementAttributes = null;

						if (isset($attributes['attributes']) && is_array($attributes['attributes']))
						{
							$elementAttributes = $attributes['attributes'];
						}
						elseif (isset($attributes['attributes']) && $attributes['attributes'] == 'default')
						{
							$elementAttributes = self::$defaultAllowedAttributes;
						}
						else
						{
							continue;
						}

						if (!is_array($elementAttributes))
						{
							continue;
						}

						$tagText = substr($text, $position, $closingTagPosition - $position);

						foreach ($elementAttributes as $attribute)
						{
							$attributeOpener = $attribute . '=' . htmlspecialchars('"');

							$attributePosition = 0;

							if (($attributePosition = stripos($tagText, $attributeOpener, $attributePosition)) !== false) // Attribute was found
							{
								$attributeClosingPosition = 0;

								if (($attributeClosingPosition = stripos($tagText, htmlspecialchars('"'), $attributePosition + strlen($attributeOpener))) === false) // If no closing position of attribute was found, skip.
								{
									continue;
								}

								$tagText = str_ireplace($attributeOpener, $attribute . '="', $tagText);

								$attributeClosingPosition -= strlen($attributeOpener) - strlen($attribute . '="');
								$tagText                   = substr_replace($tagText, '"', $attributeClosingPosition, strlen(htmlspecialchars('"')));
							}

						}

						$text = substr_replace($text, $tagText, $position, $closingTagPosition - $position);
					}

					$position++;
				}

				if (isset($attributes['endTag']) && $attributes['endTag'])
				{
					$text = str_ireplace(htmlspecialchars('</' . $element . '>'), '</' . $element . '>', $text);
				}
			}
		}

		return $text;
	}
}