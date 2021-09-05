<?php

namespace HUU;

class Bese {
	public function yukle()
	{
		global $settings;
		
		Tema::yukle();

		if(!empty($settings['bese_allow_color_select']) != 0){
			add_integration_function('integrate_load_theme', self::class.'::addCustomColorVars', false);
		}
		add_integration_function('integrate_pre_javascript_output', self::class.'::addJavascriptVars', false);
	}

	public function current_mode()
	{
		global $settings, $options;

		$modes = array('light', 'dark');
		$active_mode = 0;

		if (!empty($settings['bese_allow_user_modes']) && isset($options['bese_dark_mode']))
			$active_mode = $options['bese_dark_mode'];
		elseif (isset($settings['bese_default_mode']))
			$active_mode = $settings['bese_default_mode'];

		return $modes[$active_mode];
	}

	public function addCustomColorVars()
	{
		global $settings;

		$color_key = 'bese_color_';
		$colors = array();

		foreach ($settings as $key => $setting)
		{
			if (substr($key, 0, strlen($color_key)) !== $color_key || empty($setting))
				continue;

			$color_name = str_replace(
				array('bese_', '_'),
				array('', '-'),
				$key
			);

			if(Bese::current_mode() == 'dark'){
				if($color_name == 'color-default'){
					$color_name=str_replace('color-default', 'color-secondary', $color_name);
				}elseif($color_name == 'color-secondary'){
					$color_name=str_replace('color-secondary', 'color-default', $color_name);
				}
			}
			$hsl = huu_hex_to_hsl($setting);
			$colors["--$color_name-h"] = $hsl[0] . 'deg';
			$colors["--$color_name-s"] = $hsl[1] * 100 . '%';
			$colors["--$color_name-l"] = $hsl[2] * 100 . '%';
			$colors["--$color_name"] = "hsl(var(--$color_name-h), var(--$color_name-s), var(--$color_name-l))";
		}

		$css = '';

		foreach ($colors as $color => $value)
			$css .= "$color: $value;\n";

		if(Bese::current_mode() == 'dark'){
			addInlineCss(".dark-mode {{$css}}");
		}else{
			addInlineCss(":root {{$css}}");
		}
	}
	public static function addJavascriptVars()
	{
		global $context, $txt, $options;

		if (!$context['user']['is_logged'])
			return;

		$token_name = "profile-th{$context['user']['id']}";

		if (empty($context["{$token_name}_token_var"]))
			createToken($token_name, 'post');

		addJavaScriptVar('bese_dark_mode_toggle_var', '"'.$context["{$token_name}_token_var"].'"');
		addJavaScriptVar('bese_dark_mode_toggle', '"'.$context["{$token_name}_token"].'"');
		addJavaScriptVar('bese_dark_mode', '"'.$options['bese_dark_mode'].'"');
	}
}