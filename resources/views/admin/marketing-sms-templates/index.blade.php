@extends('admin.layouts.master')

@section('title', __('marketing_sms_templates.page_title'))

@section('content')
    <livewire:Admin.MarketingSmsTemplates.Index />
@endsection
