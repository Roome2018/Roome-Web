@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.rooms.title')</h3>
    @can('room_create')
    <p>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
        
    </p>
    @endcan

    

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($rooms) > 0 ? 'datatable' : '' }} @can('room_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('room_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('global.rooms.fields.title')</th>
                        <th>@lang('global.rooms.fields.info')</th>
                        <th>@lang('global.rooms.fields.location')</th>
                        <th>@lang('global.rooms.fields.price')</th>
                        <th>@lang('global.rooms.fields.max-tenants')</th>
                        <th>@lang('global.rooms.fields.photos')</th>
                        <th>@lang('global.rooms.fields.tenants')</th>
                        <th>@lang('global.rooms.fields.view-count')</th>
                        <th>@lang('global.rooms.fields.is-available')</th>
                                                <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($rooms) > 0)
                        @foreach ($rooms as $room)
                            <tr data-entry-id="{{ $room->id }}">
                                @can('room_delete')
                                    <td></td>
                                @endcan

                                <td field-key='title'>{{ $room->title }}</td>
                                <td field-key='info'>{{ $room->info }}</td>
                                <td field-key='location'>{{ $room->location_address }}</td>
                                <td field-key='price'>{{ $room->price }}</td>
                                <td field-key='max_tenants'>{{ $room->max_tenants }}</td>
                                <td field-key='photos'> @foreach($room->getMedia('photos') as $media)
                                <p class="form-group">
                                    <a href="{{ $media->getUrl() }}" target="_blank">{{ $media->name }} ({{ $media->size }} KB)</a>
                                </p>
                            @endforeach</td>
                                <td field-key='tenants'>
                                    @foreach ($room->tenants as $singleTenants)
                                        <span class="label label-info label-many">{{ $singleTenants->name }}</span>
                                    @endforeach
                                </td>
                                <td field-key='view_count'>{{ $room->view_count }}</td>
                                <td field-key='is_available'>{{ $room->is_available }}</td>
                                                                <td>
                                    @can('room_view')
                                    <a href="{{ route('admin.rooms.show',[$room->id]) }}" class="btn btn-xs btn-primary">@lang('global.app_view')</a>
                                    @endcan
                                    @can('room_edit')
                                    <a href="{{ route('admin.rooms.edit',[$room->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    @endcan
                                    @can('room_delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.rooms.destroy', $room->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="14">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('room_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.rooms.mass_destroy') }}';
        @endcan

    </script>
@endsection