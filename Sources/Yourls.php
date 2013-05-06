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
	protected $_rawData = array();
	public $apiUrl = '';
	public $apiAction = 'shorturl';
	public $errors = array();

	public function __construct($url)
	{
		global $modSettings;

		if (empty($url))
		{
			$this->errors[] = 'noUrl';
			return false;
		}

		$this->url = $url;
		$this->_user = !empty($modSettings['Yourls_settingsUser']) ? $modSettings['Yourls_settingsUser'] : false;
		$this->_pass = !empty($modSettings['Yourls_settingsPass']) ? $modSettings['Yourls_settingsPass'] : false;
		$this->setDomain();
	}

	protected function setDomain()
	{
		global $modSettings;

		// Check if the url has a scheme
		if (!empty($modSettings['Yourls_settingsDomain']))
		{
			$this->domain = ((substr_compare($modSettings['Yourls_settingsDomain'], 'http://', 0, 7)) === 0 || (substr_compare($modSettings['Yourls_settingsDomain'], 'https://', 0, 8)) === 0 ?  '' : 'http://') . $modSettings['Yourls_settingsDomain'];

			$this->apiUrl = $this->domain . '/yourls-api.php';
		}

		// Fill up an error, there is no domain to work with
		else
			$this->errors[] = 'emptyDomain';
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
	 * Tries to fetch the content of a given url, puts the result in a protected property Yourls::_rawData
	 *
	 * @access protected
	 * @return void
	 */
	protected function fetch_web_data()
	{
		// Overwrite
		if (!empty($url))
			$this->url = $url;

		// An extra check just to be sure
		if (!isset($this->apiAction) || empty($this->apiAction))
			$this->apiAction = 'shorturl';

		// I can haz cURL?
		if (function_exists ('curl_init'))
		{
			// From the remote API call sample
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
			curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result
			curl_setopt($ch, CURLOPT_POST, 1);              // This is a POST request
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(     // Data to POST
				'url'      => $this->url,
				'format'   => 'json',
				'action'   => $this->apiAction,
				'username' => $this->_user,
				'password' => $this->_pass
			));

			// Fetch
			$this->_rawData = curl_exec($ch);
			curl_close($ch);
		}

		// Good old SMF's fetch_web_data to the rescue!
		else
		{
			global $sourcedir;

			// Requires a function in a source file far far away...
			require_once($sourcedir .'/Subs-Package.php');

			// Send the result directly, we are gonna handle it on every case
			$this->_rawData = fetch_web_data($this->url);
		}
	}

	/**
	 * Make the call to the external server and process the results, deals with errors if any
	 *
	 * @access public
	 * @param string $apiAction the actions the API should perform
	 * @return mixed either a boolean false or an object
	 */
	public function processData($apiAction = 'shorturl')
	{
		// Set the API action
		$this->apiAction = !empty($apiAction) ? $apiAction : 'shorturl';

		// Call the server, sets _rawData
		$this->fetch_web_data();

		// There was an error
		if (empty($this->_rawData))
		{
			$this->errors[] = 'dataFetchFailed';
			return false;
		}

		return $this->data = json_decode($this->_rawData);
	}

	public function getUrlInfo($info)
	{
		// Someone forgot to call Yourls::processData(), lets call it, just to see what happens :P
		if (empty($this->_rawData) || empty($this->data))
			$this->processData();

		// Safety first, hardcode the only possible outcomes
		$safe = array('status', 'code', 'url', 'message', 'title', 'shorturl', 'statusCode');

		if (!in_array($info, $safe))
		{
			$this->errors[] = 'noValidInfoAction';
			return false;
		}

		// Return the values, "url" is a special case
		return 'url' == $info ? get_object_vars($this->data->$info) : $this->data->$info;
	}
}
