@extends('layouts.app')

@section('content')
    <livewire:public.otp-verification :invitation="$invitation" />
@endsection
