@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.digitalized-transcription.actions.edit', ['name' => $digitalizedTranscription->id]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <digitalized-transcription-form
                :action="'{{ $digitalizedTranscription->resource_url }}'"
                :data="{{ $digitalizedTranscription->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.digitalized-transcription.actions.edit', ['name' => $digitalizedTranscription->id]) }}
                    </div>

                    <div class="card-body">
                        @include('admin.digitalized-transcription.components.form-elements')
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </digitalized-transcription-form>

        </div>
    
</div>

@endsection