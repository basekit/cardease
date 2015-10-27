<?php

require_once("CertificationAuthority.php");
require_once("Error.php");
require_once("ErrorCode.php");
require_once("ICCTag.php");
require_once("ICCTagValueType.php");
require_once("PublicKey.php");
require_once("ResultCode.php");
require_once("VerificationResult.php");


/**
 * A class holding all of the data that constitutes a Response from CardEaseXML. The necessary
 * components of the request should be retrieved (using the "getters"). The
 * response can only be obtained from the Client in response to a Request.
 * @author CreditCall Communications
 * @see Client
 * @see Request
 */
class Response {

	/**
	 * The raw response data received from the address verification.
	 * @private
	 */
	var $m_addressResponseData = null;
	
	/**
	 * The result of the address verification.
	 * @private
	 */
	var $m_addressResult = VerificationResult_Empty;

	/**
	 * The authorisation code for an approved transaction.
	 * @private
	 */
	var $m_authCode = null;

	/**
	 * The CardEase system reference for the response.
	 * @private
	 */
	var $m_cardEaseReference = null;

	/**
	 * The hash of an existing card to use for manual payment in place of the PAN, ExpiryDate etc.
	 * @private
	 */
	var $m_cardHash = null;

	/**
	 * The reference to an existing card to use for manual payment in place of the PAN, ExpiryDate etc.
	 * @private
	 */
	var $m_cardReference = null;

	/**
	 * The description of the card scheme.
	 * @private
	 */
	var $m_cardScheme = null;

	/**
	 * The raw response data received from the csc verfication.
	 * @private
	 */
	var $m_cscResponseData = null;

	/**
	 * The result of the csc verification.
	 * @private
	 */
	var $m_cscResult = VerificationResult_Empty;

	/**
	 * Whether the transaction is a duplicate.
	 * @private
	 */
	var $m_duplicate = false;

	/**
	 * List of errors encountered.
	 * @private
	 */
	var $m_errors = array();

	/**
	 * The expiry date associated with the card.
	 * @private
	 */
	var $m_expiryDate = null;

	/**
	 * The format of the expiry date associated with the card.
	 * @private
	 */
	var $m_expiryDateFormat = null;

	/**
	 * The list of certification authorities.
	 * @private
	 */
	var $m_iccCertificationAuthorities = array();

	 /**
	 * Whether to clear the existing public key list.
	 * @private
	 */
	var $m_iccPublicKeyClearExisting = false;

	 /**
	 * The content of the public key list.
	 * @private
	 */
	var $m_iccPublicKeyContent = null;

	 /**
	 * Whether to replace the existing public key list.
	 * @private
	 */
	var $m_iccPublicKeyReplaceExisting = false;

	 /**
	 * The type of public key list.
	 * @private
	 */
	var $m_iccPublicKeyType = null;

	/**
	 * The list of ICC tags associated with the transaction.
	 * @private
	 */
	var $m_iccTags = array();

	/**
	 * The type of ICC transaction.
	 * @private
	 */
	var $m_iccType = null;

	/**
	 * The issue number associated with the card.
	 * @private
	 */
	var $m_issueNumber = null;

	/**
	 * The date and time at the terminals location.
	 * @private
	 */
	var $m_localDateTime = null;

	/**
	 * The format of the date and time at the terminals location.
	 * @private
	 */
	var $m_localDateTimeFormat = null;

	/**
	 * The city of the originating IP address.
	 * @private
	 */
	var $m_originatingIPAddressCity = null;

	/**
	 * The continent of the originating IP address.
	 * @private
	 */
	var $m_originatingIPAddressContinent = null;
	
	/**
	 * The ISO 3166 alpha-2 continent of the originating IP address.
	 * @private
	 */
	var $m_originatingIPAddressContinentAlpha2 = null;
	
	/**
	 * The country of the originating IP address.
	 * @private
	 */
	var $m_originatingIPAddressCountry = null;

	/**
	 * The ISO 3166 alpha-2 country of the originating IP address.
	 * @private
	 */
	var $m_originatingIPAddressCountryAlpha2 = null;

	/**
	 * The ISO 3166ode country of the originating IP address.
	 * @private
	 */
	var $m_originatingIPAddressCountryCode = null;

	/**
	 * Whether the originating IP address is black listed.
	 * @private
	 */
	var $m_originatingIPAddressIsBlackListed = false;

	/**
	 * Whether the originating IP address is a known proxy.
	 * @private
	 */
	var $m_originatingIPAddressIsKnownProxy = false;

	/**
	 * The region of the originating IP address.
	 * @private
	 */
	var $m_originatingIPAddressRegion = null;

	/**
	 * The region code of the originating IP address.
	 * @private
	 */
	var $m_originatingIPAddressRegionCode = null;

	/**
	 * The zip code of the originating IP address.
	 * @private
	 */
	var $m_originatingIPAddressZipCode = null;

	/**
	 * The PAN (Primary Account Number) associated with the card.
	 * @private
	 */
	var $m_pan = null;

	/**
	 * The result code from CardEase.
	 * @private
	 */
	var $m_resultCode = ResultCode_Empty;

	/**
	 * The name of the server software.
	 * @private
	 */
	var $m_serverName = null;
	
	/**
	 * The version of the server software.
	 * @private
	 */
	var $m_serverVersion = null;

	/**
	 * The start date associated with the card.
	 * @private
	 */
	var $m_startDate = null;

	/**
	 * The format of the start date associated with the card.
	 * @private
	 */
	var $m_startDateFormat = null;

	/**
	 * An optional user reference.
	 * @private
	 */
	var $m_userReference = null;

	/**
	 * The raw response data received from the zip code verfication.
	 * @private
	 */
	var $m_zipCodeResponseData = null;

	/**
	 * The result of the zip code verification.
	 * @private
	 */
	var $m_zipCodeResult = VerificationResult_Empty;

	/**
	 * A stack of the tags that have been parsed.
	 * @private
	 */
	var $m_tagStack;

	/**
	 * The last error code encountered.
	 * @private
	 */
	var $m_lastErrorCode;

	/**
	 * Constructs a new response and initialises all variables.
	 */
	function Response()
	{
		$this->m_addressResponseData = null;
		$this->m_addressResult = VerificationResult_Empty;
		$this->m_authCode = null;
		$this->m_cardEaseReference = null;
		$this->m_cardHash = null;
		$this->m_cardReference = null;
		$this->m_cardScheme = null;
		$this->m_cscResponseData = null;
		$this->m_cscResult = VerificationResult_Empty;
		$this->m_duplicate = false;
		$this->m_errors = array();
		$this->m_expiryDate = null;
		$this->m_expiryDateFormat = null;
		$this->m_iccCertificationAuthorities = array();
		$this->m_iccPublicKeyClearExisting = false;
		$this->m_iccPublicKeyContent = null;
		$this->m_iccPublicKeyReplaceExisting = false;
		$this->m_iccPublicKeyType = null;
		$this->m_iccTags = array();
		$this->m_iccType = null;
		$this->m_issueNumber = null;
		$this->m_localDateTime = null;
		$this->m_localDateTimeFormat = null;		
		$this->m_originatingIPAddressCity = null;
		$this->m_originatingIPAddressContinent = null;
		$this->m_originatingIPAddressContinentAlpha2 = null;
		$this->m_originatingIPAddressCountry = null;
		$this->m_originatingIPAddressCountryAlpha2 = null;
		$this->m_originatingIPAddressCountryCode = null;
		$this->m_originatingIPAddressIsBlackListed = false;
		$this->m_originatingIPAddressIsKnownProxy = false;
		$this->m_originatingIPAddressRegion = null;
		$this->m_originatingIPAddressRegionCode = null;
		$this->m_originatingIPAddressZipCode = null;		
		$this->m_pan = null;
		$this->m_resultCode = ResultCode_Empty;
		$this->m_serverName = null;		
		$this->m_serverVersion = null;
		$this->m_startDate = null;
		$this->m_startDateFormat = null;
		$this->m_userReference = null;
		$this->m_zipCodeResponseData = null;
		$this->m_zipCodeResult = VerificationResult_Empty;
	}

	/**
	 * Handles character data.
	 * @private
	 */
	function characterDataHandler($parser, $data)
	{
		$data = trim($data);

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "ADDITIONALVERIFICATION", "ADDRESS")) {
			$this->m_addressResult = VerificationResult::parse($data);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "ADDITIONALVERIFICATION", "CSC")) {
			$this->m_cscResult = VerificationResult::parse($data);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "ADDITIONALVERIFICATION", "ZIP")) {
			$this->m_zipCodeResult = VerificationResult::parse($data);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "CARDSCHEME", "DESCRIPTION")) {
			$this->m_cardScheme = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "EXPIRYDATE")) {
			$this->m_expiryDate = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "ISSUENUMBER")) {
			$this->m_issueNumber = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "ICC", "ICCTAG")) {
			$this->m_iccTags[count($this->m_iccTags)-1]->setValue($data);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "PAN")) {
			$this->m_pan = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "CARDHASH")) {
			$this->m_cardHash = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "CARDREFERENCE")) {
			$this->m_cardReference = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "STARTDATE")) {
			$this->m_startDate = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "ICCPUBLICKEYS", "CERTIFICATIONAUTHORITY", "PUBLICKEY", "ALGORITHM")) {
			$publicKeys = $this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->getPublicKeys();
			$publicKeys[count($publicKeys)-1]->setAlgorithm($data);
			$this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->setPublicKeys($publicKeys);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "ICCPUBLICKEYS", "CERTIFICATIONAUTHORITY", "PUBLICKEY", "EXPONENT")) {
			$publicKeys = $this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->getPublicKeys();
			$publicKeys[count($publicKeys)-1]->setExponent($data);
			$this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->setPublicKeys($publicKeys);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "ICCPUBLICKEYS", "CERTIFICATIONAUTHORITY", "PUBLICKEY", "MODULUS")) {
			$publicKeys = $this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->getPublicKeys();
			$publicKeys[count($publicKeys)-1]->setModulus($data);
			$this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->setPublicKeys($publicKeys);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "ICCPUBLICKEYS", "CERTIFICATIONAUTHORITY", "PUBLICKEY", "VALIDFROM")) {
			$publicKeys = $this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->getPublicKeys();
			$publicKeys[count($publicKeys)-1]->setValidFromDate($data);
			$this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->setPublicKeys($publicKeys);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "ICCPUBLICKEYS", "CERTIFICATIONAUTHORITY", "PUBLICKEY", "VALIDTO")) {
			$publicKeys = $this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->getPublicKeys();
			$publicKeys[count($publicKeys)-1]->setValidToDate($data);
			$this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->setPublicKeys($publicKeys);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "RESULT", "AUTHCODE")) {
			$this->m_authCode = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "RESULT", "ERRORS", "ERROR")) {
			$this->m_errors[] = new Error($this->m_lastErrorCode, $data);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "RESULT", "LOCALRESULT")) {
			$this->m_resultCode = ResultCode::parse($data);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "TRANSACTIONDETAILS", "CARDEASEREFERENCE")) {
			$this->m_cardEaseReference = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "TRANSACTIONDETAILS", "GEOIP", "CITY")) {
			$this->m_originatingIPAddressCity = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "TRANSACTIONDETAILS", "GEOIP", "CONTINENT")) {
			$this->m_originatingIPAddressContinent = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "TRANSACTIONDETAILS", "GEOIP", "COUNTRY")) {
			$this->m_originatingIPAddressCountry = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "TRANSACTIONDETAILS", "GEOIP", "REGION")) {
			$this->m_originatingIPAddressRegion = $data;
			return;
		}
		
		if ($this->m_tagStack === array("RESPONSE", "TRANSACTIONDETAILS", "GEOIP", "ZIPCODE")) {
			$this->m_originatingIPAddressZipCode = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "TRANSACTIONDETAILS", "LOCALDATETIME")) {
			$this->m_localDateTime = $data;
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "TRANSACTIONDETAILS", "REFERENCE")) {
			$this->m_userReference = $data;
			return;
		}

/*
		echo "<pre>";
		echo print_r($this->m_tagStack, true);
		echo print_r($data, true);
		echo "</pre>";
*/

//		trigger_error("CardEaseXMLResponse: Unexpected response data: ".print_r($data, true), E_USER_WARNING);
//		trigger_error("CardEaseXMLResponse: Unexpected response data: ".print_r($this->m_tagStack, true), E_USER_ERROR);
	}

	/**
	 * Handles an end tag.
	 * @private
	 */
	function elementEndHandler($parser, $name)
	{
		$name = trim($name);

		if (array_pop($this->m_tagStack) !== $name) {
			trigger_error("CardEaseXMLResponse: Unexpected response end tag: $name", E_USER_ERROR);
		}
	}

	/**
	 * Handles a start tag.
	 * @private
	 */
	function elementStartHandler($parser, $name, $attrs)
	{
		$name = trim($name);

		array_push($this->m_tagStack, $name);

		if (empty($attrs)) {
			return;
		}

		if ($this->m_tagStack === array("RESPONSE")) {
			$this->m_serverName = $attrs["TYPE"];
			$this->m_serverVersion = $attrs["VERSION"];
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "ADDITIONALVERIFICATION", "ADDRESS")) {
			$this->m_addressResponseData = $attrs["RAW"];
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "ADDITIONALVERIFICATION", "CSC")) {
			$this->m_cscResponseData = $attrs["RAW"];
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "ADDITIONALVERIFICATION", "ZIP")) {
			$this->m_zipCodeResponseData = $attrs["RAW"];
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "RESULT")) {
			if (!empty($attrs["DUPLICATE"])) {
				$this->m_duplicate = (bool)$attrs["DUPLICATE"];
			}
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "ICC")) {
			$this->m_iccType = $attrs["TYPE"];
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "ICC", "ICCTAG")) {
			$this->m_iccTags[] = new ICCTag($attrs["TAGID"], null);

			if (!empty($attrs["TYPE"])) {
				$this->m_iccTags[count($this->m_iccTags)-1]->setType(ICCTagValueType::parse($attrs["TYPE"]));
			}

			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "EXPIRYDATE")) {
			$this->m_expiryDateFormat = $attrs["FORMAT"];
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "CARDDETAILS", "STARTDATE")) {
			$this->m_startDateFormat = $attrs["FORMAT"];
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "ICCPUBLICKEYS")) {
			$this->m_iccPublicKeyType = $attrs["TYPE"];
			$this->m_iccPublicKeyContent = $attrs["CONTENT"];
			$this->m_iccPublicKeyClearExisting = (bool)$attrs["CLEAREXISTING"];
			$this->m_iccPublicKeyReplaceExisting = (bool)$attrs["REPLACEEXISTING"];
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "ICCPUBLICKEYS", "CERTIFICATIONAUTHORITY")) {
			$this->m_iccCertificationAuthorities[] = new CertificationAuthority($attrs["DESCRIPTION"], $attrs["RID"]);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "ICCPUBLICKEYS", "CERTIFICATIONAUTHORITY", "PUBLICKEY")) {
			$this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->addPublicKey(
				new PublicKey($attrs["INDEX"], $attrs["HASH"], $attrs["HASHALGORITHM"]));
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "ICCPUBLICKEYS", "CERTIFICATIONAUTHORITY", "PUBLICKEY", "VALIDFROM")) {
			$publicKeys = $this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->getPublicKeys();
			$publicKeys[count($publicKeys)-1]->setValidFromDateFormat($attrs["FORMAT"]);
			$this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->setPublicKeys($publicKeys);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "ICCPUBLICKEYS", "CERTIFICATIONAUTHORITY", "PUBLICKEY", "VALIDTO")) {
			$publicKeys = $this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->getPublicKeys();
			$publicKeys[count($publicKeys)-1]->setValidToDateFormat($attrs["FORMAT"]);
			$this->m_iccCertificationAuthorities[count($this->m_iccCertificationAuthorities)-1]->setPublicKeys($publicKeys);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "RESULT", "ERRORS", "ERROR")) {
			$this->m_lastErrorCode = ErrorCode::parse($attrs["CODE"]);
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "TRANSACTIONDETAILS", "GEOIP")) {
			if (!empty($attrs["IsBlackListed"])) {		
				$this->m_originatingIPAddressIsBlackListed = (bool)$attrs["IsBlackListed"];
			}
			if (!empty($attrs["IsKnownProxy"])) {
				$this->m_originatingIPAddressIsKnownProxy = (bool)$attrs["IsKnownProxy"];
			}		
			return;
		}
		
		if ($this->m_tagStack === array("RESPONSE", "TRANSACTIONDETAILS", "GEOIP", "CONTINENT")) {
			$this->m_originatingIPAddressContinentAlpha2 = $attrs["ALPHA2"];
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "TRANSACTIONDETAILS", "GEOIP", "COUNTRY")) {
			$this->m_originatingIPAddressCountryAlpha2 = $attrs["ALPHA2"];
			$this->m_originatingIPAddressCountryCode = $attrs["CODE"];
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "TRANSACTIONDETAILS", "GEOIP", "REGION")) {
			$this->m_originatingIPAddressRegionCode = $attrs["CODE"];
			return;
		}

		if ($this->m_tagStack === array("RESPONSE", "TRANSACTIONDETAILS", "LOCALDATETIME")) {
			$this->m_localDateTimeFormat = $attrs["FORMAT"];
			return;
		}

/*
		echo "<pre>";
		echo print_r($this->m_tagStack, true);
		echo print_r($attrs, true);
		echo "</pre>";
*/

//		trigger_error("CardEaseXMLResponse: Unexpected response attrs: ".print_r($attrs, true), E_USER_WARNING);
//		trigger_error("CardEaseXMLResponse: Unexpected response attrs: ".print_r($this->m_tagStack, true), E_USER_ERROR);
	}


	/**
	 * Gets the raw response data received from the address verification with
	 * the issuer. The content of this is dependant upon the acquirer, country,
	 * protocol etc. This is an alphanumeric string.
	 * This will be available if required in the original request.
	 * 
	 * @return The raw response data received from the address verfication. This
	 *	 will be null if no raw address data was found in this response.
	 */
	function getAddressResponseData() {
		return $this->m_addressResponseData;
	}

	/**
	 * Gets the result of the address verification with the issuer. This will be
	 * available if required in the original request.
	 * 
	 * @return The result of the address verification: <table>
	 *	 <tr>
	 *	 <th>NotChecked</th>
	 *	 <td>The address was not checked against the issuer records</td>
	 *	 </tr>
	 *	 <tr>
	 *	 <th>NotMatched</th>
	 *	 <td>The address did not match issuer records</td>
	 *	 </tr>
	 *	 <tr>
	 *	 <th>Matched</th>
	 *	 <td>The address matched issuer records</td>
	 *	 </tr>
	 *	 <tr>
	 *	 <th>PartialMatch</th>
	 *	 <td>The address partially matched issuer records</td>
	 *	 </tr>
	 *	 </table> This will be null if no result data was found in the
	 *	 response.
	 */
	function getAddressResult() {
		return $this->m_addressResult;
	}

	/**
	 * Gets the authorisation code found in this response. This will only be
	 * present if the transaction was approved. This is an alphanumeric string
	 * with a maximum length of 12 characters.
	 * 
	 * @return The authorisation code found in this response. This will be null
	 *	 if no authorisation code was found in this response.
	 */
	function getAuthCode() {
		return $this->m_authCode;
	}

	/**
	 * Gets the CardEaseXML reference found in this response. This is a unique
	 * reference that can be used with CardEaseXML during follow-up requests
	 * related to the original such as confirmations, refunds and voids. This is
	 * an alphanumeric string with a fixed length of 36 characters.
	 * 
	 * @return The CardEaseXML reference found in this response. This will be
	 *	 null if no CardEase reference was found in this response.
	 */
	function getCardEaseReference() {
		return $this->m_cardEaseReference;
	}

	/**
	 * Gets the card hash found in the response that can be used to reference a
	 * card in a follow-up transaction.
	 * <p>
	 * This is an alphanumeric string with a fixed length of 24 characters. Used
	 * in conjunction with the CardReference property. The benefit of being able
	 * to reference a previously used card is that an integrator need not store
	 * actual card details on their system for repeat transactions. This reduces
	 * the risk of card information being compromised, and reduces the
	 * integrators PCI requirements.
	 * 
	 * @return The card hash found in the response that can be used to reference
	 *	 a card in a follow-up transaction.
	 * @see getCardReference
	 */
	function getCardHash() {
		return $this->m_cardHash;
	}

	/**
	 * Gets the card reference found in the response that can be used to
	 * reference a card in a follow-up transaction.
	 * <p>
	 * This is an alphanumeric string with a fixed length of 36 characters. Used
	 * in conjunction with the CardHash property. The benefit of being able to
	 * reference a previously used card is that an integrator need not store
	 * actual card details on their system for repeat transactions. This reduces
	 * the risk of card information being compromised, and reduces the
	 * integrators PCI requirements.
	 * 
	 * @return The card reference found in the response that can be used to
	 *	 reference a card in a follow-up transaction.
	 * @see getCardHash
	 */
	function getCardReference() {
		return $this->m_cardReference;
	}

	/**
	 * Gets the description of the card scheme used in the request. This can be
	 * used on a receipt. This is an alphanumeric string with a maximum length
	 * of 50 characters.
	 * 
	 * @return The description of the card scheme used in the request. This will
	 *	 be null if no card scheme was found in this response.
	 */
	function getCardScheme() {
		return $this->m_cardScheme;
	}

	/**
	 * Gets the raw response data received from the security code verification
	 * with the issuer. This is also referred to as CVV, CVC and CV2. The
	 * content of this is dependant upon the acquirer, country, protocol etc.
	 * This is an alphanumeric string. This will be available if required in the original request.
	 * If the CSC validation fails the authorisation is automatically declined.
	 * 
	 * @return The raw response data received from the security code
	 *	 verfication. This will be null if no raw security code data was
	 *	 found in this response.
	 */
	function getCSCResponseData() {
		return $this->m_cscResponseData;
	}

	/**
	 * Gets the result of the security code verification with the issuer. This
	 * will be available if required in the original request.
	 * If the CSC validation fails the authorisation is automatically declined.
	 * 
	 * @return The result of the security code verification: <table>
	 *	 <tr>
	 *	 <th>NotChecked</th>
	 *	 <td>The security code was not checked against the issuer records</td>
	 *	 </tr>
	 *	 <tr>
	 *	 <th>NotMatched</th>
	 *	 <td>The security code did not match the issuer records</td>
	 *	 </tr>
	 *	 <tr>
	 *	 <th>Matched</th>
	 *	 <td>The security code matched the issuer records</td>
	 *	 </tr>
	 *	 </table> This will be null if no result data was found in the
	 *	 response.
	 */
	function getCSCResult() {
		return $this->m_cscResult;
	}

	/**
	 * Gets whether the transaction was recognised as a duplicate.
	 * @return Whether the transaction was recognised as a duplicate.
	 */
	function getDuplicate() {
		return $this->m_duplicate;
	}

	/**
	 * Gets a list of the errors that we encountered when trying to process the
	 * request. Each error contains an error code and an error message.
	 * 
	 * @return A list of the errors that we encountered when trying to process
	 *	 the request. This will be null if no errors were found in the
	 *	 response.
	 * @see Error
	 */
	function getErrors() {
		return $this->m_errors;
	}

	/**
	 * Gets the expiry date associated with the card in this response. This will
	 * match the expiry date format. This is a character string with a maximum length of 10 characters.
	 * 
	 * @return The expiry date associated with the card in this response. This
	 *	 will be null if no expiry date was found in this response.
	 * @see getExpiryDateFormat()
	 */
	function getExpiryDate() {
		return $this->m_expiryDate;
	}

	/**
	 * Gets the expiry date format associated with the card in the response.
	 * This will match the format of the expiry date and can include separators such as - and /. 
	 * This is a character string with a maximum length of 10 characters.
	 * The available options are
	 * shown in the following table:
	 * <table border=1>
	 * <tr>
	 * <th>Format</th>
	 * <th>Description</th>
	 * <th>Example</th>
	 * </tr>
	 * <tr>
	 * <td>yyyy</td>
	 * <td>Year with century</td>
	 * <td>2004</td>
	 * </tr>
	 * <tr>
	 * <td>yy</td>
	 * <td>Year without century</td>
	 * <td>04</td>
	 * </tr>
	 * <tr>
	 * <td>MM</td>
	 * <td>Month of year</td>
	 * <td>01</td>
	 * </tr>
	 * <tr>
	 * <td>dd</td>
	 * <td>Day of month</td>
	 * <td>27</td>
	 * </tr>
	 * </table>
	 * 
	 * @return The expiry date format associated with the card in this response.
	 *	 This will be null if no expiry date format was found in the
	 *	 response.
	 * @see getExpiryDate()
	 */
	function getExpiryDateFormat() {
		return $this->m_expiryDateFormat;
	}

	/**
	 * Gets the list of ICC certification authorities found in the response.
	 * 
	 * @return The list of certification authorities found in the response. This
	 *	 will be null if no certification authorities were found in the
	 *	 response.
	 */
	function getICCCertificationAuthorities() {
		return $this->m_iccCertificationAuthorities;
	}
	
	/**
	 * Gets the indicator in the response that says whether to clear the
	 * existing public key list.
	 * 
	 * @return The indicator in the response that says whether to clear the
	 *	 existing public key list. This will be null if no indicator was
	 *	 found.
	 * @see getICCPublickeyReplaceExisting()
	 */
	function getICCPublicKeyClearExisting() {
		return $this->m_iccPublicKeyClearExisting;
	}

	/**
	 * Gets a description of the public key list content. For example
	 * "complete". It is an alphanumeric string.
	 * 
	 * @return A description of the public key list content. This will be null
	 *	 if no description was found.
	 * @see getICCPublicKeyType()
	 */
	function getICCPublicKeyContent() {
		return $this->m_iccPublicKeyContent;
	}

	/**
	 * Gets the indicator in the response that says whether to replace the
	 * existing public key list.
	 * 
	 * @return The indicator in the response that says whether to replace the
	 *	 existing public key list. This will be null if no indicator was
	 *	 found.
	 * @see getICCPublicKeyClearExisting()
	 */
	function getICCPublickeyReplaceExisting() {
		return $this->m_iccPublicKeyReplaceExisting;
	}

	/**
	 * Gets the type of the public key list. For example "EMV". It is an 
	 * alphanumeric string.
	 *
	 * @return The type of the public key list. This will be null if no type was
	 *	 found.
	 * @see getICCPublicKeyContent()
	 */
	function getICCPublicKeyType() {
		return $this->m_iccPublicKeyType;
	}

	/**
	 * Gets the list of ICC tags found in this response. Each ICC tag has an id,
	 * type and value. For example, a tag of 0x9f02/AsciiHex/000000000100 is
	 * using to specify the transaction amount. These are mandatory for an EMV
	 * transaction.
	 * 
	 * @return The list of ICC tags found in this response. This will be null if
	 *	 no ICC tags were found in this response.
	 * @see ICCTag
	 */
	function getICCTags() {
		return $this->m_iccTags;
	}

	/**
	 * Gets the type of ICC transaction associated with this response. This is
	 * an alphanumeric string. This is
	 * mandatory for ICC authorisations and by default is "EMV". An EMV
	 * transaction must have associated ICC tags.
	 * 
	 * @return The type of ICC transaction associated with the request. This
	 *	 will be null if no ICC type was found in this response.
	 * @see ICCTag
	 * @see getICCTags()
	 */
	function getICCType() {
		return $this->m_iccType;
	}

	/**
	 * Gets the issue number associated with the card in this response. This is
	 * dependant upon the card scheme associated with the card and will be
	 * exactly as found on the card (including any leading 0's). This is a
	 * numeric string with a maximum length of 2 characters.
	 * 
	 * @return The issue number associated with the card in this response. This
	 *	 will be null if no issue number was found in this response.
	 */
	function getIssueNumber() {
		return $this->m_issueNumber;
	}

	/**
	 * Gets the date and time at the terminal's location. This can be used on a
	 * receipt and will match the local date and time format.
	 * This is a character string.
	 * 
	 * @return The date and time at the terminal's location. This will be null
	 *	 if no local date and time was found in this response.
	 * @see getLocalDateTimeFormat()
	 */
	function getLocalDateTime() {
		return $this->m_localDateTime;
	}

	/**
	 * Gets the local date and time format at the terminal's location. This will
	 * match the format of the local date and time and can include separators such as :, - and /.
	 * This is a character string. The available options are
	 * shown in the following table:
	 * <table border=1>
	 * <tr>
	 * <th>Format</th>
	 * <th>Description</th>
	 * <th>Example</th>
	 * </tr>
	 * <tr>
	 * <td>yyyy</td>
	 * <td>Year with century</td>
	 * <td>2004</td>
	 * </tr>
	 * <tr>
	 * <td>yy</td>
	 * <td>Year without century</td>
	 * <td>04</td>
	 * </tr>
	 * <tr>
	 * <td>MM</td>
	 * <td>Month of year</td>
	 * <td>01</td>
	 * </tr>
	 * <tr>
	 * <td>dd</td>
	 * <td>Day of month</td>
	 * <td>27</td>
	 * </tr>
	 * <tr>
	 * <td>HH</td>
	 * <td>Hour of day (24 hour)</td>
	 * <td>17</td>
	 * </tr>
	 * <tr>
	 * <td>mm</td>
	 * <td>Minute of hour</td>
	 * <td>53</td>
	 * </tr>
	 * <tr>
	 * <td>ss</td>
	 * <td>Second of minute</td>
	 * <td>43</td>
	 * </tr>
	 * </table>
	 * 
	 * @return The local date and time format at the terminal's location. This
	 *	 will be null if no local date and time format was found in the
	 *	 response.
	 * @see getLocalDateTime()
	 */
	function getLocalDateTimeFormat() {
		return $this->m_localDateTimeFormat;
	}

	/**
	 * Gets the city of the originating IP address found in this response.
	 * @return The city of the originating IP address found in this response.
	 * This will be null if no city was found in this response.
	 */
	 function getOriginatingIPAddressCity() {
	 	return $this->m_originatingIPAddressCity;
	 }
	 
	/**
	 * Gets the continent of the originating IP address found in this response.
	 * @return The continent of the originating IP address found in this response.
	 * This will be null if no continent was found in this response.
	 */
	 function getOriginatingIPAddressContinent() {
	 	return $this->m_originatingIPAddressContinent;
	 }
	 
	/**
	 * Gets the ISO 3166 continent alpha-2 of the originating IP address found in this response.
	 * @return The ISO 3166 continent alpha-2 of the originating IP address found in this response.
	 * This will be null if no ISO 3166 continent alpha-2 was found in this response.
	 */
	 function getOriginatingIPAddressContinentAlpha2() {
	 	return $this->m_originatingIPAddressContinentAlpha2;
	 }
	 
	/**
	 * Gets the country of the originating IP address found in this response.
	 * @return The country of the originating IP address found in this response.
	 * This will be null if no country was found in this response.
	 */
	 function getOriginatingIPAddressCountry() {
	 	return $this->m_originatingIPAddressCountry;
	 }

	/**
	 * Gets the ISO 3166 country alpha-2 of the originating IP address found in this response.
	 * @return The ISO 3166 country alpha-2 of the originating IP address found in this response.
	 * This will be null if no ISO 3166 country alpha-2 was found in this response.
	 */
	 function getOriginatingIPAddressCountryAlpha2() {
	 	return $this->m_originatingIPAddressCountryAlpha2;
	 }

	/**
	 * Gets the ISO 3166 country code of the originating IP address found in this response.
	 * @return The ISO 3166 country code of the originating IP address found in this response.
	 * This will be null if no ISO 3166 country code was found in this response.
	 */
	 function getOriginatingIPAddressCountryCode() {
	 	return $this->m_originatingIPAddressCountryCode;
	 }

	/**
	 * Gets whether the originating IP address is black listed.
	 * @return Whether the originating IP address is black listed.
	 * This will be false if this was not found in the response.
	 */
	function getOriginatingIPAddressIsBlackListed() {
		return $this->m_originatingIPAddressIsBlackListed;
	}

	/**
	 * Gets whether the originating IP address is a known proxy.
	 * @return Whether the originating IP address is a known proxy.
	 * This will be false if this was not found in the response.
	 */
	function getOriginatingIPAddressIsKnownProxy() {
		return $this->m_originatingIPAddressIsKnownProxy;
	}

	/**
	 * Gets the region of the originating IP address found in this response.
	 * @return The region of the originating IP address found in this response.
	 * This will be null if no region was found in this response.
	 */
	function getOriginatingIPAddressRegion() {
		return $this->m_originatingIPAddressRegion;
	}
	 
	/**
	 * Gets the region code of the originating IP address found in this response.
	 * @return The region code of the originating IP address found in this response.
	 * This will be null if no region code was found in this response.
	 */
	function getOriginatingIPAddressRegionCode() {
		return $this->m_originatingIPAddressRegionCode;
	}

	/**
	 * Gets the zip code of the originating IP address found in this response.
	 * @return The zip code of the originating IP address found in this response.
	 * This will be null if no zip code was found in this response.
	 */
	function getOriginatingIPAddressZipCode() {
		return $this->m_originatingIPAddressZipCode;
	}

	/**
	 * Gets the masked PAN (Primary Account Number) found in this response. The
	 * PAN is masked with x's for security. This is an alphanumeric string with a
	 * minimum length of 13 characters and a maximum length of 19 characters.
	 * 
	 * @return The PAN (Primary Account Number) found in this response. This
	 *	 will be null if no PAN was found in this response.
	 */
	function getPAN() {
		return $this->m_pan;
	}

	/**
	 * Gets the result code that has been obtained from the CardEase platform
	 * when processing the source request.
	 * 
	 * @return The result code that has been obtained from the CardEase platform
	 *	 when processing the source request. This will be null if no
	 *	 result code was found in this response.
	 */
	function getResultCode() {
		return $this->m_resultCode;
	}

	/**
	 * Gets the start date associated with the card in this response. This will
	 * match the start date format. This is a character string with a
	 * maximum length of 10 characters.
	 * 
	 * @return The start date associated with the card in this response. This
	 *	 will be null if no start date was found in this response.
	 * @see getStartDateFormat()
	 */
	function getStartDate() {
		return $this->m_startDate;
	}

	/**
	 * Gets the start date format associated with the card in this response.
	 * This will match the format of the start date and can include separators such as - and /.
	 * This is a character string with a maximum length of 10 characters.
	 * The available options are shown in the following table:
	 * <table border=1>
	 * <tr>
	 * <th>Format</th>
	 * <th>Description</th>
	 * <th>Example</th>
	 * </tr>
	 * <tr>
	 * <td>yyyy</td>
	 * <td>Year with century</td>
	 * <td>2004</td>
	 * </tr>
	 * <tr>
	 * <td>yy</td>
	 * <td>Year without century</td>
	 * <td>04</td>
	 * </tr>
	 * <tr>
	 * <td>MM</td>
	 * <td>Month of year</td>
	 * <td>01</td>
	 * </tr>
	 * <tr>
	 * <td>dd</td>
	 * <td>Day of month</td>
	 * <td>27</td>
	 * </tr>
	 * </table>
	 * 
	 * @return The start date format associated with the card in this response.
	 *	 This will be null if no start date format was found in the
	 *	 response.
	 * @see getStartDate()
	 */
	function getStartDateFormat() {
		return $this->m_startDateFormat;
	}

	/**
	 * Gets the user reference found in this response. This will be the same as
	 * that in the original request. This allows a user to attached their own
	 * reference against a request and verify it against this response.
	 * This is an alphanumeric string with a maximum length of 50 characters. Use of
	 * the user reference is optional for all requests.
	 * 
	 * @return The user reference found in this response. This will be null if
	 *	 no user reference was found in this response.
	 */
	function getUserReference() {
		return $this->m_userReference;
	}

	/**
	 * Gets the raw response data received from the zip code/post code
	 * verification with the issuer. The content of this is dependant upon the
	 * acquirer, country, protocol etc. This is an alphanumeric string.
	 * This will be available if required in
	 * the original request.
	 * 
	 * @return The raw response data received from the zip code/post code
	 *	 verfication. This will be null if no raw zip code/post code data
	 *	 was found in this response.
	 */
	function getZipCodeResponseData() {
		return $this->m_zipCodeResponseData;
	}

	/**
	 * Gets the result of the zip code/post code verification with the issuer.
	 * This will be available if required in the original request.
	 * 
	 * @return The result of the zip code/post code verification: <table>
	 *	 <tr>
	 *	 <th>NotChecked</th>
	 *	 <td>The zip code/post code was not checked against the issuer
	 *	 records</td>
	 *	 </tr>
	 *	 <tr>
	 *	 <th>NotMatched</th>
	 *	 <td>The zip code/post code did not match issuer records</td>
	 *	 </tr>
	 *	 <tr>
	 *	 <th>Matched</th>
	 *	 <td>The zip code/post code matched issuer records</td>
	 *	 </tr>
	 *	 <tr>
	 *	 <th>PartialMatch</th>
	 *	 <td>The zip code/post code partially matched issuer records</td>
	 *	 </tr>
	 *	 </table> This will be null if no result data was found in the
	 *	 response.
	 */
	function getZipCodeResult() {
		return $this->m_zipCodeResult;
	}

	/**
	 * Parses the XML response.
	 * @private
	 * 
	 * @param xml
	 *	 The XML to parse.
	 * @throws E_USER_ERROR
	 *	 If the XML encoding is missing or the XML is badly formed.
	 */
	function parseResponseXML($xml)
	{
		if (empty($xml)) {
			trigger_error("CardEaseXMLResponse: Unable to parse XML", E_USER_ERROR);
		}

		$this->m_tagStack = array();

		// Note: This could be done with xml_parse_into_struct
		// Then we would have to deal with levels in a different way
		$xml_parser = xml_parser_create();
		xml_set_object($xml_parser, $this);
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
		xml_set_character_data_handler($xml_parser, "characterDataHandler");
		xml_set_element_handler($xml_parser, "elementStartHandler", "elementEndHandler");
		xml_parse($xml_parser, $xml);
		xml_parser_free($xml_parser);
	}

	/**
	 * Returns a detailed string showing this response.
	 * 
	 * @return A detailed string showing this response.
	 */
	function toString() {

		$eol = "<br>\n";
		$sep = ": ";

		$str = "";
		$str .= "RESPONSE:";
		$str .= $eol;
		$str .= "AddressResponseData"; 
		$str .= $sep;
		$str .= $this->m_addressResponseData;
		$str .= $eol;
		$str .= "AddressResult"; 
		$str .= $sep;
		$str .= $this->m_addressResult;
		$str .= $eol;
		$str .= "AuthCode"; 
		$str .= $sep;
		$str .= $this->m_authCode;
		$str .= $eol;
		$str .= "CardEaseReference"; 
		$str .= $sep;
		$str .= $this->m_cardEaseReference;
		$str .= $eol;
		$str .= "CardHash"; 
		$str .= $sep;
		$str .= $this->m_cardHash;
		$str .= $eol;
		$str .= "CardReference"; 
		$str .= $sep;
		$str .= $this->m_cardReference;
		$str .= $eol;
		$str .= "CardScheme"; 
		$str .= $sep;
		$str .= $this->m_cardScheme;
		$str .= $eol;
		$str .= "CSCResponseData"; 
		$str .= $sep;
		$str .= $this->m_cscResponseData;
		$str .= $eol;
		$str .= "CSCResult"; 
		$str .= $sep;
		$str .= $this->m_cscResult;
		$str .= $eol;
		$str .= "Duplicate"; 
		$str .= $sep;
		$str .= $this->m_duplicate;
		$str .= $eol;
		$str .= "Errors"; 
		$str .= $sep;
		$str .= print_r($this->m_errors, true);
		$str .= $eol;
		$str .= "ExpiryDate"; 
		$str .= $sep;
		$str .= $this->m_expiryDate;
		$str .= $eol;
		$str .= "ExpiryDateFormat"; 
		$str .= $sep;
		$str .= $this->m_expiryDateFormat;
		$str .= $eol;
		$str .= "ICCCertificationAuthorities"; 
		$str .= $sep;
		$str .= print_r($this->m_iccCertificationAuthorities, true);
		$str .= $eol;	
		$str .= "ICCPublicKeyClearExisting"; 
		$str .= $sep;
		$str .= $this->m_iccPublicKeyClearExisting;
		$str .= $eol;
		$str .= "ICCPublicKeyContent"; 
		$str .= $sep;
		$str .= $this->m_iccPublicKeyContent;
		$str .= $eol;
		$str .= "ICCPublicKeyReplaceExisting"; 
		$str .= $sep;
		$str .= $this->m_iccPublicKeyReplaceExisting;
		$str .= $eol;
		$str .= "ICCPublicKeyType"; 
		$str .= $sep;
		$str .= $this->m_iccPublicKeyType;
		$str .= $eol;
		$str .= "ICCTags"; 
		$str .= $sep;
		$str .= print_r($this->m_iccTags, true);
		$str .= $eol;
		$str .= "ICCType"; 
		$str .= $sep;
		$str .= $this->m_iccType;
		$str .= $eol;
		$str .= "IssueNumber"; 
		$str .= $sep;
		$str .= $this->m_issueNumber;
		$str .= $eol;
		$str .= "LocalDateTime"; 
		$str .= $sep;
		$str .= $this->m_localDateTime;
		$str .= $eol;
		$str .= "LocalDateTimeFormat"; 
		$str .= $sep;
		$str .= $this->m_localDateTimeFormat;
		$str .= $eol;		
		$str .= "OriginatingIPAddressCity";
		$str .= $sep;
		$str .= $this->m_originatingIPAddressCity;
		$str .= $eol;
		$str .= "OriginatingIPAddressContinent";
		$str .= $sep;
		$str .= $this->m_originatingIPAddressContinent;
		$str .= $eol;
		$str .= "OriginatingIPAddressContinentAlpha2";
		$str .= $sep;
		$str .= $this->m_originatingIPAddressContinentAlpha2;
		$str .= $eol;
		$str .= "OriginatingIPAddressCountry";
		$str .= $sep;
		$str .= $this->m_originatingIPAddressCountry;
		$str .= $eol;
		$str .= "OriginatingIPAddressCountryAlpha2";
		$str .= $sep;
		$str .= $this->m_originatingIPAddressCountryAlpha2;
		$str .= $eol;
		$str .= "OriginatingIPAddressCountryCode";
		$str .= $sep;
		$str .= $this->m_originatingIPAddressCountryCode;
		$str .= $eol;
		$str .= "OriginatingIPAddressIsBlackListed";
		$str .= $sep;
		$str .= $this->m_originatingIPAddressIsBlackListed;
		$str .= $eol;
		$str .= "OriginatingIPAddressIsKnownProxy";
		$str .= $sep;
		$str .= $this->m_originatingIPAddressIsKnownProxy;
		$str .= $eol;
		$str .= "OriginatingIPAddressRegion";
		$str .= $sep;
		$str .= $this->m_originatingIPAddressRegion;
		$str .= $eol;
		$str .= "OriginatingIPAddressRegionCode";
		$str .= $sep;
		$str .= $this->m_originatingIPAddressRegionCode;
		$str .= $eol;
		$str .= "OriginatingIPAddressZipCode";
		$str .= $sep;
		$str .= $this->m_originatingIPAddressZipCode;
		$str .= $eol;		
		$str .= "PAN"; 
		$str .= $sep;
		$str .= $this->m_pan;
		$str .= $eol;
		$str .= "ResultCode"; 
		$str .= $sep;
		$str .= $this->m_resultCode;
		$str .= $eol;
		$str .= "StartDate"; 
		$str .= $sep;
		$str .= $this->m_startDate;
		$str .= $eol;
		$str .= "StartDateFormat"; 
		$str .= $sep;
		$str .= $this->m_startDateFormat;
		$str .= $eol;
		$str .= "UserReference"; 
		$str .= $sep;
		$str .= $this->m_userReference;
		$str .= $eol;
		$str .= "ZipCodeResponseData"; 
		$str .= $sep;
		$str .= $this->m_zipCodeResponseData;
		$str .= $eol;
		$str .= "ZipCodeResult"; 
		$str .= $sep;
		$str .= $this->m_zipCodeResult;
		$str .= $eol;
			
		return $str;
	}
}
?>
