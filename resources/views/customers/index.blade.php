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
                    <div class="columns six">
                        <input type="search" id="search" placeholder="Search..." />
                    </div>
                    <div class="columns six">
                        <select name="product" id="searchByProduct">
                            <option value="">Search by product</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->product_or_collection_id }}">{{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row" id="customerTable">
                    <div id="pagination">
                        <table class="results">
                            <thead>
                                <tr>
                                    <th class="align-center"><strong>Customer ID</strong></th>
                                    <th class="align-center"><strong>Email</strong></th>
                                    <th class="align-center"><strong>Products</strong></th>
                                    <th class="align-center"><strong>Status</strong></th>
                                    <th class="align-center"><strong>Activate/Disable</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td class="align-center">{{ $loop->index + 1 }}</td>
                                        <td class="align-center">{{ $customer->email }}</td>
                                        @forelse ($customer->categories as $category)
                                            <td class="align-center">{{ $category->title }},</td>
                                        @empty
                                            <td class="align-center">No Product Found</td>
                                        @endforelse
                                        <td class="align-center status">
                                            {{ $customer->status == 0 ? 'Not Active' : 'Active' }}</td>
                                        <td class="align-center statusHref"><a href="#" class="button editCustomerStatus"
                                                data-id={{ $customer->id }}>
                                                {{ $customer->status == 0 ? 'Activate' : 'Disable' }}</a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $customers->links('pagination') }}
                    </div>
                </div>
            </div>
        </article>
    </section>
@stop
