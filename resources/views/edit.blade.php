@extends('layout')
@section('content')
<div class="card-titel text-center p-3">
    <h3>Form Edit Invoice</h3>
</div>
<div class="row">
    <div class="col-sm-12 col-md-8 offset-2">
        <form id="invoiceForm" method="POST" action="{{ route('invoice.update', ['invoice'=>$invoice->id]) }}">
            @csrf
            @method('PATCH')
            <h4 class="border-bottom py-2">Invoice : {{str_pad($invoice->id, 4, '0', STR_PAD_LEFT);}}</h4>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Select Customer</label>
                <div class="col-sm-10">
                    <select name="customer_id" id="customer_id" class="form-control">
                        <option></option>
                        @foreach (App\Models\Customer::all() as $cust)
                            <option {{$cust->id==$invoice->customer_id ? 'selected':''}} value="{{$cust->id}}">{{$cust->name}}</option>
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
                    <input type="date" name="due_date" id="due_date" class="form-control" value="{{$invoice->due_date}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Subject</label>
                <div class="col-sm-10">
                    <input type="text" name="subject" id="subject" class="form-control" value="{{$invoice->subject}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Current Payment</label>
                <div class="col-sm-10">
                    <input type="number" name="current_payment" id="current_payment" class="form-control" value="{{$invoice->current_payment}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Tax Percent</label>
                <div class="col-sm-10">
                    <input type="number" name="tax_percent" id="tax" class="form-control" value="{{$invoice->tax_percent}}">
                </div>
            </div>
            <h4 class="border-bottom py-2">Invoice items</h4>

            <table class="table" id="item-table">
                <thead>
                    <th>Item Type</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td>{{$item->item_type}}</td>
                            <td>{{$item->pivot->description}}</td>
                            <td>{{$item->pivot->quantity}}</td>
                            <td>{{$item->pivot->price}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-center">
                <button type="submit" class="btn btn-success" id="save-btn">Update</button>
                <a href="{{ route('invoice.index') }}" class="btn btn-warning" id="cancel-btn">Cancel</a>
            </div>
        </form>

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
