document.observe('firecheckout:setResponseBefore', function (e) {
    if (e.memo.url !== checkout.urls.save || payment.getCurrentMethod() !== 'payfortcc') {
        return;
    }

    delete e.memo.response.redirect;
    delete e.memo.response.success;

    var url = checkout.urls.billing_address.replace(
        'firecheckout/index/saveBilling',
        'payfort/payment/getMerchantPageData'
    );
    if ($('div-pf-iframe')) { // @see payfort's merchant-page.phtml
        payfortFortMerchantPage.submitMerchantPage(url);
    } else {
        payfortFortMerchantPage2.submitMerchantPage(payment.getCurrentMethod(), url);
    }
});
