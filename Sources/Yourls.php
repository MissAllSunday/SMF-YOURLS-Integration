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

class Yourls
{
	public $name = 'Yourls';
	protected $user = false;
	protected $pass = false;
	protected $domain = false;

	public function __construct()
	{
		global $modSettings;

		$parsed = array();

		$this->_user = !empty($modSettings['Yourls_settingsUser']) ? $modSettings['Yourls_settingsUser'] : false;
		$this->_pass = !empty($modSettings['Yourls_settingsPass']) ? $modSettings['Yourls_settingsPass'] : false;
		$this->setDomain();

	}

	protected function setDomain()
	{
		global $modSettings;

		$parsed = array();

		if (empty($modSettings['Yourls_settingsDomain']))
			$parsed = parse_url($modSettings['Yourls_settingsDomain']);

		if (!empty($parsed) && is_array($parsed))
			$this->_domain = rtrim(empty($parsed['scheme']) ? 'http://'. $modSettings['Yourls_settingsDomain'] : $modSettings['Yourls_settingsDomain']);

		$this->_api_url = $this->_domain . '/yourls-api.php';
	}

	/**
	 * Tries to fetch the content of a given url
	 *
	 * @access protected
	 * @param string $url the url to call
	 * @return mixed either the page requested or a boolean false
	 */
	protected function fetch_web_data($url = false)
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

	protected function init()
	{
	}
}
