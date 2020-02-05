<?php

namespace Brucimus83;

/**
 * An error that can be returned as the result of a CardEaseXML request in a CardEase response. Contains
 * both an error code and a error message. This class is used to hold these.
 * 
 * @author CreditCall Communications
 */
class CardEaseError {

	/**
	 * The error code associated with this error.
	 * @private
	 */
	var $m_code = ErrorCode_Empty;

	/**
	 * The error message associated with this error code.
	 * @private
	 */
	var $m_message = null;

	/**
	 * Constructs a new error with the given error code and error message.
	 * @private
	 * 
	 * @param code
	 *	The error code associated with this error. This should be a
	 *	valid integer.
	 * @param message
	 *	The error message associated with this error. This is an alphanumeric string. This should not
	 *	be null.
	 */
	function Error($code, $message) {
		$this->m_code = ErrorCode::parse($code);
		$this->m_message = $message;
	}

	/**
	 * Gets the error code associated with this error.
	 * 
	 * @return The error code associated with this error. If null is returned
	 *	 the error code has not been specified.
	 */
	function getCode() {
		return $this->m_code;
	}

	/**
	 * Gets the error message associated with this error. This is an alphanumeric string.
	 * 
	 * @return The error message associated with this error. If null is returned
	 *	 the error message has not been specified.
	 */
	function getMessage() {
		return $this->m_message;
	}

	/**
	 * Returns a string showing both the error code and error message associated
	 * with this error.
	 * 
	 * @return A string showing both the error code and error message associated
	 *	 with this error.
	 */
	function toString() {
		return $this->m_code . ":" . $this->m_message;
	}
}

?>
