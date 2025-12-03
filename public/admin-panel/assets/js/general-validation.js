'use strict';
const generalFormValidation = document.querySelector('#general-form-validation');

document.addEventListener('DOMContentLoaded', function (e) {
    (function () {
        if (generalFormValidation) {
            const fv = FormValidation.formValidation(generalFormValidation, {
                fields: {
                    mobile: {
                        validators: {
                            notEmpty: {
                                message: 'لطفا موبایل را وارد کنید'
                            },
                            regexp: {
                                regexp: /((0?9)|(\+?989))\d{2}\W?\d{3}\W?\d{4}/i,
                                message: 'لطفا یک شماره موبایل معتبر وارد کنید'
                            }
                        }
                    },
                    first_name: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن نام الزامی است'
                            },
                            stringLength: {
                                min: 3,
                                max: 30,
                                message: 'نام وارد شده باید بیشتر از 3 و کمتر از 30 حرف باشد'
                            },
                        }
                    },
                    last_name: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن نام خانوادگی الزامی است'
                            },
                            stringLength: {
                                min: 3,
                                max: 30,
                                message: 'نام خانوادگی وارد شده باید بیشتر از 3 و کمتر از 30 حرف باشد'
                            },
                        }
                    },
                    email: {
                        validators: {
                            emailAddress: {
                                message: 'لطفا یک آدرس ایمیل معتبر وارد کنید'
                            }
                        }
                    },
                    gender: {
                        validators: {
                            notEmpty: {
                                message: 'انتخاب جنسیت الزامی است'
                            },
                        }
                    },
                    birth_date: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن تاریخ تولد الزامی است'
                            },
                        }
                    },
                    start_date: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن تاریخ شروع به کار الزامی است'
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
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن نام الزامی است'
                            },
                        }
                    },
                    number: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن شماره الزامی است'
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