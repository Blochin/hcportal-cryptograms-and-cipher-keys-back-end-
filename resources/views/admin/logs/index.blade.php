@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.user.actions.index'))

@section('body')
    @include('sweetalert::alert')
    <log-listing :data="{{ $data->toJson() }}" :url="'{{ url('admin/logs') }}'" inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{ trans('admin.logs.actions.index') }}
                    </div>
                    <div class="card-body" v-cloak>
                        <div class="card-block">
                            <form @submit.prevent="">
                                <div class="row justify-content-md-between">
                                    <div class="col col-lg-7 col-xl-5 form-group">
                                        <div class="input-group">
                                            <input class="form-control"
                                                placeholder="{{ trans('brackets/admin-ui::admin.placeholder.search') }}"
                                                v-model="search" @keyup.enter="filter('search', $event.target.value)" />
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-primary"
                                                    @click="filter('search', search)"><i class="fa fa-search"></i>&nbsp;
                                                    {{ trans('brackets/admin-ui::admin.btn.search') }}</button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-auto form-group ">
                                        <select class="form-control" v-model="pagination.state.per_page">

                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </form>

                            <table class="table table-hover table-listing">
                                <thead>
                                    <tr>
                                        <th is='sortable' :column="'action'">
                                            {{ trans('admin.logs.columns.action') }}</th>
                                        <th :column="'causer'">
                                            {{ trans('admin.logs.columns.causer') }}</th>
                                        <th is='sortable' :column="'loggable_type'">
                                            {{ trans('admin.logs.columns.loggable_type') }}</th>
                                        <th :column="'loggable'">{{ trans('admin.logs.columns.loggable') }}</th>
                                        <th is='sortable' :column="'created_at'">
                                            {{ trans('admin.logs.columns.created_at') }}</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in collection" :key="item.id"
                                        :class="bulkItems[item.id] ? 'bg-bulk' : ''">

                                        <td>@{{ item . action }}</td>
                                        <td>@{{ item . causer?.email }}</td>
                                        <td>@{{ item . loggable_type }}</td>
                                        <td>@{{ item . loggable?.name }}</td>
                                        <td>@{{ formatDate(item.created_at) }}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row" v-if="pagination.state.total > 0">
                                <div class="col-sm">
                                    <span
                                        class="pagination-caption">{{ trans('brackets/admin-ui::admin.pagination.overview') }}</span>
                                </div>
                                <div class="col-sm-auto">
                                    <pagination></pagination>
                                </div>
                            </div>

                            <div class="no-items-found" v-if="!collection.length > 0">
                                <i class="icon-magnifier"></i>
                                <h3>{{ trans('brackets/admin-ui::admin.index.no_items') }}</h3>
                                <p>{{ trans('brackets/admin-ui::admin.index.try_changing_items') }}</p>
                                <a class="btn btn-primary btn-spinner" href="{{ url('admin/users/create') }}"
                                    role="button"><i class="fa fa-plus"></i>&nbsp;
                                    {{ trans('admin.user.actions.create') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </log-listing>

@endsection
