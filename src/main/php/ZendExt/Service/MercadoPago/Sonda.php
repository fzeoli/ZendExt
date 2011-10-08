<?php 
/*

   Copyright 2011 Monits
 
   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.

*/
/**
 * API for Mercado Pagos Sonda WebService.
 *
 * @category  ZendExt
 * @package   ZendExt_Service_MercadoPago
 * @copyright 2010 Monits
 * @license   Copyright (C) 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @link      http://www.zendext.com/
 * @since     1.3.0
 */

/**
 * API for Mercado Pagos Sonda WebService.
 *
 * @category  ZendExt
 * @package   ZendExt_Service_MercadoPago
 * @author    jpcivile <jpcivile@monits.com>
 * @copyright 2010 Monits
 * @license   Copyright 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @link      http://www.zendext.com/
 * @since     1.3.0
 */
class ZendExt_Service_MercadoPago_Sonda
{
    const SONDA_URI = 'https://www.mercadopago.com/mla/sonda';

    const MERCADOPAGO_ID = 'mp_op_id';

    const ACCOUNT_ID = 'acc_id';

    const TOKEN = 'sonda_key';

    const OPERATION_ID = 'seller_op_id';

    const SUCCESS = 'OK';

    protected $_accountId;

    protected $_token;

    protected $_mpId;

    protected $_operationId;

    protected $_data = null;

    protected $_auth;

    /**
     * Construct a new instance.
     *
     * @param integer $accountId   The Mercado Pago account id.
     * @param string  $token       The accounts Sonda token.
     * @param integer $mpId        The payments' mercado pago id.
     * @param string  $operationId Optional. The operation id set by the seller.
     * @param boolean $auth        Optional. Whether to validate the connection.
     */
    public function __construct($accountId, $token, $mpId, $operationId = null, 
        $auth = true)
    {
        $this->_accountId = $accountId;
        $this->_token = $token;
        $this->_mpId = $mpId;
        $this->_operationId = $operationId;

        $this->_auth = $auth;
    }

    /**
     * Get the payment data from sonda.
     *
     * @return ZendExt_Service_MercadoPago_Payment
     */
    public function getPaymentData()
    {
        $this->_makeRequest();

        return $this->_data;
    }

    /**
     * Make the request to Sonda.
     *
     * @return void
     *
     * @throws ZendExt_Service_MercadoPago_Sonda_Exception
     */
    protected function _makeRequest()
    {
        $client = new Zend_Http_Client(self::SONDA_URI);

        if ($this->_auth) {

            $adapter = new Zend_Http_Client_Adapter_Curl();
            $adapter->setConfig(
                array(
                    'curloptions' => array(
                        CURLOPT_SSL_VERIFYPEER => true,
                        CURLOPT_SSL_VERIFYHOST => true
                    )   
                )   
            );  

            $client->setAdapter($adapter);
        }

        $client->setMethod('POST')
            ->setParameterPost(self::MERCADOPAGO_ID, $this->_mpId)
            ->setParameterPost(self::ACCOUNT_ID, $this->_accountId)
            ->setParameterPost(self::TOKEN, $this->_token)
            ->setParameterPost(self::OPERATION_ID, $this->_operationId);

        try {

            $response = $client->request();
        } catch (Zend_Http_Client_Exception $e) {

            throw new ZendExt_Service_MercadoPago_Sonda_Exception(
                'Could not complete the request.'.PHP_EOL.$e->__toString()
            );
        }

        if (!$response->getBody() || $response->isError()) {

            throw new ZendExt_Service_MercadoPago_Sonda_Exception(
                'Got an empty or error response from Sonda'
            );
        }

        $this->_parseResponse($response);
    }

    /**
     * Parse a Sonda response.
     *
     * @param Zend_Http_Response $response The response object.
     *
     * @return void
     *
     * @throws ZendExt_Service_MercadoPago_Sonda_Exception
     */
    protected function _parseResponse(Zend_Http_Response $response)
    {
        $responseData = new SimpleXMLElement($response->getBody());

        $message = $responseData->xpath('//message');
        if (false === $message) {

            throw new ZendExt_Service_MercadoPago_Sonda_Exception(
                'Invalid response'
            );
        }

        if (self::SUCCESS != array_shift($message)) {

            $this->_data = null;
            return;
        }

        $operation = $responseData->xpath('//operation');
        if (false === $operation) {

            throw new ZendExt_Service_MercadoPago_Sonda_Exception(
                'Invalid response'
            );
        }

        $result = array();
        $operation = array_shift($operation);
        foreach ($operation->children() as $key => $value) {

            $result[$key] = (string) $value;
        }

        $this->_data = new ZendExt_Service_MercadoPago_Payment($result);
    }
}
