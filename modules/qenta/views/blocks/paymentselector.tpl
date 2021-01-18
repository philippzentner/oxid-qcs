[{if !isset($wcsPaymentCount)}]
    [{$oView->getWcsRatePayConsumerDeviceId()}]
    [{ assign var="wcsPaymentCount" value="1"}]
[{/if}]
[{if $sPaymentID == "qcs_ccard" || $sPaymentID == "qcs_ccard-moto"}]
    [{include file="qcs_payment_ccard.tpl"}]
[{elseif $sPaymentID == "qcs_eps"}]
    [{include file="qcs_payment_eps.tpl"}]
[{elseif $sPaymentID == "qcs_giropay"}]
    [{include file="qcs_payment_giropay.tpl"}]
[{elseif $sPaymentID == "qcs_idl"}]
    [{include file="qcs_payment_idl.tpl"}]
[{elseif $sPaymentID == "qcs_installment"}]
    [{include file="qcs_payment_installment.tpl"}]
[{elseif $sPaymentID == "qcs_invoice_b2b" || $sPaymentID == "qcs_invoice_b2c"}]
    [{include file="qcs_payment_invoice.tpl"}]
[{elseif $sPaymentID == "qcs_pbx"}]
    [{include file="qcs_payment_pbx.tpl"}]
[{elseif $sPaymentID == "qcs_sepa-dd"}]
    [{include file="qcs_payment_sepa_dd.tpl"}]
[{elseif $sPaymentID == "qcs_trustpay"}]
    [{include file="qcs_payment_trustpay.tpl"}]
[{elseif $sPaymentID == "qcs_voucher"}]
    [{include file="qcs_payment_voucher.tpl"}]
[{elseif $oView->isWcsPaymethod($sPaymentID)}]
    [{include file="qcs_payment_other.tpl"}]
[{else}]
    [{$smarty.block.parent}]
[{/if}]