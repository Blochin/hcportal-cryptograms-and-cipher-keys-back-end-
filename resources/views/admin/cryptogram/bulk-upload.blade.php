@extends('brackets/admin-ui::admin.layout.default')
@section('title', 'Bulk Upload')
@section('body')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Cryptograms migration. Before pressing the Start button, please select the range of Cryptogram ID values you want to migrate.') }}</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ url('admin/cryptograms/bulk-upload') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="from">From:</label>
                                <input type="number" id="from" name="from" class="form-control"
                                       placeholder="From Value">
                            </div>
                            <div class="form-group">
                                <label for="to">To:</label>
                                <input type="number" id="to" name="to" class="form-control" placeholder="To Value">
                            </div>
                            <button type="submit" class="btn btn-primary">Start</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
