@extends('layout')
@section('content')
<div class="card-titel text-center p-3">
    <h3>Form Invoice</h3>
</div>
<div class="row">
    <div class="col-sm-12 col-md-8 offset-2">
        <form id="invoiceForm" method="post">
            <h4 class="border-bottom py-2">Invoice detail</h4>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Select Customer</label>
                <div class="col-sm-10">
                    <select name="customer_id" id="customer_id" class="form-control">
                        <option></option>
                        @foreach (App\Models\Customer::all() as $cust)
                        <option value="{{$cust->id}}">{{$cust->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Issued Date</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control" id="issued_date" value="{{date()}}">
    </div>
</div> --}}
<div class="form-group row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Due Date</label>
    <div class="col-sm-10">
        <input type="date" id="due_date" class="form-control">
    </div>
</div>
<div class="form-group row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Subject</label>
    <div class="col-sm-10">
        <input type="text" id="subject" class="form-control" value="">
    </div>
</div>
<div class="form-group row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Current Payment</label>
    <div class="col-sm-10">
        <input type="number" id="current_payment" class="form-control" value="0">
    </div>
</div>
<div class="form-group row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Tax Percent</label>
    <div class="col-sm-10">
        <input type="number" id="tax" class="form-control" value="10">
    </div>
</div>
<h4 class="border-bottom py-2">Invoice items</h4>
<button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#itemsModal">Add</button>
<button type="button" class="btn btn-danger mb-2" id="cancel-btn">Reset</button>

<table class="table" id="item-table">
    <thead>
        <th>Item Type</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Price</th>
    </thead>
    <tbody></tbody>
</table>
</form>
<div class="text-center">
    <button class="btn btn-success" id="save-btn">Save</button>
    <button class="btn btn-warning" id="cancel-btn">Cancel</button>
</div>
</div>
</div>
<div class="modal fade" id="itemsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Assign Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form">
                    <div class="form-group col-md-12">
                        <label for="itemType">Item Type</label>
                        <select id="itemType" class="form-control">
                            <option value=""></option>
                            @foreach (App\Models\Item::all() as $item)
                            <option value="{{$item->id}}">{{$item->item_type}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="desc">Description</label>
                        <textarea name="" id="desc" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="qty">Quantity</label>
                        <input type="number" class="form-control" id="qty">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" id="price">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="item-assign-btn">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $().ready(()=>{

        let tableBody = $('table#item-table>tbody');
        let invoice_items = [];
        let sessionItems = sessionStorage.getItem('items_invoice')
        if (sessionItems) {
            let tableData = JSON.parse(sessionItems);
            tableData.forEach(item=>{
                tableBody.append(
                `<tr>
                    <td>${item.itemType}</>
                    <td>${item.description}</>
                    <td>${item.quatity}</>
                    <td>${item.price}</>
                </tr>`
            )
            })
        }
        $('button#item-assign-btn').on('click', ()=>{
            let invoice_item ={
                item_id : $('#itemType').val(),
                itemType: $('#itemType option:selected').text(),
                description:$('#desc').val(),
                quantity:$('#qty').val(),
                price:$('#price').val()
            }

            invoice_items.push(invoice_item)
            // invoice_item.idItem = $('#itemType').val();
            // invoice_item.itemType = $('#itemType option:selected').text();
            // invoice_item.description = $('#desc').val();
            // invoice_item.quatity = $('#qty').val();
            // invoice_item.price = $('#price').val();
            tableBody.append(
                `<tr>
                    <td>${invoice_item.itemType}</>
                    <td>${invoice_item.description}</>
                    <td>${invoice_item.quantity}</>
                    <td>${invoice_item.price}</>
                </tr>`
            )

            sessionStorage.setItem('items_invoice', JSON.stringify(invoice_items));

            $('#itemsModal').modal('hide');

        })

        //Trigger on save
        $.ajaxSetup({
            headers : {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#save-btn').on('click', ()=>{

            let requestForm = {
                customer_id: $('#customer_id').val(),
                due_date: $('#due_date').val(),
                subject: $('#subject').val(),
                current_payment:$('#current_payment').val(),
                tax_percent: $('#tax').val(),
                items: JSON.parse(sessionStorage.getItem('items_invoice'))
            }
            $.ajax({
                method: 'post',
                url : '/invoice',
                data: requestForm,
                success: function(res){
                    console.log(res);
                    alert('Invoice Created.')
                    window.location.href = '/invoice'
                },
                error: err => {
                    alert(err)
                }

            })
        })
    })
</script>
@endsection
