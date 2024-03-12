@extends('brackets/admin-ui::admin.layout.default')
@section('title', 'Bulk Upload')
@section('body')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Start Cryptograms Migration') }}</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form method="POST" action="{{url('admin/cryptograms/bulk-upload') }}" enctype="multipart/form-data">
                            @csrf
                            <button type="submit" class="btn btn-primary">Start</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
