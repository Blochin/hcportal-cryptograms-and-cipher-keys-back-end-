@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.key-type.actions.edit', ['name' => $keyType->name]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <key-type-form
                :action="'{{ $keyType->resource_url }}'"
                :data="{{ $keyType->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.key-type.actions.edit', ['name' => $keyType->name]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.key-type.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </key-type-form>

        </div>
    
</div>

@endsection