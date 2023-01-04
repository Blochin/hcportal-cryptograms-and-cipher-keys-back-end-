@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.cryptogram.actions.edit', ['name' => $cryptogram->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <cryptogram-form :action="'{{ $cryptogram->resource_url }}'" :data="{{ $cryptogram->toJson() }}"
                :tags="{{ $tags->toJSON() }}" v-cloak inline-template>
                <div>
                    <modal name="update-state" height="auto">
                        <div class="card">
                            <div class="card-header">
                                {{ trans('admin.cipher-key.columns.update_state') }}
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="location"
                                            class="col-form-label">{{ trans('admin.cipher-key.columns.actual_state') }}</label>
                                        <p class="p-0 m-0 mb-2">{!! $cryptogram->state_badge !!} </p>
                                        <i class="col-form-label">Note: {{ $cryptogram->state->note ?: 'None' }}</i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-12">
                                        <div class="form-group row align-items-center"
                                            :class="{'has-danger': errors.has('state'), 'has-success': fields.location && fields.location.valid }">
                                            <label for="location" class="col-form-label"
                                                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.state') }}</label>
                                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                                                <multiselect v-model="state" label="title" :options="{{ $states }}"
                                                    :option-height="104"
                                                    placeholder="{{ trans('admin.cipher-key.columns.state') }}"
                                                    track-by="id">
                                                </multiselect>
                                                <div v-if="errors.has('state')" class="form-control-feedback form-text"
                                                    v-cloak>
                                                    @{{ errors . first('state') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-12">
                                        <div class="form-group row align-items-center"
                                            :class="{'has-danger': errors.has('note'), 'has-success': fields.note && fields.note.valid }">
                                            <label for="note" class="col-form-label"
                                                :class="isFormLocalized ? 'col-md-4' : 'col-md-12'">{{ trans('admin.cipher-key.columns.note') }}</label>
                                            <div :class="isFormLocalized ? 'col-md-4' : 'col-md-12 col-xl-12'">
                                                <div>
                                                    <textarea class="form-control" v-model="note" v-validate="''" id="note"
                                                        name="note"></textarea>
                                                </div>
                                                <div v-if="errors.has('note')" class="form-control-feedback form-text"
                                                    v-cloak>
                                                    @{{ errors . first('note') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-primary" @click.prevent="updateState">
                                    <i class="fa" class="fa-download"></i>
                                    {{ trans('admin.cipher-key.columns.update_state') }}

                                </button>
                            </div>
                        </div>
                    </modal>

                    <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action"
                        novalidate>


                        <div class="card-header">
                            <i class="fa fa-pencil"></i>
                            {{ trans('admin.cryptogram.actions.edit', ['name' => $cryptogram->name]) }}

                            <a href="" @click.prevent="showUpdateState"
                                class="btn btn-primary btn-sm pull-right m-b-0">{{ trans('admin.cipher-key.columns.update_state') }}</a>
                        </div>

                        <div class="card-body">
                            @include('admin.cryptogram.components.form-elements')
                            @include('brackets/admin-ui::admin.includes.media-uploader', [
                            'mediaCollection' => app(App\Models\Cryptogram::class)->getMediaCollection('picture'),
                            'media' => $cryptogram->getThumbs200ForCollection('picture'),
                            'label' => 'Thumbnail'
                            ])
                        </div>


                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" :disabled="submiting">
                                <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                                {{ trans('brackets/admin-ui::admin.btn.save') }}
                            </button>
                        </div>

                    </form>

                </div>
            </cryptogram-form>

        </div>

    </div>

@endsection
