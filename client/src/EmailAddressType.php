<?php

/**
 * A home email address.
 */
define("EmailAddressType_Home", "home");

/**
 * An email address that does not fit another category.
 */
define("EmailAddressType_Other", "other");

/**
 * A work email address.
 */
define("EmailAddressType_Work", "work");

/**
 * An email address with an unknown type.
 */
define("EmailAddressType_Unknown", "unknown");

/**
 * A list of the values for "quicker" parsing.
 * @private
 * @static
 */
$GLOBALS["EmailAddressTypeValues"] = array(
	EmailAddressType_Home,
	EmailAddressType_Other,
	EmailAddressType_Work,
	EmailAddressType_Unknown,
);

/**
 * A class to represent type of email address.
 * @author CreditCall Communications
 */
class EmailAddressType {

	/**
	 * Converts an email address type into an enumerated type.
	 * @private
	 * @static
	 * @param code
	 *	The type of the email address. This must not
	 *	be null.
	 * @return The resultant enumerated type.
	 */
	function parse($code) {

		foreach ($GLOBALS["EmailAddressTypeValues"] as $value) {
			if (strcasecmp($code, $value) === 0) {
				return $value;
			}
		}

		trigger_error("InvalidArgument: Unknown code: " . $code, E_USER_ERROR);
	}
}
?>
