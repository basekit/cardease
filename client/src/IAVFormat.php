<?php

/**
 * The format of the value data is ASCII Hex. For example, FF00.
 */
define("IAVFormat_AsciiHex", "asciihex");

/**
 * The format of the value data is Base64.
 */
define("IAVFormat_Base64", "base64");

/**
 * A list of the values for "quicker" parsing.
 * @private
 * @static
 */
$GLOBALS["IAVFormatValues"] = array(
	IAVFormat_AsciiHex,
	IAVFormat_Base64,
);

/**
 * The format of the IAV property
 * @author CreditCall Communications
 * @see Request
 */
class IAVFormat {

	/**
	 * Converts data format into an enumerated type.
	 * @private
	 * @static
	 * @param code
	 *	The data format to convert into an enumerated type.
	 * @return The resultant enumerated type.
	 */
	function parse($code) {

		foreach ($GLOBALS["IAVFormatValues"] as $value) {
			if (strcasecmp($code, $value) === 0) {
				return $value;
			}
		}

		trigger_error("InvalidArgument: Unknown code: " . $code, E_USER_ERROR);
	}
}
?>
