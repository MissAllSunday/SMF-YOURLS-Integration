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

global $scripturl, $txt;

$txt['Yourls_title_main'] = 'YOURLS';
$txt['Yourls_admin_title'] = 'Yourls admin panel';
$txt['Yourls_settingsEnableBBC'] = 'Enable the BBC button';
$txt['Yourls_settingsEnableBBC_sub'] = 'Check this setting for users to be able to use the yourls short url BBC, this is an independent check that does not need the master setting to be enable';
$txt['Yourls_settingsPass'] = 'User password';
$txt['Yourls_settingsPass_sub'] = 'The password used to connect with the yourl server';
$txt['Yourls_settingsUser'] = 'Yourls User';
$txt['Yourls_settingsUser_sub'] = 'The user to connect with the yourls server';
$txt['Yourls_settingsDomain'] = 'Yourls domain';
$txt['Yourls_settingsDomain_sub'] = 'The domain where the yourls script is located';
$txt['Yourls_settingsEnable'] = 'Enable the Yourls integration mod';
$txt['Yourls_settingsEnable_sub'] = 'This setting needs to be enable for the integration to work';
$txt['Yourls_settingsDesc'] = 'From here you can configure the SMF - YOURLS integration. <br /> Keep in mind that this mod uses a third party script and sometimes there could be lags as it depends on an external server response, the mod performs a check every two minutes to make sure the external server is up and running, if not, the mod would be disable and an error log will be filled to prevent any downtime in your forum.';
$txt['Yourls_shortUrl'] = 'Short Url ';
$txt['Yourls_shortUrlForTopic'] = 'Short url for this topic:';
$txt['Yourls_BBCtag'] = 'The tag for the BBC';
$txt['Yourls_BBCtag_sub'] = 'Be careful to not change this setting veru often as it will result in broken BBC code.<br />If you do not provide a custom tag, "yourls" would be used.';
$txt['Yourls_bbcDesc'] = 'Use this BBC to create a short version of any url';
$txt['Yourls_settingsIconSize'] = 'The size for the icons';
$txt['Yourls_settingsIconSize_sub'] = 'There are four predefined sizes, all icons will have the same size.';
$txt['Yourls_settingsIcon16'] = '16';
$txt['Yourls_settingsIcon24'] = '24';
$txt['Yourls_settingsIcon32'] = '32';
$txt['Yourls_settingsIcon48'] = '48';

// Error strings
$txt['Yourls_error_noUrl'] = 'No url was provided';
$txt['Yourls_error_emptyDomain'] = 'The YOURLS domain setting is empty, you need to provide a valid domain with YOURLS properly installed.';
$txt['Yourls_error_dataFetchFailed'] = 'There was a problem fetching the data from the external server';
$txt['Yourls_error_noValidInfoAction'] = 'You must provide a valid info request';
$txt['Yourls_error_server_error'] = 'The yourls API server is not responding, to prevent any downtime on your forum the mod has been disable, please manually make sure the server is up and enable the mod again.';

