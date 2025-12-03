@extends('admin.layouts.master')

@section('title', __('marketing_sms_templates.settings_title'))

@section('content')
    <livewire:Admin.MarketingSmsTemplates.Settings :marketingSmsTemplate="$marketingSmsTemplate" />
@endsection
