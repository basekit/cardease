<?php

namespace Brucimus83;

/**
 * A class used to hold phone number information.
 * <p>
 * Each phone number has a number and a type.
 * 
 * @author CreditCall Communications
 */
class PhoneNumber {

	/**
	 * The phone number.
	 * @private
	 */
	var $m_number = null;

	/**
	 * The type of the phone number.
	 * @private
	 */
	var $m_type = PhoneNumberType_Unknown;

	/**
	 * Creates a new phone number with the fields.
	 * 
	 * @param number The phone number
	 * @param type The type of the phone number.
	 */
	function PhoneNumber($number, $type) {
		$this->m_number = $number;
		$this->m_type = $type;		
	}

	/**
	 * Gets the phone number.
	 * If null it is not set.
	 * @return The phone number.
	 * @see setNumber()
	 */
	function getNumber() {
		return $this->m_number;
	}
	
	/**
	 * Gets the phone number type.
	 * If null it is not set.
	 * @return The phone number type.
	 * @see setType()
	 */
	function getType() {
		return $this->m_type;
	}

	/**
	 * Sets the phone number.
	 * @param number The phone number.  If null it is removed.
	 * @see getNumber()
	 */
	function setNumber($number) {
		$this->m_number = $number;
	}

	/**
	 * Sets the phone number type.
	 * @param type The phone number type.  If null it is removed.
	 * @see getType()
	 */
	function setType($type) {
		$this->m_type = $type;
	}

	/**
	 * Returns a string detailing the number and type of the phone number.
	 * @return A string detailing the number and type of the phone number.
	 */
	function toString() {
		$str = "";
		
		$str .= $this->m_number;
		$str .= ":";
		$str .= $this->m_type;
		
		return $str;
	}
}

?>
