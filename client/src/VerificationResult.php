<?php

namespace Brucimus83;

/**
 * The verification result is unknown.
 */
define("VerificationResult_Empty", "-1");

/**
 * The specified information matches the issuer records.
 */
define("VerificationResult_Matched", "matched");

/**
 * The specified information was not checked against issuer records.
 */
define("VerificationResult_NotChecked", "notchecked");

/**
 * The specified information did not match the issuer records.
 */
define("VerificationResult_NotMatched", "notmatched");

/**
 * The specified information was not supplied for checking.
 */
define("VerificationResult_NotSupplied", "notsupplied");

/**
 * The specified information partially matched the issuer records.
 */
define("VerificationResult_PartialMatch", "partialmatch");

/**
 * A list of the values for "quicker" parsing.
 * @private
 * @static
 */
$GLOBALS["VerificationResultValues"] = array(
	VerificationResult_Empty,
	VerificationResult_Matched,
	VerificationResult_NotChecked,
	VerificationResult_NotMatched,
	VerificationResult_NotSupplied,
	VerificationResult_PartialMatch,
);

/**
 * The verification result that can be obtained from the CardEase platform when
 * it verifies certain components during a CardEaseXML request.
 * The components can include address, security code and zip code/post code.
 * @author CreditCall Communications
 */
class VerificationResult {

	/**
	 * Converts a verification result into an enumerated type.
	 * @private
	 * @static
	 * @param code
	 *	The verification result to convert into an enumerated type.
	 * @return The resultant enumerated type.
	 */
	 function parse($code) {

		foreach ($GLOBALS["VerificationResultValues"] as $value) {
			if (strcasecmp($code, $value) === 0) {
				return $value;
			}
		}

		trigger_error("InvalidArgument: Unknown code: " . $code, E_USER_ERROR);
	}
}
?>
