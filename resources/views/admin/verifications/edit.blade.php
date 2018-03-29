@extends('layouts.admin')

@section('content')
    {{ $verifications->links() }}

    <div class="well">
        <div>Verification request #{{$verification->id}}</div>
        <div>{{$verification->created_at}}</div>
    </div>
    <div class="well">
        <img style="width: 100%" src="/verifications/{{$verification->file}}"/>
    </div>


    {!! BootForm::open(['url' => '/admin/verifications/update/'.$verification->id ]) !!}

        @if(!$verifications->hasMorePages())
            <input type="hidden" name="page_url" value="{{$verifications->previousPageUrl()}}"/>
        @endif


        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Attribute</th>
                    <th>Value</th>
                    <th>Approved</th>
                </tr>
            </thead>
            <tbody>
                @foreach($verification->user_attributes as $attr)
                    <tr>
                        <td>@lang('profile.'.$attr)</td>
                        <td>{{ $verification->user->getAttribute($attr) }}</td>
                        <td><input type="checkbox" name="user_attributes[]" value="{{$attr}}" checked="checked"/></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! BootForm::textarea('message', 'Message to user') !!}
        {!! BootForm::text('confidence', 'Confidence value (0...100)') !!}
        <input type="hidden" name="status" id="status"/>
        <button class="btn btn-success" id="btn_approve">Approve</button>
        <button class="btn btn-danger" id="btn_reject">Reject</button>
    {!! BootForm::close() !!}

    <script>
    $(document).ready(function() {
        $('#btn_approve').on('click', function() {
            $('#status').val('approved');
        })
        $('#btn_reject').on('click', function() {
            $('#status').val('rejected');
        })
    })
    </script>
@endsection