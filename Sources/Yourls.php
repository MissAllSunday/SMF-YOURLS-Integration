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
	protected $_infoOptions = array('status', 'code', 'url', 'message', 'title', 'shorturl', 'statusCode',);

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
		// Overwrite if needed
		if (!empty($url))
			$this->url = $url;

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

		else
			return false;
	}

	/**
	 * Make the call to the external server and process the results, deals with errors if any
	 *
	 * @access public
	 * @return mixed either a boolean false or an object
	 */
	protected function processData()
	{
		// Call the server, sets _rawData
		$this->fetch_web_data();

		// There was an error
		if (empty($this->_rawData))
		{
			$this->errors[] = 'dataFetchFailed';
			return false;
		}

		// Return the data as an object
		return $this->data = json_decode($this->_rawData);
	}

	/**
	 * Returns specific info about a shortened URL
	 *
	 * @access public
	 * @param string $info The specific info you're looking after
	 * @return mixed if param "url" is used, the method will return an array, for all the rest is a string
	 */
	public function getUrlInfo($info = 'shorturl')
	{
		// Set the API action
		$this->apiAction = 'shorturl';

		// Someone forgot to call Yourls::processData(), lets call it, just to see what happens :P
		if (empty($this->_rawData) || empty($this->data))
			$this->processData();

		// Check if the info string is a valid one and also check for the data existance...
		if (!in_array($info, $this->_infoOptions) || empty($this->data->$info))
		{
			$this->errors[] = 'noValidInfoAction';
			return false;
		}

		// Return the values, "url" is a special case
		return 'url' == $info ? get_object_vars($this->data->$info) : $this->data->$info;
	}

	public function getAllInfo()
	{
		// Set the API action
		$this->apiAction = 'shorturl';

		// Someone forgot to call Yourls::processData(), lets call it, just to see what happens :P
		if (empty($this->_rawData) || empty($this->data))
			$this->processData();

		if (empty($this->data))
		{
			$this->errors[] = 'noValidInfoAction';
			return false;
		}

		// All good, return the object
		return $this->data;
	}

	/**
	 * Generic method to get info
	 *
	 * @access public
	 * @param string $apiAction the action that will be performed
	 * @return mixed if param "url" is used, the method will return an array, for all the rest is a string
	 */
	public function get($apiAction)
	{
		// Set the API action
		$this->apiAction = !empty($apiAction) ? $apiAction : 'shorturl';

		// Be sure we got all we need
		if (empty($this->_rawData) || empty($this->data))
			$this->processData();

		if (empty($this->data))
		{
			$this->errors[] = 'noStats';
			return false;
		}

		return $this->data;
	}

	public function checkAPIStatus($url = false)
	{
		global $txt;

		if (!isset($txt['Yourls_title_main']))
			loadLanguage('Yourls');

		$toCheck = !empty($url) ? $url : $this->domain;

		/* Lets see if the cache has something */
		if ($return = cache_get_data('yourls_response', 120) == null)
		{
			/* Check the server */
			$ch = curl_init($toCheck);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_exec($ch);
			$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if (200 == $retcode)
				$return = 200;

			/* There is an issue, disable the mod and tell the admin */
			else
			{
				$return = false;

				/* Disable both features at once */
				updateSettings(array('Yourls_settingsEnable' => 0, 'Yourls_settingsEnableBBC' => 0), true);

				/* Tell the admin about it */
				log_error($txt['Yourls_error_server_error']);
			}

			/* Store the response */
			cache_put_data('yourls_response', $return, 120);
		}

		return $return;
	}

	static function bbCode(&$codes)
	{
		global $modSettings;

		if (empty($modSettings['Yourls_settingsEnableBBC']))
			return;

		/* Set the tag */
		$tag = !empty($modSettings['Yourls_BBCtag']) ? trim($modSettings['Yourls_BBCtag']) : 'yourls';

		$codes[] = array(
				'tag' => $tag,
				'type' => 'unparsed_content',
				'content' => '<a href="$1" class="bbc_link" target="_blank">$1</a>',
				'validate' => create_function('&$tag, &$data, $disabled', '
					$data = strtr($data, array(\'<br />\' => \'\'));
					if (strpos($data, \'http://\') !== 0 && strpos($data, \'https://\') !== 0)
						$data = \'http://\' . $data;
						$data = preg_replace(\'~[\r|\n]+~\', \'\', $data);

						$yourls = new Yourls($data);

						/* Check if everything is fine and dandy... */
						if ($yourls->checkAPIStatus() == 200)
							$data = $yourls->getUrlInfo(\'shorturl\');

				'),
			);

		$codes[] = array(
				'tag' => $tag,
				'type' => 'unparsed_equals',
				'before' => '<a href="$1" class="bbc_link" target="_blank">',
				'after' => '</a>',
				'validate' => create_function('&$tag, &$data, $disabled', '
					if (strpos($data, \'http://\') !== 0 && strpos($data, \'https://\') !== 0)
						$data = \'http://\' . $data;
						$data = preg_replace(\'~[\r|\n]+~\', \'\', $data);

						/* Check if everything is fine and dandy... */
						if ($yourls->checkAPIStatus() == 200)
							$data = $yourls->getUrlInfo(\'shorturl\');

				'),
				'disallow_children' => array('email', 'ftp', 'url', 'iurl'),
				'disabled_after' => ' ($1)',
			);
	}

	/* The bbc button */
	static function bbcButton(&$buttons)
	{
		global $txt, $modSettings;

		if (empty($modSettings['Yourls_settingsEnableBBC']))
			return;

		loadLanguage('Yourls');

		/* Set the tag */
		$tag = !empty($modSettings['Yourls_BBCtag']) ? trim($modSettings['Yourls_BBCtag']) : 'yourls';

		$buttons[count($buttons) - 1][] = array(
			'image' => 'yourls',
			'code' => $tag,
			'before' => '['. $tag .']',
			'after' => '[/'. $tag .']',
			'description' => $txt['Yourls_bbcDesc'],
		);
	}
}
