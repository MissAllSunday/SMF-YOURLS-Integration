<?php

/**
 * SMF - YOURLS Integration
 *
 * @package SMF
 * @author Suki <suki@missallsunday.com>
 * @copyright 2013 Jessica González
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

function Yourls_admin(&$areas)
{
	global $txt;

	if (!isset($txt['Yourls_title_main']))
		loadLanguage('Yourls');

	$areas['config']['areas']['modsettings']['subsections']['yourls'] = array($txt['Yourls_admin_title']);
}

function Yourls_settings(&$sub_actions)
{
	global $context;

	$sub_actions['yourls'] = 'Yourls_postSettings';
	$context[$context['admin_menu_name']]['tab_data']['tabs']['yourls'] = array();
}

function Yourls_postSettings($return_config = false)
{
	global $context, $scripturl, $txt;

	$config_vars = array(
		array('desc', 'Yourls_settingsDesc'),
		array('check', 'Yourls_settingsEnable', 'subtext' => $txt['Yourls_settingsEnable_sub']),
		array('text', 'Yourls_settingsDomain', 'subtext' => $txt['Yourls_settingsDomain_sub']),
		array('text', 'Yourls_settingsUser', 'subtext' => $txt['Yourls_settingsUser_sub']),
		array('text', 'Yourls_settingsPass', 'subtext' => $txt['Yourls_settingsPass_sub']),
		array('check', 'Yourls_settingsEnableBBC', 'subtext' => $txt['Yourls_settingsEnableBBC_sub']),
		array('text', 'Yourls_BBCtag', 'subtext' => $txt['Yourls_BBCtag_sub']),
	);

	if ($return_config)
		return $config_vars;

	$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=yourls';
	$context['settings_title'] = $txt['Yourls_admin_title'];

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
