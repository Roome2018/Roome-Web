@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.comments.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('global.comments.fields.comment')</th>
                            <td field-key='comment'>{!! $comment->comment !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.comments.fields.user')</th>
                            <td field-key='user'>{{ $comment->user->name or '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.comments.fields.room')</th>
                            <td field-key='room'>{{ $comment->room->title or '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.comments.fields.rate')</th>
                            <td field-key='rate'>{{ $comment->rate }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.comments.index') }}" class="btn btn-default">@lang('global.app_back_to_list')</a>
        </div>
    </div>
@stop
