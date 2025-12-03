'use strict';
const marketingSmsTemplateFormValidation = document.querySelector('#marketing-sms-template-form-validation');

document.addEventListener('DOMContentLoaded', function (e) {
    (function () {
        if (marketingSmsTemplateFormValidation) {
            const fv = FormValidation.formValidation(marketingSmsTemplateFormValidation, {
                fields: {
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن عنوان الزامی است'
                            },
                            stringLength: {
                                min: 3,
                                max: 30,
                                message: 'عنوان وارد شده باید بیشتر از 3 و کمتر از 30 حرف باشد'
                            },
                        }
                    },
                    target_type: {
                        validators: {
                            notEmpty: {
                                message: 'انتخاب نوع گیرنده الزامی است'
                            },
                        }
                    },
                    branch_id: {
                        validators: {
                            notEmpty: {
                                message: 'انتخاب شعبه الزامی است'
                            },
                        }
                    },
                    profession_id: {
                        validators: {
                            notEmpty: {
                                message: 'انتخاب حرفه الزامی است'
                            },
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: new FormValidation.plugins.Bootstrap5({
                        eleValidClass: '',
                        rowSelector: '.mb-3'
                    }),
                    submitButton: new FormValidation.plugins.SubmitButton(),

                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    autoFocus: new FormValidation.plugins.AutoFocus()
                },
                init: instance => {
                    instance.on('plugins.message.placed', function (e) {
                        if (e.element.parentElement.classList.contains('input-group')) {
                            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                        }
                    });
                }
            });
        }
    })();
});