@extends('admin.layouts.master')

@section('content')
    <livewire:Admin.CourseStudents.Index :course="$course" />
@endsection
