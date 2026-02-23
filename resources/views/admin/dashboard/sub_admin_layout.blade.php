@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="content-wrapper" style="min-height: 687px;">
    <section class="content">
        @include('admin.dashboard.sub_admin')
    </section>
</div>
@endsection
