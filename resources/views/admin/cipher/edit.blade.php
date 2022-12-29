@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.cipher.actions.edit', ['name' => $cipher->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <cipher-form :action="'{{ $cipher->resource_url }}'" :data="{{ $cipher->toJson() }}"
                :tags="{{ $tags->toJSON() }}" v-cloak inline-template>

                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action"
                    novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.cipher.actions.edit', ['name' => $cipher->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.cipher.components.form-elements')
                        @include('brackets/admin-ui::admin.includes.media-uploader', [
                        'mediaCollection' => app(App\Models\Thumbnail::class)->getMediaCollection('gallery'),
                        'media' => $post->getThumbs200ForCollection('gallery'),
                        'label' => 'Gallery'
                        ])
                    </div>


                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>

                </form>

            </cipher-form>

        </div>

    </div>

@endsection
