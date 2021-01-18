[{if $sPaymentID == "qcs_trustpay"}]
[{ assign var="qmoreCheckoutSeamless_paymentdata_stored" value=$oView->hasQMoreCheckoutSeamlessPaymentData($sPaymentID) }]
[{ assign var="qmoreCheckoutSeamless_paymentdata" value=$oView->getWirecardCheckoutSeamlessPaymentData($sPaymentID) }]
[{oxscript include=$oView->getWirecardStorageJsUrl() priority=1)}]
[{oxscript include=$oViewConf->getModuleUrl('qmorecheckoutseamless','out/src/qenta.js') priority=10}]
<dl>
    <dt>
        <input type="hidden" id="[{$sPaymentID}]_stored" value="[{ $qmoreCheckoutSeamless_paymentdata_stored|intval }]" />
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]">[{$oView->getQcsPaymentLogo($sPaymentID)}]<b>[{ $oView->getQcsRawPaymentDesc($paymentmethod->oxpayments__oxdesc->value)}]</b></label>
        [{if $paymentmethod->getPrice()}]
            [{assign var="oPaymentPrice" value=$paymentmethod->getPrice() }]
            [{if $oViewConf->isFunctionalityEnabled('blShowVATForPayCharge') }]
                ( [{oxprice price=$oPaymentPrice->getNettoPrice() currency=$currency}]
                [{if $oPaymentPrice->getVatValue() > 0}]
                    [{ oxmultilang ident="PLUS_VAT" }] [{oxprice price=$oPaymentPrice->getVatValue() currency=$currency }]
                [{/if}])
                [{else}]
                    ([{oxprice price=$oPaymentPrice->getBruttoPrice() currency=$currency}])
                [{/if}]
            [{/if}]

    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        <ul class="form">
            <li>
                <span>[{ oxmultilang ident="WIRECARDCHECKOUTSEAMLESS_CHOOSE_FINANCIAL_INSTITUTION" }]</span>
                [{html_options name=trustpay_financialInstitution options=$oView->getWirecardCheckoutSeamlessFinancialInstitutions($sPaymentID)}]
            </li>
        </ul>

        [{block name="checkout_payment_longdesc"}]
        [{if $paymentmethod->oxpayments__oxlongdesc->value|trim}]
            <div class="desc">
                [{ $paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
            </div>
        [{/if}]
        [{/block}]
    </dd>
</dl>
    [{/if}]