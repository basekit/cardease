<?php

/**
 * A class to hold extended property information.
 * <p>
 * Each extended property has a name and a value.
 * @author CreditCall Communications
 */
class ExtendedProperty {

	/**
	 * The name of the extended property.
	 * @private
	 */
	var $m_name = null;

	/**
	 * The value of the extended property.
	 * @private
	 */
	var $m_value = null;

	/**
	 * Creates a new extended property with the specified name and value.
	 * @param name The name of the extended property
	 * @param value The value of the extended property
	 */
	function __construct($name, $value) {
		$this->m_name = $name;
		$this->m_value = $value;
	}

	/**
	 * Returns the name of the extended property.
	 * @return The name of the extended property.
	 * @see setName()
	 */
	function getName() {
		return $this->m_name;
	}

	/**
	 * Returns the value of the extended property.
	 * @return The value of the extended property.
	 * @see setValue()
	 */
	function getValue() {
		return $this->m_value;
	}

	/**
	 * Sets the name of the extended property.
	 * @param name The name of the extended property.
	 * @see getName()
	 */
	function setName($name) {
		$this->m_name = $name;
	}

	/**
	 * Sets the value of the extended property.
	 * @param value The value of the extended property.
	 * @see getValue()
	 */
	function setValue($value) {
		$this->m_value = $value;
	}

	/**
	 * Returns a string detailing the name and value of this extended property.
	 * 
	 * @return A string detailing the name and value of this extended property.
	 */
	function toString() {
		$str = "";
		
		$str .= $this->m_name;
		$str .= ":";
		$str .= $this->m_value;
		
		return $str;
	}
}

?>
