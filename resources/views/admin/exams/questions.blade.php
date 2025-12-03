@extends('admin.layouts.master')

@section('content')
    <livewire:admin.exams.questions :exam="$exam">
@endsection
