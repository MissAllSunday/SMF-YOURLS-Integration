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

/**
* class Yourls
*
* Used to connect with a YOURLS server using YOURLS API
*
* @version 1.0
* @author Suki <suki@missallsunday.com>
*/
class Yourls
{
	/**
	* Public name to identify the mod inside SMF
	* @access public
	* @var string
	*/
	public $name = 'Yourls';

	public static $sites = array(
			'linkedin' => array(
				'name' => 'linkedin',
				'url' => 'http://www.linkedin.com/shareArticle?mini=true&url=%s',
			),
			'google' => array(
				'name' => 'google',
				'url' => 'https://plus.google.com/share?url=%s',
			),
			'facebook' => array(
				'name' => 'facebook',
				'url' => 'http://www.facebook.com/sharer/sharer.php?u=%s',
			),
			'twitter' => array(
				'name' => 'twitter',
				'url' => 'http://www.twitter.com/share?url=%s',
			),
		);

	/**
	* The user name to connect to the YOURLs API, this would be filled by an admin from the admin panel making it a string
	* @access protected
	* @var boolean
	*/
	protected $_user = false;


	/**
	* The password used to connect with the API, this would be filled by an admin from the admin panel making it a string
	* @access protected
	* @var boolean
	*/
	protected $_pass = false;


	/**
	* Domain where the YOURLS API should reside, this would be filled in the admin panel making it a string
	* @access public
	* @var boolean
	*/
	public $domain = false;

	/**
	* Holds the raw data that was received by the API, gets set by Yourls::fetch_web_data()
	* @access protected
	* @see Yourls::fetch_web_data()
	* @var array
	*/
	protected $_rawData = array();


	/**
	* Sets te url to connect to the API, uses property Yourls::$domain
	* @access public
	* @see Yourls::$domain
	* @var string
	*/
	public $apiUrl = '';


	/**
	* The action the API will perform, by default it get set to "shorturl"
	* @access public
	* @var string
	*/
	public $apiAction = 'shorturl';


	/**
	* An array that collects any possible error that might be produced during the interaction with the API.
	* @access public
	* @var array
	*/
	public $errors = array();


	/**
	* Possible info options to interact with the API.
	*
	* There might be more but this mod only uses the followig and check the input against this array
	* to make sure we are sending valid params to the API
	* @access protected
	* @var array
	*/
	protected $_infoOptions = array('status', 'code', 'url', 'message', 'title', 'shorturl', 'statusCode',);

	/**
	* Instantiates the class
	*
	* Takes an url parameter and define some other properties used across the class
	* @access public
	* @param string $url The url to be converted
	* @see Yourls::$_user
	* @see Yourls::$_pass
	* @see Yourls::$domain
	* @return void
	*/
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

	/**
	* Sets a correct url for connecting to the API, sets Yourls::$apiUrl
	*
	* Checks the user submitted data for a valid url then append the API file name
	* @access protected
	* @param string $url The url to be converted
	* @see Yourls::$domain
	* @return void
	*/
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

	/**
	* A method for handling al possible errors, unfinished
	*
	* @access protected
	* @param string $action What would the method do, fire an error page or just log the error
	* @return void
	*/
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
	 * @see Yourls::_rawData
	 * @return mixed either string or boolean false on error. The data that was received from the API
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

	/**
	 * Returns All the info feched by processData()
	 *
	 * @access public
	 * @see  Yourls::processData()
	 * @return mixed either an object or a boolean false if there was an error during fetching
	 */
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
	 * @see  Yourls::processData()
	 * @param string $apiAction the action that will be performed
	 * @return mixed either the desire object info or a boolean false
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

	/**
	 * Check the Status of the YOURLS API
	 *
	 * Check the server and gets the status code, then the status gets stored in a cache entry
	 * if the status is a bad one, it disables the entire mod and then logs the error for the admin to see it
	 * @access public
	 * @param string $url the url to check, if empty it will use Yourls::$domain property
	 * @see  Yourls::$domain
	 * @return integer the status code for the given url
	 */
	public function checkAPIStatus($url = false)
	{
		global $txt;

		if (!isset($txt['Yourls_title_main']))
			loadLanguage('Yourls');

		$toCheck = !empty($url) ? $url : $this->domain;

		// Lets see if the cache has something
		if ($return = cache_get_data('yourls_response', 120) == null)
		{
			 // Check the server
			$ch = curl_init($toCheck);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_exec($ch);
			$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if (200 == $retcode || 302 == $retcode)
				$return = 200;

			// There is an issue, disable the mod and tell the admin
			else
			{
				$return = false;

				// Disable both features at once
				updateSettings(array('Yourls_settingsEnable' => 0, 'Yourls_settingsEnableBBC' => 0), true);

				// Tell the admin about it
				log_error($txt['Yourls_error_server_error']);
			}

			// Store the response
			cache_put_data('yourls_response', $return, 120);
		}

		return $return;
	}

	/**
	 * Creates the code for the BBC tag
	 *
	 * Uses hooks, creates a tag for SMF to handle it
	 * @access public
	 * @param array $codes the entire array that SMF uses to create the tags
	 * @return void
	 */
	public static function bbCode(&$codes)
	{
		global $modSettings;

		if (empty($modSettings['Yourls_settingsEnableBBC']))
			return;

		// Set the tag
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
						$check = $yourls->checkAPIStatus();
						if ($check = 200)
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

						$yourls = new Yourls($data);

						/* Check if everything is fine and dandy... */
						$check = $yourls->checkAPIStatus();
						if ($check = 200)
							$data = $yourls->getUrlInfo(\'shorturl\');
				'),
				'disallow_children' => array('email', 'ftp', 'url', 'iurl'),
				'disabled_after' => ' ($1)',
			);
	}

	/**
	 * Creates the code for the BBC image tag
	 *
	 * Uses hooks, creates a simple array key for the SMF editor to create a proper button for the tag
	 * @access public
	 * @param array $buttons the entire array that SMF uses to create the buttons
	 * @return void
	 */
	static function bbcButton(&$buttons)
	{
		global $txt, $modSettings;

		if (empty($modSettings['Yourls_settingsEnableBBC']))
			return;

		loadLanguage('Yourls');

		// Set the tag
		$tag = !empty($modSettings['Yourls_BBCtag']) ? trim($modSettings['Yourls_BBCtag']) : 'yourls';

		$buttons[count($buttons) - 1][] = array(
			'image' => 'yourls',
			'code' => $tag,
			'before' => '['. $tag .']',
			'after' => '[/'. $tag .']',
			'description' => $txt['Yourls_bbcDesc'],
		);
	}

	/**
	 * Creates a short url for a new topic
	 *
	 * Called by integrate_create_topic hook, it creates a new short url when a new topic is created
	 * @access public
	 * @param array $msgOptions some message params not needed by this method
	 * @param array $posterOptions info about the topic poster, not needed by this method
	 * @param array $topicOptions an array containing the topic info, we need the topic ID which is stored on $topicOptions['id']
	 * @return void
	 */
	public static function createShort($msgOptions, $topicOptions, $posterOptions)
	{
		global $modSettings, $scripturl, $smcFunc;

		// Can't do much if the mod is not enable
		if (empty($modSettings['Yourls_settingsEnable']))
			return;

		// This should never happen but just to be sure...
		if (empty($topicOptions['id']))
			return;

		// Set a nice url
		$url = $scripturl . '?topic='. $topicOptions['id'] .'.0';

		// Actually create the short url
		self::createTopicShort($url, $topicOptions['id']);
	}

	/**
	 * Generic method to create urls from topic urls
	 *
	 * Needs a valid topic urls and a topic ID, stores the short url on the topics table
	 * @access public
	 * @param array $buttons the entire array that SMF uses to create the buttons
	 * @return string the shorted url
	 */
	public static function createTopicShort($url, $topicID)
	{
		global $smcFunc;

		// Do not waste my time...
		if (empty($url) || empty($topicID))
			return false;

		// Got everything we need, time to instantiate yourself...
		$yourls = new self($url);

		// Check the server
		$check = $yourls->checkAPIStatus();

		// Do this if the server is responding
		if ($check = 200)
		{
			// Get the short url from the external server
			$shortUrl = $yourls->getUrlInfo('shorturl');

			// Update the DB with the brand new short url
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}topics
				SET yourls = {string:yourls}
				WHERE id_topic = {int:id_topic}',
				array(
					'id_topic' => $topicID,
					'yourls' => $shortUrl,
				)
			);

			// Be nice and return the short url for others to use it
			return $shortUrl;
		}

		// Something went wrong, try some other time
		else
			return false;
	}

	public function shareIcons($url)
	{
		global $modSettings, $settings, $txt;

		if (empty($url))
			return false;

		$url = urlencode($url);

		$return = '<span>';

		// Construct the buttons if enable
		foreach (Yourls::$sites as $site)
			if (!empty($modSettings['Yourls_shareOn_'. $site['name']]))
					$return .='<a href="'. sprintf($site['url'], $url) .'" target="_blank" style="margin:2px;" title="'. $txt['Yourls_shareOn'] . $site['name'] .'"><img src="'. $settings['default_theme_url'] .'/images/yourls/'. $site['name'] .'-'. (!empty($modSettings['Yourls_settingsIconSize']) ? $modSettings['Yourls_settingsIconSize'] : '16') .'.png"  alt="'. $txt['Yourls_shareOn'] . $site['name'] .'" /></a>';

		$return .='</span>';

		return $return;
	}
}
