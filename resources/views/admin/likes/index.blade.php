@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.likes.title')</h3>
    @can('like_create')
    <p>
        <a href="{{ route('admin.likes.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
        
    </p>
    @endcan

    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($likes) > 0 ? 'datatable' : '' }} @can('like_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('like_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('global.likes.fields.user')</th>
                        <th>@lang('global.likes.fields.room')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($likes) > 0)
                        @foreach ($likes as $like)
                            <tr data-entry-id="{{ $like->id }}">
                                @can('like_delete')
                                    <td></td>
                                @endcan

                                <td field-key='user'>{{ $like->user->name or '' }}</td>
                                <td field-key='room'>{{ $like->room->title or '' }}</td>
                                                                <td>
                                    @can('like_view')
                                    <a href="{{ route('admin.likes.show',[$like->id]) }}" class="btn btn-xs btn-primary">@lang('global.app_view')</a>
                                    @endcan
                                    @can('like_edit')
                                    <a href="{{ route('admin.likes.edit',[$like->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    @endcan
                                    @can('like_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.likes.destroy', $like->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('like_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.likes.mass_destroy') }}';
        @endcan

    </script>
@endsection