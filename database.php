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

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');

elseif (!defined('SMF'))
	exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

/* Get on the guest list... she is! (very elitists but still a catchy song :P) */
if ((SMF == 'SSI') && !$user_info['is_admin'])
	die('Admin priveleges required.');

/* Sorry, this mod needs PHP 5.2 */
YourlsCheck();

/* All well, add the fancy new column */
$smcFunc['db_add_column'](
	'{db_prefix}topics',
	array(
		'name' => 'yourls',
		'type' => 'varchar',
		'size' => 255,
		'default' => '',
	),
	array(),
	'update',
	null
);

function YourlsCheck()
{
	if (version_compare(PHP_VERSION, '5.2.0', '<'))
		fatal_error('This mod needs PHP 5.2 or greater. You will not be able to install/use this mod, contact your host and ask for a php upgrade.');

	/* I can haz cURL? */
	if (!function_exists ('curl_init'))
		fatal_error('This mod requires the cURL library, you won\'t be able to use this mod properly, contact your host and ask them to install/enable the cURL lib');
}
