@extends('layout')
@section('content')
<div class="card-title text-center p-3">
    <h4>Invoices Data</h4>
</div>
<div class="float-right m-1">
    <a href="{{ route('invoice.create') }}" class="btn btn-primary">Add Invoice</a>
</div>
<table class="table">
    <thead class="text-center">
        <th>Invoice No.</th>
        <th>Issued Date</th>
        <th>Invoice For</th>
        <th>Subject</th>
        <th>Payment</th>
        <th>Action</th>
    </thead>
    <tbody class="text-center">
        @forelse ($invoices as $invoice)
        <tr>
            <td>{{str_pad($invoice->id, 4, '0', STR_PAD_LEFT);}}</td>
            <td>{{$invoice->issued_date}}</td>
            <td>{{$invoice->customer->name}}</td>
            <td>{{$invoice->subject}}</td>
            <td>{{$invoice->current_payment}}</td>
            <td>
                <form action="{{ route('invoice.destroy', ['invoice'=>$invoice]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a class="btn btn-primary" href="{{ route('invoice.show', ['invoice'=>$invoice]) }}"><i
                            class="fa fa-eye"></i></a>
                    <a class="btn btn-warning" href="{{ route('invoice.edit', ['invoice'=>$invoice]) }}"><i
                            class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    <button class="btn btn-danger" href="{{ route('invoice.destroy', ['invoice'=>$invoice]) }}"><i
                            class="fa fa-trash" aria-hidden="true"></i></a>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="bg-warning text-center">No Invoice Data</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
