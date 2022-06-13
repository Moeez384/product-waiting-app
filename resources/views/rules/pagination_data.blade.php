 <div id="pagination">
     <table class="results">
         <thead>
             <tr>
                 <th class="align-center"><strong>Rule ID</strong></th>
                 <th class="align-center"><strong>Rule Title</strong></th>
                 <th class="align-center"><strong>Products/Collection</strong></th>
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
                         <a class="button secondary delete-rule icon-trash" data-attr="{{ $rule->id }}"></a>
                     </td>
                 </tr>
             @endforeach
         </tbody>
     </table>
     {{ $ruleSettings->links('pagination') }}
 </div>
