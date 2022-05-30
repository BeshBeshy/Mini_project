<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends BaseController
{
    public function CreateSubCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'Name' => 'required',
            'category_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->handleError($validator->errors());
        }

        $input = $request->all();
        $category = Category::where('id', $input['category_id'])->first();
        if($category == null){
            return $this->handleError("Category not found");
        }

        $subCategory = SubCategory::create($input);
        return $this->handleResponse($subCategory, 'SubCategory successfully created!');

    }
}
