<?php
/**
 * Shop System Plugins
 * - Terms of use can be found under
 * https://guides.qenta.com/shop_plugins:info
 * - License can be found under:
 * https://github.com/qenta-cee/oxid-qcs/blob/master/LICENSE
*/

class wirecardcheckoutseamlessoxpaymentlist extends wirecardcheckoutseamlessoxpaymentlist_parent
{
    public function getPaymentList($sShipSetId, $dPrice, $oUser = null)
    {
        $paymentList = parent::getPaymentList($sShipSetId, $dPrice, $oUser);

        if (array_key_exists('qcs_invoice_b2b', $paymentList) || array_key_exists('qcs_invoice_b2c',
                $paymentList) || array_key_exists('qcs_installment', $paymentList)
        ) {
            $dob = $oUser->oxuser__oxbirthdate->value;
            $oBasket = $this->getSession()->getBasket();
            $oOrder = oxNew('oxorder');
            $config = wirecardCheckoutSeamlessConfig::getInstance();

            if (array_key_exists('qcs_invoice_b2c', $paymentList)) {
                if (!$this->_isWCSInvoiceAvailable($oUser, $oBasket,
                        $oOrder) || !empty($oUser->oxuser__oxcompany->value)
                ) {
                    unset($paymentList['qcs_invoice_b2c']);
                } elseif ($dob && $dob == '0000-00-00' && $config->getInvoiceProvider() == 'PAYOLUTION') {
                    $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
                    $oSmarty->assign("bShowDobField", true);

                    $dobData = oxRegistry::getSession()->getVariable('qcs_dobData');
                    if (!empty($dobData)) {
                        $oSmarty->assign("dobData", oxRegistry::getSession()->getVariable('qcs_dobData'));
                    }
                }
            }
            if (array_key_exists('qcs_invoice_b2b', $paymentList)) {
                $vatId = $oUser->oxuser__oxustid->value;

                if (!$this->_isWCSInvoiceAvailable($oUser, $oBasket,
                        $oOrder) || empty($oUser->oxuser__oxcompany->value)
                ) {
                    unset($paymentList['qcs_invoice_b2b']);
                }
                if ($config->getInvoiceProvider() == 'PAYOLUTION') {
                    $sVatId = oxRegistry::getSession()->getVariable('qcs_vatId');
                    if (empty($vatId)) {
                        $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
                        $oSmarty->assign("sVatId", $sVatId);
                        $oSmarty->assign("bShowVatIdField", true);
                    }
                }
            }

            if (array_key_exists('qcs_installment', $paymentList)) {
                if (!$this->_isWCSInstallmentAvailable($oUser, $oBasket, $oOrder)) {
                    unset($paymentList['qcs_installment']);
                } elseif ($dob && $dob == '0000-00-00' && $config->getInstallmentProvider() == 'PAYOLUTION') {
                    $oSmarty = oxRegistry::get("oxUtilsView")->getSmarty();
                    $oSmarty->assign("bShowDobField", true);

                    $dobData = oxRegistry::getSession()->getVariable('qcs_dobData');
                    if (!empty($dobData)) {
                        $oSmarty->assign("dobData", oxRegistry::getSession()->getVariable('qcs_dobData'));
                    }
                }
            }
        }

        if (array_key_exists('qcs_ccard-moto', $paymentList)) {
            if (!$this->getUser()->inGroup('oxidadmin')) {
                unset($paymentList['qcs_ccard-moto']);
            }
        }

        $this->_aArray = $paymentList;

        return $this->_aArray;
    }

    /**
     * check if paymentType invoice is available
     * @param oxUser $oUser
     * @return boolean
     */
    protected function _isWCSInvoiceAvailable($oUser, $oBasket, $oOrder)
    {
        if (!($oUser || $oBasket || $oOrder)) {
            return false;
        }

        $oPayment = oxNew("wirecardCheckoutSeamlessPayment");
        $config = wirecardCheckoutSeamlessConfig::getInstance();

        if (!$oPayment->wcsValidateCustomerAge($oUser)) {
            return false;
        }
        if (!($config->getInvoiceAllowDifferingAddresses() && $config->getInvoiceProvider() == 'PAYOLUTION') && !$oPayment->wcsValidateAddresses($oUser,
                $oOrder)
        ) {
            return false;
        }
        if (!$oPayment->wcsValidateCurrency($oBasket)) {
            return false;
        }

        return true;
    }

    /**
     * check if paymentType installment is available
     * @param oxUser $oUser
     * @return boolean
     */
    protected function _isWCSInstallmentAvailable($oUser, $oBasket, $oOrder)
    {
        if (!($oUser || $oBasket || $oOrder)) {
            return false;
        }

        $oPayment = oxNew("wirecardCheckoutSeamlessPayment");
        $config = wirecardCheckoutSeamlessConfig::getInstance();

        if (!$oPayment->wcsValidateCustomerAge($oUser)) {
            return false;
        }
        if (!($config->getInstallmentAllowDifferingAddresses() && $config->getInstallmentProvider() == 'PAYOLUTION') && !$oPayment->wcsValidateAddresses($oUser,
                $oOrder)
        ) {
            return false;
        }
        if (!$oPayment->wcsValidateCurrency($oBasket)) {
            return false;
        }

        return true;
    }
}