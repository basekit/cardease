<?php

/**
 * A class to hold the information about a certification authority.
 */
class CertificationAuthority {

	/**
	 * The description.
	 * @private
	 */
	var $m_description = null;

	/**
	 * The public keys.
	 * @private
	 */
	var $m_publicKeys = array();

	/**
	 * The registered identity.
	 * @private
	 */
	var $m_rid = null;

	/**
	 * Creates a new certification authority with the given description and
	 * registered identity.
	 * 
	 * @param description
	 *	The description of the certification authority. If null no
	 *	description is set.
	 * @param rid
	 *	The registered identity of the certification authority. If
	 *	null no registered identity is set.
	 * @private
	 */
	function __construct($description, $rid) {
		$this->m_description = $description;
		$this->m_rid = $rid;
		$this->m_publicKeys = array();
	}

	/**
	 * Adds a public key to the certification authority.
	 * 
	 * @param publicKey
	 *	The public key to add to the certification authority. This
	 *	should not be null.
	 * @private
	 */
	function addPublicKey($publicKey) {
		if ($this->m_publicKeys === null) {
			$this->m_publicKeys = array();
		}

		$this->m_publicKeys[] = $publicKey;
	}

	/**
	 * Gets the description of the certification authority. This is an
	 * alphanumeric string. For example, Visa, Amex etc.
	 * 
	 * @return The description of the certification authority. If this is null
	 *	no description has been set.
	 */
	function getDescription() {
		return $this->m_description;
	}

	/**
	 * Gets the public keys of the certification authority.
	 * 
	 * @return The public keys of the certification authority. If this is null
	 *	no public keys have been set.
	 */
	function getPublicKeys() {
		return $this->m_publicKeys;
	}

	/**
	 * Gets the registered identifier of the certification authority. The first
	 * five bytes of an applications AID indicates the certification authority
	 * (ie. Visa, Amex etc). This is an alphanumeric string.
	 * 
	 * @return The registered identifier of the certification authority. If this
	 *	is null no registered identifier has been set.
	 */
	function getRID() {
		return $this->m_rid;
	}

	/**
	 * Sets the public keys of the certification authority.
	 * 
	 * @param publicKeys The public keys of the certification authority. If this is null
	 *	the public keys are removed.
	 * @private
	 */
	function setPublicKeys($publicKeys) {
		$this->m_publicKeys = $publicKeys;
	}

	/**
	 * Returns a string version of this certification authority.
	 * 
	 * @return A string version of this certification authority.
	 */
	function toString() {
		
		$eol = "<br>\n";
		$sep = ": ";

		return "CERTIFICATION_AUTHORITY: " . $eol .
			" Description" . $sep . $this->m_description . $eol .
			" RID" . $sep . $this->m_rid . $eol .
			" PublicKeys" . $sep . $this->m_publicKeys . $eol;
	}
}
?>
