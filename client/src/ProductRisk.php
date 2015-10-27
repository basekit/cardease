<?php

/**
 * The product has very low risk.
 */
define("ProductRisk_VeryLow", "verylow");

/**
 * The product has low risk.
 */
define("ProductRisk_Low", "low");

/**
 * The product has medium risk.
 */
define("ProductRisk_Medium", "medium");

/**
 * The product has high risk.
 */
define("ProductRisk_High", "high");

/**
 * The product has very high risk.
 */
define("ProductRisk_VeryHigh", "veryhigh");

/**
 * A list of the values for "quicker" parsing.
 * @private
 * @static
 */
$GLOBALS["ProductRiskValues"] = array(
	ProductRisk_VeryLow,
	ProductRisk_Low,
	ProductRisk_Medium,
	ProductRisk_High,
	ProductRisk_VeryHigh,
);

/**
 * A class to represent the risk that a particular product holds.
 * <p>
 * For example, a high value product may have a higher risk, and a
 * low value product a lower risk.
 * 
 * @author CreditCall Communications
 */
class ProductRisk {

	/**
	 * Converts a product risk into an enumerated type.
	 * @private
	 * @static
	 * @param code
	 *	The product risk to convert into an enumerated type.
	 * @return The resultant enumerated type.
	 */
	function parse($code) {

		foreach ($GLOBALS["ProductRiskValues"] as $value) {
			if (strcasecmp($code, $value) === 0) {
				return $value;
			}
		}

		trigger_error("InvalidArgument: Unknown code: " . $code, E_USER_ERROR);
	}
}
?>
