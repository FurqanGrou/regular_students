@extends('dashboard.layouts.master')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">جميع الأوقات المفضلة</h4>
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
                                        <th>العنوان</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($favorite_times as $favorite_time)
                                        <tr>
                                            <th scope="row">{{ $favorite_time->id }}</th>
                                            <td>{{ $favorite_time->title }}</td>
                                            <td>{{ $favorite_time->section == 'male' ? 'بنين' : 'بنات' }}</td>
                                            <td>
                                                <a href="{{ route('dashboard.favorite-times.edit', $favorite_time->id) }}" class="btn btn-warning">
                                                    تعديل
                                                    <i class="ft ft-edit"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <form action="{{ route('dashboard.favorite-times.destroy', $favorite_time->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger">
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

{{-- Modal --}}
<div class="modal fade text-left" id="delete-all-admins" tabindex="-1" role="dialog" aria-labelledby="myModalLabel10" aria-hidden="true" style="display: none; z-index: 999999999">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger white">
                <h4 class="modal-title white" id="myModalLabel10">حذف المشرفين</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <div class="empty_record hidden">
                        <h4>يرجى تحديد المشرفين المراد حذفهم</h4>
                    </div>
                    <div class="not_empty_record hidden">
                        <h4>هل أنت متأكد من حذف المشرفين والذي عددهم <span class="record_count"></span> ? </h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <div class="empty_record hidden">
                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">إغلاق</button>
                </div>
                <div class="not_empty_record hidden">
                    <button type="button" class="btn btn-default" data-dismiss="modal">لا</button>
                    <input type="submit"  value="حسنا"  class="btn btn-outline-danger delete_all_submit" />
                </div>

            </div>
        </div>
    </div>
</div>
