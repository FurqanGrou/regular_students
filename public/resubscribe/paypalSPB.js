var __paypalSPB = {
    init: function() {
        this.wrapperSelector = '.paypal-smart-payment-buttons-wrapper';

        this.processID = Date.now().toString();
        this.paymentReady = false;
        this.required = false;

        this.wrapper = (function(){
            var wrapper = $$(this.wrapperSelector)[0];
            if (typeof wrapper === 'undefined') {
                throw 'Error: PayPal checkout data is missing.';
            }
            return wrapper;
        }.bind(this))();

        var settings;
        this.settings = settings = (function(){
            var settingsData = {
                clientid: this.wrapper.getAttribute('data-clientid'),
                currency: this.wrapper.getAttribute('data-currency'),
                environment: this.wrapper.getAttribute('data-environment'),
                merchantid: this.wrapper.getAttribute('data-merchantid'),
                styleColor: this.wrapper.getAttribute('data-style-color'),
                styleLabel: this.wrapper.getAttribute('data-style-label'),
                styleLayout: this.wrapper.getAttribute('data-style-layout'),
                styleShape: this.wrapper.getAttribute('data-style-shape'),
                styleSize: this.wrapper.getAttribute('data-style-size'),
                paypalButton: this.wrapper.getAttribute('data-paypal-button'),
                paypalButtonQid: this.wrapper.getAttribute('data-paypal-button-qid'),
            };

            var clientID = null;

            if (!!settingsData.clientid) {
                clientID = settingsData.clientid
            } else { // If user hasn`t a clientID then used our partnership credentials.
                if (settingsData && settingsData.environment == 'sandbox') {
                    clientID = 'AbO4nZJsmfTgf8GbpnV-AY4382evohAYeDcuwqoAvvrKDDN_qOYa3K5biPFub70U40iPcpl0wtwkkMp2';
                } else {
                    clientID = 'Afo1LVZtoaCSq5HI_naZpUMjB2C0_OiB6nNHlGaNe7jwBTunPXnbodmCr4ZTtpL3WT-4RkNG6DQFvX03';
                }
            }

            var currency = null;
            if (JotForm.pricingInformations && JotForm.pricingInformations.general) {
                currency = JotForm.pricingInformations.general.currency;
            }

            // Generated Translation
            var markupLang = document.querySelector('html').getAttribute('lang');
            if (typeof markupLang !== 'undefined' && markupLang !== null) {

                var lang = markupLang + '-' + markupLang.toLocaleUpperCase();
                JotForm._xdr(JotForm.getAPIEndpoint() + '/translationList', 'post', JotForm.serialize({
                        'data': "[\"" + "The Payment of <b> " + "{payment_amount}" + " " + "{payment_currency}" + " </b> is ready! It will be completed when you submit the form." + "\"]",
                        'lang': lang
                    }),
                    function (success) {
                        if (success.content) {
                            var translationObjectKey = Object.keys(success.content)[0];
                            this.postAuthenticationPaymentMessage = success.content[translationObjectKey];
                        }
                    },
                    function (error) {
                        // Nothing
                    });
            }

            var styleSizes = { small: 30, medium: 48, large: 55, responsive: 48 };

            return {
                clientID: clientID,
                currency: settingsData.currency || currency,
                environment: settingsData.environment,
                merchantId: settingsData.merchantid,
                style: {
                    color: settingsData.styleColor || 'gold',
                    label: settingsData.styleLabel || 'checkout',
                    layout: settingsData.styleLayout || 'vertical',
                    shape: settingsData.styleShape || 'rect',
                    height: styleSizes[settingsData.styleSize] || 48
                },
                paypalButton: settingsData.paypalButton === 'Yes',
                paypalButtonQid: settingsData.paypalButtonQid
            };
        }.bind(this))();

        var utils;
        this.utils = utils = {
            formType: window.FORM_MODE === 'cardform' ? 'cardform' : 'classic',

            disablePaymentFieldInputs: function() {
                var paymentFieldSelector = ".form-line[data-type='control_paypalSPB']";
                if (utils.formType === 'cardform') {
                    var productCardSelector = ".jfCard[data-type='control_paypalSPB'] .jfCard-question";
                    $$(paymentFieldSelector + " " + productCardSelector)[0].style.pointerEvents = "none";
                    return;
                }
                $$(paymentFieldSelector + " input").invoke('writeAttribute','readonly');
                $$(paymentFieldSelector + " select").each(function (el) {
                    el.addEventListener('mousedown', function(e) {
                        e.preventDefault();
                        this.blur();
                        window.focus();
                    });
                });
                $$(paymentFieldSelector + " input").each(function (el) {
                    el.stopObserving('click');
                });
                $$(paymentFieldSelector + " input").invoke('writeAttribute','onclick','return false');
            },

            showPaymentError: function(msg) {
                msg = 'Payment Error: ' + msg;
                var paymentField = utils.getPaymentField();
                var paymentInput = this.getPaymentInput();

                JotForm.errored(paymentInput, msg);
                utils.formType === 'cardform' && JotForm.showButtonMessage(msg);
                paymentField.observe('click', function() {
                    utils.removePaymentError();
                    paymentField.stopObserving('click');
                });
            },

            removePaymentError: function() {
                var paymentField = this.getPaymentField();
                var paymentInput = this.getPaymentInput();

                JotForm.corrected(paymentField);

                if (paymentInput) {
                    JotForm.corrected(paymentInput);
                }

                utils.formType === 'cardform' && JotForm.hideButtonMessage();
                paymentField.classList.remove("form-validation-error");
            },

            addSubmissionData: function(key, val) {
                var form = utils.getForm();
                $(key) && $(key).remove();
                form.insert(new Element('input', {
                    type: 'hidden',
                    name:  key,
                    id:    key,
                    value: val
                }));
            },

            changeSubmissionData: function(key, val) {
                if ($$('#' + key).length === 0) return;
                $$('#' + key)[0].value = val;
            },

            getPaymentField: function() {
                return $$('.form-line[data-type="control_paypalSPB"]')[0];
            },

            paymentType: function() {
                return (typeof window.paymentType == 'undefined') ? 'donation' : window.paymentType;
            },

            getPaymentInput: function() {
                return $$('input#cc_paypalSPB_orderID')[0];
            },

            paymentType: function() {
                return (typeof window.paymentType == 'undefined') ? 'donation' : window.paymentType;
            },

            getPaymentFieldID: function() {
                return this.getPaymentField().id.split('_')[1];
            },

            isDonation: function() {
                var isPaymentDonation = $$('[data-component="paymentDonation"]');
                return isPaymentDonation[0] ? true : false;
            },

            getDonationItem: function() {
                if(!utils.isDonation()) {
                    return null;
                }

                var itemName;
                if (utils.formType === 'cardform') {
                    itemName = $$('#id_' + utils.getPaymentFieldID() + ' label.jfField-sublabel')[0] && $$('#id_' + utils.getPaymentFieldID() + ' label.jfField-sublabel')[0].textContent.trim();
                }
                else {
                    itemName = $$('#id_' + utils.getPaymentFieldID() + ' label.form-sub-label')[0] && $$('#id_' + utils.getPaymentFieldID() + ' label.form-sub-label')[0].textContent.trim();

                    if (!itemName || itemName === '') {
                        itemName = $$('#id_' + utils.getPaymentFieldID() + ' label.form-label')[0].textContent.trim();
                    }
                }

                return {
                    name: itemName || 'Donation',
                    quantity: 1,
                    unit_amount: { value: parseFloat(JotForm.paymentTotal), currency_code: settings.currency }
                };
            },

            removeRequired: function(elem) {
                //remove any validation for the field sto works best with stripe validation
                //if the product as a class name validate, set the requried var to true
                var dClassName = elem.className;

                if(dClassName.indexOf('required') > -1) {
                    this.required = true;
                }

                var dRegex = /validate\[(.*)\]/;
                if ( dClassName.search(dRegex) > -1 ){
                    elem.className = dClassName.replace(dRegex, '');
                }
            },


            getPrice: function(type) {
                if (!JotForm.pricingInformations) { // If the payment type not equal to sell products, then use this condition.
                    if (type === "total" || type === "item_total") {
                        return parseFloat(JotForm.paymentTotal);
                    } else {
                        return 0
                    }
                }

                var total = JotForm.pricingInformations.general.net_amount || 0;
                var item_total = JotForm.pricingInformations.general.item_total || 0;
                var tax_total = JotForm.pricingInformations.general.tax_total || 0;
                var discount = JotForm.pricingInformations.general.discount || 0;
                var shipping = JotForm.pricingInformations.general.shipping || 0;

                if (discount == 0) {
                    total = JotForm.pricingInformations.general.total_amount;
                }

                if (type === "total") {
                    // console.log("Total: ", total);
                    return total;
                } else if (type === "item_total") {
                    // console.log("Item Total: ", item_total);
                    return item_total;
                } else if (type === "tax_total") {
                    // console.log("Tax Total: ", tax_total);
                    return tax_total;
                } else if (type === "shipping") {
                    // console.log("Shipping Total: ", shipping);
                    return shipping
                } else if (type === "discount") {
                    // console.log("Discount Total: ", discount);
                    return discount;
                }
            },

            getItems: function() {
                if(self.utils.isDonation()) {
                    return [utils.getDonationItem()];
                }

                if (!JotForm.pricingInformations) {
                    return null
                }

                var items = [];

                if (JotForm.pricingInformations.items && JotForm.pricingInformations.items.length >= 1) {
                    JotForm.pricingInformations.items.forEach(function(item) {
                        items.push(self.utils.getItemProperties(item));
                    });
                }

                if (JotForm.pricingInformations.noCostItems && JotForm.pricingInformations.noCostItems.length >= 1) {
                    JotForm.pricingInformations.noCostItems.forEach(function(item) {
                        items.push(self.utils.getItemProperties(item));
                    });
                }

                // console.log(items);
                return items;
            },

            getItemProperties: function(item) {
                var properties = {}, currency = JotForm.pricingInformations.general.currency;

                Object.keys(item).forEach(function(property)Â {
                    if (property == "tax" && item['tax'] === 0) {
                        // properties[property] = 0;
                    } else if (property === "name") {
                        properties[property] = item[property].substr(0, 124);
                    } else if (property == "unit_amount") {
                        properties[property] = {
                            value: item[property],
                            currency_code: currency
                        }
                    } else {
                        properties[property] = item[property];
                    }
                });

                return properties;
            },

            getForm: function() {
                return typeof JotForm.forms[0] == "undefined"
                    ? $($$('.jotform-form')[0])
                    : JotForm.forms[0];
            },

            submitWarn: function() {
                var $this = this;
                var paymentField = this.getPaymentField();
                var paymentInput = this.getPaymentInput();

                paymentField.classList.add('form-validation-error');

                $$('.form-submit-button').each(function (button) {
                    var submitText = button.innerText;
                    button.disable();
                    $this.showPaymentError('Please authorize the payment using PayPal\'s buttons before submitting.');
                    setTimeout(function() {
                        button.innerText = 'Submission Error!';
                        setTimeout(function() {
                            button.innerText = submitText;
                            button.enable();
                            JotForm.hideButtonMessage();
                        }, 5000);
                    }, 100);
                });
            },

            isDonationInputValid: function(dAmount) {
                if ( dAmount == "" || dAmount == 0 ) return false;
                // console.log('amount', dAmount, (dAmount && /^\d+$/.test(dAmount)));
                return Boolean(dAmount && /^\d+(?:[\.,]\d+)?$/.test(dAmount));
            },

            formHasErrors: function() {
                var hasErrors = false;
                $$("li.form-line").each(function(e,index){
                    if( e.hasClassName('form-line-error') )
                    {
                        hasErrors = true;
                    }
                });

                return hasErrors;
            },

            hasProductsSet: function() {
                var isSet = false;
                var self = this;

                // check whether a product is selected or not for non-donation
                if(this.isDonation()) {
                    var donationElem = $('input_' + this.getPaymentFieldID() + '_donation');

                    if (donationElem) {
                        self.removeRequired(donationElem);
                        if (this.isDonationInputValid(donationElem.getValue())) {
                            isSet = true;
                        }
                    }
                } else {
                    $H(window.productID).each(function(pair){
                        var elem = $(pair.value);

                        //if the product as a class name validate, set the requried var to true
                        //remove any validation for the field sto works best with stripe validation
                        self.removeRequired( elem );

                        if(elem.checked)
                        {
                            isSet = true;
                        }
                    });
                }

                return isSet;
            },

            clearPaymentDetails: function() {
                var orderID = $$('input#cc_paypalSPB_orderID')[0];
                var payerID = $$('input#cc_paypalSPB_payerID')[0];

                orderID.setValue('');
                payerID.setValue('');

                if(this.isDonation()) {
                    var paymentID = this.getPaymentFieldID();
                    if ($('input_' + paymentID + '_donation')) {
                        $('input_' + paymentID + '_donation').setValue('');
                    }
                }
            },

            handleIframe: function(delay) {
                if (window.parent && window.parent != window) {
                    setTimeout(function() {
                        JotForm.handleIFrameHeight();
                    }, isNaN(delay) ? 500 : delay);
                }
            }
        };

        /**
         * UTILS END..
         */

        if (JotForm.isEditMode()) {
            this.utils.disablePaymentFieldInputs();
            this.wrapper.style.pointerEvents = 'none';
        } else {
            var self = this;
            Event.observe(this.utils.getForm(), 'submit', function (event) {
                var paymentInput = self.utils.getPaymentInput();
                var paymentField = self.utils.getPaymentField();

                // Enable fields before submit
                $$('#id_' + self.utils.getPaymentFieldID() + ' .form-checkbox, .form-radio').each(function (el) {
                    var gateway = el.up('.form-line') ? el.up('.form-line').getAttribute('data-type') : null;
                    if(gateway === 'control_paypalSPB') {
                        el.enable();
                    }
                });

                if ((!JotForm.isVisible(paymentField) && JotForm.getSection(paymentField).id)  //  inside a hidden (not collapsed) form collapse
                    || $('id_' + self.utils.getPaymentFieldID()).getStyle('display') === "none"   //  hidden by condition
                    || !JotForm.validateAll()                                               //  failed validation
                    || (parseFloat(JotForm.paymentTotal) <= 0 &&  (self.utils.paymentType() == 'product'))  // do not require payer info if payment total is zero on product purchase
                ){
                    return;
                }

                self.utils.removePaymentError();

                var isOrderIdTaken = (paymentInput.value === '' || paymentInput.value.length <= 0) ? false : true;

                if (
                    !self.utils.formHasErrors() &&
                    !self.utils.hasProductsSet() &&
                    !isOrderIdTaken &&
                    !self.required
                ) {
                    self.utils.clearPaymentDetails();
                } else {
                    var errors = '';

                    if (!self.utils.hasProductsSet()) {
                        if (self.utils.isDonation()) {
                            errors += JotForm.texts.ccMissingDonation;
                        } else {
                            errors += JotForm.texts.ccMissingProduct;
                        }
                    } else if (self.utils.hasProductsSet() && !isOrderIdTaken) {
                        errors += 'Please authorize the payment using PayPal\'s buttons before submitting.';
                    }

                    if (errors.length > 0) {
                        Event.stop(event);
                        self.utils.showPaymentError(errors);
                    }
                }
            });
        }

        window.fakePaymentCompletion = function () {
            $$(this.wrapperSelector)[0].style.display = "none";
            $$('.complete-payment-prompt')[0].style.display = "block";
            this.utils.changeSubmissionData('cc_paypalSPB_orderID', 'aa');
        }.bind(this);
    },

    onApproveFunction: function() {
        return function(authData, actions) {
            return actions.order.get().then(function(data) {
                var $this = this;
                // console.log('AUTHDATA: ', authData);

                $$('.form-submit-button:not(.form-sacl-button').each(function (button) {
                    button.innerText = 'Submit and Pay';
                });

                if (!this.settings.paypalButton) {
                    var translatedText = "The Payment of <b>" +
                        "{payment_amount}" +
                        " " +
                        "{payment_currency}" +
                        "</b> is ready! It will be completed when you submit the form.";

                    if(typeof self.postAuthenticationPaymentMessage !== 'undefined') {
                        translatedText = self.postAuthenticationPaymentMessage;
                    }

                    translatedText = translatedText.replace('{payment_amount}', data.purchase_units[0].amount.value);
                    translatedText = translatedText.replace('{payment_currency}', data.purchase_units[0].amount.currency_code);

                    $$('.complete-payment-prompt')[0].innerHTML = translatedText;

                    $$('.complete-payment-prompt')[0].style.display = "block";
                    var paymentPrice = $$('.form-payment-price')[0];
                    if (paymentPrice) {
                        paymentPrice.classList.add('ready');
                    }
                }

                $$(this.wrapperSelector)[0].style.display = "none";

                this.utils.removePaymentError();
                this.utils.disablePaymentFieldInputs();

                var orderID = $$('#cc_paypalSPB_orderID')[0];
                var payerID = $$('#cc_paypalSPB_payerID')[0];

                if (!orderID || !payerID) {
                    this.utils.addSubmissionData('paypalSPB_orderID', authData.orderID);
                    this.utils.addSubmissionData('paypalSPB_payerID', authData.payerID);
                } else {
                    this.utils.changeSubmissionData('cc_paypalSPB_orderID', authData.orderID);
                    this.utils.changeSubmissionData('cc_paypalSPB_payerID', authData.payerID);
                }

                this.utils.addSubmissionData('paypalSPB_processID', $this.processID);

                this.paymentReady = true;

                if (this.settings.paypalButton) {
                    var submitButton = $$('.form-line#id_' + this.settings.paypalButtonQid + ' .form-submit-button[type="submit"]')[0];
                    submitButton.click();
                }
            }.bind(this));
        }.bind(this);
    },

    getOnErrorFunction: function() {
        return function (err) {
            console.warn(err);
            var error = String(err);
            console.log("ERROR: ", error);
            try {
                var parsedError = JSON.parse(error.match(/{[^]*}/)[0]);
                this.utils.showPaymentError(parsedError.error_description || parsedError.details[0].issue ||  'Payment error occurred. Please check console.');
                var $this = this;
                var event_type = parsedError['details'][0] ? parsedError['details'][0]['issue'] : parsedError['name'];

            } catch (e) {
                var $this = this;
                this.utils.showPaymentError(error ||Â 'Payment error occurred. Please check console.');

            }
        }.bind(this)
    },

    beforeCreateOrder: function(data, actions) {

        var isValidate = this.settings.paypalButton;
        var paymentField = this.utils.getPaymentField();

        if (isValidate) {
            if (!JotForm.validateAll()
                || (!JotForm.isVisible(paymentField) && JotForm.getSection(paymentField).id)  //  inside a hidden (not collapsed) form collapse
                || $('id_' + this.utils.getPaymentFieldID()).getStyle('display') === "none")   //  hidden by condition
            {
                var submitButton = $$('.form-line#id_' + this.settings.paypalButtonQid + ' .form-submit-button[type="submit"]')[0];
                JotForm.errored(submitButton, 'There are errors on your form. Please correct them to continue checkout.');

                setTimeout(function() {
                    JotForm.corrected(submitButton);
                }, 5000);

                return actions.reject();
            }
            else if((parseFloat(JotForm.paymentTotal) <= 0 &&  (this.utils.paymentType() == 'product'))) {
                var submitButton = $$('.form-line#id_' + this.settings.paypalButtonQid + ' .form-submit-button[type="submit"]')[0];
                submitButton.click();
                return actions.reject();
            }
        } else {
            if (this.utils.getPrice('total') == 0) {
                var isPaymentDonation = $$('[data-component="paymentDonation"]');
                if (isPaymentDonation[0]) {
                    this.utils.showPaymentError('Please enter a larger price');
                    return actions.reject();
                } else {
                    this.utils.showPaymentError('Please select at least one product');
                }
            }
        }
    },

    createOrder: function() {
        $this = this;
        var originLocation =  window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');

        return new Promise(function(resolve, reject) {
            var data = {
                intent: "CAPTURE",
                purchase_units: [
                    {
                        amount: {
                            currency_code: $this.settings.currency,
                            value: $this.utils.getPrice('total'),
                            breakdown: {
                                item_total: {
                                    value: $this.utils.getPrice('item_total'),
                                    currency_code: $this.settings.currency,
                                },
                                tax_total: {
                                    value: $this.utils.getPrice('tax_total'),
                                    currency_code: $this.settings.currency
                                },
                                shipping: {
                                    value: $this.utils.getPrice('shipping'),
                                    currency_code: $this.settings.currency
                                },
                                discount: {
                                    value: $this.utils.getPrice('discount'),
                                    currency_code: $this.settings.currency
                                }
                            }
                        },
                        payee: {
                            merchant_id: $this.settings.merchantId
                        },
                        description: "Payment form: " + originLocation + window.location.pathname,
                        items: $this.utils.getItems(),
                    }
                ]
            };

            $this.utils.handleIframe(500);

            new Ajax.Jsonp(JotForm.server, {
                parameters: {
                    action: 'createPaypalOrder',
                    data: JSON.stringify(data),
                    environment: $this.settings.environment,
                    isMerchantId: $this.settings.merchantId ? true : false,
                    processID: $this.processID,
                    formId: $this.utils.getForm().id
                },
                evalJSON: 'force',
                onComplete: function(t) {
                    if (t.responseJSON.id) {
                        resolve(t.responseJSON.id);
                        $this.utils.handleIframe(4000);
                    } else {
                        reject("Payment can't created");
                    }
                }
            });
        });
    },


    renderMessages: function() {
        if (paypal && typeof paypal.Messages === 'function' && JotForm.paymentProperties && JotForm.paymentProperties.payLaterEnabled === 'Yes' && this.utils.paymentType() == 'product') {
            paypal.Messages({
                amount: this.utils.getPrice('total'),
                placement: 'payment',
                style: {
                    layout: JotForm.paymentProperties.payLaterLayout || '',
                    text: {
                        color: JotForm.paymentProperties.payLaterTextColor || '',
                        size: JotForm.paymentProperties.payLaterTextSize || '',
                    },
                    logo: {
                        type: JotForm.paymentProperties.payLaterLogoType || '',
                        position: JotForm.paymentProperties.payLaterLogoPosition || '',
                    },
                    color: JotForm.paymentProperties.payLaterBackgroundColor || '',
                    ratio: JotForm.paymentProperties.payLaterRatio || '',
                },
            }).render('.paypal-paylater-messages .message-area');
        }
    },

    render: function() {
        var wrapperSelector = this.wrapperSelector;
        var submitButton = $$('.form-line#id_' + this.settings.paypalButtonQid + ' .form-submit-button')[0];

        if (submitButton && this.settings.paypalButton && this.settings.paypalButtonQid) {
            wrapperSelector = 'li[data-type="control_button"]#id_' + this.settings.paypalButtonQid + ' .paypal-submit-button-wrapper';
            var buttonContainer = JotForm.getContainer(submitButton);

            if (buttonContainer) {
                buttonContainer.setAttribute('paypal-button-status', 'show');
            }

            var paymentField = this.utils.getPaymentField();
            paymentField.observe('click', function() {
                if (buttonContainer)
                    buttonContainer.setAttribute('paypal-button-status', 'show');
            });
        }

        if (typeof paypal === 'undefined') {
            throw 'Error: PayPal checkout script was not loaded.';
        }

        if (typeof this.utils.getPaymentField() !== 'undefined') {
            this.wrapper.innerHTML = '';
            this.renderMessages();
            paypal.Buttons({
                onClick: this.beforeCreateOrder.bind(this),
                createOrder: this.createOrder.bind(this),
                onApprove: this.onApproveFunction(),
                onError: this.getOnErrorFunction(),
                style: this.settings.style
            }).render(wrapperSelector);
        } else {
            this.wrapper.parentNode.remove();
        }
    }
};
