<?php
class GMOsliderView
{
	private $slides = array();

	function __construct($slidesProperties = array())
	{
		if (is_array($slidesProperties))
		{
			foreach ($slidesProperties as $slideProperties)
			{
				$this->slides[] = new GMOsliderSlide($slideProperties);
			}
		}
	}

	function addSlide($slideProperties)
	{
		if (is_array($slideProperties))
		{
			$this->slides[] = new GMOsliderSlide($slideProperties);
		}
	}

	function toHTML($return = true)
	{
		$html = '';

		foreach ($this->slides as $slide)
		{
			$html .= $slide->toHTML();
		}

		$html .= '<div style="clear: both;"></div>';

		if ($return)
		{
			return $html;
		}

		echo $html;

		return "";
	}

	function toAdminHTML($return = true)
	{
		$html = '';
		foreach ($this->slides as $slide)
		{
			if (!($slide instanceof GMOsliderSlide))
			{
				continue;
			}

			$html .= $slide->toAdminHTML();
		}

		if ($return)
		{
			return $html;
		}

		echo $html;

		return "";
	}
}