'use strict';
const generalFormValidation = document.querySelector('#general-form-validation');

document.addEventListener('DOMContentLoaded', function (e) {
    (function () {
        if (generalFormValidation) {
            const fv = FormValidation.formValidation(generalFormValidation, {
                fields: {
                    title: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن نام الزامی است'
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
                    week_days: {
                        validators: {
                            notEmpty: {
                                message: 'انتخاب روز های برگزاری الزامی است'
                            },
                        }
                    },
                    start_date: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن تاریخ شروع الزامی است'
                            },
                        }
                    },
                    end_date: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن تاریخ پایان الزامی است'
                            },
                        }
                    },
                    start_time: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن ساعت شروع الزامی است'
                            },
                        }
                    },
                    end_time: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن ساعت پایان الزامی است'
                            },
                        }
                    },
                    duration_hours: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن مدت زمان الزامی است'
                            },
                        }
                    },
                    price: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن شهریه الزامی است'
                            },
                        }
                    },
                    capacity: {
                        validators: {
                            notEmpty: {
                                message: 'وارد کردن ظرفیت الزامی است'
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
                    teacher_id: {
                        validators: {
                            notEmpty: {
                                message: 'انتخاب استاد الزامی است'
                            },
                        }
                    },
                    class_room_id: {
                        validators: {
                            notEmpty: {
                                message: 'انتخاب کلاس الزامی است'
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
