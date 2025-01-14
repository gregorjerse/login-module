@extends('layouts.popup')

@section('content')

    @if($errors->any())
        <div class="alert-section">
            <ul class="alert alert-danger">
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <div class="panel-body">
            <div class="sectionTitle">
                <i class="fas fa-shield-alt icon"></i>
                @lang('collected_data.header')
            </div>
            <div class="panel-content">
            <p>@lang('collected_data.p1')</p>
            <p>@lang('collected_data.p2')</p>
            <p>@lang('collected_data.p3')</p>

            <ul class="actionsList">
                <li>
                    <span class="actionsLabel">@lang('collected_data.item_self'):</span>
                    <span class="actionsButtons pull-right">
                        <button type="button" class="btn-link" data-toggle="modal" data-target="#data-summary">
                            <i class="fas fa-eye icon"></i>
                            @lang('collected_data.summary')
                        </button>
                        <a class="btn-link" href="/collected_data/export">
                            <i class="fas fa-download icon"></i>
                            @lang('collected_data.export')
                        </a>
                        <a class="btn-link" href="#" id="btn-delete">
                            <i class="far fa-trash-alt icon"></i>
                            @lang('collected_data.delete')
                        </a>
                    </span>
                </li>
                @foreach($clients as $client)
                    <li>
                        @lang('collected_data.item_client') <strong>{{$client->name }}</strong>:
                        @if($client->api_url)
                            <a class="btn btn-default btn-xs"
                                href="{!!$client->api_url!!}?module=collected_data&user_id={{Auth::user()->id}}">
                                @lang('collected_data.manage')
                            </a>
                        @else
                            @lang('collected_data.api_not_available')
                        @endif
                    </li>
                @endforeach
            </ul>
            </div>
        </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="data-summary">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">+</span>
                    </button>
                    <div class="sectionTitle">
                        <i class="fas fa-shield-alt icon"></i>
                        @lang('collected_data.item_self')
                    </div>
                </div>
                <div class="modal-body">
                    <div class="modal-body-content">
                        @include('collected_data.summary', ['data' => $data])
                    </div>
                    <button type="button" class="btn btn-primary btn-rounded btn-centered" data-dismiss="modal">
                        <i class="fas fa-times icon"></i>
                        @lang('ui.close')
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="delete-confirmation">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">+</span>
                    </button>
                    <div class="sectionTitle">
                        <i class="fas fa-shield-alt icon"></i>
                        @lang('collected_data.confirmation.header')
                    </div>
                </div>
                <div class="modal-body">
                    <div class="modal-body-content">
                        {!! BootForm::open(['url' => '/collected_data/delete', 'id' => 'delete']) !!}
                            {!! BootForm::checkbox('confirm1', trans('collected_data.confirmation.cb1')) !!}
                            {!! BootForm::checkbox('confirm2', trans('collected_data.confirmation.cb2')) !!}
                        {!! BootForm::close() !!}
                    </div>
                    <button type="button" class="btn btn-danger btn-rounded btn-centered" data-dismiss="modal" id="btn-confirm">
                        <i class="fas fa-trash-alt icon"></i>
                        @lang('collected_data.confirmation.submit')
                    </button>
                    <button type="button" class="btn btn-primary btn-rounded btn-centered" data-dismiss="modal">
                        <i class="fas fa-times icon"></i>
                        @lang('ui.cancel')
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" role="dialog" id="delete-alert">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p>@lang('collected_data.alert.p1')</p>
                    <ul>
                        @foreach($clients as $client)
                            <li>
                                <strong>{{$client->name }}</strong>
                                @if($client->email)
                                    <a href="mailto:{{$client->email}}">{{$client->email}}</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    <p>@lang('collected_data.alert.p2', ['email' => config('mail.from.address')])</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        @lang('ui.ok')
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            function toggleBtnConfirm() {
                var all_checked = true;
                $('form#delete').find('input[type=checkbox]').each(function() {
                    all_checked = all_checked && $(this).prop('checked');
                });
                $('#btn-confirm').toggle(all_checked);
            }

            function showConfirmation() {
                $('form#delete').find('input[type=checkbox]').each(function() {
                    $(this).prop('checked', false);
                    $(this).change(toggleBtnConfirm);
                });
                $('#btn-confirm').hide();
                $('#delete-confirmation').modal('show');
            }


            $('#btn-confirm').click(function() {
                $('#btn-confirm').prop('diabled', true);
                $('form#delete').submit();
            })


            var clients_linked = {!! count($clients) > 0 ? 'true' : 'false' !!};
            $('#btn-delete').click(function(e) {
                if(clients_linked) {
                    $('#delete-alert').modal('show');
                } else {
                    showConfirmation();
                }
            })


        })
    </script>
@endsection