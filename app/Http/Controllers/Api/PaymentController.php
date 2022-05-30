<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends BaseController
{
    public function CreatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required',
            'Amount' => 'required',
            'Paid_on' => 'required',
        ]);
        if($validator->fails()){
            return $this->handleError($validator->errors());
        }

        $input = $request->all();

        $transaction = Transaction::where('id', $input['transaction_id'])->first();
        if($transaction == null){
            return $this->handleError("Transaction not found");

        }elseif ($transaction['status_id'] == 1){
            return $this->handleError("Transaction is fully paid");
        }else{

            $newPaidAmount = $transaction['PaidAmount'] + $input['Amount'];
            if($newPaidAmount > $transaction['Amount']){
                $remaining = $transaction['Amount'] - $transaction['PaidAmount'];
                return $this->handleError("Transaction just needs " . $remaining . " to be fully paid");
            }elseif ($newPaidAmount < $transaction['Amount']){

                $transaction->update(['PaidAmount' => $newPaidAmount]);
            }else{
                $transaction->update(['PaidAmount' => $newPaidAmount,'status_id' => 1]);

            }
            $payment = Payment::create($input);
            return $this->handleResponse(new \App\Http\Resources\Payment($payment) , 'Payment successfully created!');

        }


    }

    public function ViewAllPayments(){
        $payments = Payment::all();
        return $this->handleResponse(new \App\Http\Resources\PaymentCollection($payments) , 'Payments successfully retrieved!');
    }

    public function ViewTransactionPayments(Request $request){
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->handleError($validator->errors());
        }
        $input = $request->all();

        $transaction = Transaction::where('id', $input['transaction_id'])->first();
        if($transaction == null){
            return $this->handleError("Transaction not found");
        }
        $transactionPayments = Payment::where('transaction_id',$input['transaction_id'])->get();
        if (count($transactionPayments) != 0){
            return $this->handleResponse(new \App\Http\Resources\PaymentCollection($transactionPayments) , 'Payments successfully retrieved!');
        }else{
            return $this->handleError("No payments for this transaction");
        }
    }
}
