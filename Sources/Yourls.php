<?php

/**
 * SMF - YOURLS Integration
 *
 * @package SMF
 * @author Suki <suki@missallsunday.com>
 * @copyright 2013 Jessica Gonz�lez
 * @license http://www.mozilla.org/MPL/ MPL 2.0
 *
 * @version 1.0
 */

/*
 * Version: MPL 2.0
 *
 * This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
 * If a copy of the MPL was not distributed with this file,
 * You can obtain one at http://mozilla.org/MPL/2.0/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 */

if (!defined('SMF'))
	die('No direct access');

class Yourls
{
	protected $name = 'Yourls';

	public function __construct()
	{
	}

	static function admin(&$areas)
	{
		global $txt;

		if (!isset($txt['Yourls_title_main']))
			loadLanguage(self::$name);

		$areas['config']['areas']['modsettings']['subsections']['yourls'] = array($txt['Yourls_admin_title']);
	}

	static function settings(&$sub_actions)
	{
		global $context;

		$sub_actions['yourls'] = 'Yourls::postSettings';
		$context[$context['admin_menu_name']]['tab_data']['tabs']['yourls'] = array();
	}

	static function postSettings(&$return_config = false)
	{
		global $context, $scripturl, $txt;

		$config_vars = array(
			array('desc', self::$name .'_settingsDesc'),
			array('check', self::$name .'_settingsEnable', 'subtext' => $txt[self::$name .'_settingsEnable_sub']),
			array('text', self::$name .'_settingsDomain', 'subtext' => $txt[self::$name .'_settingsDomain_sub']),
			array('text', self::$name .'_settingsUser', 'subtext' => $txt[self::$name .'_settingsUser_sub']),
			array('text', self::$name .'_settingsPass', 'subtext' => $txt[self::$name .'_settingsPass_sub']),
			array('check', self::$name .'_settingsEnableBBC', 'subtext' => $txt[self::$name .'_settingsEnableBBC_sub']),
		);

		if ($return_config)
			return $config_vars;

		$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=yourls';
		$context['settings_title'] = $txt[self::$name .'_admin_title'];

		if (empty($config_vars))
		{
			$context['settings_save_dont_show'] = true;
			$context['settings_message'] = '<div align="center">' . $txt['modification_no_misc_settings'] . '</div>';

			return prepareDBSettingContext($config_vars);
		}

		if (isset($_GET['save']))
		{
			checkSession();
			$save_vars = $config_vars;
			saveDBSettings($save_vars);
			redirectexit('action=admin;area=modsettings;sa=yourls');
		}

		prepareDBSettingContext($config_vars);
	}

	/**
	 * Tries to fetch the content of a given url
	 *
	 * @access protected
	 * @param string $url the url to call
	 * @return mixed either the page requested or a boolean false
	 */
	protected function fetch_web_data($url)
	{
		/* Safety first! */
		if (empty($url))
			return false;

		/* I can haz cURL? */
		if (function_exists ('curl_init'))
		{
			/* From the remote API call sample */
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $api_url);
			curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result
			curl_setopt($ch, CURLOPT_POST, 1);              // This is a POST request
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(     // Data to POST
					'url'      => $url,
					'keyword'  => $keyword,
					'format'   => $format,
					'action'   => 'shorturl',
					'username' => $username,
					'password' => $password
				));

			/* Fetch */
			$data = curl_exec($ch);
			curl_close($ch);

			/* Send the data directly, evil, I'm evil! :P */
			return $data;
		}

		/* Good old SMF's fetch_web_data to the rescue! */
		else
		{
			/* Requires a function in a source file far far away... */
			require_once($this->_sourcedir .'/Subs-Package.php');

			/* Send the result directly, we are gonna handle it on every case */
			return fetch_web_data($url);
		}
	}
}
