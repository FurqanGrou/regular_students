@extends('dashboard.layouts.master')

@section('content')

    @include('dashboard.partials.errors')
    @include('dashboard.partials.success')

    <form class="form" method="POST" action="{{ route('dashboard.coupons.store') }}">
        @csrf
        @method('POST')

        <div class="form-body">
            <h4 class="form-section"><i class="ft-user"></i> اضافة كوبون جديد</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="code">كود الخصم</label>
                        <input type="text" id="code" class="form-control" placeholder="كود الخصم" name="code" value="{{ old('code') }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="title_en">نوع الكوبون</label>
                        <select name="type" id="type" class="form-control select2">
                            <option value="percent">نسبة مئوية</option>
                            <option value="fixed">ثابت</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="value">القيمة</label>
                        <input type="number" id="value" class="form-control" placeholder="القيمة" name="value" value="{{ old('value') }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="usage_limit">عدد المستفيدين المسموح به</label>
                        <input type="number" id="usage_limit" class="form-control" placeholder="عدد المستفيدين المسموح به" name="usage_limit" value="{{ old('usage_limit') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date">تاريخ البداية</label>
                        <input type="date" id="start_date" class="form-control" placeholder="تاريخ البداية" name="start_date" value="{{ old('start_date') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date">تاريخ الانتهاء</label>
                        <input type="date" id="end_date" class="form-control" placeholder="تاريخ الانتهاء" name="end_date" value="{{ old('end_date') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="active">الحالة</label>
                        <select name="active" id="active" class="form-control select2">
                            <option value="1">فعال</option>
                            <option value="0">متوقف</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="specific_users">تخصيص طلاب</label>
                        <select name="specific_users[]" id="specific_users" class="form-control select2" multiple>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="limit_user">صالح لمرة واحدة فقط لكل طالب</label>
                        <input type="checkbox" name="limit_user" id="limit_user" data-color="danger" class="toggle-status switchery" />
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="course_id">الدورة</label>
                        <select name="course_id" id="course_id" class="form-control select2" required>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="la la-check-square-o"></i> اضافة
            </button>
            <button type="reset" class="btn btn-warning mr-1">
                <i class="ft-x"></i> إلغاء
            </button>
        </div>

    </form>

@endsection
