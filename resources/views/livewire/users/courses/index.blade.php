<div class="container-fluid flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">دوره های حضوری</h4>
                <div class="search-box">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                        <input type="text" wire:model.live="search" class="form-control" placeholder="جستجوی دوره...">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            @include('admin.layouts.alerts')
            <div class="mb-3" wire:ignore>
                <label for="selected_profession">انتخاب حرفه</label>
                <select class="form-select select2" wire:model="selected_profession" onchange="myFunction()" id="selected_profession">
                    <option value="">انتخاب تخصص</option>
                    @foreach ($professions as $profession)
                        <option value="{{ $profession->id }}">{{ $profession->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr class="font-14">
                            <th>عنوان دوره</th>
                            <th>ظرفیت باقی مانده</th>
                            <th class="text-center">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $index => $course)
                            @php
                                $capacity = $course->capacity - $course->courseRegisters->count();
                            @endphp
                            <tr class="font-14">
                                <td class="py-2">
                                    <div class="d-flex align-items-center">
                                        <span>{{ $course->title }}</span>
                                    </div>
                                </td>
                                <td class="py-2">
                                    <span class="badge bg-{{ $capacity > 0 ? 'info' : 'danger' }}">{{ $capacity }}</span>
                                </td>
                                <td class="text-center py-2">
                                    @if ($capacity > 0)
                                        <a href="{{ route('user.courses.show', $course) }}" class="btn btn-xs btn-primary">
                                            جزئیات و ثبت نام
                                        </a>
                                    @else
                                        <span class="badge bg-danger">ظرفیت پر است</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-3">
                                    <div class="text-muted">
                                        <i class="fa fa-info-circle fa-2x mb-2"></i>
                                        <p>هیچ دوره‌ای یافت نشد!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
    <script>
        function myFunction() {
            const value = $('select#selected_profession').val();
            @this.setProfessionIdValue(value)
        }
        myFunction()
    </script>
</div>
