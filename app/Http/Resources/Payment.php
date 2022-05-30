<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Payment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'Transaction' => [
                'id' => $this->transaction->id
            ],
            'Amount' => $this->Amount,
            'Paid_at' => $this->Paid_on,
            'details' => $this->Details,
        ];
    }
}
