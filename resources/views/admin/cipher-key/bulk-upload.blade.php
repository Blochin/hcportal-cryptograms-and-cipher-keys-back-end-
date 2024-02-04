@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'Bulk Upload')

@section('body')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Upload SQL Dump') }}</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{url('admin/cipher-keys/bulk-upload') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="sql_dump">Select SQL Dump File</label>
                                <input type="file" name="sql_dump" class="form-control-file" id="sql_dump" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
