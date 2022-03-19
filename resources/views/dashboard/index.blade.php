@extends('dashboard.layouts.master')

@section('content')
    <div id="crypto-stats-3" class="row">

        {{--today_subscribed_success--}}
        <div class="col-6 col-md-4">
            <div class="card crypto-card-3 pull-up">
                <div class="card-content">

                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-2">
                                <h1><i class="ft-award font-large-1" title="محاولات الأشتراك الناجحة لهذا اليوم"></i></h1>
                            </div>
                            <div class="col-6 pl-2">
                                <h4>محاولات الأشتراك الناجحة لهذا اليوم</h4>
                            </div>
                            <div class="col-4 text-center">
                                <h4>{{ $statistics['today_subscribed_success'] }}</h4>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{--today_subscribed_fail--}}
        <div class="col-6 col-md-4">
            <div class="card crypto-card-3 pull-up">
                <div class="card-content">

                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-2">
                                <h1><i class="ft-book font-large-1" title="محاولات الأشتراك الفاشلة لهذا اليوم"></i></h1>
                            </div>
                            <div class="col-6 pl-2">
                                <h4>محاولات الأشتراك الفاشلة لهذا اليوم</h4>
                            </div>
                            <div class="col-4 text-center">
                                <h4>{{ $statistics['today_subscribed_fail'] }}</h4>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12 border-bottom-blue mb-2"></div>

        {{--last_try_subscription--}}
        <div class="col-6 col-md-4">
            <div class="card crypto-card-3 pull-up">
                <div class="card-content">

                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-2">
                                <h1><i class="ft-file-text font-large-1" title="أخر محاولة اشتراك"></i></h1>
                            </div>
                            <div class="col-6">
                                <h4>أخر محاولة اشتراك</h4>
                            </div>
                            <div class="col-4 text-center">
                                <h5 class="danger">{{ $statistics['last_try_subscription'] }}</h5>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
