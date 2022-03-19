@extends('dashboard.layouts.master')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">جميع استمارات التسجيل</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                </div>

                @include('dashboard.partials.success')

                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                            <div class="box-body">

                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>عنوان الاستمارة</th>
                                        <th>الرمز</th>
                                        <th>الرسوم</th>
                                        <th>تعديل</th>
                                        <th>حذف</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($courses as $course)
                                        <tr>
                                            <th scope="row">{{ $course->id }}</th>
                                            <td>{{ $course->name }}</td>
                                            <td>
                                                <span class="badge badge-success">{{ $course->code }}</span>
                                            </td>
                                            <td style="font-weight: bold">{{ '$' . $course->price}}</td>
                                            <td>
                                                <a href="{{ route('dashboard.courses.edit', $course->id) }}" class="btn btn-warning">
                                                    تعديل
                                                    <i class="ft ft-edit"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <form action="#" method="POST">
                                                    @csrf
{{--                                                    @method('DELETE')--}}

                                                    <button type="submit" class="btn btn-danger disabled cursor-not-allowed" disabled>
                                                        <i class="la la-trash"></i>
                                                        حذف
                                                    </button>

                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')

        <script type="text/javascript">
        </script>

    @endpush

@endsection
