<section id='order_form' class="container">
    <div class="row">
        <h1 class="section_head">
            @lang("Public_ViewEvent.order_details")
        </h1>
    </div>
    <div class="row">
        <div class="col-md-12" style="text-align: center">
            @lang("Public_ViewEvent.below_order_details_header")
        </div>
        <div class="col-md-4 col-md-push-8">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="ico-cart mr5"></i>
                        @lang("Public_ViewEvent.order_summary")
                    </h3>
                </div>

                <div class="panel-body pt0">
                    <table class="table mb0 table-condensed">
                        @foreach($tickets as $ticket)
                            <tr>
                                <td class="pl0">{{{$ticket['ticket']['title']}}} X <b>{{$ticket['qty']}}</b></td>
                                <td style="text-align: right;">
                                    @isFree($ticket['full_price'])
                                    @lang("Public_ViewEvent.free")
                                    @else
                                        {{ money($ticket['full_price'], $event->currency) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                @if($order_total > 0)
                    <div class="panel-footer">
                        <h5>
                            @lang("Public_ViewEvent.total"): <span
                                style="float: left;"><b>{{ $orderService->getOrderTotalWithBookingFee(true) }}</b></span>
                        </h5>
                        @if($event->organiser->charge_tax)
                            <h5>
                                {{ $event->organiser->tax_name }} ({{ $event->organiser->tax_value }}%):
                                <span style="float: right;"><b>{{ $orderService->getTaxAmount(true) }}</b></span>
                            </h5>
                            <h5>
                                <strong>@lang("Public_ViewEvent.grand_total")</strong>
                                <span style="float: left;"><b>{{  $orderService->getGrandTotal(true) }}</b></span>
                            </h5>
                        @endif
                    </div>
                @endif

            </div>
            <div class="help-block">
                {!! @trans("Public_ViewEvent.time", ["time"=>"<span id='countdown'></span>"]) !!}
            </div>
        </div>
        <div class="col-md-8 col-md-pull-4">
            <div class="event_order_form">
                {!! Form::open(['url' => route('postValidateOrder', ['event_id' => $event->id ]), 'class' => 'ajax payment-form', 'id' => 'payment-form']) !!}

                {!! Form::hidden('event_id', $event->id) !!}

                <h3> @lang("Public_ViewEvent.your_information")</h3>

                <div class="row">

                    <div class="col-xs-12">
                        <div class="form-group">
                            {!! Form::label("order_first_name"," الاسم الثلاثي") !!}
                            {!! Form::text("order_first_name", null, ['required' => 'required', 'class' => 'form-control order_first_name' ,
                            "oninvalid"=>"InvalidMsg(this)"
]) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            {!! Form::label("university", "الجامعة") !!}
                            <select name="order_university_id" id="university_id" class="form-control order_university_id" required oninvalid="InvalidMsg(this);">
                                <option value="">اختر </option>
                                @foreach($universities as $university)
                                    <option data-staff_domain="{{$university->staff_domain}}"
                                            data-stud_domain="{{$university->stud_domain}}"
                                            value="{{$university->id}}">{{$university->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            {!! Form::label("order_type", "الصفة") !!}
                            <select name="order_type" id="type" class="form-control order_type" required oninvalid="InvalidMsg(this);">
                                <option value="">اختر </option>
{{--                                @if($universities->first()->staff_domain)--}}
{{--                                    <option value="staff_domain">عضو هيئه تدريس</option>--}}
{{--                                    <option value="employee_domain">موظف / موظفة</option>--}}
{{--                                @endif--}}
{{--                                @if($universities->first()->stud_domain)--}}
{{--                                    <option value="stud_domain">طالب /طالبة</option>--}}
{{--                                @endif--}}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            {!! Form::label("order_faculty", trans("Public_ViewEvent.faculty")) !!}
                            {!! Form::text("order_faculty", null, ['required' => 'required', 'class' => 'form-control order_faculty'
,                            "oninvalid"=>"InvalidMsg(this)"
]) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">

                        <div class="input-group mb-3" style="    margin-bottom: 10px;">
                            {!! Form::label("order_email", "البريد الإلكتروني (اسم المستخدم فقط)") !!}
                            <div class="input-group">
                                <div class="input-group-addon"
                                     id="domainHTML">
                                </div>
{{--                                {{$universities->first()->stud_domain??$universities->first()->staff_domain}}@--}}
                                <input class="form-control order_email" name="order_email"  required placeholder="email" oninvalid="InvalidMsg(this)">
                            </div>
                        </div>
                        <input type="hidden" name="domain" id="domain" value="">
{{--                        {{$universities->first()->stud_domain??$universities->first()->staff_domain}}--}}
                    </div>
                </div>


                <div class="row">

                    <div class="col-xs-12">
                        <div class="form-group">
                            {!! Form::label("order_phone", "رقم الجوال (يبدأ ب 966)") !!}
                            {!! Form::number("order_phone", null, ['required' => 'required', 'class' => 'form-control order_phone',
                            "oninvalid"=>"InvalidMsg(this)"]) !!}
                        </div>
                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-md-12">
                        <script src="https://www.google.com/recaptcha/api.js?hl=ar" async defer></script>
                        <div class="g-recaptcha" data-sitekey="{{config('attendize.captcha.captcha_key')}}"></div>


                    </div>
                </div>
                {{--                <div class="row"><div class="col-md-12">&nbsp;</div></div>--}}
                {{--                <div class="row">--}}
                {{--                    <div class="col-md-12">--}}
                {{--                        <div class="form-group">--}}
                {{--                            <div class="custom-checkbox">--}}
                {{--                                {!! Form::checkbox('is_business', 1, null, ['data-toggle' => 'toggle', 'id' => 'is_business']) !!}--}}
                {{--                                {!! Form::label('is_business', trans("Public_ViewEvent.is_business"), ['class' => 'control-label']) !!}--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                <div class="row">
                    <div class="col-md-12">&nbsp;</div>
                </div>
                {{--                <div class="row hidden" id="business_details">--}}
                {{--                    <div class="col-md-12">--}}
                {{--                        <div class="form-group">--}}
                {{--                            <div class="row">--}}
                {{--                                <div class="col-xs-6">--}}
                {{--                                    <div class="form-group">--}}
                {{--                                        {!! Form::label("business_name", trans("Public_ViewEvent.business_name")) !!}--}}
                {{--                                        {!! Form::text("business_name", null, ['class' => 'form-control']) !!}--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <div class="col-xs-6">--}}
                {{--                                    <div class="form-group">--}}
                {{--                                        {!! Form::label("business_tax_number", trans("Public_ViewEvent.business_tax_number")) !!}--}}
                {{--                                        {!! Form::text("business_tax_number", null, ['class' => 'form-control']) !!}--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                            <div class="row">--}}
                {{--                                <div class="col-xs-6">--}}
                {{--                                    <div class="form-group">--}}
                {{--                                        {!! Form::label("business_address_line1", trans("Public_ViewEvent.business_address_line1")) !!}--}}
                {{--                                        {!! Form::text("business_address_line1", null, ['class' => 'form-control']) !!}--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <div class="col-xs-6">--}}
                {{--                                    <div class="form-group">--}}
                {{--                                        {!! Form::label("business_address_line2", trans("Public_ViewEvent.business_address_line2")) !!}--}}
                {{--                                        {!! Form::text("business_address_line2", null, ['class' => 'form-control']) !!}--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                            <div class="row">--}}
                {{--                                <div class="col-xs-4">--}}
                {{--                                    <div class="form-group">--}}
                {{--                                        {!! Form::label("business_address_state", trans("Public_ViewEvent.business_address_state_province")) !!}--}}
                {{--                                        {!! Form::text("business_address_state", null, ['class' => 'form-control']) !!}--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <div class="col-xs-4">--}}
                {{--                                    <div class="form-group">--}}
                {{--                                        {!! Form::label("business_address_city", trans("Public_ViewEvent.business_address_city")) !!}--}}
                {{--                                        {!! Form::text("business_address_city", null, ['class' => 'form-control']) !!}--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                                <div class="col-xs-4">--}}
                {{--                                    <div class="form-group">--}}
                {{--                                        {!! Form::label("business_address_code", trans("Public_ViewEvent.business_address_code")) !!}--}}
                {{--                                        {!! Form::text("business_address_code", null, ['class' => 'form-control']) !!}--}}
                {{--                                    </div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <div class="row"><div class="col-md-12">&nbsp;</div></div>--}}
                {{--                <div class="p20 pl0">--}}
                {{--                    <a href="javascript:void(0);" class="btn btn-primary btn-xs" id="mirror_buyer_info">--}}
                {{--                        @lang("Public_ViewEvent.copy_buyer")--}}
                {{--                    </a>--}}
                {{--                </div>--}}

                <div class="row" hidden="">
                    <div class="col-md-12">
                        <div class="ticket_holders_details">
                            <h3>@lang("Public_ViewEvent.ticket_holder_information")</h3>
                            <?php
                            $total_attendee_increment = 0;
                            ?>
                            @foreach($tickets as $ticket)
                                @for($i=0; $i<=$ticket['qty']-1; $i++)
                                    <div class="panel panel-primary">

                                        <div class="panel-heading">
                                            <h3 class="panel-title">
                                                <b>{{$ticket['ticket']['title']}}</b>: @lang("Public_ViewEvent.ticket_holder_n", ["n"=>$i+1])
                                            </h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {!! Form::label("ticket_holder_first_name[{$i}][{$ticket['ticket']['id']}]", trans("Public_ViewEvent.first_name")) !!}
                                                        {!! Form::text("ticket_holder_first_name[{$i}][{$ticket['ticket']['id']}]", null, [ 'class' => "ticket_holder_first_name.$i.{$ticket['ticket']['id']} ticket_holder_first_name form-control"]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {!! Form::label("ticket_holder_last_name[{$i}][{$ticket['ticket']['id']}]", trans("Public_ViewEvent.last_name")) !!}
                                                        {!! Form::text("ticket_holder_last_name[{$i}][{$ticket['ticket']['id']}]", null, [ 'class' => "ticket_holder_last_name.$i.{$ticket['ticket']['id']} ticket_holder_last_name form-control"]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {!! Form::label("ticket_holder_email[{$i}][{$ticket['ticket']['id']}]", trans("Public_ViewEvent.email_address")) !!}
                                                        {!! Form::text("ticket_holder_email[{$i}][{$ticket['ticket']['id']}]", null, ['class' => "ticket_holder_email.$i.{$ticket['ticket']['id']} ticket_holder_email form-control"]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {!! Form::label("ticket_holder_phone[{$i}][{$ticket['ticket']['id']}]", trans("Public_ViewEvent.phone")) !!}
                                                        {!! Form::text("ticket_holder_phone[{$i}][{$ticket['ticket']['id']}]", null, [ 'class' => "ticket_holder_phone.$i.{$ticket['ticket']['id']} ticket_holder_phone form-control"]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {!! Form::label("ticket_holder_university_id[{$i}][{$ticket['ticket']['id']}]", trans("Public_ViewEvent.university_id")) !!}
                                                        {!! Form::text("ticket_holder_university_id[{$i}][{$ticket['ticket']['id']}]", null, [ 'class' => "ticket_holder_university_id.$i.{$ticket['ticket']['id']} ticket_holder_university_id form-control"]) !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-md-12">
                        @include('Public.ViewEvent.Partials.AttendeeQuestions', ['ticket' => $ticket['ticket'],'attendee_number' => $total_attendee_increment++])
                    </div>
                </div>
                @if($event->pre_order_display_message)
                    <div class="well well-small">
                        {!! nl2br(e($event->pre_order_display_message)) !!}
                    </div>
                @endif

                {!! Form::hidden('is_embedded', $is_embedded) !!}
                {!! Form::submit(trans("Public_ViewEvent.checkout_order"), ['class' => 'btn btn-lg btn-success card-submit', 'style' => 'width:100%;']) !!}
                {!! Form::close() !!}

            </div>
        </div>
    </div>
    <img src="https://cdn.attendize.com/lg.png"/>
</section>
@if(session()->get('message'))
    <script>showMessage('{{session()->get('message')}}');</script>
@endif

