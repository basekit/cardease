<?php

namespace Brucimus83;

/**
 * The amount is in major units.
 */
define("AmountUnit_Major", "major");

/**
 * The amount is in minor units.
 */
define("AmountUnit_Minor", "minor");

/**
 * A list of the values for "quicker" parsing.
 * @private
 * @static
 */
$GLOBALS["AmountUnitValues"] = array(
	AmountUnit_Major,
	AmountUnit_Minor,
);

/**
 * The AmountUnit is used to describe the units with which an amount in a request
 * is supplied to the CardEase platform.
 * <p>
 * For example, 1 GBP can be specified as 1 Major or 100 Minor.
 * 
 * @author CreditCall Communications
 */
class AmountUnit {

	/**
	 * Converts a amount unit into an enumerated type.
	 * @private
	 * @static
	 * @param code
	 *	The amount unit to convert into an enumerated type.
	 * @return The resultant enumerated type.
	 */
	function parse($code) {

		foreach ($GLOBALS["AmountUnitValues"] as $value) {
			if (strcasecmp($code, $value) === 0) {
				return $value;
			}
		}

		trigger_error("IllegalArgument: Unknown code: " . $code, E_USER_ERROR);
	}
}

?>
