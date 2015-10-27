<?php

/**
 * Internal use to indicate no Transaction Status is known.
 */
define("ThreeDSecureTransactionStatus_Empty", "-1");

/**
 * The 3-D Secure authentication returned "attempted".
 * All associated ECI, CAVV/AAV and XID data should also be sent.
 */
define("ThreeDSecureTransactionStatus_Attempted", "Attempted");

/**
 * The 3-D Secure authentication returned "failure".
 */
define("ThreeDSecureTransactionStatus_Failed", "Failed");

/**
 * The 3-D Secure authentication did not return a value.
 */
define("ThreeDSecureTransactionStatus_None", "None");

/**
 * The 3-D Secure authentication returned "success".
 * All associated ECI, CAVV/AAV and XID data should also be sent.
 */
define("ThreeDSecureTransactionStatus_Successful", "Successful");

/**
 * The 3-D Secure authentication returned "unknown" or "unable".
 */
define("ThreeDSecureTransactionStatus_Unknown", "Unknown");

/**
 * A list of the values for "quicker" parsing.
 * @private
 * @static
 */
$GLOBALS["ThreeDSecureTransactionStatusValues"] = array(
	ThreeDSecureTransactionStatus_Empty,
	ThreeDSecureTransactionStatus_Attempted,
	ThreeDSecureTransactionStatus_Failed,
	ThreeDSecureTransactionStatus_None,
	ThreeDSecureTransactionStatus_Successful,
	ThreeDSecureTransactionStatus_Unknown,
);

/**
 * The result returned from a 3-D Secure authentication.
 * 
 * @author CreditCall Communications
 * @see Request
 */
class ThreeDSecureTransactionStatus {

	/**
	 * Converts a 3-D Secure result into an enumerated type.
	 * @private
	 * @static
	 * @param code
	 *	The 3-D Secure result to convert into an enumerated type.
	 * @return The resultant enumerated type.
	 */
	function parse($code) {

		foreach ($GLOBALS["ThreeDSecureTransactionStatusValues"] as $value) {
			if (strcasecmp($code, $value) === 0) {
				return $value;
			}
		}

		trigger_error("InvalidArgument: Unknown code: " . $code, E_USER_ERROR);
	}
}
?>
