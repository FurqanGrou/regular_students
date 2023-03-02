@extends('dashboard.layouts.master')

@section('content')

    @include('dashboard.partials.errors')
    @include('dashboard.partials.success')

    <form class="form" method="POST" action="{{ route('dashboard.import.custom.prices.store') }}" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="form-body">
            <h4 class="form-section">
                <i class="ft-user"></i>
                تحديث الأسعار المخصصة للطلاب
            </h4>
            <div class="row">

                {{--Upload File--}}
                <div class="col-12">
                    <div class="card crypto-card-3 pull-up">
                        <div class="card-content">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-3">
                                        <input type="file" name="file_path" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-danger" id="reset-custom-prices" type="button">التخلص من الخصومات - Reset</button>
                                    </div>
                                </div>
                            </div>

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

            $(document).on('click', '#reset-custom-prices', function (e) {

                swal({
                    title: "هل أنت متأكد من تعطيل جميع الخصومات السابقة؟",
                    text: "بعد الضغط على تأكيد لن يمكنك التراجع عن العملية!",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "لا، إلغاء!",
                            value: null,
                            visible: true,
                            className: "btn-danger",
                            closeModal: false,
                        },
                        confirm: {
                            text: "نعم، تعطيل!",
                            value: true,
                            visible: true,
                            className: "btn-success",
                            closeModal: false
                        }
                    }
                }).then((isConfirm) => {
                    if (isConfirm) {
                        $.ajax({
                            type: "GET",
                            dataType: "json",
                            url: '{{ route('dashboard.reset-custom-prices') }}',
                            success: function (data) {
                                swal("تم تعطيل الخصومات بنجاح", "يمكنك إغلاق الرسالة", "success");
                            },
                            error: function (data){
                                swal("فشلت عملية التعطيل!", "تم إلغاء عملية التعطيل", "error");
                            }
                        });
                    } else {
                        swal("تم الإلغاء!", "تم إلغاء عملية التعطيل", "error");
                    }
                });

            });

        })
    </script>
@endpush
