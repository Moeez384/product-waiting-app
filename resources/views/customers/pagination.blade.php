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
                     <td class="align-center statusHref"> <a href="#" class="button editCustomerStatus"
                             data-id={{ $customer->id }}>
                             {{ $customer->status == 0 ? 'Activate' : 'Disable' }}</a></td>
                 </tr>
             @endforeach
         </tbody>
     </table>
     {{ $customers->links('pagination') }}
 </div>
