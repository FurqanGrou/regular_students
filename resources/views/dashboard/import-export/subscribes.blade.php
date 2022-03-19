@extends('dashboard.layouts.master')

@section('content')

    @include('dashboard.partials.errors')
    @include('dashboard.partials.success')

    <form class="form" method="GET" action="#" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="form-actions">
            <a href="{{ route('dashboard.export.old.subscribes') }}" class="btn btn-primary">
                <i class="la la-check-square-o"></i> الطلبة القدامى
            </a>
            <a href="{{ route('dashboard.export.new.subscribes') }}" class="btn btn-primary">
                <i class="la la-check-square-o"></i> الطلبة الجدد
            </a>
        </div>

    </form>

@endsection

@push('js')
    <script>
        $(function() {

        })
    </script>
@endpush
