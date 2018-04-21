@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.users.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('global.users.fields.name')</th>
                            <td field-key='name'>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.users.fields.email')</th>
                            <td field-key='email'>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.users.fields.image')</th>
                            <td field-key='image'>@if($user->image)<a href="{{ asset(env('UPLOAD_PATH').'/' . $user->image) }}" target="_blank"><img src="{{ asset(env('UPLOAD_PATH').'/thumb/' . $user->image) }}"/></a>@endif</td>
                        </tr>
                        <tr>
                            <th>@lang('global.users.fields.nationality')</th>
                            <td field-key='nationality'>{{ $user->nationality }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.users.fields.role')</th>
                            <td field-key='role'>
                                @foreach ($user->role as $singleRole)
                                    <span class="label label-info label-many">{{ $singleRole->title }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#likes" aria-controls="likes" role="tab" data-toggle="tab">Likes</a></li>
<li role="presentation" class=""><a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Comments</a></li>
<li role="presentation" class=""><a href="#booking" aria-controls="booking" role="tab" data-toggle="tab">Booking</a></li>
<li role="presentation" class=""><a href="#rooms" aria-controls="rooms" role="tab" data-toggle="tab">Rooms</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    
<div role="tabpanel" class="tab-pane active" id="likes">
<table class="table table-bordered table-striped {{ count($likes) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('global.likes.fields.user')</th>
                        <th>@lang('global.likes.fields.room')</th>
                                                <th>&nbsp;</th>

        </tr>
    </thead>

    <tbody>
        @if (count($likes) > 0)
            @foreach ($likes as $like)
                <tr data-entry-id="{{ $like->id }}">
                    <td field-key='user'>{{ $like->user->name or '' }}</td>
                                <td field-key='room'>{{ $like->room->title or '' }}</td>
                                                                <td>
                                    @can('view')
                                    <a href="{{ route('likes.show',[$like->id]) }}" class="btn btn-xs btn-primary">@lang('global.app_view')</a>
                                    @endcan
                                    @can('edit')
                                    <a href="{{ route('likes.edit',[$like->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    @endcan
                                    @can('delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['likes.destroy', $like->id])) !!}
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
<div role="tabpanel" class="tab-pane " id="comments">
<table class="table table-bordered table-striped {{ count($comments) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('global.comments.fields.comment')</th>
                        <th>@lang('global.comments.fields.user')</th>
                        <th>@lang('global.comments.fields.room')</th>
                        <th>@lang('global.comments.fields.rate')</th>
                        @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        @endif
        </tr>
    </thead>

    <tbody>
        @if (count($comments) > 0)
            @foreach ($comments as $comment)
                <tr data-entry-id="{{ $comment->id }}">
                    <td field-key='comment'>{!! $comment->comment !!}</td>
                                <td field-key='user'>{{ $comment->user->name or '' }}</td>
                                <td field-key='room'>{{ $comment->room->title or '' }}</td>
                                <td field-key='rate'>{{ $comment->rate }}</td>
                                @if( request('show_deleted') == 1 )
                                <td>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'POST',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['comments.restore', $comment->id])) !!}
                                    {!! Form::submit(trans('global.app_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                    {!! Form::close() !!}
                                                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['comments.perma_del', $comment->id])) !!}
                                    {!! Form::submit(trans('global.app_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                                                </td>
                                @else
                                <td>
                                    @can('view')
                                    <a href="{{ route('comments.show',[$comment->id]) }}" class="btn btn-xs btn-primary">@lang('global.app_view')</a>
                                    @endcan
                                    @can('edit')
                                    <a href="{{ route('comments.edit',[$comment->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    @endcan
                                    @can('delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['comments.destroy', $comment->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                                @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9">@lang('global.app_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
<div role="tabpanel" class="tab-pane " id="booking">
<table class="table table-bordered table-striped {{ count($bookings) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('global.booking.fields.room')</th>
                        <th>@lang('global.booking.fields.user')</th>
                        <th>@lang('global.booking.fields.date')</th>
                        <th>@lang('global.booking.fields.status')</th>
                                                <th>&nbsp;</th>

        </tr>
    </thead>

    <tbody>
        @if (count($bookings) > 0)
            @foreach ($bookings as $booking)
                <tr data-entry-id="{{ $booking->id }}">
                    <td field-key='room'>{{ $booking->room->title or '' }}</td>
                                <td field-key='user'>{{ $booking->user->name or '' }}</td>
                                <td field-key='date'>{{ $booking->date }}</td>
                                <td field-key='status'>{{ $booking->status }}</td>
                                                                <td>
                                    @can('view')
                                    <a href="{{ route('bookings.show',[$booking->id]) }}" class="btn btn-xs btn-primary">@lang('global.app_view')</a>
                                    @endcan
                                    @can('edit')
                                    <a href="{{ route('bookings.edit',[$booking->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    @endcan
                                    @can('delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['bookings.destroy', $booking->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>

                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9">@lang('global.app_no_entries_in_table')</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
<div role="tabpanel" class="tab-pane " id="rooms">
<table class="table table-bordered table-striped {{ count($rooms) > 0 ? 'datatable' : '' }}">
    <thead>
        <tr>
            <th>@lang('global.rooms.fields.title')</th>
                        <th>@lang('global.rooms.fields.info')</th>
                        <th>@lang('global.rooms.fields.location')</th>
                        <th>@lang('global.rooms.fields.price')</th>
                        <th>@lang('global.rooms.fields.max-tenants')</th>
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
                    <td field-key='title'>{{ $room->title }}</td>
                                <td field-key='info'>{{ $room->info }}</td>
                                <td field-key='location'>{{ $room->location_address }}</td>
                                <td field-key='price'>{{ $room->price }}</td>
                                <td field-key='max_tenants'>{{ $room->max_tenants }}</td>
                                <td field-key='photos'>@if($room->photos)<a href="{{ asset(env('UPLOAD_PATH').'/' . $room->photos) }}" target="_blank"><img src="{{ asset(env('UPLOAD_PATH').'/thumb/' . $room->photos) }}"/></a>@endif</td>
                                <td field-key='tenants'>
                                    @foreach ($room->tenants as $singleTenants)
                                        <span class="label label-info label-many">{{ $singleTenants->name }}</span>
                                    @endforeach
                                </td>
                                <td field-key='view_count'>{{ $room->view_count }}</td>
                                <td field-key='is_available'>{{ $room->is_available }}</td>
                                                                <td>
                                    @can('view')
                                    <a href="{{ route('rooms.show',[$room->id]) }}" class="btn btn-xs btn-primary">@lang('global.app_view')</a>
                                    @endcan
                                    @can('edit')
                                    <a href="{{ route('rooms.edit',[$room->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    @endcan
                                    @can('delete')
{!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['rooms.destroy', $room->id])) !!}
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

            <p>&nbsp;</p>

            <a href="{{ route('admin.users.index') }}" class="btn btn-default">@lang('global.app_back_to_list')</a>
        </div>
    </div>
@stop
