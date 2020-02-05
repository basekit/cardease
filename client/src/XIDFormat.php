<?php

namespace Brucimus83;

/**
 * The format of the value data is ASCII. For example, =XYZ.
 */
define("XIDFormat_Ascii", "ascii");

/**
 * The format of the value data is ASCII Hex. For example, FF00.
 */
define("XIDFormat_AsciiHex", "asciihex");

/**
 * The format of the value data is Base64.
 */
define("XIDFormat_Base64", "base64");

/**
 * A list of the values for "quicker" parsing.
 * @private
 * @static
 */
$GLOBALS["XIDFormatValues"] = array(
	XIDFormat_Ascii,
	XIDFormat_AsciiHex,
	XIDFormat_Base64,
);

/**
 * The format of the XID property
 * @author CreditCall Communications
 * @see Request
 */
class XIDFormat {

	/**
	 * Converts data format into an enumerated type.
	 * @private
	 * @static
	 * @param code
	 *	The data format to convert into an enumerated type.
	 * @return The resultant enumerated type.
	 */
	function parse($code) {

		foreach ($GLOBALS["XIDFormatValues"] as $value) {
			if (strcasecmp($code, $value) === 0) {
				return $value;
			}
		}

		trigger_error("InvalidArgument: Unknown code: " . $code, E_USER_ERROR);
	}
}
?>
