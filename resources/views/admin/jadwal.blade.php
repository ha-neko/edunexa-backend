@extends('component.layouts.admin.main')
@section('title', 'Jadwal Pelajaran')
@section('scripts')
    <script src="{{ asset('assets/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('assets/js/demo/chart-pie-demo.js') }}"></script>
@endsection
@section('content')
@include('component.schedule')

@endsection