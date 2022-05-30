<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController as BaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReportController extends BaseController
{
    public function CreateBasicReport(Request $request){

        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        if($validator->fails()){
            return $this->handleError($validator->errors());
        }
        $input = $request->all();

        $start_date = Carbon::createFromFormat('Y-m-d',  $input['start_date']);
        $end_date = Carbon::createFromFormat('Y-m-d',  $input['end_date']);
        $transactions = Transaction::whereBetween('created_at',[$start_date, $end_date])
                                    ->select('id', 'Amount', 'PaidAmount', 'Duo_on')
                                    ->get();
        if(count($transactions) == 0){
            return $this->handleError("No transactions found");
        }
        $finalData = [];
        $dateNow = Carbon::now()->format('Y-m-d');

        foreach ($transactions as $record){
            $data['id'] = $record->id;
            $data['PaidAmount'] = $record->PaidAmount;
            $recordDuoOn = Carbon::createFromFormat('Y-m-d H:i:s', $record->Duo_on)->format('Y-m-d');
            if($recordDuoOn > $dateNow){
                $data['OutstandingAmount'] = $record->Amount - $record->PaidAmount;
                $data['OverdueAmount'] = 0.0;
            }else{
                $data['OverdueAmount'] = $record->Amount - $record->PaidAmount;
                $data['OutstandingAmount'] = 0.0;
            }
            array_push($finalData,$data);
        }
        return $this->handleResponse( $finalData, 'Basic Report successfully generated!');

    }

    // assumption -> retrieve current year report
    public function MonthlyReport(){
        $monthTransactions = Transaction::whereBetween('created_at', [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear()])
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->created_at)->format('m');
            })
            ->toArray();

        if(count($monthTransactions) == 0){
            return $this->handleError("No transactions found");
        }
        $keys = array_keys($monthTransactions);
        $final = [];
        $year = Carbon::now()->format('Y');
        $it = 0;
        $dateNow = Carbon::now()->format('Y-m-d');
        foreach ($monthTransactions as $month){
            $data['month'] = $keys[$it];
            $it++;
            $data['year'] = $year;
            $paid = 0.0;
            $outstanding = 0.0;
            $overDue = 0.0;
            foreach ($month as $item){
                $recordDuoOn = Carbon::createFromFormat('Y-m-d H:i:s', $item['Duo_on'])->format('Y-m-d');
                $paid += $item['PaidAmount'];
                if($recordDuoOn > $dateNow){
                    $outstanding += $item['Amount'] - $item['PaidAmount'];
                }else{
                    $overDue += $item['Amount'] - $item['PaidAmount'];
                }
            }
            $data['Paid'] = $paid;
            $data['outstanding'] = $outstanding;
            $data['overdue'] = $overDue;

            array_push($final, $data);
        }
        return $this->handleResponse( $final, 'Monthly Report successfully generated!');
    }
}
