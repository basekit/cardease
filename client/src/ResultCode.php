<?php

/**
 * No result code.
 */
define("ResultCode_Empty", "-1");

/**
 * The requested transaction was approved.
 */
define("ResultCode_Approved", "0");

/**
 * The requested transaction was declined.
 */
define("ResultCode_Declined", "1");

/**
 * The requested test transaction was successful.
 */
define("ResultCode_TestOK", "99");

/**
 * A list of the values for "quicker" parsing.
 * @private
 * @static
 */
$GLOBALS["ResultCodeValues"] = array(
	ResultCode_Empty,
	ResultCode_Approved,
	ResultCode_Declined,
	ResultCode_TestOK,
);

/**
 * The result code that can be obtained from the CardEase platform when it
 * processes a CardEaseXML request.
 * 
 * @author CreditCall Communications
 */
class ResultCode {

	/**
	 * Converts a result code into an enumerated type.
	 * @private
	 * @static
	 * @param code
	 *	The result code to convert into an enumerated type.
	 * @return The resultant enumerated type.
	 */
	public static function parse($code) {

		foreach ($GLOBALS["ResultCodeValues"] as $value) {
			if (strcasecmp($code, $value) === 0) {
				return $value;
			}
		}

		trigger_error("InvalidArgument: Unknown code: " . $code, E_USER_ERROR);
	}
}
?>
