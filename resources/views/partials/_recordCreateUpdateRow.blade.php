 <tr id="transaction_{{$index}}">
     <td>
         <div class="form-group">
             <select name="transactions[{{$index}}][coa_id]" id="coa_{{$index}}">
                 <x-hierarchical-dropdown :parents="$coaCategory" with="accountsubcat" childFieldToDisplay="name" />
             </select>

         </div>
     </td>
     <td>
         <div class="form-group">
             <select class="contacttype" id="contacttype_{{$index}}" name="transactions[{{$index}}][contact_id]">
                 <option disabled selected>Contact types</option>
                 <x-hierarchical-dropdown :parents="$contacttypes" with="contact" childFieldToDisplay="user-username" />
             </select>
         </div>

     </td>
     <td>
         <div class="ml-4 form-group">
             <select name="transactions[{{$index}}][budget_id]" id="budge_{{$index}}" class="budget_dropdown">
                 <option value="" disabled selected>Budgets</option>
             </select>
         </div>
     </td>
     <td>
         <div class="form-group">
             <textarea name="transactions[{{$index}}][desc]" id="description_{{$index}}" rows="2" class="form-control"></textarea>
         </div>
     </td>
     <td>
         <div class="form-group">
             <input type="number" class="form-control net_amount" step="0.0001" name="transactions[{{$index}}][amount]"
                 id="amount_{{$index}}">
         </div>
     </td>
     <td>
         <button class="btn btn-danger remove-budget-btn d-none" data-transaction-index="{{$index}}"><i
                 class="bi bi-trash3"></i></button>
     </td>
 </tr>
