<?php

/**
 * The format of the ICC value data is ASCII Hex. For example, FF00.
 */
define("ICCTagValueType_AsciiHex", "asciihex");

/**
 * The format of the ICC value data is a String. For example, REQ01.
 */
define("ICCTagValueType_String", "string");

/**
 * A list of the values for "quicker" parsing.
 * @private
 * @static
 */
$GLOBALS["ICCTagValueTypeValues"] = array(
	ICCTagValueType_AsciiHex,
	ICCTagValueType_String,
);

/**
 * A class to represent the type of value data held in an EMV ICC tag.
 * The possible values are AsciiHex and String.
 * @author CreditCall Communications
 */
class ICCTagValueType {

	/**
	 * Converts a ICC tag value type into an enumerated type.
	 * @private
	 * @static
	 * @param code
	 *	The type of the value data held in the ICC tag. This must not
	 *	be null.
	 * @return The resultant enumerated type.
	 */
	function parse($code) {

		foreach ($GLOBALS["ICCTagValueTypeValues"] as $value) {
			if (strcasecmp($code, $value) === 0) {
				return $value;
			}
		}

		trigger_error("InvalidArgument: Unknown code: " . $code, E_USER_ERROR);
	}
}
?>
