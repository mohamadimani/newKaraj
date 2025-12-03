@extends('users.layouts.master')

@section('content')
<livewire:users.orders.show :order="$order" />
@endsection
