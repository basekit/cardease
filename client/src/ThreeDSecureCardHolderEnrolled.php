<?php

/**
 * Internal use to indicate no CardHolder Enrollment is known.
 */
define("ThreeDSecureCardHolderEnrolled_Empty", "-1");

/**
 * The card holder is not enrolled.
 */
define("ThreeDSecureCardHolderEnrolled_No", "No");

/**
 * The 3-D Secure enrollment check did not return anything.
 */
define("ThreeDSecureCardHolderEnrolled_None", "None");

/**
 * The 3-D Secure enrollment check returned "unknown" or "unable".
 */
define("ThreeDSecureCardHolderEnrolled_Unknown", "Unknown");

/**
 * The card holder is enrolled.
 */
define("ThreeDSecureCardHolderEnrolled_Yes", "Yes");

/**
 * A list of the values for "quicker" parsing.
 * @private
 * @static
 */
$GLOBALS["ThreeDSecureCardHolderEnrolledValues"] = array(
	ThreeDSecureCardHolderEnrolled_Empty,
	ThreeDSecureCardHolderEnrolled_No,
	ThreeDSecureCardHolderEnrolled_None,
	ThreeDSecureCardHolderEnrolled_Unknown,
	ThreeDSecureCardHolderEnrolled_Yes,
);

/**
 * The result returned from a 3-D Secure enrollment checking.
 * 
 * @author CreditCall Communications
 * @see Request
 */
class ThreeDSecureCardHolderEnrolled {

	/**
	 * Converts a 3-D Secure card holder enrollment into an enumerated type.
	 * @private
	 * @static
	 * @param code
	 *	The 3-D Secure card holder enrollment to convert into an enumerated type.
	 * @return The resultant enumerated type.
	 */
	function parse($code) {

		foreach ($GLOBALS["ThreeDSecureCardHolderEnrolledValues"] as $value) {
			if (strcasecmp($code, $value) === 0) {
				return $value;
			}
		}

		trigger_error("InvalidArgument: Unknown code: " . $code, E_USER_ERROR);
	}
}
?>
