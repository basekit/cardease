<?php

namespace Brucimus83;

/**
 * A class used to hold email address information.
 * <p>
 * Each email address has an address and a type.
 * 
 * @author CreditCall Communications
 */
class EmailAddress {

	/**
	 * The email address.
	 * @private
	 */
	var $m_address = null;

	/**
	 * The type of the email address.
	 * @private
	 */
	var $m_type = EmailAddressType_Unknown;

	/**
	 * Creates a new email address with the fields.
	 * 
	 * @param address The email address.
	 * @param type The type of the email address.
	 */
	function EmailAddress($address, $type) {
		$this->m_address = $address;
		$this->m_type = $type;		
	}

	/**
	 * Gets the email address.
	 * If null it has not been set.
	 * @return The email address.
	 * @see setAddress()
	 */
	function getAddress() {
		return $this->m_address;
	}
	
	/**
	 * Gets the type of the email address.
	 * If null it has not been set.
	 * @return The type of the email address.
	 * @see setType()
	 */
	function getType() {
		return $this->m_type;
	}

	/**
	 * Sets the email address.
	 * @param address The email address.  If null it is removed.
	 * @see getAddress()
	 */
	function setAddress($address) {
		$this->m_address = $address;
	}

	/**
	 * Sets the type of the email address.
	 * @param type The type of the email address.  If null it is removed.
	 * @see getType()
	 */
	function setType($type) {
		$this->m_type = $type;
	}

	/**
	 * Returns a string detailing the address and type of the email address.
	 * @return A string detailing the address and type of the email address.
	 */
	function toString() {
		$str = "";
		
		$str .= $this->m_address;
		$str .= ":";
		$str .= $this->m_type;
		
		return $str;
	}
}

?>
