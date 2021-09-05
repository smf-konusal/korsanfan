<?php

function theme_huu(){
	global $txt;

	return $txt['themecopyright'];
}

function inject_buttonlist_icons_bese(&$button_strip)
{
	$icons = array(
		'reply' => 'reply_button',
		'add_poll' => 'poll',
		'notify' => 'notify_button',
		'print' => 'inbox',
		'mark_unread' => 'read_button',
		'move' => 'move',
		'delete' => 'delete',
		'lock' => 'lock',
		'unlock' => 'lock',
		'sticky' => 'sticky',
		'merge' => 'merge',
		'post_poll' => 'poll',
		'new_topic' => 'topics_replies',
		'markread' => 'read_button',
		'calendar' => 'calendar',
	);

	foreach ($icons as $button_key => $icon)
	{
		if (!isset($button_strip[$button_key]))
			continue;

		$button_strip[$button_key]['icon'] = $icon;
	}
}

function huu_hex_to_hsl($hex)
{
	$hex = str_split(ltrim($hex, '#'), 2);

	$rgb = array_map(function($part) {
		return hexdec($part) / 255;
	}, $hex);

	$min = min($rgb);
	$max = max($rgb);

	// Initialize all to 0
	$h = $s = $l = 0;

	// calculate the luminace value by adding the max and min values and divide by 2
	$l = ($min + $max) / 2;

	// If $max and $min are unequal, we need to calculate the saturation and hue
	if ($max !== $min)
	{
		// Saturation
		if ($l < 0.5)
			$s = ($max - $min) / ($max + $min);
		else
			$s = ($max - $min) / (2 - $max - $min);

		// Hue
		switch ($max)
		{
			case $rgb[0]:
				$h = ($rgb[1] - $rgb[2]) / ($max - $min);
				break;
			case $rgb[1]:
				$h = 2 + ($rgb[2] - $rgb[0]) / ($max - $min);
				break;
			case $rgb[2]:
				$h = 4 + ($rgb[0] - $rgb[1]) / ($max - $min);
		}

		// Convert the Hue to degrees
		$h *= 60;

		if ($h < 0)
			$h += 360;
	}

	return array($h, $s, $l);
}
