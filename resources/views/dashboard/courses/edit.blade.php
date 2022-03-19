@extends('dashboard.layouts.master')
<title>تعديل رسوم استمارة - {{ $course->name }}</title>
@section('content')

    @include('dashboard.partials.errors')
    @include('dashboard.partials.success')

    <form class="form" method="POST" action="{{ route('dashboard.courses.update', $course->id) }}">
        @csrf
        @method('PUT')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-user"></i> تعديل رسوم استمارة - {{ $course->name }}</h4>
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="code">قيمة الرسوم - $</label>
                        <input type="number" id="code" class="form-control" placeholder="قيمة الرسوم - $" name="amount" value="{{ old('amount', $course->price) }}" required>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="la la-check-square-o"></i> تحديث
            </button>
            <button type="reset" class="btn btn-warning mr-1">
                <i class="ft-x"></i> إلغاء
            </button>
        </div>

    </form>

@endsection
