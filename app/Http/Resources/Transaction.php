<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $subCategory = null;
        if($this->sub_category_id != null){
            $subCategory = $this->subCategory->Name;
        }
        return [
            'id' => $this->id,
            'payer' => [
                'name' => $this->payer->name
            ],
            'category' => [
                'name' => $this->category->Name
            ],
            'sub_category' => [
                'name' => $subCategory
            ],
            'Amount' => $this->Amount,
            'status' => [
                'name' => $this->status->Name
            ],
            'Duo_on' => $this->Duo_on,
        ];
    }
}
