@extends('dashboard.layouts.master')

@section('content')

<div class="card card-docs mb-2">

    <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
        <h2 class="mb-3">ترجمة النصوص - {{ $language }}</h2>
        <form class="form-horizontal" action="{{ route('dashboard.languages.key_value_store', app()->getLocale()) }}" method="POST">
            @csrf
            @method('POST')

            <input type="hidden" name="id" value="{{ $language }}">
            <table class="table table-striped table-bordered zero-configuration" id="kt_datatable">

            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Key')}}</th>
                    <th>{{__('Value')}}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach(openJSONFile('en') as $key => $value)
                    <tr>
                        <td>{{ $i }}</td>
                        <td class="key">{{ $key }}</td>
                        <td>
                            <input type="text" class="form-control value" style="width:100%" name="key[{{ $key }}]" @isset(openJSONFile($language)[$key])
                                value="{{ openJSONFile($language)[$key] }}"
                            @endisset>
                        </td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
            </tbody>

        </table>
        <div class="panel-footer text-right">
            <button type="submit" class="btn btn-primary">حفظ</button>
        </div>
    </form>

    </div>
</div>

@endsection

@section('styles')

<link href="{{asset('/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('script')
<script src="{{ asset('/plugins/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
@endsection
