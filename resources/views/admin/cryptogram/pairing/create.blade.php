@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.cryptogram.actions.bulk-pair'))

@section('body')

    <div class="container-xl">

        <div class="card">

            <cryptogram-pairing-form :action="'{{ url('admin/pair-keys-cryptograms') }}'" v-cloak inline-template>

                <form class="form-horizontal form-create" method="post" @submit.prevent="onSubmit" :action="action"
                    novalidate>

                    <div class="card-header">
                        <i class="fa fa-plus"></i> {{ trans('admin.cryptogram.actions.bulk-pair') }}
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="form-group row align-items-center"
                                    :class="{'has-danger': errors.has('cipher_keys'), 'has-success': fields.cipher_keys && fields.cipher_keys.valid }">
                                    <label for="cipher_keys" class="col-form-label"
                                        :class="isFormLocalized ? 'col-md-12' : 'col-md-12'">{{ trans('admin.cipher-key.title') }}</label>
                                    <div :class="isFormLocalized ? 'col-md-12' : 'col-md-12 col-xl-12'">
                                        <multiselect v-model="form.keys" :close-on-select="false"
                                            placeholder="Search cipher key" :multiple="true" label="name"
                                            :loading="isLoading" :internal-search="false" @search-change="filterKeys"
                                            :options="filteredKeys" :option-height="280"
                                            placeholder="{{ trans('admin.cipher-key-similarity.columns.cipher_keys') }}"
                                            track-by="id">
                                        </multiselect>
                                        <div v-if="errors.has('cipher_keys')" class="form-control-feedback form-text"
                                            v-cloak>
                                            @{{ errors . first('cipher_keys') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group row align-items-center"
                                    :class="{'has-danger': errors.has('cryptograms'), 'has-success': fields.cryptograms && fields.cryptograms.valid }">
                                    <label for="cryptograms" class="col-form-label"
                                        :class="isFormLocalized ? 'col-md-12' : 'col-md-12'">{{ trans('admin.cryptogram.title') }}</label>
                                    <div :class="isFormLocalized ? 'col-md-12' : 'col-md-12 col-xl-12'">
                                        <multiselect v-model="form.cryptograms" :close-on-select="false"
                                            placeholder="Search cryptograms" :multiple="true" label="name"
                                            :loading="isLoading" :internal-search="false" @search-change="filterCryptograms"
                                            :options="filteredCryptograms" :option-height="280"
                                            placeholder="{{ trans('admin.cipher-key-similarity.columns.cryptograms') }}"
                                            track-by="id">
                                        </multiselect>
                                        <div v-if="errors.has('cryptograms')" class="form-control-feedback form-text"
                                            v-cloak>
                                            @{{ errors . first('cryptograms') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>

                </form>

                </cryptogram-form>

        </div>

    </div>


@endsection
