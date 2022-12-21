@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.cipher-key-similarity.actions.edit', ['name' => $cipherKeySimilarity->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <cipher-key-similarity-form :action="'{{ $cipherKeySimilarity->resource_url }}'"
                :data="{{ $cipherKeySimilarity->toJson() }}" :cipher-keys="{{ $cipherKeys->toJSON() }}" v-cloak
                inline-template>

                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action"
                    novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i>
                        {{ trans('admin.cipher-key-similarity.actions.edit', ['name' => $cipherKeySimilarity->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.cipher-key-similarity.components.form-elements')
                    </div>


                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>

                </form>

            </cipher-key-similarity-form>

        </div>

    </div>

@endsection
