<?php
/**
 * Shop System Plugins - Terms of Use
 *
 * The plugins offered are provided free of charge by Qenta Payment CEE GmbH
 * (abbreviated to Qenta CEE) and are explicitly not part of the Qenta CEE range of
 * products and services.
 *
 * They have been tested and approved for full functionality in the standard configuration
 * (status on delivery) of the corresponding shop system. They are under General Public
 * License Version 2 (GPLv2) and can be used, developed and passed on to third parties under
 * the same terms.
 *
 * However, Qenta CEE does not provide any guarantee or accept any liability for any errors
 * occurring when used in an enhanced, customized shop system configuration.
 *
 * Operation in an enhanced, customized configuration is at your own risk and requires a
 * comprehensive test phase by the user of the plugin.
 *
 * Customers use the plugins at their own risk. Qenta CEE does not guarantee their full
 * functionality neither does Qenta CEE assume liability for any disadvantages related to
 * the use of the plugins. Additionally, Qenta CEE does not guarantee the full functionality
 * for customized shop systems or installed plugins of other vendors of plugins within the same
 * shop system.
 *
 * Customers are responsible for testing the plugin's functionality before starting productive
 * operation.
 *
 * By installing the plugin into the shop system the customer agrees to these terms of use.
 * Please do not use the plugin if you do not agree to these terms of use!
 */

class Qenta_CheckoutSeamless_Block_Processing extends Mage_Core_Block_Template
{
    /**
     * Return checkout session instance
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Return order instance
     *
     * @return Mage_Sales_Model_Order|null
     */
    protected function _getOrder()
    {
        if ($this->getOrder()) {
            return $this->getOrder();
        } elseif ($this->_getCheckout()->getLastRealOrderId()) {
            $orderIncrementId = $this->_getCheckout()->getLastRealOrderId();
            return Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
        } else {
            return null;
        }
    }


    public function getFormData()
    {
        $params = array();

        $url = parse_url($this->_getOrder()->getPayment()->getMethodInstance()->getUrl());
        $url = explode('&', $url['query']);
        foreach ($url as $currentPair) {
            $currentPair = explode('=', $currentPair);
            $params[$currentPair[0]] = $currentPair[1];
        }

        return $params;
    }

    public function getFormAction()
    {
        $url = parse_url($this->_getOrder()->getPayment()->getMethodInstance()->getUrl());

        return $url['scheme'] . '://' . $url['host'] . $url['path'];
    }
}