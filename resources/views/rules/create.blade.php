@extends('shopify-app::layouts.default')

@section('content')
    <section></section>
    <section class="full-width">
        <article>
            <div class="columns six">
                <div class="align-left">
                    <a class="button secondary user-guide" href="#">
                        <i class="icon-question append-spacing"></i>
                        <span>User Guide</span>
                    </a>
                    <a class="link" target="_blank" style="padding-left: 1rem;"
                        href="https://support.extendons.com/">
                        <span>Get Support</span>
                    </a>
                </div>
            </div>
            <div class="columns six">
                <div class="align-right">
                    <a class="button home" href="{{ route('rules.index') }}">
                        <span>Rules</span>
                    </a>
                    <a class="button general-settings" href="{{ route('general.settings') }}">
                        <span>Settings</span>
                    </a>
                </div>
            </div>
        </article>
    </section>

    <section class="full-width error-message default-hidden" id="errorMessages">
        <article>
            <div class="columns twelve">
                <div class="alert error">
                    <dl>
                        <dt>Error Alert</dt>
                        <dd class="exception-error">All fields are Required</dd>
                    </dl>
                </div>
            </div>
        </article>
    </section>

    <section class="full-width success-message-1 default-hidden">
        <article>
            <div class="columns twelve">
                <div class="column twelve success-message"></div>
            </div>
        </article>
    </section>

    <section class="full-width">
        <article>
            <div class="column twelve">
                <div class="card has-sections" style="padding: 0;">
                    <ul class="tabs">
                        <li class="active" id="ruleLi">
                            <a href="#"><i class="icon-edit append-spacing" id="ruleSettings"></i> Rule </a>
                        </li>
                        {{-- <li id="productLi">
                            <a href="#"><i class="icon-pages append-spacing" id="products"></i>Products</a>
                        </li> --}}
                    </ul>
                    {!! Form::open(['id' => 'product-rule-settings']) !!}
                    {{ Form::hidden('id', $ruleSettings['id'], ['id' => 'id']) }}
                    {{ Form::hidden('domain', Auth::user()->name) }}
                    <div class="tab-content-setting tab-content" id="rulesTab">
                        <div class="card-section">
                            <div class="row column twelve">
                                <div class="row">
                                    <div class="columns five">
                                        {{ Form::label('status_cb', 'Enable/Disable the rule') }}
                                    </div>
                                    <div class="columns seven">
                                        @if ($ruleSettings['status'] == 0)
                                            {{ Form::checkbox('status_cb', '0', false, ['id' => 'status_cb']) }}
                                            {{ Form::hidden('status', 0, ['id' => 'status']) }}
                                        @else
                                            {{ Form::checkbox('status_cb', '1', true, ['id' => 'status_cb']) }}
                                            {{ Form::hidden('status', 1, ['id' => 'status']) }}
                                        @endif
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="columns five">
                                        {{ Form::label('title', 'Enter rule title') }}
                                    </div>
                                    <div class="columns seven">
                                        {{ Form::text('title', $ruleSettings['title'], ['placeholder' => 'Enter rule title', 'id' => 'title']) }}
                                        <label class="error_title fieldErrors default-hidden" id="titleError">
                                            <ul>
                                                <li style="color:#D8000C; font-size:14px;"></li>
                                            </ul>
                                        </label>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="columns five">
                                        {{ Form::label('no_of_customers', 'Set No. of Customer') }}
                                    </div>
                                    <div class="columns seven">

                                        {{ Form::number('no_of_customers', $ruleSettings['no_of_customers'], ['min' => 1, 'placeholder' => 'Set the number of customers', 'id' => 'noOfcustomers']) }}
                                        <label class="error_title fieldErrors default-hidden" id="noOfCutomerError">
                                            <ul>
                                                <li style="color:#D8000C; font-size:14px;"></li>
                                            </ul>
                                        </label>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="columns five">
                                        {{ Form::label('start date', 'Enter Start Date') }}
                                    </div>
                                    <div class="columns seven">
                                        {{ Form::text('start_date', $ruleSettings['start_date'], ['placeholder' => 'Enter Start Date', 'class' => 'datepicker', 'id' => 'start_date']) }}
                                        <label class="error_title fieldErrors default-hidden" id="startDateError">
                                            <ul>
                                                <li style="color:#D8000C; font-size:14px;"></li>
                                            </ul>
                                        </label>
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="columns five">
                                        {{ Form::label('End Date', 'Enter End Date') }}
                                    </div>
                                    <div class="columns seven">
                                        {{ Form::text('end_date', $ruleSettings['end_date'], ['placeholder' => 'Enter End Date', 'class' => 'datepicker', 'id' => 'end_date']) }}
                                        <label class="error_title fieldErrors default-hidden" id="endDateError">
                                            <ul>
                                                <li style="color:#D8000C; font-size:14px;"></li>
                                            </ul>
                                        </label>
                                    </div>
                                </div>
                                <br>

                                @if ($ruleSettings['id'] == 0)
                                    <div class="row" id="pro">
                                        <div class="columns five">
                                            {{ Form::label('Products', 'Products') }}
                                        </div>
                                        <div class="columns seven">
                                            {!! Form::select('products[]', ['pr' => 'xyz', 'c' => 'zbc'], null, ['id' => 'product', 'placeholder' => 'Please Select the Product', 'multiple' => 'multiple', 'class' => 'myselect', 'style' => 'width:100%']) !!}
                                            <label class="error_title fieldErrors default-hidden" id="productError">
                                                <ul>
                                                    <li style="color:#D8000C; font-size:14px;">
                                                    </li>
                                                </ul>
                                            </label>
                                        </div>
                                    </div>
                                @else
                                    <div class="row" id="pro">
                                        <div class="columns five">
                                            {{ Form::label('Products', 'Products') }}
                                        </div>
                                        <div class="columns seven">
                                            <select name="products[]" id="product" multiple="multiple" style="width: 100%;">
                                                @foreach ($ruleSettings->categories as $category)
                                                    <option value="{{ $category->product_or_collection_id }}" selected>
                                                        {{ $category->title }}</option>
                                                @endforeach
                                            </select>
                                            <label class="error_title fieldErrors default-hidden" id="productError">
                                                <ul>
                                                    <li style="color:#D8000C; font-size:14px;">
                                                    </li>
                                                </ul>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="columns twelve">
                                        <div class="align-right">
                                            <a class="button btn-save">
                                                <span>Save</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </article>
    </section>
@stop
