@extends('dashboard.layouts.master')

@section('content')

    @include('dashboard.partials.errors')
    @include('dashboard.partials.success')

    <form class="form" method="POST" action="{{ route('dashboard.import.students.store') }}" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="form-body">
            <h4 class="form-section">
                <i class="ft-user"></i>
                تحديث بيانات الطلاب
            </h4>
            <div class="row">

                {{--Upload File--}}
                <div class="col-6 col-md-4">
                    <div class="card crypto-card-3 pull-up">
                        <div class="card-content">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <input type="file" name="file_path" class="form-control">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">القسم</span>
                        </div>
                        <select name="section" id="section" class="form-control">
                            <option value="0">اختر قسم</option>
                            <option value="1">البنين</option>
                            <option value="2">البنات</option>
                        </select>
                    </div>
                </div>

            </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="la la-check-square-o"></i> استيراد
            </button>
            <button type="reset" class="btn btn-warning mr-1">
                <i class="ft-x"></i> إلغاء
            </button>
        </div>

    </form>

@endsection

@push('js')
    <script>
        $(function() {

        })
    </script>
@endpush
