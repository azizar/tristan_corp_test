<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index', ['invoices' => Invoice::with('customer')->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $invoice = new Invoice();
        $invoice->customer_id = $request->customer_id;
        $invoice->issued_date = $request->issued_date ? $request->issued_date : date('Y-m-d');
        $invoice->due_date = $request->due_date;
        $invoice->subject = $request->subject;
        $invoice->current_payment = $request->current_payment;
        $invoice->tax_percent = $request->tax_percent;
        $invoice->save();

        foreach ($request->items as $key => $value) {
            $invoice->items()->attach($value['item_id'], [
                'price' => $value['price'],
                'quantity' => $value['quantity'],
                'amount_total' => $value['price'] * $value['quantity'],
                'description' => $value['description'],
            ]);
        }
        return response()->json(['message'=>'Success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $data = $invoice->load('customer', 'items');

        $subtotal = 0;
        foreach ($data->items as $item) {
            $subtotal += $item->pivot->amount_total;
        }
        $amountTax = ($subtotal * $data->tax_percent) / 100;
        $amountPayment = ($subtotal + $amountTax) - $data->current_payment;
        // dump('Sub Total : ' . $subtotal);
        // dump('Tax : ' . $amountTax);
        // dump('Current Payment : ' . $data->current_payment);
        // dump('Total : ' . $amountPayment);

        return view('show', [
            'invoice' => $data,
            'subtotal' => $subtotal,
            'tax' => $amountTax,
            'amount_payment' => $amountPayment
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        return view('edit', ['invoice' => $invoice->load('customer', 'items')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $invoice)
    {
        $invoiceData = Invoice::where('id', $invoice)->first();

        $invoiceData->customer_id = $request->customer_id;
        $invoiceData->due_date = $request->due_date;
        $invoiceData->subject = $request->subject;
        $invoiceData->current_payment = $request->current_payment;
        $invoiceData->tax_percent = $request->tax_percent;

        $invoiceData->save();

        return redirect()->route('invoice.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->items()->detach();
        $invoice->delete();
        return redirect()->route('invoice.index');
    }
}
