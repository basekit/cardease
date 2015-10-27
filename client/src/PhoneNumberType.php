<?php

/**
 * A home phone number.
 */
define("PhoneNumberType_Home", "home");

/**
 * A mobile phone number.
 */
define("PhoneNumberType_Mobile", "mobile");

/**
 * A phone number that does not match any other category.
 */
define("PhoneNumberType_Other", "other");

/**
 * A work phone number.
 */
define("PhoneNumberType_Work", "work");

/**
 * A phone number with an unknown type.
 */
define("PhoneNumberType_Unknown", "unknown");

/**
 * A list of the values for "quicker" parsing.
 * @private
 * @static
 */
$GLOBALS["PhoneNumberTypeValues"] = array(
	PhoneNumberType_Home,
	PhoneNumberType_Mobile,
	PhoneNumberType_Other,
	PhoneNumberType_Work,
	PhoneNumberType_Unknown,
);

/**
 * A class to represent type of phone number.
 * @author CreditCall Communications
 */
class PhoneNumberType {

	/**
	 * Converts a phone number type into an enumerated type.
	 * @private
	 * @static
	 * @param code
	 *	The type of the phone number. This must not be null.
	 * @return The resultant enumerated type.
	 */
	function parse($code) {

		foreach ($GLOBALS["PhoneNumberTypeValues"] as $value) {
			if (strcasecmp($code, $value) === 0) {
				return $value;
			}
		}

		trigger_error("InvalidArgument: Unknown code: " . $code, E_USER_ERROR);
	}
}
?>
