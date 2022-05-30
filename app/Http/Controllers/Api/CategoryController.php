<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    public function CreateCategory(Request $request){

        $validator = Validator::make($request->all(), [
            'Name' => 'required',
        ]);

        if($validator->fails()){
            return $this->handleError($validator->errors());
        }

        $input = $request->all();
        $category = Category::create($input);
        return $this->handleResponse($category, 'Category successfully created!');
    }
}
