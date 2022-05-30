<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Api\AuthController as AuthCon;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Payment;
use App\Models\Status;
use App\Models\SubCategory;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends BaseController
{
    public function CreateTransaction(Request $request){
        $validator = Validator::make($request->all(), [
            'Amount' => 'required',
            'Duo_on' => 'required',
            'Vat' => 'required',
            'Is_vat_included' => 'required',
            'category_id' => 'required',
            'payer_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->handleError($validator->errors());
        }

        $input = $request->all();

        $category = Category::where('id', $input['category_id'])->first();
        if($category == null){
            return $this->handleError("Category not found");
        }

        if($request->has('sub_category_id')){

            $subCategory = SubCategory::where('id', $input['sub_category_id'])->first();
            if($subCategory == null){
                return $this->handleError("SubCategory not found");
            }

        }

        $payer = User::where('id', $input['payer_id'])->first();
        if($payer == null){
            return $this->handleError("User not found");
        }

        $currentDate = Carbon::now();
        $duoDate = Carbon::createFromFormat('Y-m-d H:i:s',  $input['Duo_on']);
        if($currentDate->gte($duoDate)){
            $input['status_id'] = 3;
        }else{
            $input['status_id'] = 2;
        }
        $input['PaidAmount'] = 0.0;

        $transaction = Transaction::create($input);
        return $this->handleResponse(new \App\Http\Resources\Transaction($transaction) , 'Transaction successfully created!');

    }

    public function ViewAllTransactions(){
        $transactions = Transaction::all();
        $today = Carbon::now();
        $transactions->map(function ($single) use ($today){
            if( ($single->PaidAmount < $single->Amount) && ($today->gt($single->Duo_on)) ){
                $single->status_id = 3;
                $single->update(['status_id'=>3]);
            }
        });
        return $this->handleResponse(new \App\Http\Resources\TransactionCollection($transactions) , 'Transactions successfully retrieved!');
    }

    public function ShowTransactions(){
        $userId = $this->getUser();
        $transactions = Transaction::where('payer_id', $userId)->select('Amount', 'PaidAmount', 'Duo_on')->get();
        $transactions->map(function($item){
           $item->Duo_on = Carbon::createFromFormat('Y-m-d H:i:s', $item->Duo_on)->format('Y-m-d');
        });
        return $this->handleResponse($transactions , 'Transactions successfully retrieved!');

    }


}
