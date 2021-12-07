<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\InvoicePayment;
use App\InvoiceProduct;
use App\Project;
use App\ProjectTask;
use App\ProjectUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usr             = Auth::user();
        $user_projects   = $usr->projects()->pluck('project_id')->toArray();
        $send_invoice    = Invoice::whereIn('project_id', $user_projects)->where('created_by', '=', $usr->id)->get();
        $receive_invoice = Invoice::whereIn('project_id', $user_projects)->where('client_id', '=', $usr->id)->get();

        return view('invoices.index', compact('send_invoice', 'receive_invoice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Auth::user()->projects()->where('permission', 'owner')->get();

        return view('invoices.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'project_id' => 'required',
                               'client_id' => 'required',
                               'due_date' => 'required',
                               'tax_id' => 'required',
                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('invoices.index')->with('error', $messages->first());
        }

        $invoice             = new Invoice();
        $invoice->invoice_id = $this->invoiceNumber();
        $invoice->project_id = $request->project_id;
        $invoice->client_id  = $request->client_id;
        $invoice->due_date   = $request->due_date;
        $invoice->tax_id     = $request->tax_id;
        $invoice->created_by = \Auth::user()->getCreatedBy();
        $invoice->save();

        return redirect()->route('invoices.index')->with('success', __('Invoice successfully created!'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Invoice $invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $usr = Auth::user();

        $left_address = $usr->decodeDetails();

        if($invoice->client_id == $usr->id)
        {
            $right_address = $usr->decodeDetails($invoice->created_by);
        }
        else
        {
            $right_address = $usr->decodeDetails($invoice->client_id);
        }

        return view('invoices.show', compact('invoice', 'left_address', 'right_address'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Invoice $invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        if(Auth::user()->id == $invoice->created_by)
        {
            $projects = Auth::user()->projects()->where('permission', 'owner')->get();

            return view('invoices.edit', compact('invoice', 'projects'));
        }
        else
        {
            return redirect()->route('invoices.index')->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Invoice $invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        if(Auth::user()->id == $invoice->created_by)
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'project_id' => 'required',
                                   'client_id' => 'required',
                                   'due_date' => 'required',
                                   'tax_id' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('invoices.index')->with('error', $messages->first());
            }

            $invoice->project_id = $request->project_id;
            $invoice->client_id  = $request->client_id;
            $invoice->due_date   = $request->due_date;
            $invoice->tax_id     = $request->tax_id;
            $invoice->save();

            return redirect()->back()->with('success', __('Invoice successfully updated!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Invoice $invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        if($invoice->created_by == \Auth::user()->id)
        {
            InvoicePayment::where('invoice_id', '=', $invoice->id)->delete();
            InvoiceProduct::where('invoice_id', '=', $invoice->id)->delete();
            $invoice->delete();

            return redirect()->route('invoices.index')->with('success', __('Invoice successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    // get invoice number
    function invoiceNumber()
    {
        $latest = Invoice::where('created_by', '=', \Auth::user()->getCreatedBy())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->invoice_id + 1;
    }

    // project wise load client
    function jsonClient(Request $request)
    {
        $client_user = ProjectUser::where('project_id', '=', $request->project_id)->where('permission', 'client')->pluck('user_id')->toArray();
        $clients     = User::whereIn('id', array_unique($client_user))->get()->pluck('name', 'id');

        return response()->json($clients, 200);
    }

    public function productAdd($id)
    {
        $usr     = Auth::user();
        $invoice = Invoice::find($id);

        if($invoice->created_by == $usr->id)
        {
            $tasks = ProjectTask::where('project_id', '=', $invoice->project_id)->pluck('name')->toArray();

            return view('invoices.item', compact('invoice', 'tasks'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function productStore($id, Request $request)
    {
        $usr     = Auth::user();
        $invoice = Invoice::find($id);
        if($invoice->created_by == $usr->id)
        {
            $validate = [];
            if($invoice->getTotal() == 0.0)
            {
                Invoice::change_status($invoice->id, 1);
            }

            if($request->from == 'tasks-tab')
            {
                $validate = [
                    'task' => 'required',
                ];

                $item = $request->task;
            }
            else
            {
                $validate = [
                    'title' => 'required',
                ];

                $item = $request->title;
            }

            $validate['price'] = 'required|numeric|min:0';

            $validator = Validator::make(
                $request->all(), $validate
            );

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            InvoiceProduct::create(
                [
                    'invoice_id' => $invoice->id,
                    'item' => $item,
                    'price' => $request->price,
                    'type' => str_replace('-tab', '', $request->from),
                ]
            );

            return redirect()->back()->with('success', __('Item successfully added.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function productDelete($id, $product_id)
    {
        $usr     = Auth::user();
        $invoice = Invoice::find($id);

        if($invoice->created_by == $usr->id)
        {
            $invoiceProduct = InvoiceProduct::find($product_id);
            $invoiceProduct->delete();

            if($invoice->getDue() <= 0.0)
            {
                Invoice::change_status($invoice->id, 3);
            }

            return redirect()->back()->with('success', __('Item successfully deleted.'));
        }
    }

    public function paymentAdd($id)
    {
        $usr     = Auth::user();
        $invoice = Invoice::find($id);
        if($invoice->created_by == $usr->id)
        {
            return view('invoices.payment', compact('invoice'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function paymentStore($id, Request $request)
    {
        $usr     = Auth::user();
        $invoice = Invoice::find($id);

        if($invoice->created_by == $usr->id)
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'amount' => 'required|numeric|min:1',
                                   'date' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            InvoicePayment::create(
                [
                    'transaction_id' => $this->transactionNumber(),
                    'invoice_id' => $invoice->id,
                    'amount' => $request->amount,
                    'date' => $request->date,
                    'payment_id' => 0,
                    'payment_type' => __('MANUAL'),
                    'notes' => $request->notes,
                ]
            );
            if($invoice->getDue() == 0.0)
            {
                Invoice::change_status($invoice->id, 3);
            }
            else
            {
                Invoice::change_status($invoice->id, 2);
            }

            return redirect()->back()->with('success', __('Payment successfully added.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function transactionNumber()
    {
        $latest = InvoicePayment::select('invoice_payments.*')->join('invoices', 'invoice_payments.invoice_id', '=', 'invoices.id')->where('invoices.created_by', '=', \Auth::user()->id)->latest()->first();
        if($latest)
        {
            return $latest->transaction_id + 1;
        }

        return 1;
    }
}
