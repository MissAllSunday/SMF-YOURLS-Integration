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
	protected $_user = false;
	protected $_pass = false;
	public $domain = false;
	public $data = array();
	public $apiUrl = '';
	public $errors = array();

	public function __construct()
	{
		global $modSettings;

		$this->_user = !empty($modSettings['Yourls_settingsUser']) ? $modSettings['Yourls_settingsUser'] : false;
		$this->_pass = !empty($modSettings['Yourls_settingsPass']) ? $modSettings['Yourls_settingsPass'] : false;
		$this->setDomain();
	}

	protected function setDomain()
	{
		global $modSettings;

		$parsed = array();

		if (!empty($modSettings['Yourls_settingsDomain']))
		{
			$parsed = parse_url($modSettings['Yourls_settingsDomain']);

			if (!empty($parsed) && is_array($parsed))
				$this->domain = rtrim(empty($parsed['scheme']) ? 'http://'. $modSettings['Yourls_settingsDomain'] : $modSettings['Yourls_settingsDomain']);

			$this->apiUrl = $this->domain . '/yourls-api.php';
		}

		// Fill up an error
		else
			$this->errors[] = 'noValidDomain';

	}

	protected function handleErrors($action)
	{
		global $txt;

		// No point going further...
		if (empty($action))
			return false;

		// Load the language strings
		if (!isset($txt['Yourls_title_main']))
			loadLanguage('Yourls');
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
		/* Overwrite */
		if (!empty($url))
			$this->url = $url;

		/* I can haz cURL? */
		if (function_exists ('curl_init'))
		{
			/* From the remote API call sample */
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
			curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result
			curl_setopt($ch, CURLOPT_POST, 1);              // This is a POST request
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(     // Data to POST
					'url'      => $this->url,
					'format'   => 'json',
					'action'   => 'shorturl',
					'username' => $this->_user,
					'password' => $this->_pass
				));

			/* Fetch */
			$this->data = curl_exec($ch);
			curl_close($ch);
		}

		/* Good old SMF's fetch_web_data to the rescue! */
		else
		{
			/* Requires a function in a source file far far away... */
			require_once($this->_sourcedir .'/Subs-Package.php');

			/* Send the result directly, we are gonna handle it on every case */
			$this->data = fetch_web_data($this->url);
		}
	}

	public function getData($url)
	{
		$this->url = $url;
		$this->fetch_web_data();

		return $this->data;
	}
}
