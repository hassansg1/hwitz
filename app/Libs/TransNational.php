<?php

// Add namespace as it's required in laravel
namespace App\Libs;

use SimpleXMLElement;

define("APPROVED", 1);
define("DECLINED", 2);
define("ERROR", 3);

// Change name of the class as in laravel it's compulsary filename and classname is same
class TransNational {

	// Initial Setting Functions

	function setLogin($username, $password, $prefix) {/*{{{*/
		$this->login['username'] = $username;
		$this->login['password'] = $password;
		$this->login['prefix'] = $prefix;
	}/*}}}*/

	function setOrder($orderid,
            $orderdescription,
            $tax,
            $shipping,
            $ponumber,
            $ipaddress) {/*{{{*/
        $this->order['orderid']          = $orderid;
        $this->order['orderdescription'] = $orderdescription;
        $this->order['tax']              = $tax;
        $this->order['shipping']         = $shipping;
        $this->order['ponumber']         = $ponumber;
        $this->order['ipaddress']        = $ipaddress;
	}/*}}}*/

	function setBilling($firstname,
            $lastname,
            $company,
            $address1,
            $address2,
            $city,
            $state,
            $zip,
            $country,
            $phone,
            $fax,
            $email,
            $website) {/*{{{*/
        $this->billing['firstname'] = $firstname;
        $this->billing['lastname']  = $lastname;
        $this->billing['company']   = $company;
        $this->billing['address1']  = $address1;
        $this->billing['address2']  = $address2;
        $this->billing['city']      = $city;
        $this->billing['state']     = $state;
        $this->billing['zip']       = $zip;
        $this->billing['country']   = $country;
        $this->billing['phone']     = $phone;
        $this->billing['fax']       = $fax;
        $this->billing['email']     = $email;
        $this->billing['website']   = $website;
	}/*}}}*/

	function setShipping($firstname,
            $lastname,
            $company,
            $address1,
            $address2,
            $city,
            $state,
            $zip,
            $country,
            $email) {/*{{{*/
        $this->shipping['firstname'] = $firstname;
        $this->shipping['lastname']  = $lastname;
        $this->shipping['company']   = $company;
        $this->shipping['address1']  = $address1;
        $this->shipping['address2']  = $address2;
        $this->shipping['city']      = $city;
        $this->shipping['state']     = $state;
        $this->shipping['zip']       = $zip;
        $this->shipping['country']   = $country;
        $this->shipping['email']     = $email;
	}/*}}}*/

	// Transaction Functions

	function doSale($amount, $ccnumber, $ccexp, $cvv="") {/*{{{*/
        dde("doVaultSale: ".substr($ccnumber,-4)." $amount",'payments.log');
		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		$query .= "cvv=" . urlencode($cvv) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		$query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "type=sale";
		return $this->_doPost($query);

	}/*}}}*/

	function doCheck($amount,
        $checkname,
        $checkaba,
        $checkaccount,
        $account_holder_type,
        $account_type) {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Sales Information
		$query .= "checkname=" . urlencode($checkname) . "&";
		$query .= "checkaba=" . urlencode($checkaba) . "&";
		$query .= "checkaccount=" . urlencode($checkaccount) . "&";
		$query .= "account_holder_type=" . urlencode($account_holder_type) . "&";
		$query .= "account_type=" . urlencode($account_type) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		$query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "type=sale";
		return $this->_doPost($query);

	}/*}}}*/

	function doAuth($amount, $ccnumber, $ccexp, $cvv="") {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		$query .= "cvv=" . urlencode($cvv) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		$query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "type=auth";
		return $this->_doPost($query);

	}/*}}}*/

	function doCredit($amount, $ccnumber, $ccexp) {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		$query .= "type=credit";
		return $this->_doPost($query);

	}/*}}}*/

	function doOffline($authorizationcode, $amount, $ccnumber, $ccexp) {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		$query .= "authorizationcode=" . urlencode($authorizationcode) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		// Shipping Information
		$query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
		$query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
		$query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
		$query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
		$query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
		$query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
		$query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
		$query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
		$query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
		$query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
		$query .= "type=offline";
		return $this->_doPost($query);

	}/*}}}*/

	function doCapture($transactionid, $amount =0) {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Transaction Information
		$query .= "transactionid=" . urlencode($transactionid) . "&";
		if ($amount>0) {
			$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		}
		$query .= "type=capture";
		return $this->_doPost($query);

	}/*}}}*/

	function doVoid($transactionid) {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Transaction Information
		$query .= "transactionid=" . urlencode($transactionid) . "&";
		$query .= "type=void";
		return $this->_doPost($query);

	}/*}}}*/

	function doRefund($transactionid, $amount = 0) {/*{{{*/

		$query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Transaction Information
		$query .= "transactionid=" . urlencode($transactionid) . "&";
		if ($amount>0) {
			$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		}
		$query .= "type=refund";
		return $this->_doPost($query);

	}/*}}}*/
    //
    // Add Credit Card to Vault
    function doVaultAdd($id, $ccnumber, $ccexp, $cvv='') {
        //
        // If there is a prefix use it.
        if (!empty($this->login['prefix']))
            $id = $this->login['prefix'].$id;
        $query = '';
		$query .= 'customer_vault=add_customer&';
		$query .= 'customer_vault_id='.urlencode($id).'&';
        // Login Info
		$query .= 'username=' . urlencode($this->login['username']) . '&';
		$query .= 'password=' . urlencode($this->login['password']) . '&';

        // Acccount Info
		$query .= 'ccnumber=' . urlencode($ccnumber) . '&';
		$query .= 'ccexp=' . urlencode($ccexp) . '&';
		$query .= 'cvv=' . urlencode($cvv) . '&';

		// Billing Information
		$query .= 'firstname=' . urlencode($this->billing['firstname']) . '&';
		$query .= 'lastname=' . urlencode($this->billing['lastname']) . '&';
		$query .= 'company=' . urlencode($this->billing['company']) . '&';
		$query .= 'address1=' . urlencode($this->billing['address1']) . '&';
		$query .= 'address2=' . urlencode($this->billing['address2']) . '&';
		$query .= 'city=' . urlencode($this->billing['city']) . '&';
		$query .= 'state=' . urlencode($this->billing['state']) . '&';
		$query .= 'zip=' . urlencode($this->billing['zip']) . '&';
		$query .= 'country=' . urlencode($this->billing['country']) . '&';
		$query .= 'phone=' . urlencode($this->billing['phone']) . '&';
		$query .= 'fax=' . urlencode($this->billing['fax']) . '&';
		$query .= 'email=' . urlencode($this->billing['email']) . '&';

		// Shipping Information
        if (!empty($this->shipping)) {
            $query .= 'shipping_firstname=' . urlencode($this->shipping['firstname']) . '&';
            $query .= 'shipping_lastname=' . urlencode($this->shipping['lastname']) . '&';
            $query .= 'shipping_company=' . urlencode($this->shipping['company']) . '&';
            $query .= 'shipping_address1=' . urlencode($this->shipping['address1']) . '&';
            $query .= 'shipping_address2=' . urlencode($this->shipping['address2']) . '&';
            $query .= 'shipping_city=' . urlencode($this->shipping['city']) . '&';
            $query .= 'shipping_state=' . urlencode($this->shipping['state']) . '&';
            $query .= 'shipping_zip=' . urlencode($this->shipping['zip']) . '&';
            $query .= 'shipping_country=' . urlencode($this->shipping['country']) . '&';
            $query .= 'shipping_email=' . urlencode($this->shipping['email']) . '&';
        }
		return $this->_doPost($query);
    }

    // Add ACH Entry to Vault
    function doVaultAddACH($id,
            $checkname,
            $checkaba,
            $checkaccount,
            $account_holder_type,
            $account_type) {

        if (!empty($this->login['prefix']))
            $id = $this->login['prefix'].$id;
        $query = '';
		$query .= 'customer_vault=add_customer&';
		$query .= 'customer_vault_id='.urlencode($id).'&';
        // Login Info
		$query .= 'username=' . urlencode($this->login['username']) . '&';
		$query .= 'password=' . urlencode($this->login['password']) . '&';

        // Acccount Info
		$query .= 'checkname=' . urlencode($checkname) . '&';
		$query .= 'checkaba=' . urlencode($checkaba) . '&';
		$query .= 'checkaccount=' . urlencode($checkaccount) . '&';
		$query .= 'account_holder_type=' . urlencode($account_holder_type) . '&';
		$query .= 'account_type=' . urlencode($account_type) . '&';

		// Billing Information
		$query .= 'firstname=' . urlencode($this->billing['firstname']) . '&';
		$query .= 'lastname=' . urlencode($this->billing['lastname']) . '&';
		$query .= 'company=' . urlencode($this->billing['company']) . '&';
		$query .= 'address1=' . urlencode($this->billing['address1']) . '&';
		$query .= 'address2=' . urlencode($this->billing['address2']) . '&';
		$query .= 'city=' . urlencode($this->billing['city']) . '&';
		$query .= 'state=' . urlencode($this->billing['state']) . '&';
		$query .= 'zip=' . urlencode($this->billing['zip']) . '&';
		$query .= 'country=' . urlencode($this->billing['country']) . '&';
		$query .= 'phone=' . urlencode($this->billing['phone']) . '&';
		$query .= 'fax=' . urlencode($this->billing['fax']) . '&';
		$query .= 'email=' . urlencode($this->billing['email']) . '&';

		// Shipping Information
        if (!empty($this->shipping)) {
            $query .= 'shipping_firstname=' . urlencode($this->shipping['firstname']) . '&';
            $query .= 'shipping_lastname=' . urlencode($this->shipping['lastname']) . '&';
            $query .= 'shipping_company=' . urlencode($this->shipping['company']) . '&';
            $query .= 'shipping_address1=' . urlencode($this->shipping['address1']) . '&';
            $query .= 'shipping_address2=' . urlencode($this->shipping['address2']) . '&';
            $query .= 'shipping_city=' . urlencode($this->shipping['city']) . '&';
            $query .= 'shipping_state=' . urlencode($this->shipping['state']) . '&';
            $query .= 'shipping_zip=' . urlencode($this->shipping['zip']) . '&';
            $query .= 'shipping_country=' . urlencode($this->shipping['country']) . '&';
            $query .= 'shipping_email=' . urlencode($this->shipping['email']) . '&';
        }
		return $this->_doPost($query);
    }

    // Update existing Credit Card Vault Entry
    function doVaultUpdate($id, $ccnumber='', $ccexp='', $cvv='') {

        if (!empty($this->login['prefix']))
            $id = $this->login['prefix'].$id;
        $query = '';
		$query .= 'customer_vault=update_customer&';
		$query .= 'customer_vault_id='.urlencode($id).'&';
        // Login Info
		$query .= 'username=' . urlencode($this->login['username']) . '&';
		$query .= 'password=' . urlencode($this->login['password']) . '&';

        // Acccount Info
        // If account number is not provided then don't update
        if (!empty($ccnumber)) {
            $query .= 'ccnumber=' . urlencode($ccnumber) . '&';
            $query .= 'ccexp=' . urlencode($ccexp) . '&';
            $query .= 'cvv=' . urlencode($cvv) . '&';
        }

		// Billing Information
		$query .= 'firstname=' . urlencode($this->billing['firstname']) . '&';
		$query .= 'lastname=' . urlencode($this->billing['lastname']) . '&';
		$query .= 'company=' . urlencode($this->billing['company']) . '&';
		$query .= 'address1=' . urlencode($this->billing['address1']) . '&';
		$query .= 'address2=' . urlencode($this->billing['address2']) . '&';
		$query .= 'city=' . urlencode($this->billing['city']) . '&';
		$query .= 'state=' . urlencode($this->billing['state']) . '&';
		$query .= 'zip=' . urlencode($this->billing['zip']) . '&';
		$query .= 'country=' . urlencode($this->billing['country']) . '&';
		$query .= 'phone=' . urlencode($this->billing['phone']) . '&';
		$query .= 'fax=' . urlencode($this->billing['fax']) . '&';
		$query .= 'email=' . urlencode($this->billing['email']) . '&';

		// Shipping Information
        if (!empty($this->shipping)) {
            $query .= 'shipping_firstname=' . urlencode($this->shipping['firstname']) . '&';
            $query .= 'shipping_lastname=' . urlencode($this->shipping['lastname']) . '&';
            $query .= 'shipping_company=' . urlencode($this->shipping['company']) . '&';
            $query .= 'shipping_address1=' . urlencode($this->shipping['address1']) . '&';
            $query .= 'shipping_address2=' . urlencode($this->shipping['address2']) . '&';
            $query .= 'shipping_city=' . urlencode($this->shipping['city']) . '&';
            $query .= 'shipping_state=' . urlencode($this->shipping['state']) . '&';
            $query .= 'shipping_zip=' . urlencode($this->shipping['zip']) . '&';
            $query .= 'shipping_country=' . urlencode($this->shipping['country']) . '&';
            $query .= 'shipping_email=' . urlencode($this->shipping['email']) . '&';
        }
		return $this->_doPost($query);

    }

    // Update Existing ACH Vault Entry
    function doVaultUpdateACH($id,
            $checkname,
            $checkaba,
            $checkaccount,
            $account_holder_type,
            $account_type) {

        if (!empty($this->login['prefix']))
            $id = $this->login['prefix'].$id;
        $query = '';
		$query .= 'customer_vault=update_customer&';
		$query .= 'customer_vault_id='.urlencode($id).'&';

        // Login Info
		$query .= 'username=' . urlencode($this->login['username']) . '&';
		$query .= 'password=' . urlencode($this->login['password']) . '&';

        // Acccount Info
		$query .= 'checkname=' . urlencode($checkname) . '&';
                if ($checkaba != '') {
                    $query .= 'checkaba=' . urlencode($checkaba) . '&';
                }

                if ($checkaccount != '') {
                    $query .= 'checkaccount=' . urlencode($checkaccount) . '&';
                }

		$query .= 'account_holder_type=' . urlencode($account_holder_type) . '&';
		$query .= 'account_type=' . urlencode($account_type) . '&';

		// Billing Information
		$query .= 'firstname=' . urlencode($this->billing['firstname']) . '&';
		$query .= 'lastname=' . urlencode($this->billing['lastname']) . '&';
		$query .= 'company=' . urlencode($this->billing['company']) . '&';
		$query .= 'address1=' . urlencode($this->billing['address1']) . '&';
		$query .= 'address2=' . urlencode($this->billing['address2']) . '&';
		$query .= 'city=' . urlencode($this->billing['city']) . '&';
		$query .= 'state=' . urlencode($this->billing['state']) . '&';
		$query .= 'zip=' . urlencode($this->billing['zip']) . '&';
		$query .= 'country=' . urlencode($this->billing['country']) . '&';
		$query .= 'phone=' . urlencode($this->billing['phone']) . '&';
		$query .= 'fax=' . urlencode($this->billing['fax']) . '&';
		$query .= 'email=' . urlencode($this->billing['email']) . '&';

		// Shipping Information
        if (!empty($this->shipping)) {
            $query .= 'shipping_firstname=' . urlencode($this->shipping['firstname']) . '&';
            $query .= 'shipping_lastname=' . urlencode($this->shipping['lastname']) . '&';
            $query .= 'shipping_company=' . urlencode($this->shipping['company']) . '&';
            $query .= 'shipping_address1=' . urlencode($this->shipping['address1']) . '&';
            $query .= 'shipping_address2=' . urlencode($this->shipping['address2']) . '&';
            $query .= 'shipping_city=' . urlencode($this->shipping['city']) . '&';
            $query .= 'shipping_state=' . urlencode($this->shipping['state']) . '&';
            $query .= 'shipping_zip=' . urlencode($this->shipping['zip']) . '&';
            $query .= 'shipping_country=' . urlencode($this->shipping['country']) . '&';
            $query .= 'shipping_email=' . urlencode($this->shipping['email']) . '&';
        }
		return $this->_doPost($query);
    }


    // Delete Vault entry for this $id
    function doVaultDelete($id) {

        if (!empty($this->login['prefix']))
            $id = $this->login['prefix'].$id;
        $query = '';
		$query .= 'customer_vault=delete_customer&';
		$query .= 'customer_vault_id='.urlencode($id).'&';
        // Login Info
		$query .= 'username=' . urlencode($this->login['username']) . '&';
		$query .= 'password=' . urlencode($this->login['password']) . '&';
		return $this->_doPost($query);
    }

    // Do a sale from the Vault Entry
    function doVaultSale($id, $amount) {

        if (!empty($this->login['prefix']))
            $id = $this->login['prefix'].$id;
        dde("doVaultSale: $id $amount",'payments.log');
        $query = '';
		$query .= 'billing_method=recurring&';
		$query .= 'customer_vault_id='.urlencode($id).'&';
		$query .= 'amount=' . urlencode(number_format($amount,2,'.','')) . '&';
        // Login Info
		$query .= 'username=' . urlencode($this->login['username']) . '&';
		$query .= 'password=' . urlencode($this->login['password']) . '&';
        //
		// Order Information
		$query .= 'ipaddress=' . urlencode($this->order['ipaddress']) . '&';
		$query .= 'orderid=' . urlencode($this->order['orderid']) . '&';
		$query .= 'orderdescription=' . urlencode($this->order['orderdescription']) . '&';
        if ($this->order['tax'])
            $query .= 'tax=' . urlencode(number_format($this->order['tax'],2,'.','')) . '&';
        if ($this->order['shipping'])
            $query .= 'shipping=' . urlencode(number_format($this->order['shipping'],2,'.','')) . '&';
		$query .= 'ponumber=' . urlencode($this->order['ponumber']) . '&';

		return $this->_doPost($query);
    }
    //
    // Do a auth from the Vault Entry
    function doVaultAuth($id, $amount) {

        if (!empty($this->login['prefix']))
            $id = $this->login['prefix'].$id;
        dde("doVaultAuth: $id $amount",'payments.log');
        $query = '';
		$query .= 'billing_method=recurring&';
		$query .= 'type=auth&';
		$query .= 'customer_vault_id='.urlencode($id).'&';
		$query .= 'amount=' . urlencode(number_format($amount,2,'.','')) . '&';
        // Login Info
		$query .= 'username=' . urlencode($this->login['username']) . '&';
		$query .= 'password=' . urlencode($this->login['password']) . '&';
        //
		// Order Information
		$query .= 'ipaddress=' . urlencode($this->order['ipaddress']) . '&';
		$query .= 'orderid=' . urlencode($this->order['orderid']) . '&';
		$query .= 'orderdescription=' . urlencode($this->order['orderdescription']) . '&';
        if ($this->order['tax'])
            $query .= 'tax=' . urlencode(number_format($this->order['tax'],2,'.','')) . '&';
        if ($this->order['shipping'])
            $query .= 'shipping=' . urlencode(number_format($this->order['shipping'],2,'.','')) . '&';
		$query .= 'ponumber=' . urlencode($this->order['ponumber']) . '&';

		return $this->_doPost($query);
    }

    // Returns info from the Vault.
    function queryVault($id) {
        if (!empty($this->login['prefix']))
            $id = $this->login['prefix'].$id;
        $query = '';
        $query .= 'username=' . urlencode($this->login['username']). '&';
        $query .= 'password=' . urlencode($this->login['password']). '&';
        $query .= 'report_type=customer_vault&';
		$query .= 'customer_vault_id='.urlencode($id).'&';

		return $this->_doQuery($query);

    }


	function _doPost($query) {/*{{{*/
//        error_log(date('Y-m-d H:i:s ')."Attend TNBCI query: $query\n",3,'/tmp/tnbci.log');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://secure.networkmerchants.com/api/transact.php");
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
		curl_setopt($ch, CURLOPT_POST, 1);

		if (!($data = curl_exec($ch))) {
			return ERROR;
		}
		curl_close($ch);
		unset($ch);
		// print "\n$query\n";
		$data = explode("&",$data);
		for($i=0;$i<count($data);$i++) {
			$rdata = explode("=",$data[$i]);
			$this->responses[$rdata[0]] = $rdata[1];
            // echo "$data[$i]<br >";
		}

        switch($this->responses['response_code']) {
        case '100':
            $response_code = 'Transaction Approved';
            break;
        case '200':
            $response_code = 'Transaction Declined';
            break;
        case '300':
            $response_code = 'Transaction Rejected';
            break;
        case '400':
            $response_code = 'Transaction Error';
            break;
        default:
            $response_code = 'Other';
            break;
        }

        $this->html_response = '<table style="border-collapse:collapse;border:1px;">'.
            "<tr><td>Response</td><td>$response_code</td></tr>".
            "<tr><td>Authorization Code</td><td>{$this->responses['authcode']}</td></tr>".
            "<tr><td>Transaction ID</td><td>{$this->responses['transactionid']}</td></tr>".
            "<tr><td>Reference</td><td>{$this->responses['orderid']}</td></tr>".
            '</table>';
        dde("_doPost: ".print_r($this->responses,true),'payments.log');
		return $this->responses;
	}/*}}}*/

    function _doQuery($query) {/*{{{*/
        $url = 'https://secure.tnbcigateway.com/api/query.php?'.$query;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        if (!($data = curl_exec($ch))) {
            return ERROR;
        }
        curl_close($ch);
        unset($ch);

        $xml = new SimpleXMLElement($data);

        return $xml->customer_vault->customer;

    }/*}}}*/

}


?>
