@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.cipher-key.actions.edit', ['name' => $cipherKey->name ?:
    $cipherKey->complete_structure]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <cipher-key-form :action="'{{ $cipherKey->resource_url }}'" :data="{{ $cipherKey->toJson() }}"
                :archives="{{ $archives->toJSON() }}" :persons="{{ $users->toJSON() }}"
                :fonds="{{ $fonds->toJSON() }}" :folders="{{ $folders->toJSON() }}" :tags="{{ $tags->toJSON() }}"
                :edit="true" v-cloak inline-template>
                <div>
                    <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action"
                        novalidate>


                        <div class="card-header">
                            <i class="fa fa-pencil"></i>
                            {{ trans('admin.cipher-key.actions.edit', ['name' => $cipherKey->name ?: $cipherKey->complete_structure]) }}

                        </div>

                        <div class="card-body">
                            @include('admin.cipher-key.components.form-elements')
                            @include('admin.cipher-key.components.images')
                        </div>


                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" :disabled="submiting">
                                <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                                {{ trans('brackets/admin-ui::admin.btn.save') }}
                            </button>
                        </div>

                    </form>
                </div>

            </cipher-key-form>

        </div>

    </div>

@endsection
