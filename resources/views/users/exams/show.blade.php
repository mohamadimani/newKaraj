@extends('users.layouts.master')

@section('content')
    <livewire:users.exams.show :exam="$exam" :course-register="$courseRegister" />
@endsection