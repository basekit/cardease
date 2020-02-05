<?php

namespace Brucimus83;

if (!defined("TERMINALID_LENGTH")) define("TERMINALID_LENGTH", 8);

if (!defined("SOFTWARENAME_MAX_LENGTH")) define("SOFTWARENAME_MAX_LENGTH", 50);
if (!defined("SOFTWAREVERSION_MAX_LENGTH")) define("SOFTWAREVERSION_MAX_LENGTH", 20);

if (!defined("CURRENCYCODE_LENGTH")) define("CURRENCYCODE_LENGTH", 3);

if (!defined("PAN_MIN_LENGTH")) define("PAN_MIN_LENGTH", 13);
if (!defined("PAN_MAX_LENGTH")) define("PAN_MAX_LENGTH", 19);

if (!defined("ISSUENUMBER_MIN_LENGTH")) define("ISSUENUMBER_MIN_LENGTH", 1);
if (!defined("ISSUENUMBER_MAX_LENGTH")) define("ISSUENUMBER_MAX_LENGTH", 2);

if (!defined("EXPIRYDATEFORMAT_MAX_LENGTH")) define("EXPIRYDATEFORMAT_MAX_LENGTH", 10);
if (!defined("STARTDATEFORMAT_MAX_LENGTH")) define("STARTDATEFORMAT_MAX_LENGTH", 10);

if (!defined("CARDEASE_REFERENCE_LENGTH")) define("CARDEASE_REFERENCE_LENGTH", 36);
if (!defined("CARDHASH_LENGTH")) define("CARDHASH_LENGTH", 28);
if (!defined("CARDREFERENCE_LENGTH")) define("CARDREFERENCE_LENGTH", 36);

if (!defined("TRACK1_MAX_LENGTH")) define("TRACK1_MAX_LENGTH", 79);
if (!defined("TRACK2_MAX_LENGTH")) define("TRACK2_MAX_LENGTH", 40);
if (!defined("TRACK2_START_SENTINEL")) define("TRACK2_START_SENTINEL", ';');
if (!defined("TRACK2_START_SENTINEL")) define("TRACK2_START_SENTINEL", '?');
if (!defined("TRACK3_MAX_LENGTH")) define("TRACK3_MAX_LENGTH", 107);

if (!defined("CSC_MIN_LENGTH")) define("CSC_MIN_LENGTH", 3);
if (!defined("CSC_MAX_LENGTH")) define("CSC_MAX_LENGTH", 4);

if (!defined("ECI_MIN_LENGTH")) define("ECI_MIN_LENGTH", 1);
if (!defined("ECI_MAX_LENGTH")) define("ECI_MAX_LENGTH", 2);

/**
 * A class holding all of the data that constitutes a request to CardEaseXML.
 * The necessary components of the request should be specified (using the
 * "setters"). The request can then be submitted to the Client in order to
 * obtain a Response.
 * <p>
 * For each request there are a number of optional and mandatory components
 * depending upon the type of the request.
 * <p>
 * In brief, these rules are:
 * <ul>
 * <li>All requests:
 * <ul>
 * <li>RequestType - the type of the request
 * <li>SoftwareName - the name of the software using the SDK
 * <li>SoftwareVersion - the version of the software using the SDK
 * <li>TerminalID - the ID of the terminal making the request
 * <li>TransactionKey - the transaction key allocated to a terminal or set of
 * terminals
 * </ul>
 * <li>Auth requests:
 * <ul>
 * <li>Amount - the transaction amount
 * <li>ICCTags or ManualType or Track2 - to specify the card details
 * </ul>
 * <li>Conf requests:
 * <ul>
 * <li>CardEaseReference - the reference of the transaction being confirmed
 * </ul>
 * <li>Offline requests:
 * <ul>
 * <li>Amount - the transaction amount
 * <li>ICCTags or ManualType or Track2 - to specify the card details
 * </ul>
 * <li>Refund requests:
 * <ul>
 * <li>CardEaseReference - the reference of the transaction being refunded
 * <li>ICCTags or ManualType or Track2 - to specify the card details
 * </ul>
 * <li>Void requests:
 * <ul>
 * <li>CardEaseReference - the reference of the transaction being made void
 * <li>VoidReason - the reason for which this transaction is being made void
 * </ul>
 * </ul>
 * If a manual request is being made the PAN, expiry date and expiry date
 * format should be present as a minimum.
 * @author CreditCall Communications
 * @see Client
 * @see Response
 */
class CardEaseXML_Request {

	/**
	 * The 3-D Secure Card Holder Enrollment.
	 * @private
	 */
	var $m_3DSecureCardHolderEnrolled = ThreeDSecureCardHolderEnrolled_Empty;

	/**
	 * The 3-D Secure Electronic Commerce Indicator.
	 * @private
	 */
	var $m_3DSecureECI = null;

	/**
	 * The 3-D Secure Authentication Verification Value.
	 * @private
	 */
	var $m_3DSecureIAV = null;

	/**
	 * The 3-D Secure Authentication Verification algorithm.
	 * @private
	 */
	var $m_3DSecureIAVAlgorithm = null;

	/** The 3-D Secure Authentication Verification Value format.
	 * @private
	 */
	var $m_3DSecureIAVFormat = IAVFormat_Base64;

	/**
	 * The 3-D Secure Transaction Status.
	 * @private
	 */
	var $m_3DSecureTransactionStatus = ThreeDSecureTransactionStatus_Empty;

	/**
	 * The 3-D Secure Transaction Identifier.
	 * @private
	 */
	var $m_3DSecureXID = null;

	/**
	 * The 3-D Secure Transaction Identifier format.
	 * @private
	 */
	var $m_3DSecureXIDFormat = XIDFormat_Ascii;

	/**
	 * The address used for additional verification.
	 * @private
	 */
	var $m_address = null;

	/**
	 * The transaction amount.
	 * @private
	 */
	var $m_amount = null;

	/**
	 * The units of the transaction amount.
	 * @private
	 */
	var $m_amountUnit = AmountUnit_Minor;

	/**
	 * Whether the request is automatically confirmed.
	 * @private
	 */
	var $m_autoConfirm = false;

	/**
	 * An optional batch reference to describe a set of transactions
	 */
	var $m_batchReference = null;

	/**
	 * The CardEase reference that has been obtained during previous
	 * requests.
	 * @private
	 */
	var $m_cardEaseReference = null;

	/**
	 * The version of CardEaseXML that this client adheres to.
	 * @private
	 */
	var $m_cardEaseXMLVersion = "1.1.0";

	/**
	 * The hash of an existing card to use for manual payment in place of
	 * the PAN, ExpiryDate etc.
	 * @private
	 */
	var $m_cardHash = null;
	
	/**
	 * The card holder's address.
	 */
	var $m_cardHolderAddress = null; // Cannot initialise here
	
	/**
	 * The card holder's email addresses.
	 */
	var $m_cardHolderEmailAddresses = array();
	
	/**
	 * The card holder's name.
	 */
	var $m_cardHolderName = null; // Cannot initialise here
	
	/**
	 * The card holder's phone numbers.
	 */
	var $m_cardHolderPhoneNumbers = array();
	
	/**
	 * The reference of an existing card to use for manual payment in
	 * place of the PAN, ExpiryDate etc.
	 * @private
	 */
	var $m_cardReference = null;

	/**
	 * The security code printed on the card.
	 * @private
	 */
	var $m_csc = null;

	/**
	 * The currency code or mnemonic related to the transaction.
	 * @private
	 */
	var $m_currencyCode = null;

	/**
	 * The delivery address.
	 */
	var $m_deliveryAddress = null; // Cannot initialise here
	
	/**
	 * The delivery email addresses.
	 */
	var $m_deliveryEmailAddresses = array();
	
	/**
	 * The delivery name.
	 */
	var $m_deliveryName = null; // Cannot initialise here
	
	/**
	 * The delivery phone numbers.
	 */
	var $m_deliveryPhoneNumbers = array();

	/**
	 * The expiry date associated with the card.
	 * @private
	 */
	var $m_expiryDate = null;

	/**
	 * The format of the expiry date associated with the card.
	 * @private
	 */
	var $m_expiryDateFormat = "yyMM";

	/**
	 * The list of extended properties associated with this transaction.
	 * @private
	 */
	var $m_extendedProperties = array();

	/**
	 * Whether the transaction was a fallback from EMV to magnetic strip.
	 * @private
	 */
	var $m_iccFallback = false;

	/**
	 * The ICC management function associated with the ICC management
	 * request.
	 * @private
	 */
	var $m_iccManagementFunction = null;

	/**
	 * The list of ICC tags associated with the transaction.
	 * @private
	 */
	var $m_iccTags = array();

	/**
	 * The type of ICC transaction.
	 * @private
	 */
	var $m_iccType = "EMV";

	/**
	 * The invoice address.
	 */
	var $m_invoiceAddress = null; // Cannot initialise here
	
	/**
	 * The invoice email addresses.
	 */
	var $m_invoiceEmailAddresses = array();
	
	/**
	 * The invoice name.
	 */
	var $m_invoiceName = null; // Cannot initialise here
	
	/**
	 * The invoice phone numbers.
	 */
	var $m_invoicePhoneNumbers = array();

	/**
	 * The issue number associated with the card.
	 * @private
	 */
	var $m_issueNumber = null;

	/**
	 * The machine reference for this request.
	 * @private
	 */
	var $m_machineReference = null;

	/**
	 * The type of manual transaction.
	 * @private
	 */
	var $m_manualType = "cnp";

	/**
	 * The date and/or time of the transaction is processed offline.
	 * @private
	 */
	var $m_offlineDateTime = null;

	/**
	 * The format of the date and/or time of the transaction if processed offline.
	 */
	var $m_offlineDateTimeFormat = "ddMMyy HHmmss";

	/**
	 * The originating IP address of the request.  E.g. client browser.
	 * @private
	 */
	var $m_originatingIPAddress = null;

	/**
	 * The PAN (Primary Account Number) associated with the card.
	 * @private
	 */
	var $m_pan = null;

	/**
	 *  The product list associated with this request.
	 */
	var $m_products = array();

	/**
	 * The type of the request.
	 * @private
	 */
	var $m_requestType = REQUESTTYPE_AUTH;

	/**
	 * The name of the software using the SDK.
	 * @private
	 */
	var $m_softwareName = null;

	/**
	 * The version of the software using the SDK.
	 * @private
	 */
	var $m_softwareVersion = null;

	/**
	 * The start date associated with the card.
	 * @private
	 */
	var $m_startDate = null;

	/**
	 * The format of the start date associated with the card.
	 * @private
	 */
	var $m_startDateFormat = "yyMM";

	/**
	 * The terminal ID associated with the transaction.
	 * @private
	 */
	var $m_terminalID = null;

	/**
	 * The track1 associated with the card.
	 * @private
	 */
	var $m_track1 = null;

	/**
	 * The track2 associated with the card.
	 * @private
	 */
	var $m_track2 = null;

	/**
	 * The track3 associated with the card.
	 * @private
	 */
	var $m_track3 = null;

	/**
	 * The transaction key associated with the transaction.
	 * @private
	 */
	var $m_transactionKey = null;

	/**
	 * An optional user reference.
	 * @private
	 */
	var $m_userReference = null;

	/**
	 * The reason for which a void request is being made.
	 * @private
	 */
	var $m_voidReason = VoidReason_Empty;

	/**
	 * The zip code associated with the card.
	 * @private
	 */
	var $m_zipCode = null;

	/**
	 * Constructs a Request and initialises variables.
	 */
	function Request() {
		$this->m_3DSecureCardHolderEnrolled = ThreeDSecureCardHolderEnrolled_Empty;
		$this->m_3DSecureECI = null;
		$this->m_3DSecureIAV = null;
		$this->m_3DSecureIAVAlgorithm = null;
		$this->m_3DSecureIAVFormat = IAVFormat_Base64;
		$this->m_3DSecureTransactionStatus = ThreeDSecureTransactionStatus_Empty;
		$this->m_3DSecureXID = null;
		$this->m_3DSecureXIDFormat = XIDFormat_Ascii;
		$this->m_address = null;
		$this->m_amount = null;
		$this->m_amountUnit = AmountUnit_Minor;
		$this->m_autoConfirm = false;
		$this->m_batchReference = null;
		$this->m_cardEaseReference = null;
		$this->m_cardEaseXMLVersion = "1.1.0";
		$this->m_cardHash = null;
		$this->m_cardHolderAddress = new Address();
		$this->m_cardHolderEmailAddresses = array();
		$this->m_cardHolderName = new Name();
		$this->m_cardHolderPhoneNumbers = array();
		$this->m_cardReference = null;
		$this->m_csc = null;
		$this->m_currencyCode = null;
		$this->m_deliveryAddress = new Address();
		$this->m_deliveryEmailAddresses = array();
		$this->m_deliveryName = new Name();	
		$this->m_deliveryPhoneNumbers = array();
		$this->m_expiryDate = null;
		$this->m_expiryDateFormat = "yyMM";
		$this->m_extendedProperties = array();
		$this->m_iccFallback = false;
		$this->m_iccManagementFunction = null;
		$this->m_iccTags = array();
		$this->m_iccType = "EMV";
		$this->m_invoiceAddress = new Address();
		$this->m_invoiceEmailAddresses = array();
		$this->m_invoiceName = new Name();	
		$this->m_invoicePhoneNumbers = array();
		$this->m_issueNumber = null;
		$this->m_machineReference = null;
		$this->m_manualType = "cnp";
		$this->m_offlineDateTime = null;
		$this->m_offlineDateTimeFormat = "ddMMyy HHmmss";
		$this->m_originatingIPAddress = null;
		$this->m_pan = null;
		$this->m_products = array();
		$this->m_requestType = REQUESTTYPE_AUTH;
		$this->m_softwareName = null;
		$this->m_softwareVersion = null;
		$this->m_startDate = null;
		$this->m_startDateFormat = "yyMM";
		$this->m_terminalID = null;
		$this->m_track1 = null;
		$this->m_track2 = null;
		$this->m_track3 = null;
		$this->m_transactionKey = null;
		$this->m_userReference = null;
		$this->m_voidReason = VoidReason_Empty;
		$this->m_zipCode = null;
	}

	/**
	 * Adds an email address to the list of email addresses associated with the card holder.
	 * @param emailAddress The email address to add.  Should not be null.
	 * @see getCardHolderEmailAddresses()
	 * @see setCardHolderEmailAddresses()
	 * @see EmailAddress
	 */
	function addCardHolderEmailAddress($emailAddress) {
		
		if ($this->m_cardHolderEmailAddresses === null) {
			$this->m_cardHolderEmailAddresses = array();
		}
		
		$this->m_cardHolderEmailAddresses[] = $emailAddress;
	}

	/**
	 * Adds a phone number to the list of phone numbers associated with the card holder.
	 * @param phoneNumber The phone number to add.  Should not be null.
	 * @see getCardHolderPhoneNumbers()
	 * @see setCardHolderPhoneNumbers()
	 * @see PhoneNumber
	 */
	function addCardHolderPhoneNumber($phoneNumber) {
		
		if ($this->m_cardHolderPhoneNumbers === null) {
			$this->m_cardHolderPhoneNumbers = array();
		}
		
		$this->m_cardHolderPhoneNumbers[] = $phoneNumber;
	}

	/**
	 * Adds an email address to the list of email addresses associated with the delivery address.
	 * @param emailAddress The email address to add.  Should not be null.
	 * @see getDeliveryEmailAddresses()
	 * @see setDeliveryEmailAddresses()
	 * @see EmailAddress
	 */
	function addDeliveryEmailAddress($emailAddress) {
		
		if ($this->m_deliveryEmailAddresses === null) {
			$this->m_deliveryEmailAddresses = array();
		}
		
		$this->m_deliveryEmailAddresses[] = $emailAddress;
	}

	/**
	 * Adds a phone number to the list of phone numbers associated with the delivery address.
	 * @param phoneNumber The phone number to add.  Should not be null.
	 * @see getDeliveryPhoneNumbers()
	 * @see setDeliveryPhoneNumbers()
	 * @see PhoneNumber
	 */
	function addDeliveryPhoneNumber($phoneNumber) {
		
		if ($this->m_deliveryPhoneNumbers === null) {
			$this->m_deliveryPhoneNumbers = array();
		}
		
		$this->m_deliveryPhoneNumbers[] = $phoneNumber;
	}

	/**
	 * Adds an extended property to the list of extended properties associated with this request.
	 * 
	 * @param extendedProperty The extended property to add to the list of extended properties associated with this request.  Should not be null.
	 * @see getExtendedProperties()
	 * @see setExtendedProperties()
	 * @see ExtendedProperty
	 */
	function addExtendedProperty($extendedProperty) {
		
		if ($this->m_extendedProperties === null) {
			$this->m_extendedProperties = array();
		}
		
		$this->m_extendedProperties[] = $extendedProperty;
	}

	/**
	 * Adds an ICC tag to the list of ICC tags associated with this
	 * request. Each ICC tag has an id, type and value. For example, a tag
	 * of 0x9f02/AsciiHex/000000000100 is using to specify the transaction
	 * amount. These are mandatory for an EMV transaction.
	 * 
	 * @param iccTag
	 *	The ICC tag to add to the list of ICC tags associated with
	 *	this request. This should not be null.
	 * @see ICCTag
	 * @see getICCTags()
	 * @see setICCTags()
	 */
	function addICCTag($iccTag) {

		if ($this->m_iccTags === null) {
			$this->m_iccTags = array();
		}

		$this->m_iccTags[] = $iccTag;
	}
	
	/**
	 * Adds an email address to the list of email addresses associated with the invoice address.
	 * @param emailAddress The email address to add.  Should not be null.
	 * @see getInvoiceEmailAddresses()
	 * @see setInvoiceEmailAddresses()
	 * @see EmailAddress
	 */
	function addInvoiceEmailAddress($emailAddress) {
		
		if ($this->m_invoiceEmailAddresses === null) {
			$this->m_invoiceEmailAddresses = array();
		}
		
		$this->m_invoiceEmailAddresses[] = $emailAddress;
	}

	/**
	 * Adds a phone number to the list of phone numbers associated with the invoice address.
	 * @param phoneNumber The phone number to add.  Should not be null.
	 * @see getInvoicePhoneNumbers()
	 * @see setInvoicePhoneNumbers()
	 * @see PhoneNumber
	 */
	function addInvoicePhoneNumber($phoneNumber) {
		
		if ($this->m_invoicePhoneNumbers === null) {
			$this->m_invoicePhoneNumbers = array();
		}
		
		$this->m_invoicePhoneNumbers[] = $phoneNumber;
	}

	/**
	 * Adds a product to the list of products associated with this request.
	 * @param product The product to add.  Should not be null.
	 * @see getProducts()
	 * @see setProducts()
	 * @see Product
	 */
	function addProduct($product) {

		if ($this->m_products === null) {
			$this->m_products = array();
		}

		$this->m_products[] = $product;
	}

	/**
	 * Generates the XML that represents the content of this request.
	 * Before processing is performed, the request data is validated to
	 * ensure CardEaseXML standard conformity.
	 * @private
	 * @param writer
	 *	The target of the XML data. This must not be null.
	 * @throws E_USER_ERROR
	 *	If the request data is not valid.
	 */
	function generateRequestXML($writer)
	{
		$writer->writeStartDocument(true);
		$writer->writeStartElement("Request");
		$writer->writeAttributeString("type", "CardEaseXML");

		if ($this->m_cardEaseXMLVersion !== null)
		{
			$writer->writeAttributeString("version", $this->m_cardEaseXMLVersion);
		}

		// TransactionDetails
		$writer->writeStartElement("TransactionDetails");

		$writer->writeStartElement("MessageType");

		if ($this->m_autoConfirm)
		{
			$writer->writeAttributeString("autoconfirm", "true");
		}

		$writer->writeString($this->m_requestType);
		$writer->writeEndElement(); // MessageType

		if ($this->m_iccManagementFunction !== null)
		{
			$writer->writeElementString("ManagementFunction", $this->m_iccManagementFunction);
		}

        if ($this->m_offlineDateTime !== null)
        {
            $writer->WriteStartElement("OfflineDateTime");

            if ($this->m_offlineDateTimeFormat !== null)
            {
                $writer->WriteAttributeString("format", $this->m_offlineDateTimeFormat);
            }

            $writer->WriteString($this->m_offlineDateTime);
            $writer->WriteEndElement(); // OfflineDateTime
        }

		if ($this->m_originatingIPAddress !== null)
		{
			$writer->writeElementString("OriginatingIP", $this->m_originatingIPAddress);
		}

		if ($this->m_userReference !== null)
		{
			$writer->writeElementString("Reference", $this->m_userReference);
		}

		// DeliveryAddress
		if (($this->m_deliveryAddress !== null && !$this->m_deliveryAddress->isEmpty()) ||
			($this->m_deliveryEmailAddresses !== null && count($this->m_deliveryEmailAddresses) !== 0) ||
			($this->m_deliveryName !== null && !$this->m_deliveryName->isEmpty()) ||
			($this->m_deliveryPhoneNumbers !== null && count($this->m_deliveryPhoneNumbers) !== 0))
		{			
			$writer->writeStartElement("Delivery");
		
			if ($this->m_deliveryAddress !== null && !$this->m_deliveryAddress->isEmpty())
			{
				$writer->writeStartElement("Address");
				
				$writer->writeAttributeString("format", "standard");
				
				if ($this->m_deliveryAddress->getRecipient() !== null)
				{					
					$recipient = $this->m_deliveryAddress->getRecipient();
				
					for ($id = 0; $id < count($recipient); $id++)
					{
						$writer->writeStartElement("Recipient");
						$writer->writeAttributeString("id", $id + 1);
						$writer->writeString($recipient[$id]);
						$writer->writeEndElement(); // Recipient
					}
					
					unset($recipient);
				}
				
				if ($this->m_deliveryAddress->getLines() !== null)
				{					
					$lines = $this->m_deliveryAddress->getLines();
				
					for ($id = 0; $id < count($lines); $id++)
					{
						$writer->writeStartElement("Line");
						$writer->writeAttributeString("id", $id + 1);
						$writer->writeString($lines[$id]);
						$writer->writeEndElement(); // Line
					}
					
					unset($lines);
				}
				
				if ($this->m_deliveryAddress->getCity() !== null)
				{
					$writer->writeElementString("City", $this->m_deliveryAddress->getCity());
				}
				
				if ($this->m_deliveryAddress->getState() !== null)
				{
					$writer->writeElementString("State", $this->m_deliveryAddress->getState());
				}
				
				if ($this->m_deliveryAddress->getZipCode() !== null)
				{
					$writer->writeElementString("ZipCode", $this->m_deliveryAddress->getZipCode());
				}
				
				if ($this->m_deliveryAddress->getCountry() !== null)
				{
					$writer->writeElementString("Country", $this->m_deliveryAddress->getCountry());
				}
				
				$writer->writeEndElement(); // Address
			}
			
			// DeliveryName
			if (($this->m_deliveryEmailAddresses !== null && count($this->m_deliveryEmailAddresses) !== 0) ||
				($this->m_deliveryName !== null && !$this->m_deliveryName->isEmpty()) ||
				($this->m_deliveryPhoneNumbers !== null && count($this->m_deliveryPhoneNumbers) !== 0))
			{				
				$writer->writeStartElement("Contact");
				
				if ($this->m_deliveryEmailAddresses !== null && count($this->m_deliveryEmailAddresses) !== 0)
				{					
					$writer->writeStartElement("EmailAddressList");
					
					for ($id = 0; $id < count($this->m_deliveryEmailAddresses); $id++)
					{						
						$emailAddress = $this->m_deliveryEmailAddresses[$id];
					
						$writer->writeStartElement("EmailAddress");
						$writer->writeAttributeString("id", $id + 1);
						$writer->writeAttributeString("type", $emailAddress->getType());
						$writer->writeString($emailAddress->getAddress());
						$writer->writeEndElement(); // EmailAddress
						
						unset($emailAddress);
					}
					
					$writer->writeEndElement(); // EmailAddressList
				}
				
				if ($this->m_deliveryName !== null && !$this->m_deliveryName->isEmpty())
				{					
					$writer->writeStartElement("Name");
					
					if ($this->m_deliveryName->getTitle() !== null)
					{
						$writer->writeElementString("Title", $this->m_deliveryName->getTitle());
					}
					
					if ($this->m_deliveryName->getFirstName() !== null)
					{
						$writer->writeElementString("FirstName", $this->m_deliveryName->getFirstName());
					}
					
					if ($this->m_deliveryName->getInitials() !== null)
					{
						$writer->writeElementString("Initials", $this->m_deliveryName->getInitials());
					}
					
					if ($this->m_deliveryName->getLastName() !== null)
					{
						$writer->writeElementString("LastName", $this->m_deliveryName->getLastName());
					}
					
					$writer->writeEndElement(); // Name
				}
				
				if ($this->m_deliveryPhoneNumbers !== null && count($this->m_deliveryPhoneNumbers) !== 0)
				{					
					$writer->writeStartElement("PhoneNumberList");
					
					for ($id = 0; $id < count($this->m_deliveryPhoneNumbers); $id++)
					{						
						$phoneNumber = $this->m_deliveryPhoneNumbers[$id];
					
						$writer->writeStartElement("PhoneNumber");
						$writer->writeAttributeString("id", $id + 1);
						$writer->writeAttributeString("type", $phoneNumber->getType());
						$writer->writeString($phoneNumber->getNumber());
						$writer->writeEndElement(); // PhoneNumber
						
						unset($phoneNumber);
					}
					
					$writer->writeEndElement(); // PhoneNumberList
				}
				
				$writer->writeEndElement(); // Contact
			}
				
			$writer->writeEndElement(); // Delivery
		}

		// ExtendedPropertyList
		if ($this->m_extendedProperties !== null && count($this->m_extendedProperties) !== 0)
		{
			$writer->writeStartElement("ExtendedPropertyList");
			
			foreach ($this->m_extendedProperties as $extendedProperty)
			{
				$writer->writeStartElement("ExtendedProperty");
				$writer->writeAttributeString("id", $extendedProperty->getName());
				$writer->writeString($extendedProperty->getValue());
				$writer->writeEndElement(); // ExtendedProperty
			}
			
			$writer->writeEndElement(); // ExtendedPropertyList
		}

		// Invoice
		if (($this->m_invoiceAddress !== null && !$this->m_invoiceAddress->isEmpty()) ||
			($this->m_invoiceEmailAddresses !== null && count($this->m_invoiceEmailAddresses) !== 0) ||
			($this->m_invoiceName !== null && !$this->m_invoiceName->isEmpty()) ||
			($this->m_invoicePhoneNumbers !== null && count($this->m_invoicePhoneNumbers) !== 0))
		{			
			$writer->writeStartElement("Invoice");
			
			// InvoiceAddress
			if (($this->m_invoiceAddress !== null && !$this->m_invoiceAddress->isEmpty()))
			{				
				$writer->writeStartElement("Address");
				
				$writer->writeAttributeString("format", "standard");
				
				if ($this->m_invoiceAddress->getRecipient() !== null)
				{					
					$recipient = $this->m_invoiceAddress->getRecipient();
				
					for ($id = 0; $id < count($recipient); $id++)
					{
						$writer->writeStartElement("Recipient");
						$writer->writeAttributeString("id", $id + 1);
						$writer->writeString($recipient[$id]);
						$writer->writeEndElement(); // Recipient
					}
					
					unset($recipient);
				}
				
				if ($this->m_invoiceAddress->getLines() !== null)
				{					
					$lines = $this->m_invoiceAddress->getLines();
				
					for ($id = 0; $id < count($lines); $id++)
					{
						$writer->writeStartElement("Line");
						$writer->writeAttributeString("id", $id + 1);
						$writer->writeString($lines[$id]);
						$writer->writeEndElement(); // Line
					}
					
					unset($lines);
				}
				
				if ($this->m_invoiceAddress->getCity() !== null)
				{
					$writer->writeElementString("City", $this->m_invoiceAddress->getCity());
				}
				
				if ($this->m_invoiceAddress->getState() !== null)
				{
					$writer->writeElementString("State", $this->m_invoiceAddress->getState());
				}
				
				if ($this->m_invoiceAddress->getZipCode() !== null)
				{
					$writer->writeElementString("ZipCode", $this->m_invoiceAddress->getZipCode());
				}
				
				if ($this->m_invoiceAddress->getCountry() !== null)
				{
					$writer->writeElementString("Country", $this->m_invoiceAddress->getCountry());
				}
				
				$writer->writeEndElement(); // Address
			}
			
			// InvoiceName
			if (($this->m_invoiceEmailAddresses !== null && count($this->m_invoiceEmailAddresses) !== 0) ||
				($this->m_invoiceName !== null && !$this->m_invoiceName->isEmpty()) ||
				($this->m_invoicePhoneNumbers !== null && count($this->m_invoicePhoneNumbers) !== 0))
			{				
				$writer->writeStartElement("Contact");
				
				if ($this->m_invoiceEmailAddresses !== null && count($this->m_invoiceEmailAddresses) !== 0)
				{					
					$writer->writeStartElement("EmailAddressList");
					
					for ($id = 0; $id < count($this->m_invoiceEmailAddresses); $id++)
					{						
						$emailAddress = $this->m_invoiceEmailAddresses[$id];						
					
						$writer->writeStartElement("EmailAddress");
						$writer->writeAttributeString("id", $id + 1);
						$writer->writeAttributeString("type", $emailAddress->getType());
						$writer->writeString($emailAddress->getAddress());
						$writer->writeEndElement(); // EmailAddress
						
						unset($emailAddress);
					}
					
					$writer->writeEndElement(); // EmailAddressList
				}
				
				if ($this->m_invoiceName !== null && !$this->m_invoiceName->isEmpty())
				{				
					$writer->writeStartElement("Name");
					
					if ($this->m_invoiceName->getTitle() !== null)
					{
						$writer->writeElementString("Title", $this->m_invoiceName->getTitle());
					}
					
					if ($this->m_invoiceName->getFirstName() !== null)
					{
						$writer->writeElementString("FirstName", $this->m_invoiceName->getFirstName());
					}
					
					if ($this->m_invoiceName->getInitials() !== null)
					{
						$writer->writeElementString("Initials", $this->m_invoiceName->getInitials());
					}
					
					if ($this->m_invoiceName->getLastName() !== null)
					{
						$writer->writeElementString("LastName", $this->m_invoiceName->getLastName());
					}
					
					$writer->writeEndElement(); // Name
				}
				
				if ($this->m_invoicePhoneNumbers !== null && count($this->m_invoicePhoneNumbers) !== 0)
				{					
					$writer->writeStartElement("PhoneNumberList");
					
					for ($id = 0; $id < count($this->m_invoicePhoneNumbers); $id++)
					{						
						$phoneNumber = $this->m_invoicePhoneNumbers[$id];						
					
						$writer->writeStartElement("PhoneNumber");
						$writer->writeAttributeString("id", $id + 1);
						$writer->writeAttributeString("type", $phoneNumber->getType());
						$writer->writeString($phoneNumber->getNumber());
						$writer->writeEndElement(); // PhoneNumber
						
						unset($phoneNumber);
					}
					
					$writer->writeEndElement(); // PhoneNumbers
				}
				
				$writer->writeEndElement(); // Contact
			}
			
			$writer->writeEndElement(); // Invoice
		}

		// Product List
		if ($this->m_products !== null && count($this->m_products) !== 0)
		{			
			$writer->writeStartElement("ProductList");
			
			for ($id = 0; $id < count($this->m_products); $id++)
			{				
				$writer->writeStartElement("Product");
				$writer->writeAttributeString("id", $id + 1);
				
				$product = $this->m_products[$id];
				
				if ($product->getAmount() !== null)
				{
					$writer->writeStartElement("Amount");
					
					// Default is minor
					if ($product->getAmountUnit() === AmountUnit_Major)
					{
						$writer->writeAttributeString("unit", "major");
					}
					
					$writer->writeString($product->getAmount());
					$writer->writeEndElement(); // Amount
				}
				
				if ($product->getCategory() !== null)
				{
					$writer->writeElementString("Category", $product->getCategory());
				}
				
				if ($product->getCode() !== null)
				{
					$writer->writeElementString("Code", $product->getCode());
				}
				
				if ($product->getDescription() !== null)
				{
					$writer->writeElementString("Description", $product->getDescription());
				}
				
				if ($product->getCurrencyCode() !== null)
				{
					$writer->writeElementString("CurrencyCode", $product->getCurrencyCode());
				}
				
				if ($product->getName() !== null)
				{
					$writer->writeElementString("Name", $product->getName());
				}
				
				if ($product->getQuantity() !== null)
				{
					$writer->writeElementString("Quantity", $product->getQuantity());
				}
				
				if ($product->getRisk() !== null)
				{
					$writer->writeElementString("Risk", $product->getRisk());
				}
				
				if ($product->getType() !== null)
				{
					$writer->writeElementString("Type", $product->getType());
				}
				
				$writer->writeEndElement(); // Product
				
				unset($product);
			}
			
			$writer->writeEndElement(); // ProductList			
		}

        if ($this->m_batchReference != null)
        {
            $writer->writeElementString("BatchReference", $this->m_batchReference);
        }

		if ($this->m_cardEaseReference !== null)
		{
			$writer->writeElementString("CardEaseReference", $this->m_cardEaseReference);
		}

		if ($this->m_amount !== null)
		{
			$writer->writeStartElement("Amount");

			// Default is minor
			if ($this->m_amountUnit === AmountUnit_Major)
			{
				$writer->writeAttributeString("unit", "major");
			}

			$writer->writeString($this->m_amount);
			$writer->writeEndElement(); // Amount
		}

		if ($this->m_currencyCode !== null)
		{
			$writer->writeElementString("CurrencyCode", $this->m_currencyCode);
		}

		if ($this->m_voidReason !== VoidReason_Empty)
		{
			$writer->writeElementString("VoidReason", $this->m_voidReason);
		}

		$writer->writeEndElement(); // TransactionDetails

		// TerminalDetails
		$writer->writeStartElement("TerminalDetails");

		if ($this->m_terminalID !== null)
		{
			$writer->writeElementString("TerminalID", $this->m_terminalID);
		}

		if ($this->m_transactionKey !== null)
		{
			$writer->writeElementString("TransactionKey", $this->m_transactionKey);
		}

		if ($this->m_machineReference !== null)
		{
			$writer->writeElementString("MachineReference", $this->m_machineReference);
		}

		if ($this->m_softwareName !== null)
		{
			$writer->writeStartElement("Software");

			if ($this->m_softwareVersion !== null)
			{
				$writer->writeAttributeString("version", $this->m_softwareVersion);
			}

			$writer->writeString($this->m_softwareName);
			$writer->writeEndElement(); // Software
		}

		$writer->writeStartElement("Component");
		$writer->writeAttributeString("version", Client::getCardEaseXMLSDKVersion());
		$writer->writeString(Client::getCardEaseXMLSDKName());
		$writer->writeEndElement(); // Component

		$writer->writeEndElement(); // TerminalDetails

		// CardDetails
		if ($this->m_requestType === REQUESTTYPE_AUTH
				|| $this->m_requestType === RequestType_Offline
				|| $this->m_requestType === RequestType_PreAuth
				|| $this->m_requestType === RequestType_Refund)
		{
			if (($this->m_iccTags !== null && count($this->m_iccTags) !== 0) ||
				$this->m_track2 !== null || $this->m_pan !== null || $this->m_cardReference !== null)
			{		
				$writer->writeStartElement("CardDetails");
	
				if ($this->m_iccTags !== null && count($this->m_iccTags) !== 0)
				{	
					$writer->writeStartElement("ICC");
					$writer->writeAttributeString("type", $this->m_iccType);
	
					foreach ($this->m_iccTags as $tag)
					{	
						if ($tag === null)
						{
							continue;
						}
	
						$writer->writeStartElement("ICCTag");
						$writer->writeAttributeString("tagid", $tag->getID());
	
						if ($tag->getType() === ICCTagValueType_String)
						{
							$writer->writeAttributeString("type", "string");
						}
	
						$writer->writeString($tag->getValue());
						$writer->writeEndElement(); // ICCTag
					}
	
					$writer->writeEndElement(); // ICC
	
				} 
				else if ($this->m_track2 !== null)
				{	
					$writer->writeStartElement("CAT");
	
					if ($this->m_iccFallback)
					{
						$writer->writeAttributeString("fallback", "true");
					}
	
					if ($this->m_track1 !== null)
					{
						$writer->writeElementString("Track1", $this->m_track1);
					}
	
					if ($this->m_track2 !== null)
					{
						$writer->writeElementString("Track2", $this->m_track2);
					}
	
					if ($this->m_track3 !== null)
					{
						$writer->writeElementString("Track3", $this->m_track3);
					}
	
					$writer->writeEndElement(); // CAT
	
				}
				else if ($this->m_pan !== null || $this->m_cardReference !== null)
				{	
					$writer->writeStartElement("Manual");
	
					if ($this->m_manualType !== null)
					{
						$writer->writeAttributeString("type", $this->m_manualType);
					}
	
					if ($this->m_cardReference !== null)
					{
						$writer->writeElementString("CardReference", $this->m_cardReference);
					}
					
					if ($this->m_cardHash !== null)
					{
						$writer->writeElementString("CardHash", $this->m_cardHash);
					}
	
					if ($this->m_pan !== null)
					{
						$writer->writeElementString("PAN", $this->m_pan);
					}
	
					if ($this->m_expiryDate !== null)
					{	
						$writer->writeStartElement("ExpiryDate");
	
						if ($this->m_expiryDateFormat !== null)
						{
							$writer->writeAttributeString("format",	$this->m_expiryDateFormat);
						}
	
						$writer->writeString($this->m_expiryDate);
						$writer->writeEndElement(); // ExpiryDate
	
					}
	
					if ($this->m_startDate !== null)
					{	
						$writer->writeStartElement("StartDate");
	
						if ($this->m_startDateFormat !== null)
						{
							$writer->writeAttributeString("format", $this->m_startDateFormat);
						}
	
						$writer->writeString($this->m_startDate);
						$writer->writeEndElement(); // StartDate	
					}
	
					if ($this->m_issueNumber !== null)
					{
						$writer->writeElementString("IssueNumber", $this->m_issueNumber);
					}
	
					$writer->writeEndElement(); // Manual
				}
	
				if ($this->m_address !== null || $this->m_csc !== null || $this->m_zipCode !== null)
				{	
					$writer->writeStartElement("AdditionalVerification");
	
					if ($this->m_address !== null)
					{
						$writer->writeElementString("Address", $this->m_address);
					}
	
					if ($this->m_csc !== null)
					{
						$writer->writeElementString("CSC", $this->m_csc);
					}
	
					if ($this->m_zipCode !== null)
					{
						$writer->writeElementString("Zip", $this->m_zipCode);
					}
	
					$writer->writeEndElement(); // AdditionalVerification
				}

				// CardHolderAddress
				if ($this->m_cardHolderAddress !== null && !$this->m_cardHolderAddress->IsEmpty())
				{					
					$writer->writeStartElement("Address");
					
					$writer->writeAttributeString("format", "standard");
					
					if ($this->m_cardHolderAddress->getRecipient() !== null)
					{						
						$recipient = $this->m_cardHolderAddress->getRecipient();
					
						for ($id = 0; $id < count($recipient); $id++)
						{
							$writer->writeStartElement("Recipient");
							$writer->writeAttributeString("id", $id + 1);
							$writer->writeString($recipient[$id]);
							$writer->writeEndElement(); // Recipient
						}
						
						unset($recipient);
					}
					
					if ($this->m_cardHolderAddress->getLines() !== null)
					{						
						$lines = $this->m_cardHolderAddress->getLines();
					
						for ($id = 0; $id < count($lines); $id++)
						{
							$writer->writeStartElement("Line");
							$writer->writeAttributeString("id", $id + 1);
							$writer->writeString($lines[$id]);
							$writer->writeEndElement(); // Line
						}
						
						unset($lines);
					}
					
					if ($this->m_cardHolderAddress->getCity() !== null)
					{
						$writer->writeElementString("City", $this->m_cardHolderAddress->getCity());
					}
					
					if ($this->m_cardHolderAddress->getState() !== null)
					{
						$writer->writeElementString("State", $this->m_cardHolderAddress->getState());
					}
					
					if ($this->m_cardHolderAddress->getZipCode() !== null)
					{
						$writer->writeElementString("ZipCode", $this->m_cardHolderAddress->getZipCode());
					}
					
					if ($this->m_cardHolderAddress->getCountry() !== null)
					{
						$writer->writeElementString("Country", $this->m_cardHolderAddress->getCountry());
					}
					
					$writer->writeEndElement(); // Address
				}

				// CardHolderName
				if (($this->m_cardHolderEmailAddresses !== null && count($this->m_cardHolderEmailAddresses) !== 0) ||
					($this->m_cardHolderName !== null && !$this->m_cardHolderName->isEmpty()) ||
					($this->m_cardHolderPhoneNumbers !== null && count($this->m_cardHolderPhoneNumbers) !== 0))
				{					
					$writer->writeStartElement("Contact");
					
					if ($this->m_cardHolderEmailAddresses !== null && count($this->m_cardHolderEmailAddresses) !== 0)
					{					
						$writer->writeStartElement("EmailAddressList");
						
						for ($id = 0; $id < count($this->m_cardHolderEmailAddresses); $id++)
						{							
							$emailAddress = $this->m_cardHolderEmailAddresses[$id];							
						
							$writer->writeStartElement("EmailAddress");
							$writer->writeAttributeString("id", $id + 1);
							$writer->writeAttributeString("type", $emailAddress->getType());
							$writer->writeString($emailAddress->getAddress());
							$writer->writeEndElement(); // EmailAddress
							
							unset($emailAddress);
						}
						
						$writer->writeEndElement(); // EmailAddressList
					}
					
					if ($this->m_cardHolderName !== null && !$this->m_cardHolderName->isEmpty())
					{						
						$writer->writeStartElement("Name");
						
						if ($this->m_cardHolderName->getTitle() !== null)
						{
							$writer->writeElementString("Title", $this->m_cardHolderName->getTitle());
						}
						
						if ($this->m_cardHolderName->getFirstName() !== null)
						{
							$writer->writeElementString("FirstName", $this->m_cardHolderName->getFirstName());
						}
						
						if ($this->m_cardHolderName->getInitials() !== null)
						{
							$writer->writeElementString("Initials", $this->m_cardHolderName->getInitials());
						}
						
						if ($this->m_cardHolderName->getLastName() !== null)
						{
							$writer->writeElementString("LastName", $this->m_cardHolderName->getLastName());
						}
						
						$writer->writeEndElement(); // Name
					}
					
					if ($this->m_cardHolderPhoneNumbers !== null && count($this->m_cardHolderPhoneNumbers) !== 0)
					{					
						$writer->writeStartElement("PhoneNumberList");
						
						for ($id = 0; $id < count($this->m_cardHolderPhoneNumbers); $id++)
						{							
							$phoneNumber = $this->m_cardHolderPhoneNumbers[$id];
						
							$writer->writeStartElement("PhoneNumber");
							$writer->writeAttributeString("id", $id + 1);
							$writer->writeAttributeString("type", $phoneNumber->getType());
							$writer->writeString($phoneNumber->getNumber());
							$writer->writeEndElement(); // PhoneNumber
							
							unset($phoneNumber);
						}
						
						$writer->writeEndElement(); // PhoneNumbers
					}
					
					$writer->writeEndElement(); // Contact
				}

				// 3-D Secure
				if ($this->m_3DSecureCardHolderEnrolled !== ThreeDSecureCardHolderEnrolled_Empty
					|| $this->m_3DSecureTransactionStatus !== ThreeDSecureTransactionStatus_Empty)
				{		
					$writer->writeStartElement("ThreeDSecure");
		
					if ($this->m_3DSecureCardHolderEnrolled !== ThreeDSecureCardHolderEnrolled_Empty)
					{
						$writer->writeElementString("CardHolderEnrolled",
							$this->m_3DSecureCardHolderEnrolled);
					}
		
					if ($this->m_3DSecureECI !== null)
					{
						$writer->writeElementString("ECI", $this->m_3DSecureECI);
					}
		
					if ($this->m_3DSecureIAV !== null)
					{
						$writer->writeStartElement("IAV");
		
						if ($this->m_3DSecureIAVAlgorithm !== null)
						{
							$writer->writeAttributeString("algorithm", $this->m_3DSecureIAVAlgorithm);
						}
		
						$writer->writeAttributeString("format", $this->m_3DSecureIAVFormat);		
						$writer->writeString($this->m_3DSecureIAV);
						$writer->writeEndElement(); // IAV
					}
		
					if ($this->m_3DSecureTransactionStatus !== ThreeDSecureTransactionStatus_Empty)
					{
						$writer->writeElementString("TransactionStatus", $this->m_3DSecureTransactionStatus);
					}
	
					if ($this->m_3DSecureXID !== null)
					{
						$writer->writeStartElement("XID");
						$writer->writeAttributeString("format", $this->m_3DSecureXIDFormat);		
						$writer->writeString($this->m_3DSecureXID);
						$writer->writeEndElement(); // XID
					}
		
					$writer->writeEndElement(); // ThreeDSecure
				}
				
				$writer->writeEndElement(); // CardDetails
			}
		}

		$writer->writeEndElement(); // Request
		$writer->writeEndDocument();
		return $writer->close();
	}

	/**
	 * Gets the 3-D Secure Card Holder Enrollment. This is required
	 * for authorisations in which the liability shift is possible due to
	 * the integration with a 3-D Secure MPI.
	 *
	 * @return The 3-D Secure Card Holder Enrollment.
	 * @see ThreeDSecureCardHolderEnrolled
	 * @see setThreeDSecureCardHolderEnrolled()
	 * @see getThreeDSecureECI()
	 * @see getThreeDSecureIAV()
	 * @see getThreeDSecureIAVAlgorithm()
	 * @see getThreeDSecureTransactionStatus()
	 * @see getThreeDSecureXID()
	 */
	function getThreeDSecureCardHolderEnrolled() {
		return $this->m_3DSecureCardHolderEnrolled;
	}

	/**
	 * Gets the 3-D Secure Electronic Commerce Indicator. This is required
	 * for authorisations in which a liability shift is possible due to the
	 * integration with a 3-D Secure MPI. It is a numeric string with a
	 * length of 2 characters.
	 *
	 * @return The 3-D Secure Electronic Commerce Indicator.
	 * @see setThreeDSecureECI()
	 * @see getThreeDSecureCardHolderEnrolled()
	 * @see getThreeDSecureIAV()
	 * @see getThreeDSecureIAVAlgorithm()
	 * @see getThreeDSecureTransactionStatus()
	 * @see getThreeDSecureXID()
	 */
	function getThreeDSecureECI() {
		return $this->m_3DSecureECI;
	}

	/**
	 * Gets the 3-D Secure Authentication Verification Value. This is
	 * required for authorisations in which the liability shift is possible
	 * due to the integration with a 3-D Secure MPI. It is an alphanumeric
	 * string with a maximum size of 32 characters.
	 * <p>
	 * With Verified by Visa this is called CAVV.
	 * <p>
	 * With MasterCard SecureCode this is called AAV.
	 *
	 * @return The 3-D Secure Authentication Verification Value.
	 * @see setThreeDSecureIAV()
	 * @see getThreeDSecureCardHolderEnrolled()
	 * @see getThreeDSecureECI()
	 * @see getThreeDSecureIAVAlgorithm()
	 * @see getThreeDSecureIAVFormat()
	 * @see getThreeDSecureTransactionStatus()
	 * @see getThreeDSecureXID()
	 */
	function getThreeDSecureIAV() {
		return $this->m_3DSecureIAV;
	}

	/**
	 * Gets the 3-D Secure Authentication Verification algorithm. This is
	 * required for authorisations in which the liability shift is possible
	 * due to the integration with a 3-D Secure MPI.
	 *
	 * @return The 3-D Secure Authentication Verification algorithm.
	 * @see getThreeDSecureCardHolderEnrolled()
	 * @see setThreeDSecureIAVAlgorithm()
	 * @see getThreeDSecureECI()
	 * @see getThreeDSecureIAV()
	 * @see getThreeDSecureIAVFormat()
	 * @see getThreeDSecureTransactionStatus()
	 * @see getThreeDSecureXID()
	 */
	function getThreeDSecureIAVAlgorithm() {
		return $this->m_3DSecureIAVAlgorithm;
	}

	/**
	 * Gets the 3-D Secure Authentication Verification format. This
	 * can be either Base64 or AsciiHex. The default is Base64.
	 *
	 * @return The 3-D Secure Authentication Verification format.
	 * @see setThreeDSecureIAVFormat()
	 * @see getThreeDSecureIAV()
	 */
	function getThreeDSecureIAVFormat() {
		return $this->m_3DSecureIAVFormat;
	}

	/**
	 * Gets the 3-D Secure Transaction Status. This is required for
	 * authorisations in which the liability shift is possible due to the
	 * integration with a 3-D Secure MPI.
	 *
	 * @return The 3-D Secure Transaction Status.
	 * @see ThreeDSecureTransactionStatus
	 * @see setThreeDSecureTransactionStatus()
	 * @see getThreeDSecureCardHolderEnrolled()
	 * @see getThreeDSecureECI()
	 * @see getThreeDSecureIAV()
	 * @see getThreeDSecureIAVAlgorithm()
	 * @see getThreeDSecureXID()
	 */
	function getThreeDSecureTransactionStatus() {
		return $this->m_3DSecureTransactionStatus;
	}

	/**
	 * Gets the 3-D Secure Transaction Identifier. This is required for
	 * authorisations in which the liability shift is possible due to the
	 * integration with a 3-D Secure MPI. It is an alphanumeric string
	 * with a maximum length of of 28 characters.
	 *
	 * @return The 3-D Secure Transaction Identifier.
	 * @see setThreeDSecureXID()
	 * @see getThreeDSecureCardHolderEnrolled()
	 * @see getThreeDSecureECI()
	 * @see getThreeDSecureIAV()
	 * @see getThreeDSecureIAVAlgorithm()
	 * @see getThreeDSecureTransactionStatus()
	 * @see getThreeDSecureXIDFormat()
	 */
	function getThreeDSecureXID() {
		return $this->m_3DSecureXID;
	}

	/**
	 * Gets the 3-D Secure Transaction Identifier format. This
	 * can be either Base64, Ascii or AsciiHex. The default is Ascii.
	 *
	 * @return The 3-D Secure Transaction Identifier format.
	 * @see setThreeDSecureXIDFormat()
	 * @see getThreeDSecureXID()
	 */
	function getThreeDSecureXIDFormat() {
		return $this->m_3DSecureXIDFormat;
	}

	/**
	 * Gets the address details associated with the card in this request.
	 * This can be used for additional verification of the card details
	 * with the issuer. The content of this is dependant upon the country
	 * in which authorisation is being performed. Typically it is the
	 * first line of the address where the card is registered. This is an
	 * alphanumeric string. It is optional.
	 * 
	 * @return The address details associated with the card in this
	 *	request. If null is returned the address has not been
	 *	specified.
	 * @see setAddress()
	 */
	function getAddress() {
		return $this->m_address;
	}

	/**
	 * Gets the amount associated with this request. This may be in major
	 * or minor units. For example 1.23 GBP (Major) == 123 GBP (Minor).
	 * The amount is mandatory for Auth and Offline requests.
	 * 
	 * @return The amount associated with this request. If null is
	 *	returned the amount has not been specified.
	 * @see setAmount()
	 * @see setAmount()
	 * @see AmountUnit
	 * @see getAmountUnit()
	 * @see setAmountUnit()
	 */
	function getAmount() {
		return $this->m_amount;
	}

	/**
	 * Gets the units in which the amount associated with this request is
	 * specified. This may be Major or Minor. For example 1.23 GBP (Major)
	 * == 123 GBP (Minor). The default is Minor.
	 * 
	 * @return The units in which the amount associated with this request
	 *	is specified. If null is returned the amount unit for this
	 *	request has not been specified.
	 * @see AmountUnit
	 * @see setAmountUnit()
	 * @see getAmount()
	 * @see setAmount()
	 * @see setAmount()
	 */
	function getAmountUnit() {
		return $this->m_amountUnit;
	}

	/**
	 * Gets whether an authorisation request is automatically confirmed
	 * without a confirmation request. By default is is false, a
	 * confirmation request will be required for this transaction.
	 * 
	 * @return Whether an authorisation request is automatically confirmed
	 *	without a confirmation request.
	 * @see setAutoConfirm()
	 */
	function getAutoConfirm() {
		return $this->m_autoConfirm;
	}
	
    /**
     * Gets the batch reference associated with this request.
     * <p>
     * This allows the user to attach a reference to a transaction
     * to help group similar transactions.
     *
     * @return The batch reference associated with this request.
     * @see setBatchReference()
     */
    function getBatchReference() {
        return $this->m_batchReference;
    }

	/**
	 * Gets the CardEase reference associated with this request. This is a
	 * unique reference that has been obtained from the CardEase platform
	 * during previous requests. This is an alphanumeric string with a
	 * fixed length of 36 characters. This is mandatory for Conf, Refund
	 * and Void requests.
	 * 
	 * @return The CardEaseXML reference associated with this request. If
	 *	null is returned the CardEase reference has not been specified.
	 * @see setCardEaseReference()
	 */
	function getCardEaseReference() {
		return $this->m_cardEaseReference;
	}

	/**
	 * Gets the version of CardEaseXML that the client supports. The
	 * default is to "1.0.0". This is mandatory for all requests.
	 * 
	 * @return The version of CardEaseXML that the client supports. If
	 *	null is returned the version has not been specified.
	 */
	function getCardEaseXMLVersion() {
		return $this->m_cardEaseXMLVersion;
	}

	/**
	 * Gets the card hash returned from a previous transaction that
	 * references the card details that should also be used for this
	 * transaction.
	 * <p>
	 * This is an alphanumeric string with a fixed length of 24 characters.
	 * Used in conjunction with the CardReference property. The benefit of
	 * being able to reference a previously used card is that an
	 * integrator need not store actual card details on their system for
	 * repeat transactions. This reduces the risk of card information
	 * being compromised, and reduces the integrators PCI requirements.
	 * 
	 * @return The card hash returned from a previous transaction that
	 *	references the card details that should also be used for this
	 *	transaction.
	 * @see setCardHash()
	 * @see getCardReference()
	 * @see setCardReference()
	 */
	function getCardHash() {
		return $this->m_cardHash;
	}

	/**
	 * Gets the card holder's address.
	 * @return The card holder's address.
	 */
	function getCardHolderAddress() {
		return $this->m_cardHolderAddress;
	}

	/**
	 * Gets the card holder's email addresses.
	 * @return The card holder's email addresses.
	 */
	function getCardHolderEmailAddresses() {
		return $this->m_cardHolderEmailAddresses;
	}

	/**
	 * Gets the card holder's name.
	 * @return The card holder's name.
	 */
	function getCardHolderName() {
		return $this->m_cardHolderName;
	}

	/**
	 * Gets the card holder's phone numbers.
	 * @return The card holder's phone numbers.
	 */
	function getCardHolderPhoneNumbers() {
		return $this->m_cardHolderPhoneNumbers;
	}

	/**
	 * Gets the card reference returned from a previous transaction that
	 * references the card details that should also be used for this
	 * transaction.
	 * <p>
	 * This is an alphanumeric string with a fixed length of 36 characters.
	 * Used in conjunction with the CardHash property. The benefit of being
	 * able to reference a previously used card is that an integrator need
	 * not store actual card details on their system for repeat
	 * transactions. This reduces the risk of card information being
	 * compromised, and reduces the integrators PCI requirements.
	 * 
	 * @return The card reference returned from a previous transaction
	 *	that references the card details that should also be used for
	 *	this transaction.
	 * @see setCardReference()
	 * @see getCardHash()
	 * @see setCardHash()
	 */
	function getCardReference() {
		return $this->m_cardReference;
	}

	/**
	 * Gets the security code associated with the card in this request.
	 * This can be used for additional verification with the issuer. This
	 * is also referred to as CVV, CVC and CV2. This is an numeric
	 * string with a minimum length of 3 characters and a maximum length of
	 * 4 characters. This is optional. If the CSC validation fails the
	 * authorisation is automatically declined.
	 * <p>
	 * On Visa and MasterCard this is the last three digits of the
	 * signature strip.
	 * <p>
	 * On Amex this is the four digits printed above the PAN.
	 * 
	 * @return The security code associated with the card in this request.
	 *	If null is returned the security code has not been specified.
	 * @see setCSC()
	 */
	function getCSC() {
		return $this->m_csc;
	}

	/**
	 * Gets the ISO currency code or mnemonic associated with this request
	 * amount. For example, GBP or USD. If this is not specified the
	 * currency code held against the terminal ID in the CardEase platform
	 * is assumed. This is an alphanumeric string with a fixed length of 3
	 * characters.
	 * <p>
	 * Recognised currency codes and mnemonics: <table border=1>
	 * <tr>
	 * <th>Currency Code</th>
	 * <th>Mnemonic</th>
	 * <th>Description</th>
	 * </tr>
	 * <tr>
	 * <td>826</td>
	 * <td>GBP</td>
	 * <td>United Kingdom, Pound</td>
	 * </tr>
	 * <tr>
	 * <td>840</td>
	 * <td>USD</td>
	 * <td>United States, Dollar</td>
	 * </tr>
	 * <tr>
	 * <td>978</td>
	 * <td>EUR</td>
	 * <td>European Euro</td>
	 * </tr>
	 * <tr>
	 * <td>124</td>
	 * <td>CAD</td>
	 * <td>Canada, Dollar</td>
	 * </tr>
	 * <tr>
	 * <td>392</td>
	 * <td>JPY</td>
	 * <td>Japan, Yen</td>
	 * </tr>
	 * <tr>
	 * <td>208</td>
	 * <td>DKK</td>
	 * <td>Denmark, Krone</td>
	 * </tr>
	 * <tr>
	 * <td>756</td>
	 * <td>CHF</td>
	 * <td>Switzerland, Franc</td>
	 * </tr>
	 * <tr>
	 * <td>752</td>
	 * <td>SEK</td>
	 * <td>Sweden, Krona</td>
	 * </tr>
	 * </table>
	 * 
	 * @return The ISO currency code or mnemonic associated with this
	 *	request amount. If null is returned the currency code has not
	 *	been specified.
	 * @see setCurrencyCode()
	 */
	function getCurrencyCode() {
		return $this->m_currencyCode;
	}

	/**
	 * Gets the delivery address.
	 * @return The delivery address.
	 */
	function getDeliveryAddress() {
		return $this->m_deliveryAddress;
	}

	/**
	 * Gets the delivery email addresses.
	 * @return The delivery email addresses.
	 */
	function getDeliveryEmailAddresses() {
		return $this->m_deliveryEmailAddresses;
	}

	/**
	 * Gets the delivery name.
	 * @return The delivery name.
	 */
	function getDeliveryName() {
		return $this->m_deliveryName;
	}

	/**
	 * Gets the delivery phone numbers.
	 * @return The delivery phone numbers.
	 */
	function getDeliveryPhoneNumbers() {
		return $this->m_deliveryPhoneNumbers;
	}

	/**
	 * Gets the expiry date associated with the card in this request. This
	 * is a character string with a maximum length of 10 characters.
	 * This is mandatory for manual authorisation requests (such as Card
	 * Not Present). This should match the expiry date format.
	 * 
	 * @return The expiry date associated with the card in this request.
	 *	If null is returned the expiry date has not been specified.
	 * @see setExpiryDate()
	 * @see getExpiryDateFormat()
	 * @see setExpiryDateFormat()
	 * @see getStartDate()
	 * @see setStartDate()
	 * @see getManualType()
	 * @see setManualType()
	 */
	function getExpiryDate() {
		return $this->m_expiryDate;
	}

	/**
	 * Gets the expiry date format associated with the card in this
	 * request. This is a character string with a maximum length of 10
	 * characters. This is mandatory for manual authorisation requests
	 * (such as Card Not Present). By default this is "yyMM". This should
	 * match the format of the expiry date and can include separators such
	 * as - and /. The available options are shown in the following table:
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
	 * @return The expiry date format associated with the card in this
	 *	request. If null is returned the expiry date has not been
	 *	specified.
	 * @see setExpiryDateFormat()
	 * @see getExpiryDate()
	 * @see setExpiryDate()
	 * @see getManualType()
	 * @see setManualType()
	 */
	function getExpiryDateFormat() {
		return $this->m_expiryDateFormat;
	}

	/**
	 * Gets the list of extended properties associated with this request.
	 * 
	 * @return The list of extended properties associated with this request.
	 * @see addExtendedProperty
	 * @see setExtendedProperties()
	 */
	function getExtendedProperties() {
		return $this->m_extendedProperties;
	}

	/**
	 * Gets whether an ICC fallback has occured. Default is false.
	 * 
	 * @return Whether an ICC fallback has occured.
	 * @see setICCFallback()
	 */
	function getICCFallback() {
		return $this->m_iccFallback;
	}

	/**
	 * Gets the ICC management function associated with an ICCManagement
	 * request. This must be set for an ICCManagement request. It is an
	 * alphanumeric string.
	 * 
	 * @return The ICC management function associated with an ICC
	 *	Management request. If null is returned no management function
	 *	has been specified.
	 * @see setICCManagementFunction()
	 * @see getRequestType()
	 * @see setRequestType()
	 */
	function getICCManagementFunction() {
		return $this->m_iccManagementFunction;
	}

	/**
	 * Gets the list of ICC tags associated with this request. Each ICC tag
	 * has an id, type and value. For example, a tag of
	 * 0x9f02/AsciiHex/000000000100 is using to specify the transaction
	 * amount. These are mandatory for an EMV transaction.
	 * 
	 * @return The list of ICC tags associated with this request. If null
	 *	is returned no ICC tags have been specified.
	 * @see ICCTag
	 * @see addICCTag()
	 * @see setICCTags()
	 * @see getICCType()
	 * @see setICCType()
	 */
	function getICCTags() {
		return $this->m_iccTags;
	}

	/**
	 * Gets the type of ICC transaction associated with this request. This
	 * is an alphanumeric string. This is mandatory for ICC authorisations
	 * and by default is "EMV". An ICC transaction must have associated
	 * ICC tags.
	 * 
	 * @return The type of ICC transaction associated with this request.
	 *	If null is returned no ICC transaction type has been specified.
	 * @see setICCType()
	 * @see ICCTag
	 * @see addICCTag()
	 * @see getICCTags()
	 * @see setICCTags()
	 */
	function getICCType() {
		return $this->m_iccType;
	}

	/**
	 * Gets the invoice address.
	 * @return The invoice address.
	 */
	function getInvoiceAddress() {
		return $this->m_invoiceAddress;
	}

	/**
	 * Gets the invoice email addresses.
	 * @return The invoice email addresses.
	 */
	function getInvoiceEmailAddresses() {
		return $this->m_invoiceEmailAddresses;
	}

	/**
	 * Gets the invoice name.
	 * @return The invoice name.
	 */
	function getInvoiceName() {
		return $this->m_invoiceName;
	}

	/**
	 * Gets the invoice phone numbers.
	 * @return The invoice phone numbers.
	 */
	function getInvoicePhoneNumbers() {
		return $this->m_invoicePhoneNumbers;
	}

	/**
	 * Gets the issue number associated with the card in this request.
	 * This is a numeric string with a maximum length of 2 characters. The
	 * requirement for this is dependant upon the card scheme associated
	 * with the card and must be exactly as found on the card (including
	 * any leading 0's).
	 * 
	 * @return The issue number associated with the card in this request.
	 *	If null is returned no issue number has been specified.
	 * @see setIssueNumber()
	 */
	function getIssueNumber() {
		return $this->m_issueNumber;
	}

	/**
	 * Gets the machine reference associated with this request. This is
	 * mandatory if the TerminalID is a Master Terminal ID used to
	 * represent multiple terminals. This is an alphanumeric string with a
	 * maximum length of 50 characters.
	 * 
	 * @return The machine reference associated with this request. If null
	 *	is returned no machine reference has been specified.
	 * @see setMachineReference()
	 * @see getTerminalID()
	 * @see setTerminalID()
	 */
	function getMachineReference() {
		return $this->m_machineReference;
	}

	/**
	 * Gets the type of manual authorisation being used for this request.
	 * By default this is "cnp" (i.e. Card Not Present). This is an
	 * alphanumeric string. This is mandatory for manual authorisations.
	 * 
	 * @return The type of manual authorisation being used for this
	 *	request. If null is returned no manual authorisation type has
	 *	been specified.
	 * @see setManualType()
	 */
	function getManualType() {
		return $this->m_manualType;
	}

	/**
	 * Gets the date and/or time when the transaction was processed offline.
	 * @return The date and/or time when the transaction was processed offline.
	 */
	function getOfflineDateTime() {
		return $this->m_offlineDateTime;
	}

	/**
	 * Gets the format of the date and/or time of the transaction if processed offline.
	 * <p>
	 * This is a character string with a maximum length of 16 characters.
     * By default this is "ddMMyyy hhmmss". This should match the format of the
     * offline date/time and can include separators such as - and /. The available
     * options are shown in the following table:
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
	 * <td>hh</td>
	 * <td>Hour</td>
	 * <td>12</td>
	 * </tr>
	 * <tr>
	 * <td>mm</td>
	 * <td>Minute</td>
	 * <td>54</td>
	 * </tr>
	 * <tr>
	 * <td>ss</td>
	 * <td>Second</td>
	 * <td>22</td>
	 * </tr>
	 * </table>
	 * 
	 * @return The format of the date and/or time of the transaction if processed offline.
	 * @see setOfflineDateTimeFormat()
	 * @see getOfflineDateTime()
	 * @see setOfflineDateTime()
	 */
	function getOfflineDateTimeFormat() {
		return $this->m_offlineDateTimeFormat;
	}

	/**
	 * Gets the originating IP address of the request.  E.g. client browser.
	 * 
	 * @return The originating IP address of the request.  If null is returned no
	 * IP address has been specified.
	 * @see setOriginatingIPAddress()
	 */
	function getOriginatingIPAddress() {
		return $this->m_originatingIPAddress;
	}

	/**
	 * Gets the PAN (Primary Account Number) associated with the card in
	 * this request. This is a numeric string with a minimum length of 13
	 * characters and a maximum length of 19 characters. This is a
	 * requirement for manual authorisation requests (such as Card Not
	 * Present).
	 * 
	 * @return The PAN (Primary Account Number) associated with the card in
	 *	this request. If null is returned no PAN has been specified.
	 * @see setPAN()
	 */
	function getPAN() {
		return $this->m_pan;
	}

	/**
	 * Gets the list of products.
	 * @return The list of products.
	 */
	function getProducts() {
		return $this->m_products;
	}

	/**
	 * Gets the type of this request. This can be Auth, Conf, Test and so
	 * on. By default this is "Auth". This is mandatory for all requests.
	 * 
	 * @return The type of this request. If null is returned no request
	 *	type has been specified.
	 * @see setRequestType()
	 * @see RequestType
	 */
	function getRequestType() {
		return $this->m_requestType;
	}

	/**
	 * Gets the name of the software/firmware using the CardEaseXML SDK.
	 * This is an alphanumeric string with a maximum length of 50
	 * characters. This is mandatory for all requests.
	 * 
	 * @return The name of the software/firmware using the CardEaseXML
	 *	SDK. If null is returned no software name has been specified.
	 * @see setSoftwareName()
	 */
	function getSoftwareName() {
		return $this->m_softwareName;
	}

	/**
	 * Gets the version of the software/firmware using the CardEaseXML SDK.
	 * This is an alphanumeric string with a maximum length of 20
	 * characters. This is mandatory for all requests.
	 * 
	 * @return The version of the software/firmware using the CardEaseXML
	 *	SDK. If null is returned no software version has been specified.
	 * @see setSoftwareVersion()
	 */
	function getSoftwareVersion() {
		return $this->m_softwareVersion;
	}

	/**
	 * Gets the start date associated with the card in this request. This
	 * is a character string with a maximum length of 10 characters.
	 * This is optional for manual authorisation requests (such as Card Not
	 * Present). This should match the start date format.
	 * 
	 * @return The start date associated with the card in this request. If
	 *	null is returned no start date has been specified.
	 * @see setStartDate()
	 * @see getStartDateFormat()
	 * @see setStartDateFormat()
	 * @see getExpiryDate()
	 * @see setExpiryDate()
	 * @see getManualType()
	 * @see setManualType()
	 */
	function getStartDate() {
		return $this->m_startDate;
	}

	/**
	 * Gets the start date format associated with the card in this request.
	 * This is a character string with a maximum length of 10
	 * characters. This is optional for manual authorisation requests
	 * (such as Card Not Present). By default this is "yyMM". This should
	 * match the format of the start date and can include separators such
	 * as - and /. The available options are shown in the following table:
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
	 * @return The start date format associated with the card in this
	 *	request. If null is returned no start date format has been
	 *	specified.
	 * @see setStartDateFormat()
	 * @see getStartDate()
	 * @see setStartDate()
	 * @see getManualType()
	 * @see setManualType()
	 */
	function getStartDateFormat() {
		return $this->m_startDateFormat;
	}

	/**
	 * Gets the terminal ID associated with the machine performing this
	 * request. This is mandatory for all requests and is supplied by
	 * CreditCall Communications. It is unique across all CardEase
	 * products. It is a numeric string with a fixed length of 8
	 * characters.
	 * 
	 * @return The terminal ID associated with the machine performing this
	 *	request. If null is returned no terminal ID has been specified.
	 * @see setTerminalID()
	 */
	function getTerminalID() {
		return $this->m_terminalID;
	}

	/**
	 * Gets the track 1 associated with the card in a magnetic stripe
	 * authorisation. This is an alphanumeric string with a maximum length
	 * of 79 characters. This is optional.
	 * 
	 * @return The track 1 associated with the card in a magnetic stripe
	 *	authorisation. If null is returned no track 1 has been
	 *	specified.
	 * @see setTrack1()
	 */
	function getTrack1() {
		return $this->m_track1;
	}

	/**
	 * Gets the track 2 associated with the card in a magnetic stripe
	 * authorisation. This is an alphanumeric string with a maximum length
	 * of 40 characters. This should include start and end sentinels (and
	 * separator character if provided). It is mandatory for magnetic
	 * stripe authorisation.
	 * 
	 * @return The track 2 associated with the card in a magnetic stripe
	 *	authorisation. If null is returned no track 2 has been
	 *	specified.
	 * @see setTrack2()
	 */
	function getTrack2() {
		return $this->m_track2;
	}

	/**
	 * Gets the track 3 associated with the card in a magnetic stripe
	 * authorisation. This is an alphanumeric string with a maximum length
	 * of 107 characters. This is optional.
	 * 
	 * @return The track 3 associated with the card in a magnetic stripe
	 *	authorisation. If null is returned no track 3 has been
	 *	specified.
	 * @see setTrack3()
	 */
	function getTrack3() {
		return $this->m_track3;
	}

	/**
	 * Gets the transaction key associated with this request. This is
	 * mandatory for all requests and is supplied by CreditCall
	 * Communications for a terminal or number of terminals. It must be in
	 * exactly the same case as provided by CreditCall. This is an
	 * alphanumeric string with a maximum length of 20 characters.
	 * 
	 * @return The transaction key associated with this request. If null is
	 *	returned no transaction key has been specified.
	 * @see setTransactionKey()
	 */
	function getTransactionKey() {
		return $this->m_transactionKey;
	}

	/**
	 * Gets the user reference associated with this request. This allows a
	 * user to attach their own reference against a request. This is an
	 * alphanumeric string with a maximum length of 50 characters. This is
	 * optional for all requests.
	 * 
	 * @return The user reference associated with this request. If null is
	 *	returned no user reference has been specified.
	 * @see setUserReference()
	 */
	function getUserReference() {
		return $this->m_userReference;
	}

	/**
	 * Gets the reason for which a void request is being made. This is
	 * mandatory for Void requests.
	 * 
	 * @return The reason for which a void request is being made. If null
	 *	is returned no void reason has been specified.
	 * @see VoidReason
	 * @see setVoidReason()
	 */
	function getVoidReason() {
		return $this->m_voidReason;
	}

	/**
	 * Gets the zip code/post code details associated with the card in this
	 * request. This can be used for additional verification with the
	 * issuer. The content of this is dependant upon the country in which
	 * authorisation is being performed. This is an alphanumeric string. It
	 * is optional.
	 * 
	 * @return The zip code/post code details associated with the card in
	 *	this request. If null is returned no zip code/post code has
	 *	been specified.
	 * @see setZipCode()
	 */
	function getZipCode() {
		return $this->m_zipCode;
	}

	/**
	 * Sets the 3-D Secure Card Holder Enrollment. This is required
	 * for authorisations in which the liability shift is possible due to
	 * the integration with a 3-D Secure MPI.
	 *
	 * @param enrolled
	 *	The 3-D Secure Card Holder Enrollment.
	 * @see ThreeDSecureCardHolderEnrolled
	 * @see getThreeDSecureCardHolderEnrolled()
	 * @see setThreeDSecureECI()
	 * @see setThreeDSecureIAV()
	 * @see setThreeDSecureIAVAlgorithm()
	 * @see setThreeDSecureTransactionStatus()
	 * @see setThreeDSecureXID()
	 */
	function setThreeDSecureCardHolderEnrolled($enrolled) {
		$this->m_3DSecureCardHolderEnrolled = $enrolled;
	}

	/**
	 * Sets the 3-D Secure Electronic Commerce Indicator. This is required
	 * for authorisations in which a liability shift is possible due to the
	 * integration with a 3-D Secure MPI. It is a numeric string with a
	 * length of 2 characters.
	 *
	 * @param eci
	 *	The 3-D Secure Electronic Commerce Indicator.
	 *
	 * @see getThreeDSecureECI()
	 * @see setThreeDSecureCardHolderEnrolled()
	 * @see setThreeDSecureIAV()
	 * @see setThreeDSecureIAVAlgorithm()
	 * @see setThreeDSecureTransactionStatus()
	 * @see setThreeDSecureXID()
	 */
	function setThreeDSecureECI($eci) {
		$this->m_3DSecureECI = $eci;
	}

	/**
	 * Sets the 3-D Secure Authentication Verification Value. This is
	 * required for authorisations in which the liability shift is possible
	 * due to the integration with a 3-D Secure MPI. It is an alphanumeric
	 * string with a maximum size of 32 characters.
	 * <p>
	 * With Verified by Visa this is called CAVV.
	 * <p>
	 * With MasterCard SecureCode this is called AAV.
	 *
	 * @param iav
	 *	The 3-D Secure Authentication Verification Value.
	 *
	 * @see getThreeDSecureIAV()
	 * @see setThreeDSecureCardHolderEnrolled()
	 * @see setThreeDSecureECI()
	 * @see setThreeDSecureIAVAlgorithm()
	 * @see setThreeDSecureIAVFormat()
	 * @see setThreeDSecureTransactionStatus()
	 * @see setThreeDSecureXID()
	 */
	function setThreeDSecureIAV($iav) {
		$this->m_3DSecureIAV = $iav;
	}

	/**
	 * Sets the 3-D Secure Authentication Verification algorithm. This is
	 * required for authorisations in which the liability shift is possible
	 * due to the integration with a 3-D Secure MPI.
	 *
	 * @param iavAlgorithm
	 *	The 3-D Secure Authentication Verification algorithm.
	 *
	 * @see getThreeDSecureIAVAlgorithm()
	 * @see setThreeDSecureCardHolderEnrolled()
	 * @see setThreeDSecureECI()
	 * @see setThreeDSecureIAV()
	 * @see setThreeDSecureIAVFormat()
	 * @see setThreeDSecureTransactionStatus()
	 * @see setThreeDSecureXID()
	 */
	function setThreeDSecureIAVAlgorithm($iavAlgorithm) {
		$this->m_3DSecureIAVAlgorithm = $iavAlgorithm;
	}

	/**
	 * Sets the 3-D Secure Authentication Verification format. This
	 * can be either Base64 or AsciiHex. The default is Base64.
	 *
	 * @param format
	 *	The 3-D Secure Authentication Verification format.
	 * @see getThreeDSecureIAVFormat()
	 * @see setThreeDSecureIAV()
	 */
	function setThreeDSecureIAVFormat($format) {
		$this->m_3DSecureIAVFormat = $format;
	}

	/**
	 * Sets the 3-D Secure Transaction Status. This is required for
	 * authorisations in which the liability shift is possible due to the
	 * integration with a 3-D Secure MPI.
	 *
	 * @param status
	 *	The 3-D Secure Transaction Status.
	 *
	 * @see ThreeDSecureTransactionStatus
	 * @see getThreeDSecureTransactionStatus()
	 * @see setThreeDSecureCardHolderEnrolled()
	 * @see setThreeDSecureECI()
	 * @see setThreeDSecureIAV()
	 * @see setThreeDSecureIAVAlgorithm()
	 * @see setThreeDSecureXID()
	 */
	function setThreeDSecureTransactionStatus($status) {
		$this->m_3DSecureTransactionStatus = $status;
	}

	/**
	 * Sets the 3-D Secure Transaction Identifier. This is required for
	 * authorisations in which the liability shift is possible due to the
	 * integration with a 3-D Secure MPI. It is an alphanumeric string
	 * with a maximum length of of 28 characters.
	 *
	 * @param xid
	 *	The 3-D Secure Transaction Identifier.
	 *
	 * @see getThreeDSecureXID()
	 * @see setThreeDSecureCardHolderEnrolled()
	 * @see setThreeDSecureECI()
	 * @see setThreeDSecureIAV()
	 * @see setThreeDSecureIAVAlgorithm()
	 * @see setThreeDSecureTransactionStatus()
	 * @see setThreeDSecureXIDFormat()
	 */
	function setThreeDSecureXID($xid) {
		$this->m_3DSecureXID = $xid;
	}

	/**
	 * Sets the 3-D Secure Transaction Identifier format. This
	 * can be either Base64, Ascii or AsciiHex. The default is Ascii.
	 *
	 * @param format
	 *	The 3-D Secure Transaction Identifier format.
	 * @see getThreeDSecureXIDFormat()
	 * @see setThreeDSecureXID()
	 */
	function setThreeDSecureXIDFormat($format) {
		$this->m_3DSecureXIDFormat = $format;
	}

	/**
	 * Sets the address details associated with the card in this request.
	 * This can be used for additional verification of the card details
	 * with the issuer. The content of this is dependant upon the country
	 * in which authorisation is being performed. Typically it is the
	 * first line of the address where the card is registered. This is an
	 * alphanumeric string. It is optional.
	 * 
	 * @param address
	 *	The address details associated with the card in this request.
	 *	If this is null the address is removed.
	 * @see getAddress()
	 */
	function setAddress($address) {
		$this->m_address = $address;
	}

	/**
	 * Sets the amount associated with this request. This may be in major
	 * or minor units. For example 1.23 GBP (Major) == 123 GBP (Minor).
	 * The amount is mandatory for Auth and Offline requests.
	 * 
	 * @param amount
	 *	The amount associated with this request.
	 * @see getAmount()
	 * @see setAmount()
	 */
	function setAmount($amount) {
		$this->m_amount = $amount;
	}

	/**
	 * Sets the units in which the amount associated with this request is
	 * specified. This may be Major or Minor. For example 1.23 GBP (Major)
	 * == 123 GBP (Minor). The default is Minor.
	 * 
	 * @param amountUnit
	 *	The units in which the amount associated with this request is
	 *	specified. If this is null the amount unit is removed.
	 * @see AmountUnit
	 * @see getAmountUnit()
	 */
	function setAmountUnit($amountUnit) {
		$this->m_amountUnit = $amountUnit;
	}

	/**
	 * Sets whether an authorisation request is automatically confirmed
	 * without a confirmation request. By default is is false, a
	 * confirmation request will be required for this transaction.
	 * 
	 * @param autoConfirm
	 *	Whether an authorisation request is automatically confirmed
	 *	without a confirmation request.
	 * @see getAutoConfirm()
	 */
	function setAutoConfirm($autoConfirm) {
		$this->m_autoConfirm = $autoConfirm;
	}

    /**
     * Sets the batch reference associated with this request.
     * <p>
     * This allows the user to attach a reference to a transaction
     * to help group similar transactions.
     *
     * @param batchReference The batch reference associated with this request.
     * @see getBatchReference()
     */
    function setBatchReference($batchReference) {
        $this->m_batchReference = $batchReference;
    }

	/**
	 * Sets the CardEase reference associated with this request. This is a
	 * unique reference that has been obtained from the CardEase platform
	 * during previous requests. This is an alphaumeric string with a fixed
	 * length of 36 characters. This is mandatory for Conf, Refund and Void
	 * requests.
	 * 
	 * @param cardEaseReference
	 *	The CardEaseXML reference associated with this request. If
	 *	this is null the CardEase reference is removed.
	 * @see getCardEaseReference()
	 */
	function setCardEaseReference($cardEaseReference) {
		$this->m_cardEaseReference = $cardEaseReference;
	}

	/**
	 * Sets the card hash returned from a previous transaction that
	 * references the card details that should also be used for this
	 * transaction.
	 * <p>
	 * This is an alphanumeric string with a fixed length of 24 characters.
	 * Used in conjunction with the CardReference property. The benefit of
	 * being able to reference a previously used card is that an integrator
	 * need not store actual card details on their system for repeat
	 * transactions. This reduces the risk of card information being
	 * compromised, and reduces the integrators PCI requirements.
	 * 
	 * @param cardHash The card hash returned from a previous transaction
	 *	that references the card details that should also be used for
	 *	this transaction.
	 * @see getCardHash()
	 * @see getCardReference()
	 * @see setCardReference()
	 */
	function setCardHash($cardHash) {
		$this->m_cardHash = $cardHash;
	}

	/**
	 * Sets the card holder's address.
	 * @param cardHolderAddress The card holder's address.
	 */
	function setCardHolderAddress($cardHolderAddress) {
		$this->m_cardHolderAddress = $cardHolderAddress;
	}

	/**
	 * Sets the card holder's email address.
	 * @param cardHolderEmailAddresses The card holder's email addresses.
	 */
	function setCardHolderEmailAddresses($cardHolderEmailAddresses) {
		$this->m_cardHolderEmailAddresses = $cardHolderEmailAddresses;
	}

	/**
	 * Sets the card holder's name.
	 * @param cardHolderName The card holder's name.
	 */
	function setCardHolderName($cardHolderName) {
		$this->m_cardHolderName = $cardHolderName;
	}

	/**
	 * Sets the card holder's phone numbers.
	 * @param cardHolderPhoneNumbers The card holder's phone numbers.
	 */
	function setCardHolderPhoneNumbers($cardHolderPhoneNumbers) {
		$this->m_cardHolderPhoneNumbers = $cardHolderPhoneNumbers;
	}

	/**
	 * Gets the card reference returned from a previous transaction that
	 * references the card details that should also be used for this
	 * transaction.
	 * <p>
	 * This is an alphanumeric string with a fixed length of 36 characters.
	 * Used in conjunction with the CardHash property. The benefit of being
	 * able to reference a previously used card is that an integrator need
	 * not store actual card details on their system for repeat
	 * transactions. This reduces the risk of card information being
	 * compromised, and reduces the integrators PCI requirements.
	 * 
	 * @param cardReference The card reference returned from a previous
	 *	transaction that references the card details that should also
	 *	be used for this transaction.
	 * @see getCardReference()
	 * @see getCardHash()
	 * @see setCardHash()
	 */
	function setCardReference($cardReference) {
		$this->m_cardReference = $cardReference;
	}

	/**
	 * Sets the security code associated with the card in this request.
	 * This can be used for additional verification with the issuer. This
	 * is also referred to as CVV, CVC and CV2. This is an numeric
	 * string with a minimum length of 3 characters and a maximum length
	 * of 4 characters. This is optional. If the CSC validation fails the
	 * authorisation is automatically declined.
	 * <p>
	 * On Visa and MasterCard this is the last three digits of the
	 * signature strip.
	 * <p>
	 * On Amex this is the four digits printed above the PAN.
	 * 
	 * @param csc
	 *	The security code associated with the card in this request. If
	 *	this is null the security code is removed.
	 * @see getCSC()
	 */
	function setCSC($csc) {
		$this->m_csc = $csc;
	}

	/**
	 * Sets the ISO currency code or mnemonic associated with this request
	 * amount. For example, GBP or USD. If this is not specified the
	 * currency code held against the terminal ID in the CardEase platform
	 * is assumed. This is an alphanumeric string with a fixed length of 3
	 * characters.
	 * <p>
	 * Recognised currency codes and mnemonics: <table border=1>
	 * <tr>
	 * <th>Currency Code</th>
	 * <th>Mnemonic</th>
	 * <th>Description</th>
	 * </tr>
	 * <tr>
	 * <td>826</td>
	 * <td>GBP</td>
	 * <td>United Kingdom, Pound</td>
	 * </tr>
	 * <tr>
	 * <td>840</td>
	 * <td>USD</td>
	 * <td>United States, Dollar</td>
	 * </tr>
	 * <tr>
	 * <td>978</td>
	 * <td>EUR</td>
	 * <td>European Euro</td>
	 * </tr>
	 * <tr>
	 * <td>124</td>
	 * <td>CAD</td>
	 * <td>Canada, Dollar</td>
	 * </tr>
	 * <tr>
	 * <td>392</td>
	 * <td>JPY</td>
	 * <td>Japan, Yen</td>
	 * </tr>
	 * <tr>
	 * <td>208</td>
	 * <td>DKK</td>
	 * <td>Denmark, Krone</td>
	 * </tr>
	 * <tr>
	 * <td>756</td>
	 * <td>CHF</td>
	 * <td>Switzerland, Franc</td>
	 * </tr>
	 * <tr>
	 * <td>752</td>
	 * <td>SEK</td>
	 * <td>Sweden, Krona</td>
	 * </tr>
	 * </table>
	 * 
	 * @param currencyCode
	 *	The ISO currency code or mnemonic associated with this request
	 *	amount. If this is null the currency code is removed.
	 * 
	 * @see getCurrencyCode()
	 */
	function setCurrencyCode($currencyCode) {
		$this->m_currencyCode = $currencyCode;
	}

	/**
	 * Sets the delivery address.
	 * @param deliveryAddress The delivery address.
	 */
	function setDeliveryAddress($deliveryAddress) {
		$this->m_deliveryAddress = $deliveryAddress;
	}

	/**
	 * Sets the delivery email addresses.
	 * @param deliveryEmailAddresses The delivery email addresses.
	 */
	function setDeliveryEmailAddresses($deliveryEmailAddresses) {
		$this->m_deliveryEmailAddresses = $deliveryEmailAddresses;
	}

	/**
	 * Sets the delivery name.
	 * @param deliveryName The delivery name.
	 */
	function setDeliveryName($deliveryName) {
		$this->m_deliveryName = $deliveryName;
	}
	
	/**
	 * Sets the delivery phone numbers.
	 * The delivery phone numbers.
	 */
	function setDeliveryPhoneNumbers($deliveryPhoneNumbers) {
		$this->m_deliveryPhoneNumbers = $deliveryPhoneNumbers;
	}

	/**
	 * Sets the expiry date associated with the card in this request. This
	 * is a character string with a maximum length of 10 characters.
	 * This is mandatory for manual authorisation requests (such as Card
	 * Not Present). This should match the expiry date format.
	 * 
	 * @param expiryDate
	 *	The expiry date associated with the card in this request. If
	 *	this is null the expiry date is removed.
	 * @see getExpiryDate()
	 * @see getExpiryDateFormat()
	 * @see setExpiryDateFormat()
	 * @see getManualType()
	 * @see setManualType()
	 */
	function setExpiryDate($expiryDate) {
		$this->m_expiryDate = $expiryDate;
	}

	/**
	 * Sets the expiry date format associated with the card in this
	 * request. This is a character string with a maximum length of 10
	 * characters. This is mandatory for manual authorisation requests
	 * (such as Card Not Present). By default this is "yyMM". This should
	 * match the format of the expiry date and can include separators such
	 * as - and /. The available options are shown in the following table:
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
	 * @param expiryDateFormat
	 *	The expiry date format associated with the card in this
	 *	request. If this is null the expiry date format is removed.
	 * @see getExpiryDateFormat()
	 * @see getExpiryDate()
	 * @see setExpiryDate()
	 */
	function setExpiryDateFormat($expiryDateFormat) {
		$this->m_expiryDateFormat = $expiryDateFormat;
	}

	/**
	 * Sets the list of extended properties associated with this request.
	 * 
	 * @param extendedProperties The list of extended properties associated with this request.
	 * @see addExtendedProperty
	 * @see getExtendedProperties()
	 */
	function setExtendedProperties($extendedProperties) {
		$this->m_extendedProperties = $extendedProperties;
	}

	/**
	 * Sets whether an ICC fallback has occured. Default is false.
	 * 
	 * @param iccFallback
	 *	Whether an ICC fallback has occured.
	 * @see getICCFallback()
	 */
	function setICCFallback($iccFallback) {
		$this->m_iccFallback = $iccFallback;
	}

	/**
	 * Sets the ICC management function associated with an ICCManagement
	 * request. This must be set for an ICCManagement request. It is an
	 * alphanumeric string.
	 * 
	 * @param iccManagementFunction
	 *	The ICC management function associated with an ICC Management
	 *	request. If this is null the ICC management function is
	 *	removed.
	 * @see getICCManagementFunction()
	 * @see getRequestType()
	 * @see setRequestType()
	 */
	function setICCManagementFunction($iccManagementFunction) {
		$this->m_iccManagementFunction = $iccManagementFunction;
	}

	/**
	 * Sets the list of ICC tags associated with this request. Each ICC tag
	 * has an id, type and value. For example, a tag of
	 * 0x9f02/AsciiHex/000000000100 is using to specify the transaction
	 * amount. These are mandatory for an EMV transaction.
	 * 
	 * @param iccTags
	 *	The list of ICC tags associated with this request. If this is
	 *	null the list of ICC tags is removed.
	 * @see ICCTag
	 * @see addICCTag()
	 * @see getICCTags()
	 * @see getICCType()
	 * @see setICCType()
	 */
	function setICCTags($iccTags) {
		$this->m_iccTags = $iccTags;
	}

	/**
	 * Sets the type of ICC transaction associated with this request. This
	 * is an alphanumeric string. This is mandatory for ICC authorisations
	 * and by default is "EMV". An EMV transaction must have associated
	 * ICC tags.
	 * 
	 * @param iccType
	 *	The type of ICC transaction associated with this request. If
	 *	this is null the ICC type is removed.
	 * @see getICCType()
	 * @see ICCTag
	 * @see addICCTag()
	 * @see getICCTags()
	 * @see setICCTags()
	 */
	function setICCType($iccType) {
		$this->m_iccType = $iccType;
	}

	/**
	 * Sets the invoice address.
	 * @param invoiceAddress The invoice address.
	 */
	function setInvoiceAddress($invoiceAddress) {
		$this->m_invoiceAddress = $invoiceAddress;
	}

	/**
	 * Sets the invoice email addresses.
	 * @param invoiceEmailAddresses The invoice email addresses.
	 */
	function setInvoiceEmailAddresses($invoiceEmailAddresses) {
		$this->m_invoiceEmailAddresses = $invoiceEmailAddresses;
	}

	/**
	 * Sets the invoice name.
	 * @param invoiceName The invoice name.
	 */
	function setInvoiceName($invoiceName) {
		$this->m_invoiceName = $invoiceName;
	}

	/**
	 * Sets the invoice phone numbers.
	 * @param invoicePhoneNumbers The invoice phone numbers.
	 */
	function setInvoicePhoneNumbers($invoicePhoneNumbers) {
		$this->m_invoicePhoneNumbers = $invoicePhoneNumbers;
	}

	/**
	 * Sets the issue number associated with the card in this request.
	 * This is a numeric string with a maximum length of 2 characters. The
	 * requirement for this is dependant upon the card scheme associated
	 * with the card and must be exactly as found on the card (including
	 * any leading 0's).
	 * 
	 * @param issueNumber
	 *	The issue number associated with the card in this request. If
	 *	this is null the issue number is removed.
	 * @see getIssueNumber()
	 */
	function setIssueNumber($issueNumber) {
		$this->m_issueNumber = $issueNumber;
	}

	/**
	 * Sets the machine reference associated with this request. This is
	 * mandatory if the TerminalID is a Master Terminal ID used to
	 * represent multiple terminals. This is an alphanumeric string with a
	 * maximum length of 50 characters.
	 * 
	 * @param machineReference
	 *	The machine reference associated with this request. If this is
	 *	null the machine reference is removed.
	 * @see getMachineReference()
	 * @see getTerminalID()
	 * @see setTerminalID()
	 */
	function setMachineReference($machineReference) {
		$this->m_machineReference = $machineReference;
	}

	/**
	 * Sets the type of manual authorisation being used for this request.
	 * By default this is "cnp" (i.e. Card Not Present). This is an
	 * alphanumeric string. This is mandatory for manual authorisations.
	 * 
	 * @param manualType
	 *	The type of manual authorisation being used for this request.
	 *	If this is null the manual type is removed.
	 * @see getManualType()
	 */
	function setManualType($manualType) {
		$this->m_manualType = $manualType;
	}
	
	/**
	 * Sets the date and/or time when the transaction was processed offline.
	 * @param offlineDateTime The date and/or time when the transaction was processed offline.
	 */
	function setOfflineDateTime($offlineDateTime) {
		$this->m_offlineDateTime = $offlineDateTime;
	}

	/**
	 * Sets the format of the date and/or time of the transaction if processed offline.
	 * <p>
	 * This is a character string with a maximum length of 16 characters.
     * By default this is "ddMMyyy hhmmss". This should match the format of the
     * offline date/time and can include separators such as - and /. The available
     * options are shown in the following table:
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
	 * <td>hh</td>
	 * <td>Hour</td>
	 * <td>12</td>
	 * </tr>
	 * <tr>
	 * <td>mm</td>
	 * <td>Minute</td>
	 * <td>54</td>
	 * </tr>
	 * <tr>
	 * <td>ss</td>
	 * <td>Second</td>
	 * <td>22</td>
	 * </tr>
	 * </table>
	 * 
	 * @param offlineDateTimeFormat The format of the date and/or time of the transaction if processed offline.
	 * @see getOfflineDateTimeFormat()
	 * @see getOfflineDateTime()
	 * @see setOfflineDateTime()
	 */
	function setOfflineDateTimeFormat($offlineDateTimeFormat) {
		$this->m_offlineDateTimeFormat = $offlineDateTimeFormat;
	}

	/**
	 * Gets the originating IP address of the request.  E.g. client browser.
	 * 
	 * @param $originatingIPAddress The originating IP address of the request.
	 * If this is null the originating IP address is removed.
	 * @see getOriginatingIPAddress()
	 */
	function setOriginatingIPAddress($originatingIPAddress) {
		$this->m_originatingIPAddress = $originatingIPAddress;
	}

	/**
	 * Sets the PAN (Primary Account Number) associated with the card in
	 * this request. This is a numeric string with a minimum length of 13
	 * characters and a maximum length of 19 characters. This is a
	 * requirement for manual authorisation requests (such as Card Not
	 * Present).
	 * 
	 * @param pan
	 *	The PAN (Primary Account Number) associated with the card in
	 *	this request. If this is null the pan is removed.
	 * @see getPAN()
	 */
	function setPAN($pan) {
		$this->m_pan = $pan;
	}

	/**
	 * Sets the list of products.
	 * @param products The list of products.
	 */
	function setProducts($products) {
		$this->m_products = $products;
	}

	/**
	 * Sets the type of this request. This can be Auth, Conf, Test and so
	 * on. By default this is "Auth". This is mandatory for all requests.
	 * 
	 * @param requestType
	 *	The type of this request. If this is null the request type is
	 *	removed.
	 * @see getRequestType()
	 * @see RequestType
	 */
	function setRequestType($requestType) {
		$this->m_requestType = $requestType;
	}

	/**
	 * Sets the name of the software/firmware using the CardEaseXML SDK.
	 * This is an alphanumeric string with a maximum length of 50
	 * characters. This is mandatory for all requests.
	 * 
	 * @param softwareName
	 *	The name of the software/firmware using the CardEaseXML SDK.
	 *	If this is null the software/firmware name is removed.
	 * @see getSoftwareName()
	 */
	function setSoftwareName($softwareName) {
		$this->m_softwareName = $softwareName;
	}

	/**
	 * Sets the version of the software/firmware using the CardEaseXML SDK.
	 * This is an alphanumeric string with a maximum length of 20
	 * characters. This is mandatory for all requests.
	 * 
	 * @param softwareVersion
	 *	The version of the software/firmware using the CardEaseXML
	 *	SDK. If this is null the software/firmware version is removed.
	 * @see getSoftwareVersion()
	 */
	function setSoftwareVersion($softwareVersion) {
		$this->m_softwareVersion = $softwareVersion;
	}

	/**
	 * Sets the start date associated with the card in this request. This
	 * is a character string with a maximum length of 10 characters.
	 * This is optional for manual authorisation requests (such as Card
	 * Not Present). This should match the start date format.
	 * 
	 * @param startDate
	 *	The start date associated with the card in this request. If
	 *	this is null the start date is removed.
	 * @see getStartDate()
	 * @see getStartDateFormat()
	 * @see setStartDateFormat()
	 * @see getManualType()
	 * @see setManualType()
	 */
	function setStartDate($startDate) {
		$this->m_startDate = $startDate;
	}

	/**
	 * Sets the start date format associated with the card in this request.
	 * This is a character string with a maximum length of 10
	 * characters. This is optional for manual authorisation requests
	 * (such as Card Not Present). By default this is "yyMM". This should
	 * match the format of the start date and can include separators such
 	 * as - and /. The available options are shown in the following table:
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
	 * @param startDateFormat
	 *	The start date format associated with the card in this
	 *	request. If this is null the start date format is removed.
	 * @see getStartDateFormat()
	 * @see getStartDate()
	 * @see setStartDate()
	 * @see getManualType()
	 * @see setManualType()
	 */
	function setStartDateFormat($startDateFormat) {
		$this->m_startDateFormat = $startDateFormat;
	}

	/**
	 * Sets the terminal ID associated with the machine performing this
	 * request. This is mandatory for all requests and is supplied by
	 * CreditCall Communications. It is unique across all CardEase
	 * products. It is a numeric string with a fixed length of 8
	 * characters.
	 * 
	 * @param terminalID
	 *	The terminal ID associated with the machine performing this
	 *	request. If this is null the terminal ID is removed.
	 * @see getTerminalID()
	 */
	function setTerminalID($terminalID) {
		$this->m_terminalID = $terminalID;
	}

	/**
	 * Sets the track 1 associated with the card in a magnetic stripe
	 * authorisation. This is an alphanumeric string with a maximum length
	 * of 79 characters. This is optional.
	 * 
	 * @param track1
	 *	The track 1 associated with the card in a magnetic stripe
	 *	authorisation. If this is null the track 1 is removed.
	 * @see getTrack1()
	 */
	function setTrack1($track1) {
		$this->m_track1 = $track1;
	}

	/**
	 * Sets the track 2 associated with the card in a magnetic stripe
	 * authorisation. This is an alphanumeric string with a maximum length
	 * of 40 characters. This should include start and end sentinels (and
	 * separator character if provided). It is mandatory for magnetic
	 * stripe authorisation.
	 * 
	 * @param track2
	 *	The track 2 associated with the card in a magnetic stripe
	 *	authorisation. If this is null the track 2 is removed.
	 * @see getTrack2()
	 */
	function setTrack2($track2) {
		$this->m_track2 = $track2;
	}

	/**
	 * Sets the track 3 associated with the card in a magnetic stripe
	 * authorisation. This is an alphanumeric string with a maximum length
	 * of 107 characters. This is optional.
	 * 
	 * @param track3
	 *	The track 3 associated with the card in a magnetic stripe
	 *	authorisation. If this is null the track 3 is removed.
	 * @see getTrack3()
	 */
	function setTrack3($track3) {
		$this->m_track3 = $track3;
	}

	/**
	 * Sets the transaction key associated with this request. This is
	 * mandatory for all requests and is supplied by CreditCall
	 * Communications for a terminal or number of terminals. It must be in
	 * exactly the same case as provided by CreditCall. This is an
	 * alphanumeric string with a maximum length of 20 characters.
	 * 
	 * @param transactionKey
	 *	The transaction key associated with this request. If this is
	 *	null the transaction key is removed.
	 * @see getTransactionKey()
	 */
	function setTransactionKey($transactionKey) {
		$this->m_transactionKey = $transactionKey;
	}

	/**
	 * Sets the user reference associated with this request. This allows a
	 * user to attach their own reference against a request. This is an
	 * alphanumeric string with a maximum length of 50 characters. This is
	 * optional for all requests.
	 * 
	 * @param userReference
	 *	The user reference associated with this request. If this is
	 *	null the user reference is removed.
	 * @see setUserReference()
	 */
	function setUserReference($userReference) {
		$this->m_userReference = $userReference;
	}

	/**
	 * Sets the reason for which a void request is being made. This is
	 * mandatory for Void requests.
	 * 
	 * @param voidReason
	 *	The reason for which a void request is being made. If this is
	 *	null the void reason is removed.
	 * @see VoidReason
	 * @see setVoidReason()
	 */
	function setVoidReason($voidReason) {
		$this->m_voidReason = $voidReason;
	}

	/**
	 * Sets the zip code/post code details associated with the card in this
	 * request. This can be used for additional verification with the
	 * issuer. The content of this is dependant upon the country in which
	 * authorisation is being performed. This is an alphanumeric string.
	 * It is optional.
	 * 
	 * @param zipCode
	 *	The zip code/post code details associated with the card in
	 *	this request. If this is null the zip code is removed.
	 * @see getZipCode()
	 */
	function setZipCode($zipCode) {
		$this->m_zipCode = $zipCode;
	}

	/**
	 * Gets a string version of this requests details.
	 * 
	 * @return A string version of this requests details.
	 */
	function toString() {
		$eol = "<br>\n";
		$sep = ": ";

		$str = "";
		$str .= "REQUEST:";
		$str .= $eol;
		$str .= "3DSecureCardHolderEnrolled";
		$str .= $sep;
		$str .= $this->m_3DSecureCardHolderEnrolled;
		$str .= $eol;
		$str .= "3DSecureECI";
		$str .= $sep;
		$str .= $this->m_3DSecureECI;
		$str .= $eol;
		$str .= "3DSecureIAV";
		$str .= $sep; 
		$str .= $this->m_3DSecureIAV;
		$str .= $eol;			
		$str .= "3DSecureIAVAlgorithm";
		$str .= $sep; 
		$str .= $this->m_3DSecureIAVAlgorithm;
		$str .= $eol;
		$str .= "3DSecureIAVFormat";
		$str .= $sep; 
		$str .= $this->m_3DSecureIAVFormat;
		$str .= $eol;
		$str .= "3DSecureTransactionStatus";
		$str .= $sep; 
		$str .= $this->m_3DSecureTransactionStatus;
		$str .= $eol;
		$str .= "3DSecureXID";
		$str .= $sep; 
		$str .= $this->m_3DSecureXID;
		$str .= $eol;
		$str .= "3DSecureXIDFormat";
		$str .= $sep; 
		$str .= $this->m_3DSecureXIDFormat;
		$str .= $eol;
		$str .= "Address";
		$str .= $sep; 
		$str .= $this->m_address;
		$str .= $eol;
		$str .= "Amount";
		$str .= $sep; 
		$str .= $this->m_amount;
		$str .= $eol;
		$str .= "AmountUnit";
		$str .= $sep; 
		$str .= $this->m_amountUnit;
		$str .= $eol;
		$str .= "AutoConfirm";
		$str .= $sep; 
		$str .= $this->m_autoConfirm;
		$str .= $eol;
		$str .= "BatchReference";
		$str .= $sep; 
		$str .= $this->m_batchReference;
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
		$str .= "CSC";
		$str .= $sep; 
		$str .= $this->m_csc;
		$str .= $eol;
		$str .= "CurrencyCode";
		$str .= $sep; 
		$str .= $this->m_currencyCode;
		$str .= $eol;
		$str .= "ExpiryDate";
		$str .= $sep; 
		$str .= $this->m_expiryDate;
		$str .= $eol;
		$str .= "ExpiryDateFormat";
		$str .= $sep; 
		$str .= $this->m_expiryDateFormat;
		$str .= $eol;
		$str .= "ExtendedProperties";
		$str .= $sep; 
		$str .= print_r($this->m_extendedProperties, true);
		$str .= $eol;
		$str .= "ICCFallback";
		$str .= $sep; 
		$str .= $this->m_iccFallback;
		$str .= $eol;
		$str .= "ICCManagementFunction";
		$str .= $sep; 
		$str .= $this->m_iccManagementFunction;
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
		$str .= "MachineReference";
		$str .= $sep; 
		$str .= $this->m_machineReference;
		$str .= $eol;
		$str .= "ManualType";
		$str .= $sep; 
		$str .= $this->m_manualType;
		$str .= $eol;
		$str .= "OriginatingIPAddress";
		$str .= $sep; 
		$str .= $this->m_originatingIPAddress;
		$str .= $eol;
		$str .= "PAN";
		$str .= $sep; 
		$str .= $this->m_pan;
		$str .= $eol;
		$str .= "RequestType";
		$str .= $sep; 
		$str .= $this->m_requestType;
		$str .= $eol;
		$str .= "SoftwareName";
		$str .= $sep; 
		$str .= $this->m_softwareName;
		$str .= $eol;
		$str .= "SoftwareVersion";
		$str .= $sep; 
		$str .= $this->m_softwareVersion;
		$str .= $eol;
		$str .= "StartDate";
		$str .= $sep; 
		$str .= $this->m_startDate;
		$str .= $eol;
		$str .= "StartDateFormat";
		$str .= $sep; 
		$str .= $this->m_startDateFormat;
		$str .= $eol;
		$str .= "TerminalID";
		$str .= $sep; 
		$str .= $this->m_terminalID;
		$str .= $eol;
		$str .= "Track1";
		$str .= $sep; 
		$str .= $this->m_track1;
		$str .= $eol;
		$str .= "Track2";
		$str .= $sep; 
		$str .= $this->m_track2;
		$str .= $eol;
		$str .= "Track3";
		$str .= $sep; 
		$str .= $this->m_track3;
		$str .= $eol;
		$str .= "TransactionKey";
		$str .= $sep; 
		$str .= $this->m_transactionKey;
		$str .= $eol;
		$str .= "UserReference";
		$str .= $sep; 
		$str .= $this->m_userReference;
		$str .= $eol;
		$str .= "VoidReason";
		$str .= $sep; 
		$str .= $this->m_voidReason;
		$str .= $eol;
		$str .= "ZipCode";
		$str .= $sep; 
		$str .= $this->m_zipCode;
		$str .= $eol;
		
		return $str;
	}

	/**
	 * Validates the information held in this request to ensure that it is
	 * suitable for processing. This is called before a request is
	 * processed and can also be used for testing purposes.
	 * 
	 * @throws E_USER_ERROR
	 *	If the information held in this request is not suitable for
	 *	processing.
	 */
	function validate() {

		// A 3-D Secure result code is set
		if (($this->m_3DSecureCardHolderEnrolled !== null && $this->m_3DSecureCardHolderEnrolled !== ThreeDSecureCardHolderEnrolled_Empty) || ($this->m_3DSecureTransactionStatus !== null && $this->m_3DSecureTransactionStatus !== ThreeDSecureTransactionStatus_Empty)) {

			// 3-D Secure must have two result codes
			if ($this->m_3DSecureCardHolderEnrolled === null || $this->m_3DSecureCardHolderEnrolled === ThreeDSecureCardHolderEnrolled_Empty || $this->m_3DSecureTransactionStatus === null || $this->m_3DSecureTransactionStatus === ThreeDSecureTransactionStatus_Empty) {
				trigger_error("CardEaseXMLRequest: 3DSecureCardHolderEnrolled must be set with 3DSecureTransactionStatus", E_USER_ERROR);

			} else if ($this->m_3DSecureCardHolderEnrolled == ThreeDSecureCardHolderEnrolled_Yes && $this->m_3DSecureTransactionStatus == ThreeDSecureTransactionStatus_Failed) {
				// A bad authentication should not be authorised - ever.
				trigger_error("CardEaseXMLRequest: Unable to authorise failed 3-D Secure authentication", E_USER_ERROR);

// Not true: XID can be optional
//			} else if ($this->m_3DSecureXID === null) {
//				// XID is always required
//				trigger_error("CardEaseXMLRequest: XID must be set for all 3-D Secure transactions", E_USER_ERROR);
			}

			if ($this->m_3DSecureECI !== null) {
				if (strlen($this->m_3DSecureECI) < ECI_MIN_LENGTH || strlen($this->m_3DSecureECI) > ECI_MAX_LENGTH) {
					trigger_error("CardEaseXMLRequest: ECI has an invalid length", E_USER_ERROR);
				} else if (!ctype_digit((string)$this->m_3DSecureECI)) {
					trigger_error("CardEaseXMLRequest: ECI has an invalid format", E_USER_ERROR);
				}
			}

			if ($this->m_3DSecureIAV !== null || $this->m_3DSecureIAVAlgorithm !== null) { 

				if ($this->m_3DSecureIAV === null || $this->m_3DSecureIAVAlgorithm === null) { 
					trigger_error("CardEaseXMLRequest: 3DSecureIAV must be set with 3DSecureIAVAlgorithm", E_USER_ERROR); 
				}

				if (!ctype_digit((string)$this->m_3DSecureIAVAlgorithm)) {
					trigger_error("CardEaseXMLRequest: IAVAlgorithm has an invalid format", E_USER_ERROR);
				}
			}

		} else if ($this->m_3DSecureECI !== null || $this->m_3DSecureIAV !== null || $this->m_3DSecureIAVAlgorithm !== null || $this->m_3DSecureXID !== null) {
			// No other 3-D Secure must be set
			trigger_error("CardEaseXMLRequest: 3DSecure data missing 3DSecureCardHolderEnrolled and 3DSecureTransactionStatus", E_USER_ERROR);
		}

        if ($this->m_originatingIPAddress != null)
        {
            if (preg_match("/^(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}$/", $this->m_originatingIPAddress) !== 1)
            {
		        trigger_error("CardEaseXMLRequest: OriginatingIPAddress has an invalid format", E_USER_ERROR);
	        }
        }

		if ($this->m_softwareName === null) {
			trigger_error("CardEaseXMLRequest: SoftwareName must be specified for " . $this->m_requestType . " requests", E_USER_ERROR);
		} else if (strlen($this->m_softwareName) === 0 || strlen($this->m_softwareName) > SOFTWARENAME_MAX_LENGTH) {
			// SoftwareName has an invalid length
			trigger_error("CardEaseXMLRequest: SoftwareName has an invalid format", E_USER_ERROR);
		}

		if ($this->m_softwareVersion === null) {
			trigger_error("CardEaseXMLRequest: SoftwareVersion must be specified for " . $this->m_requestType . " requests", E_USER_ERROR);
		} else if (strlen($this->m_softwareVersion) === 0 || strlen($this->m_softwareVersion) > SOFTWAREVERSION_MAX_LENGTH) {
			// SoftwareVersion has an invalid length
			trigger_error("CardEaseXMLRequest: SoftwareVersion has an invalid format", E_USER_ERROR);
		}

		if ($this->m_terminalID === null) {
			// TerminalID has not been set
			trigger_error("CardEaseXMLRequest: TerminalID must be specified for " . $this->m_requestType . " requests", E_USER_ERROR);
		} else if (strlen($this->m_terminalID) !== TERMINALID_LENGTH) {
			// TerminalID has an invalid length
			trigger_error("CardEaseXMLRequest: TerminalID has an invalid format", E_USER_ERROR);
		} else if (!ctype_digit((string)$this->m_terminalID)) {
				// TerminalID has invalid chars
				trigger_error("CardEaseXMLRequest: TerminalID has an invalid format", E_USER_ERROR);
		}

		if ($this->m_transactionKey === null) {
			trigger_error("CardEaseXMLRequest: TransactionKey must be specified for " . $this->m_requestType . " requests", E_USER_ERROR);
		} else if (strlen($this->m_transactionKey) === 0) {
			trigger_error("CardEaseXMLRequest: TransactionKey has an invalid format", E_USER_ERROR);
		}

		if ($this->m_cardEaseReference !== null) {
			if (strlen($this->m_cardEaseReference) !== CARDEASE_REFERENCE_LENGTH) {
				// CardEaseReference has an invalid length
				trigger_error("CardEaseXMLRequest: CardEaseReference has an invalid format", E_USER_ERROR);
			}
		}

		if ($this->m_cardHash !== null) {
			if (strlen($this->m_cardHash) !== CARDHASH_LENGTH) {
				// CardHash has an invalid length
				trigger_error("CardEaseXMLRequest: CardHash has an invalid format", E_USER_ERROR);
			}
		}

		if ($this->m_cardReference !== null) {
			if (strlen($this->m_cardReference) !== CARDREFERENCE_LENGTH) {
				// CardReference has an invalid length
				trigger_error("CardEaseXMLRequest: CardReference has an invalid format", E_USER_ERROR);
			}
		}

		if ($this->m_currencyCode !== null) {
			if (strlen($this->m_currencyCode) !== CURRENCYCODE_LENGTH) {
				// CurrencyCode has an invalid length
				trigger_error("CardEaseXMLRequest: CurrencyCode has an invalid format", E_USER_ERROR);
			}

			if (!ctype_digit((string)$this->m_currencyCode) && !ctype_alpha((string)$this->m_currencyCode)) {
				trigger_error("CardEaseXMLRequest: CurrencyCode has an invalid format", E_USER_ERROR);
			}
		}

		// The expiry date length must match the expiry date format length
		if ($this->m_expiryDate !== null) {

			if ($this->m_expiryDateFormat === null) {
				trigger_error("CardEaseXMLRequest: ExpiryDate and ExpiryDateFormat must be specified", E_USER_ERROR);
			} else if (strlen($this->m_expiryDateFormat) === 0 || strlen($this->m_expiryDateFormat) > EXPIRYDATEFORMAT_MAX_LENGTH) {
				// ExpiryDateFormat has an invalid length
				trigger_error("CardEaseXMLRequest: ExpiryDateFormat has an invalid format", E_USER_ERROR);
			} else {
				for ($i = 0; $i < strlen($this->m_expiryDateFormat); $i++) {
					if (!$this->isValidDateTimeFormatCharacter($this->m_expiryDateFormat{$i})) {
						// ExpiryDateFormat has invalid chars
						trigger_error("CardEaseXMLRequest: ExpiryDateFormat has an invalid format", E_USER_ERROR);
					}
				}
			}

			if (strlen($this->m_expiryDate) !== strlen($this->m_expiryDateFormat)) {
				trigger_error("CardEaseXMLRequest: ExpiryDate does not conform to ExpiryDateFormat", E_USER_ERROR);
			}
			
			// check format against date
		}

		// The offline date time length must match the offline date time format length
		if ($this->m_offlineDateTime !== null) {

			if ($this->m_offlineDateTimeFormat === null) {
				trigger_error("CardEaseXMLRequest: ExpiryDate and ExpiryDateFormat must be specified", E_USER_ERROR);
			} else if (strlen($this->m_expiryDateFormat) === 0 || strlen($this->m_expiryDateFormat) > EXPIRYDATEFORMAT_MAX_LENGTH) {
				// ExpiryDateFormat has an invalid length
				trigger_error("CardEaseXMLRequest: ExpiryDateFormat has an invalid format", E_USER_ERROR);
			} else {
				for ($i = 0; $i < strlen($this->m_expiryDateFormat); $i++) {
					if (!$this->isValidDateTimeFormatCharacter($this->m_expiryDateFormat{$i})) {
						// ExpiryDateFormat has invalid chars
						trigger_error("CardEaseXMLRequest: ExpiryDateFormat has an invalid format", E_USER_ERROR);
					}
				}
			}

			if (strlen($this->m_expiryDate) !== strlen($this->m_expiryDateFormat)) {
				trigger_error("CardEaseXMLRequest: ExpiryDate does not conform to ExpiryDateFormat", E_USER_ERROR);
			}
			
			// check format against date
		}

		// The start date length must match the start date format length
		if ($this->m_startDate !== null) {

			if ($this->m_startDateFormat === null) {
				trigger_error("CardEaseXMLRequest: StartDate and StartDateFormat must be specified", E_USER_ERROR);
			} else if (strlen($this->m_startDateFormat) === 0 || strlen($this->m_startDateFormat) > STARTDATEFORMAT_MAX_LENGTH) {
				// StartDateFormat has an invalid length
				trigger_error("CardEaseXMLRequest: StartDateFormat has an invalid format", E_USER_ERROR);
			} else {
				for ($i = 0; $i < strlen($this->m_startDateFormat); $i++) {
					if (!$this->isValidDateTimeFormatCharacter($this->m_startDateFormat{$i})) {
						// StartDateFormat has invalid chars
						trigger_error("CardEaseXMLRequest: StartDateFormat has an invalid format", E_USER_ERROR);
					}
				}
			}

			if (strlen($this->m_startDate) !== strlen($this->m_startDateFormat)) {
				trigger_error("CardEaseXMLRequest: StartDate does not conform to StartDateFormat", E_USER_ERROR);
			}
			
			// check format against date
		}

        if ($this->m_cardHolderEmailAddresses !== null)
        {
            foreach ($this->m_cardHolderEmailAddresses as $emailAddress)
            {
                if (!$this->IsValidEmailAddress($emailAddress->getAddress()))
                {
                    trigger_error("CardEaseXMLRequest: CardHolderEmailAddress has invalid format", E_USER_ERROR);
                }
            }
        }

        if ($this->m_deliveryEmailAddresses !== null)
        {
            foreach ($this->m_deliveryEmailAddresses as $emailAddress)
            {
                if (!$this->IsValidEmailAddress($emailAddress->getAddress()))
                {
                    trigger_error("CardEaseXMLRequest: DeliveryEmailAddress has invalid format", E_USER_ERROR);
                }
            }
        }

        if ($this->m_invoiceEmailAddresses !== null)
        {
            foreach ($this->m_invoiceEmailAddresses as $emailAddress)
            {
                if (!$this->IsValidEmailAddress($emailAddress->getAddress()))
                {
                    trigger_error("CardEaseXMLRequest: InvoiceEmailAddress has invalid format", E_USER_ERROR);
                }
            }
        }

		if ($this->m_products !== null)
        {
            foreach ($this->m_products as $product)
            {
                if ($product->getAmount() !== null)
                {
                    if (!$this->IsValidAmount($product->getAmount()))
                    {
                        trigger_error("CardEaseXMLRequest: Product Amount has invalid format", E_USER_ERROR);
                    }
                }

                if ($product->getCurrencyCode() !== null)
                {
                    if (strlen($product->getCurrencyCode()) !== CURRENCYCODE_LENGTH)
                    {
                        // CurrencyCode has an invalid length
                        trigger_error("CardEaseXMLRequest: Product CurrencyCode has an invalid format", E_USER_ERROR);
                    }

                    if (!ctype_digit((string)$product->getCurrencyCode()) && !ctype_alpha((string)$product->getCurrencyCode()))
                    {
                        trigger_error("CardEaseXMLRequest: Product CurrencyCode has an invalid format", E_USER_ERROR);
                    }
                }
            }
        }

		// A void reason must be present for a void request
		if ($this->m_requestType === RequestType_Void) {

			if ($this->m_voidReason === null || $this->m_voidReason === VoidReason_Empty) {
				trigger_error("CardEaseXMLRequest: VoidReason must be specified for " . $this->m_requestType . " requests", E_USER_ERROR);
			}
		}

		// A management function must be present for a management request
		if ($this->m_requestType === RequestType_ICCManagement) {

			if ($this->m_iccManagementFunction === null) {
				trigger_error("CardEaseXMLRequest: ICCManagementFunction must be specified for " . $this->m_requestType . " requests", E_USER_ERROR);
			}
		}

		// A auth and offline request require an amount
		if ($this->m_requestType === REQUESTTYPE_AUTH
				|| $this->m_requestType === RequestType_Offline) {

			if ($this->m_amount === null) {
				trigger_error("CardEaseXMLRequest: Amount must be specified for " . $this->m_requestType . " requests", E_USER_ERROR);
			} else if (!$this->isValidAmount($this->m_amount)) {
				trigger_error("CardEaseXMLRequest: Amount has an invalid format", E_USER_ERROR);
			}
		}

		// A conf, refund or void must have a card ease reference
		if ($this->m_requestType === RequestType_Conf
			|| $this->m_requestType === RequestType_Refund
				|| $this->m_requestType === RequestType_Void) {

			if ($this->m_cardEaseReference === null) {
				trigger_error("CardEaseXMLRequest: CardEaseReference must be specified for " . $this->m_requestType . " requests", E_USER_ERROR);
			}
		}

		// An auth, offline, preauth and refund must have card details
		if ($this->m_requestType === REQUESTTYPE_AUTH
				|| $this->m_requestType === RequestType_Offline
				|| $this->m_requestType === RequestType_PreAuth) {

			// ICCTags are supplied
			$ICCPresent = ($this->m_iccTags !== null && count($this->m_iccTags) > 0) ? 1 : 0;
			// Track2 is supplied
			$CATPresent = ($this->m_track2 !== null) ? 1 : 0;
			// ManualType is supplied
			$ManPresent = ($this->m_pan !== null || ($this->m_cardReference !== null && $this->m_cardHash !== null)) ? 1 : 0;

			$totalPresent = $ICCPresent + $CATPresent + $ManPresent;

			// nothing present
			//if (($this->m_iccTags === null || count($this->m_iccTags) === 0)
			//		&& $this->m_track2 === null && $this->m_manualType === null) {
			if ($totalPresent === 0) {
				trigger_error("CardEaseXMLRequest: CardDetails must be specified for " . $this->m_requestType . " requests (icc, track2 or pan)", E_USER_ERROR);
			} else if ($totalPresent > 1) { // more than one present
				trigger_error("CardEaseXMLRequest: Ambiguous CardDetails. Multiple settings supplied", E_USER_ERROR);
			}

			// Manual details must have pan and expiry details or cardreference and cardhash details
			//if (($this->m_iccTags === null || count($this->m_iccTags) === 0)
			//		&& $this->m_track2 === null && $this->m_manualType !== null) {
			if ($ManPresent === 1) {

				if (($this->m_cardReference === null && $this->m_cardHash !== null) ||
					($this->m_cardReference !== null && $this->m_cardHash === null)) {

					trigger_error("CardEaseXMLRequest: Both CardReference and CardHash must be specified if used", E_USER_ERROR);
				}
				else if ($this->m_cardReference === null && $this->m_cardHash === null)
				{
					if ($this->m_pan === null) {
						trigger_error("CardEaseXMLRequest: PAN must be specified for manual card details", E_USER_ERROR);
					} else if (strlen($this->m_pan) < PAN_MIN_LENGTH || strlen($this->m_pan) > PAN_MAX_LENGTH) {
						// PAN has an invalid length
						trigger_error("CardEaseXMLRequest: PAN has an invalid format", E_USER_ERROR);
					} else if (!$this->isValidLuhn($this->m_pan)) {
						// PAN failed LuhnCheck and/or contains non digits
						trigger_error("CardEaseXMLRequest: PAN has an invalid format", E_USER_ERROR);
					}
	
					if ($this->m_expiryDate === null) {
						trigger_error("CardEaseXMLRequest: ExpiryDate must be specified for manual card details", E_USER_ERROR);
					}
	
					if ($this->m_expiryDateFormat === null) {
						trigger_error("CardEaseXMLRequest: ExpiryDateFormat must be specified for manual card details", E_USER_ERROR);
					}

					if ($this->m_issueNumber !== null) {
						if (strlen($this->m_issueNumber) < ISSUENUMBER_MIN_LENGTH || strlen($this->m_issueNumber)> ISSUENUMBER_MAX_LENGTH) {
							// Issue number has an invalid length
							trigger_error("CardEaseXMLRequest: IssueNumber has an invalid format", E_USER_ERROR);
						}

						if (!ctype_digit((string)$this->m_issueNumber)) {
							// Issue number contains non digits
							trigger_error("CardEaseXMLRequest: IssueNumber has an invalid format", E_USER_ERROR);
						}
					}
				}
			}

			// An icc request must have a type and tags
			if ($ICCPresent === 1) {

				if ($this->m_iccType === null) {
					trigger_error("CardEaseXMLRequest: ICCType must be specified for ICCTag details", E_USER_ERROR);
				} else if (strlen($this->m_iccType) === 0) {
					trigger_error("CardEaseXMLRequest: ICCType has an invalid format", E_USER_ERROR);
				}

				// The tags must have all data
				foreach ($this->m_iccTags as $tag) {

					if ($tag === null) {
						trigger_error("CardEaseXMLRequest: ICCTag must be specified for ICCTag details", E_USER_ERROR);
					}

					if ($tag->getID() === null) {
						trigger_error("CardEaseXMLRequest: ICCTag ID must be specified for ICCTag details", E_USER_ERROR);
					} else if (strlen($tag->getID()) === 0) {
						 trigger_error("CardEaseXMLRequest: ICCTag ID has an invalid format");
					}

					if ($tag->getValue() === null) {
						trigger_error("CardEaseXMLRequest: ICCTag Value must be specified for ICCTag details", E_USER_ERROR);
					}
				}
			}

			if ($CATPresent === 1) {

				if ($this->m_track1 !== null) {
					if (strlen($this->m_track1) > TRACK1_MAX_LENGTH) {
						trigger_error("CardEaseXMLRequest: Track1 has an invalid format", E_USER_ERROR);
					}
				}

				if ($this->m_track2 !== null) {
					if (strlen($this->m_track2) === 0 || strlen($this->m_track2) > TRACK2_MAX_LENGTH) {
						trigger_error("CardEaseXMLRequest: Track2 has an invalid format", E_USER_ERROR);
					} else if ($this->m_track2{0} !== TRACK2_START_SENTINEL) // check start sentinel
						trigger_error("CardEaseXMLRequest: Track2 has an invalid format", E_USER_ERROR);
					} else if ($this->m_track2{strlen($this->m_track2) - 1} !== TRACK2_END_SENTINEL) { // check end sentinel
						trigger_error("CardEaseXMLRequest: Track2 has an invalid format", E_USER_ERROR);
					}
				}

				if ($this->m_track3 !== null) {
					if (strlen($this->m_track3) > TRACK1_MAX_LENGTH) {
						trigger_error("CardEaseXMLRequest: Track3 has an invalid format", E_USER_ERROR);
					}
				}
			}

		if ($this->m_csc !== null) {

			if (strlen($this->m_csc) < CSC_MIN_LENGTH || strlen($this->m_csc) > CSC_MAX_LENGTH) {
				trigger_error("CardEaseXMLRequest: CSC has an invalid format", E_USER_ERROR);
			}

			if (!ctype_digit((string)$this->m_csc)) {
				trigger_error("CardEaseXMLRequest: CSC has an invalid format", E_USER_ERROR);
			}
		}
	}

	/**
	 * Method to determine if character is a valid date and time character.
	 * @private
	 * @param c
	 *	The character to check.
	 * @return Whether the character is a valid date and time character.
	 */
	function isValidDateTimeFormatCharacter($c) {
		return ($c === 'y') || ($c === 'M') || ($c === 'd') || ($c === 'H') || ($c === 'm') || ($c === 's') || ($c === '/') || ($c === '-') || ($c === ':') || ($c === '.') || ($c === ' ');
	}

	/**
	 * Determines whether an amount is valid.
	 * <p>
	 * Accepts 1 1. 1.1 .1 
	 * @param amount The amount to validate.
	 * @return If the amount is valid.
	 */
	function isValidAmount($amount) {
		return preg_match("/^(\d+|\d+\.\d+|\.\d+|\d+\.)$/", $amount) === 1;
	}

    /**
     * Determines whether an email address is valid.
     * @param emailAddress The email address to check.
     * @returns If the email address is valid.
     */
    function isValidEmailAddress($emailAddress)
    {           
        $qtext = "[^\\x0d\\x22\\x5c\\x80-\\xff]";
        $dtext = "[^\\x0d\\x5b-\\x5d\\x80-\\xff]";
        $atom = "[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+";
        $quoted_pair = "\\x5c[\\x00-\\x7f]";
        $domain_literal = "\\x5b(" . $dtext . "|" . $quoted_pair . ")*\\x5d";
        $quoted_string = "\\x22(" . $qtext . "|" . $quoted_pair . ")*\\x22";
        $domain_ref = $atom;
        $sub_domain = "(" . $domain_ref . "|" . $domain_literal . ")";
        $word = "(" . $atom . "|" . $quoted_string . ")";
        $domain = $sub_domain . "(\\x2e" . $sub_domain . ")*";
        $local_part = $word . "(\\x2e" . $word . ")*";
        $addr_spec = $local_part . "\\x40" . $domain;

        return preg_match("/^" . $addr_spec . "$/", $emailAddress) === 1;
	}

	/**
	 * Determines whether the supplied PAN is valid. Will also check to see whether the PAN is numeric.
	 * @private
	 * @param pan
	 *	The PAN to validate.
	 * @return If the PAN failed the Luhn Check of if the PAN is not numeric.
	 */
	function isValidLuhn($pan) {

		if (!ctype_digit((string)$pan)) {
			return false;
		}

		$nSum = 0;
		for ($nPos = strlen($pan) - 1, $nMultiple = 0; $nPos >= 0; $nPos--, $nMultiple ^= 1) {
			$nProduct = $pan{$nPos} * ($nMultiple + 1);
			$nSum += (($nProduct > 9) ? $nProduct - 9 : $nProduct);
		}
		return (($nSum % 10) === 0);
	}
}
?>
