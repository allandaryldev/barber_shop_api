<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Customers;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionsResource;
use App\Filters\TransactionsFilter;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TransactionsResource::collection((new TransactionsFilter())->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request)
    {
        $input = $request->validated();
        $input['user_id'] = auth()->id();
        $transaction = Transaction::create($input);
        if($transaction->total >=1500){

            $user = Customers::find($transaction->customer->id); 
            $user->points +=1;
            $user->update(['points'=>$user->points]);
            
        }
        $items = collect($request->items);

        $items->each(function ($item) use ($transaction) {
            $transaction->items()->create($item);
        });

        return new TransactionsResource($transaction);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return new TransactionsResource($transaction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $input = $request->validate();
        $transaction->update($input);

        

        return new TransactionsResource($transaction);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response()->noContent();
    }
}
