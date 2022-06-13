@extends('shopify-app::layouts.default')

@section('content')
    <section></section>
    <section class="full-width">
        <article>
            <div class="columns seven">
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
            <div class="columns five">
                <div class="align-right">
                    <a class="button add-rule" href="{{ route('rules.create') }}">
                        <span>Add Rule</span>
                    </a>
                    <a class="button general-settings" href="{{ route('general.settings') }}">
                        <span>Settings</span>
                    </a>
                    <a class="button general-settings" id="exportCsv">
                        <span>Export Csv</span>
                    </a>
                </div>
            </div>
        </article>
    </section>

    <section class="full-width error-message default-hidden">
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
                <div class="column twelve success-message">
                    <div class="alert success">
                        <dl>
                            <dt>Success</dt>
                            <dd id="message"></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <section class="full-width">
        <article>
            <div class="card">
                <div class="row">
                    <input type="search" id="search" placeholder="Search..." />
                </div>
                <div class="row" id="ruleTable">
                    <div id="pagination">
                        <table class="results">
                            <thead>
                                <tr>
                                    <th class="align-center"><strong>Rule ID</strong></th>
                                    <th class="align-center"><strong>Rule Title</strong></th>
                                    <th class="align-center"><strong>Products</strong></th>
                                    <th class="align-center"><strong>Start Date</strong></th>
                                    <th class="align-center"><strong>End Date</strong></th>
                                    <th class="align-center"><strong>Status</strong></th>
                                    <th class="align-center"><strong>No of Customers</strong></th>
                                    <th class="align-center"><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ruleSettings as $rule)
                                    <tr>
                                        <td class="align-center">{{ $loop->index + 1 }}</td>
                                        <td class="align-center">{{ $rule->title }}</td>

                                        <td>
                                            @foreach ($rule->categories as $categories)
                                                {{ $categories->title }},
                                            @endforeach
                                        </td>
                                        <td class="align-center">{{ $rule->start_date }}</td>
                                        <td class="align-center">{{ $rule->end_date }}</td>

                                        <td class="align-center">{{ $rule->status }}</td>
                                        <td class="align-center">{{ $rule->no_of_customers }}</td>

                                        <td>
                                            <a class="button secondary edit-rule icon-edit"
                                                href="{{ route('rules.edit', $rule->id) }}"></a>
                                            <a class="button secondary delete-rule icon-trash"
                                                data-attr="{{ $rule->id }}"></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $ruleSettings->links('pagination') }}
                    </div>
                </div>
            </div>
        </article>
    </section>

    {{-- @include('rule.partials.models.modelViewFiles') --}}
@stop
