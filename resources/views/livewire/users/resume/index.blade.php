<div>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    {{-- user info --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <h5 class="card-header heading-color">اطلاعات فردی</h5>
                                <!-- Account -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-md-6 text-center">
                                            <img src="{{ user()?->student?->personal_image ? GetImage('students/personal/'.user()->student->personal_image) : asset('images/admin/default.png')    }}"
                                                height="100" width="100" id="uploadedAvatar" class="d-block rounded">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <div class="button-wrapper">
                                                <form action="{{ route('user.resume.upload_image') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <label for="upload" class="btn btn-primary me-2 mb-4 btn-sm" tabindex="0">
                                                        <span class="d-none d-sm-block me-2">تغییر عکس</span>
                                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                                        <input type="file" id="personal_image" name="personal_image" class="account-file-input" accept="image/png, image/jpeg">
                                                    </label>
                                                    <button type="submit" class="btn btn-success account-image-reset mb-4 btn-md">
                                                        <i class=" d-block d-sm-none"></i>ثبت
                                                    </button>
                                                </form>
                                                <p class="mb-0 font-12">فایل‌های JPG، یا PNG مجاز هستند. حداکثر اندازه فایل 1000KB </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="firstName" class="form-label">نام</label>
                                            <input class="form-control" type="text" id="firstName" wire:model="first_name" autofocus>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="lastName" class="form-label">نام خانوادگی</label>
                                            <input class="form-control" type="text" wire:model="last_name" id="lastName">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="email" class="form-label">ایمیل</label>
                                            <input class="form-control text-start" type="text" id="email" wire:model="email" placeholder="test@example.com" dir="ltr">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="mobile" class="form-label">موبایل</label>
                                            <input class="form-control text-start" type="text" id="mobile" wire:model="mobile" placeholder="0912..." dir="ltr" disabled>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="military_status">وضعیت خدمت سربازی</label>
                                            <select id="country" class="select2 form-select" wire:model="military_status">
                                                <option value="">انتخاب</option>
                                                @foreach (militaryStatus() as $id => $title)
                                                <option value="{{ $id }}">{{ $title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="province">استان</label>
                                            <select id="province" class="select2 form-select" wire:model="province" onchange="setProvince()">
                                                <option value="">انتخاب</option>
                                                @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                @endforeach
                                            </select>

                                            <script>
                                                function setProvince() {
                                                    const value = $('select#province').val();
                                                    @this.setProvince(value)
                                                }
                                            </script>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="birth_date">تاریخ تولد</label>
                                            <input data-jdp type="text" wire:model="birth_date" id="birth_date" class="form-control">
                                            @include('admin.layouts.jdp')
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="address" class="form-label">آدرس</label>
                                            <input type="text" class="form-control" id="address" wire:model="address" placeholder="آدرس">
                                        </div>
                                        <div class="mb-3 col-md-6 ">
                                            <label for="gender" class="form-label">جنسیت : </label>
                                            <div class="col-md-12 d-flex border rounded">
                                                <div class="col-3 mt-2"></div>
                                                <div class="mt-2 col-4">
                                                    <label for="gender1" class="form-label">آقا</label>
                                                    <input type="radio" id="gender1" wire:model="gender" value="male">
                                                </div>
                                                <div class="mt-2 col-4">
                                                    <label for="gender2" class="form-label">خانم</label>
                                                    <input type="radio" id="gender2" wire:model="gender" value="femail">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-6 ">
                                            <label for="marital_status" class="form-label">وضعیت تاهل : </label>
                                            <div class="col-md-12 d-flex border rounded">
                                                <div class=" mt-2 col-3"> </div>
                                                <div class=" mt-2 col-4">
                                                    <label for="marital_status1" class="form-label">مجرد</label>
                                                    <input type="radio" id="marital_status1" wire:model="marital_status" value="single">
                                                </div>
                                                <div class=" mt-2 col-4">
                                                    <label for="marital_status2" class="form-label">متأهل</label>
                                                    <input type="radio" id="marital_status2" wire:model="marital_status" value="married">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary me-2" wire:click="updateResume()">ذخیره تغییرات</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- user profession --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <h5 class="card-header heading-color">مهارت‌ها</h5>
                                <div class="card-body">
                                    <div class="row">
                                        @include('users.layouts.alerts')
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="province">مهارت</label>
                                            <select wire:ignore id="professionId" class="select2 form-select select2" wire:model="professionId" onchange="setProfession()" required>
                                                <option value="">انتخاب</option>
                                                @foreach ($professions as $profession)
                                                <option value="{{ $profession->id }}">{{ $profession->title }}</option>
                                                @endforeach
                                            </select>
                                            <script>
                                                function setProfession() {
                                                    const value = $('select#professionId').val();
                                                    @this.setProfession(value)
                                                }
                                            </script>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="persent" class="form-label">درصد تسلط</label>
                                            <input class="form-control" type="number" id="persent" wire:model="persent" required>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary me-2" wire:click="storeProfession()">ذخیره تغییرات</button>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <ul class="doughnut-legend justify-content-around ps-0 mb-2 pt-1">
                                            @foreach ($userResumePerofessions as $userResumePerofession)
                                            <li class="ct-series-0 d-flex flex-column">
                                                <tag class="tagify__tag ">
                                                    <x title="" class="tagify__tag__removeBtn" wire:click="removePerofession({{$userResumePerofession->id}})"></x>
                                                    <div><span class="tagify__tag-text"> {{ $userResumePerofession->profession->title }}</span></div>
                                                </tag>
                                                <span class="badge badge-dot my-2  rounded-pill" style="background-color: rgb(102, 110, 232); width:<?= $userResumePerofession->persent ?>%; height: 6px"></span>
                                                <div class="text-muted">{{ $userResumePerofession->persent }} %</div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
