@extends('layout')
@section('content')
<div class="card-title">
    <h3>INVOICE</h3>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="row">
            <div class="col-sm-4 border-right">Invoice No.</div>
            <div class="col-sm-8">
                {{str_pad($invoice->id, 4, '0', STR_PAD_LEFT)}}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 border-right">Issued Date</div>
            <div class="col-sm-8">
                {{$invoice->issued_date}}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 border-right">Due Date</div>
            <div class="col-sm-8">
                {{$invoice->due_date}}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 border-right">Subject</div>
            <div class="col-sm-8">
                {{$invoice->subject}}
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="row">
            <div class="col-md-4 border-right">Invoice For</div>
            <div class="col-md-8">
                {{$invoice->customer->name}}
                {{$invoice->customer->address_street}} <br>
                {{$invoice->customer->address_state}} <br>
                {{$invoice->customer->address_country}}
            </div>
        </div>
    </div>
</div>
<div class="my-5">
    <table class="table" id="item-table">
        <thead>
            <th>Item Type</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Amount</th>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
            <tr>
                <td>{{$item->item_type}}</td>
                <td>{{$item->pivot->description}}</td>
                <td>{{$item->pivot->quantity}}</td>
                <td>{{$item->pivot->price}}</td>
                <td>{{$item->pivot->quantity*$item->pivot->price}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="w-25 float-right">
        <div class="row">
            <div class="col-md-4">Subtotal</div>
            <div class="col-md-8">
                {{$subtotal}}
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">Tax ({{$invoice->tax_percent.'%'}})</div>
            <div class="col-md-8">
                {{$tax}}
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">Payment</div>
            <div class="col-md-8">
                {{$invoice->current_payment}}
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-4 font-weight-bold">Amount</div>
            <div class="col-md-8 font-weight-bold">
                {{$amount_payment}}
            </div>
        </div>
    </div>
</div>
@endsection
