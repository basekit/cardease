<?php

namespace Brucimus83;

/**
 * Internal use to indicate no void reason is known.
 */
define("VoidReason_Empty", "-1");

/**
 * A communication failure has occured.
 */
define("VoidReason_CommunicationFailure", "05");

/**
 * The terminal failed to print a receipt.
 */
define("VoidReason_PrintFailure", "02");

/**
 * A reset or power failure has occurred.
 */
define("VoidReason_ResetOrPowerFailure", "04");

/**
 * A transaction has failed to complete.
 */
define("VoidReason_TransactionFailure", "01");

/**
 * The terminal failed to vend the product.
 */
define("VoidReason_VendFailure", "03");

/**
 * A list of the values for "quicker" parsing.
 * @private
 * @static
 */
$GLOBALS["VoidReasonValues"] = array(
	VoidReason_Empty,
	VoidReason_CommunicationFailure,
	VoidReason_PrintFailure,
	VoidReason_ResetOrPowerFailure,
	VoidReason_TransactionFailure,
	VoidReason_VendFailure,
);

/**
 * The reason for which a void request is being made.
 * This must be specified for a void request to be valid.
 * @author CreditCall Communications
 */
class VoidReason {

	/**
	 * Converts a void reason into an enumerated type.
	 * @private
	 * @static
	 * @param code
	 *	The void reason to convert into an enumerated type.
	 * @return The resultant enumerated type.
	 */
	function parse($code) {

		foreach ($GLOBALS["VoidReasonValues"] as $value) {
			if (strcasecmp($code, $value) === 0) {
				return $value;
			}
		}

		trigger_error("InvalidArgument: Unknown code: " . $code, E_USER_ERROR);
	}

}
?>
