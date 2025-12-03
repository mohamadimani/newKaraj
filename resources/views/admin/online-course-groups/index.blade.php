@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card pb-3">
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 text-gray-800">لیست گروه های دوره های آنلاین</h1>
                    <a href="{{ route('online-course-groups.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> افزودن گروه جدید
                    </a>
                </div>
                @include('admin.layouts.alerts')
                <div class="overflow-x-auto">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ردیف
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    دوره ها
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    عملیات
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($onlineCourseGroups ?? [] as $group)
                                <tr>
                                    <td class="whitespace-nowrap">
                                        {{ $group->name }}
                                    </td>
                                    <td>
                                        <ul class="list-disc list-inside m-0">
                                            @foreach ($group->onlineCourses as $onlineCourse)
                                                <li class="text-indigo-600 hover:text-indigo-900 mr-3">{{ $onlineCourse->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="whitespace-nowrap text-sm">
                                        <form action="{{ route('online-course-groups.destroy', $group) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-gray-500">
                                        گروهی یافت نشد
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
