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

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');

elseif (!defined('SMF'))
	exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

$hooks = array(
	'integrate_admin_include' => '$sourcedir/YourlsHooks.php',
	'integrate_pre_include' => '$sourcedir/Yourls.php',
	'integrate_bbc_codes' => 'Yourls::bbcCode',
	'integrate_bbc_buttons' => 'Yourls::bbcButton',
	'integrate_modify_modifications' => 'Yourls_settings',
	'integrate_admin_areas' => 'Yourls_admin',
	'integrate_load_permissions' => 'Yourls_permissions',
	'integrate_create_topic' => 'Yourls::createShort',
);

foreach ($hooks as $hook => $function)
	remove_integration_function($hook, $function);
