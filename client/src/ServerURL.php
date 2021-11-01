<?php

/**
 * A URL and associated attributes that can be used for processing CardEaseXML
 * requests. At least one of these needs to be supplied to the Client for processing to take place.
 * 
 * @author CreditCall Communications
 */

class ServerURL
{
	/**
	 * The read timeout.
	 * @private
	 */
	var $m_timeout = 45000;

	/**
	 * The connection URL.
	 * @private
	 */
	var $m_url = null;

	/**
	 * Constructs a new server URL with specified URL and timeout.
	 * 
	 * @param url
	 *	The actual URL of the server URL. This should be a HTTP URL
	 *	and in the form: "http://..." or "https://...". This must not
	 *	be null.
	 * @param timeout
	 *	The read timeout for the specified server URL in milliseconds.
	 *	If zero is specified an infinite timeout is used.
	 *	For most requests a timeout of 45 seconds (45000) is recommended.
	 */
	function __construct($url, $timeout) {
		if ($timeout < 30000) {
			trigger_error("CardEaseXMLCommunication: Timeout cannot be less than 30 seconds", E_USER_ERROR);
		}
		$this->m_url = $url;
		$this->m_timeout = $timeout;
	}

	/**
	 * Gets the server URL read timeout in milliseconds. If this
	 * is zero an infinite timeout is used. For most requests a timeout of 45
	 * seconds (45000) is recommended.
	 * 
	 * @return The server URL read timeout in milliseconds.
	 */
	function getTimeout() {
		return $this->m_timeout;
	}

	/**
	 * Gets the server URL for the connection.
	 * 
	 * @return The server URL for the connection. If null is returned the URL
	 *	 has not been specified.
	 */
	function getURL() {
		return $this->m_url;
	}

	/**
	 * Sets the server URL read timeout in milliseconds. If zero
	 * is specified an infinite timeout is used. For most requests a timeout of
	 * 45 seconds (45000) is recommended.  A timeout of less than 30 seconds is
	 * not permitted as some authorisations may take this long.
	 * 
	 * @param timeout
	 *	The server URL read timeout in milliseconds.
	 */
	function setTimeout($timeout) {
		if ($timeout < 30000) {
			trigger_error("CardEaseXMLCommunication: Timeout cannot be less than 30 seconds", E_USER_ERROR);
		}
		$this->m_timeout = $timeout;
	}

	/**
	 * Sets the server URL for the connection.
	 * 
	 * @param url
	 *	The server URL for the connection. This must not be null.
	 * @see setURL()
	 */
	function setURL($url) {
		$this->m_url = $url;
	}

	/**
	 * Returns a string describing this server URL and its attributes.
	 * 
	 * @return A string describing this server URL and its attributes.
	 */
	function toString() {
		return $this->m_url . " " . $this->m_timeout;
	}
}
?>
