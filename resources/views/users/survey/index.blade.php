@extends('users.layouts.master')

@section('content')
<div class="row text-center  justify-center m-4 rtl ">
    <div class="card  ">
        <div class="card-header font-18 ">
            فرم نظرسنجی
        </div>
        <hr>
        <div class="card-body shadow1 ">
            @if (session()->has('message'))
            <div class="text-center alert-warning p-3 mb-3">{{ session()->get('message') }}</div>
            @endif
            <div class="massage_create"></div>
            <form method="post" action="{{ route('user.documents.storeSurvey',[$courseRegister->id]) }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 ">
                        <label for="comment" class="float-start mb-2">لطفا برای بهبود وضعیت آموزشی، پیشنهادات و انتقادات خود را بیان کنید.</label>
                        <textarea class="form-control    mt-3" name="comment" id="comment" placeholder="پیشنهادات و نظرات شما..."></textarea>
                        <div id="message_description" class="invalid-feedback">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="desc mb-0 mt-3">امتیاز شما به ما</p>
                        <style>
                            * {
                                margin: 0;
                                padding: 0;
                            }

                            .rate {
                                height: 46px;
                                padding: 0 10px;
                            }

                            .rate:not(:checked)>input {
                                display: none;
                            }

                            .rate:not(:checked)>label {
                                width: 25px;
                                overflow: hidden;
                                white-space: nowrap;
                                cursor: pointer;
                                font-size: 30px;
                                color: rgb(178, 178, 178);
                            }

                            .rate:not(:checked)>label:before {
                                content: '★ ';
                            }

                            .rate>input:checked~label {
                                color: rgb(255, 200, 0);
                            }

                            .rate:not(:checked)>label:hover,
                            .rate:not(:checked)>label:hover~label {
                                color: rgb(255, 200, 0);
                            }

                            .rate>input:checked+label:hover,
                            .rate>input:checked+label:hover~label,
                            .rate>input:checked~label:hover,
                            .rate>input:checked~label:hover~label,
                            .rate>label:hover~input:checked~label {
                                color: rgb(255, 200, 0);
                            }
                        </style>
                        <div class="rate">
                            <input type="radio" id="star5" name="star" value="5" required/>
                            <label for="star5" title="text">5 stars</label>
                            <input type="radio" id="star4" name="star" value="4" />
                            <label for="star4" title="text">4 stars</label>
                            <input type="radio" id="star3" name="star" value="3" />
                            <label for="star3" title="text">3 stars</label>
                            <input type="radio" id="star2" name="star" value="2" />
                            <label for="star2" title="text">2 stars</label>
                            <input type="radio" id="star1" name="star" value="1" />
                            <label for="star1" title="text">1 star</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary shadow bg-btn mt-5" id=" ">ارسال نظر</button>
                </div>
                <hr class="mt-4">
                <span class="btn btn-info mb-5 mt-4 ">
                    در صورت تمایل در نظر سنجی شرکت کنید
                </span>

                <div class="mt-3">
                    <div class=" mt-4">
                        <div class="col-md-12 mb-3">
                            <div class="text-right h6">1- نظر شما راجع به دوره گذرانده شده در این آموزشگاه چیست؟ آیا دوره برای شما مفید بود؟</div>
                            <div class="row text-right">
                                <div class="col-3">
                                    <span>
                                        <div class="mb-4">
                                            <div class="custom-control custom-radio pr-0">
                                                <input type="radio" id="customRadio1" name="q_1" value="5" class="custom-control-input q_1">
                                                <label class="custom-control-label h6 pl-4 text-success" for="customRadio1"> عالی </label>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <span>
                                        <div class="custom-control custom-radio pr-0">
                                            <input type="radio" id="customRadio2" name="q_1" value="4" class="custom-control-input n1">
                                            <label class="custom-control-label h6 pl-4 text-info" for="customRadio2">خوب</label>
                                        </div>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <span>
                                        <div class="custom-control custom-radio pr-0">
                                            <input type="radio" id="customRadio3" name="q_1" value="3" class="custom-control-input n1">
                                            <label class="custom-control-label h6 pl-4 text-warning" for="customRadio3">متوسط</label>
                                        </div>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <span>
                                        <div class="custom-control custom-radio pr-0">
                                            <input type="radio" id="customRadio4" name="q_1" value="2" class="custom-control-input n1">
                                            <label class="custom-control-label h6 pl-4 text-danger" for="customRadio4">ضعیف</label>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <textarea name="q_1_comment" class="form-control ltr" placeholder="توضیحات بیشتر..."></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12 mb-3">
                            <div class="text-right h6">2- تدریس استاد و تسلط ایشان به موضوع درسی را در چه سطحی ارزیابی می کنید؟</div>
                            <div class="row text-right">
                                <div class="col-3">
                                    <span>
                                        <div class="mb-4">
                                            <div class="custom-control custom-radio pr-0">
                                                <input type="radio" id="radioO1" name="q_2" value="5" class="custom-control-input n2">
                                                <label class="custom-control-label h6 pl-4 text-success" for="radioO1"> عالی </label>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <span>
                                        <div class="custom-control custom-radio pr-0">
                                            <input type="radio" id="radioO2" name="q_2" value="4" class="custom-control-input n2">
                                            <label class="custom-control-label h6 pl-4 text-info" for="radioO2">خوب</label>
                                        </div>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <span>
                                        <div class="custom-control custom-radio pr-0">
                                            <input type="radio" id="radioO3" name="q_2" value="3" class="custom-control-input n2">
                                            <label class="custom-control-label h6 pl-4 text-warning" for="radioO3">متوسط</label>
                                        </div>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <span>
                                        <div class="custom-control custom-radio pr-0">
                                            <input type="radio" id="radioO4" name="q_2" value="2" class="custom-control-input n2">
                                            <label class="custom-control-label h6 pl-4 text-danger" for="radioO4">ضعیف</label>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <textarea name="q_2_comment" class="form-control ltr" placeholder="توضیحات بیشتر..."></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12 mb-3">
                            <div class="text-right h6">3- به نظر شما تجهیزات آموزشی کارگاه چطور بود؟</div>
                            <div class="row text-right">
                                <div class="col-3">
                                    <span>
                                        <div class="mb-4">
                                            <div class="custom-control custom-radio pr-0">
                                                <input type="radio" id="radioV1" name="q_3" value="5" class="custom-control-input n3">
                                                <label class="custom-control-label h6 pl-4 text-success" for="radioV1"> عالی </label>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <span>
                                        <div class="custom-control custom-radio pr-0">
                                            <input type="radio" id="radioV2" name="q_3" value="4" class="custom-control-input n3">
                                            <label class="custom-control-label h6 pl-4 text-info" for="radioV2">خوب</label>
                                        </div>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <span>
                                        <div class="custom-control custom-radio pr-0">
                                            <input type="radio" id="radioV3" name="q_3" value="3" class="custom-control-input n3">
                                            <label class="custom-control-label h6 pl-4 text-warning" for="radioV3">متوسط</label>
                                        </div>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <span>
                                        <div class="custom-control custom-radio pr-0">
                                            <input type="radio" id="radioV4" name="q_3" value="2" class="custom-control-input n3">
                                            <label class="custom-control-label h6 pl-4 text-danger" for="radioV4">ضعیف</label>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <textarea name="q_3_comment" class="form-control ltr" placeholder="توضیحات بیشتر..."></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12 mb-3">
                            <div class="text-right h6">4- برخورد و رفتار کارکنان آموزشگاه و استاد دوره را چگونه ارزیابی می کنید؟ آیا شخصی هست که از برخوردش ناراضی باشید؟ لطفا نام ببرید.</div>
                            <div class="row text-right">
                                <div class="col-3">
                                    <span>
                                        <div class="mb-4">
                                            <div class="custom-control custom-radio pr-0">
                                                <input type="radio" id="radioB1" name="q_4" value="5" class="custom-control-input n4">
                                                <label class="custom-control-label h6 pl-4 text-success" for="radioB1"> عالی </label>
                                            </div>
                                        </div>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <span>
                                        <div class="custom-control custom-radio pr-0">
                                            <input type="radio" id="radioB2" name="q_4" value="4" class="custom-control-input n4">
                                            <label class="custom-control-label h6 pl-4 text-info" for="radioB2">خوب</label>
                                        </div>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <span>
                                        <div class="custom-control custom-radio pr-0">
                                            <input type="radio" id="radioB3" name="q_4" value="3" class="custom-control-input n4">
                                            <label class="custom-control-label h6 pl-4 text-warning" for="radioB3">متوسط</label>
                                        </div>
                                    </span>
                                </div>
                                <div class="col-3">
                                    <span>
                                        <div class="custom-control custom-radio pr-0">
                                            <input type="radio" id="radioB4" name="q_4" value="2" class="custom-control-input n4">
                                            <label class="custom-control-label h6 pl-4 text-danger" for="radioB4">ضعیف</label>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <textarea name="q_4_comment" class="form-control ltr" placeholder="توضیحات بیشتر..."></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3 bt-5 h5">لطفا سوالات زیر را با "<span class="text-success">بله</span> یا <span class="text-danger">خیر</span>" پاسخ دهید</div>
                        <hr>
                        <div class="col-md-12">
                            <div class="row ">
                                <div class="col-md-12 mb-3">
                                    - آیا سرفصل های دوره به صورت کامل به شما آموزش داده شد؟
                                </div>
                                <div class="col-6">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="radioK1" name="yes_no_q_1" value="1" class="custom-control-input bool1">
                                        <label class="custom-control-label h5 pl-4 text-success" for="radioK1"> بله </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="radioK2" name="yes_no_q_1" value="0" class="custom-control-input bool1">
                                        <label class="custom-control-label h5 pl-4 text-danger" for="radioK2"> خیر </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <div class="row ">
                                <div class="col-md-12 mb-3">
                                    - آیا به میزان کافی، در کارگاه کار عملی انجام دادید؟
                                </div>
                                <div class="col-6">
                                    <div class="custom-control custom-radio pr-0">
                                        <input type="radio" id="radioA1" name="yes_no_q_2" value="1" class="custom-control-input bool2">
                                        <label class="custom-control-label h5 pl-4 text-success" for="radioA1"> بله </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-control custom-radio pr-0">
                                        <input type="radio" id="radioA2" name="yes_no_q_2" value="0" class="custom-control-input bool2">
                                        <label class="custom-control-label h5 pl-4 text-danger" for="radioA2"> خیر </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <div class="row ">
                                <div class="col-md-12 mb-3">
                                    - در نهایت، آیا میزان رضایت شما از خروجی دوره به گونه ای بوده که ما را در آینده به دوستان و آشنایان خود معرفی کنید؟
                                </div>
                                <div class="col-6">
                                    <div class="custom-control custom-radio pr-0">
                                        <input type="radio" id="radioM1" name="yes_no_q_3" value="1" class="custom-control-input bool3">
                                        <label class="custom-control-label h5 pl-4 text-success" for="radioM1"> بله </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-control custom-radio pr-0">
                                        <input type="radio" id="radioM2" name="yes_no_q_3" value="0" class="custom-control-input bool3">
                                        <label class="custom-control-label h5 pl-4 text-danger" for="radioM2"> خیر </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary shadow  bg-btn" id="create_comment">ارسال نظر</button>
            </form>
        </div>
    </div>
</div>
@endsection
