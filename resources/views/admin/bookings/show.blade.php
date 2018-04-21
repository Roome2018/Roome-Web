@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.booking.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('global.booking.fields.room')</th>
                            <td field-key='room'>{{ $booking->room->title or '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.booking.fields.user')</th>
                            <td field-key='user'>{{ $booking->user->name or '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.booking.fields.date')</th>
                            <td field-key='date'>{{ $booking->date }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.booking.fields.status')</th>
                            <td field-key='status'>{{ $booking->status }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.bookings.index') }}" class="btn btn-default">@lang('global.app_back_to_list')</a>
        </div>
    </div>
@stop
