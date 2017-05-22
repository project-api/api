<?php

namespace App\Http\Controllers;

use Validator;
use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class CategoryController extends Controller
{
    public function __construct() {
      $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //GET
      $cats = Category::paginate(5);
      $cats_array = $cats->toArray();
      foreach ($cats_array['data'] as $key => $value) {
        $created_at = strtotime($value['created_at']);
        $cats_array['data'][$key]['created_at'] = date('Y-m-d\TH:i:s\Z', $created_at);

        if($value['updated_at'] != null) {
          $updated_at = strtotime($value['updated_at']);
          $cats_array['data'][$key]['updated_at'] = date('Y-m-d\TH:i:s\Z', $updated_at);
        } else {
          $cats_array['data'][$key]['updated_at'] = null;
        }
      }

      $token = JWTAuth::getToken();

      return response(array(
        'meta' => [
          'status' => 200,
          'total' => $cats->total(),
          'total-pages' => round($cats->total()/$cats->perPage()),
          'per-page' => $cats->perPage(),
          'count' => $cats->count()
        ],
        'categories' => $cats_array['data'],
        'links' => [
          'self' => "http://web-api.dev/api/categories?page=".$cats->currentPage()."&token=".$token,
          'first' => $cats->url(1)."&token=".$token,
          'prev' => $cats->previousPageUrl()."&token=".$token,
          'next' => $cats->nextPageUrl()."&token=".$token,
          'last' => "http://web-api.dev/api/categories?page=".$cats->lastPage()."&token=".$token,
          ]
      ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // Validate
      $validator = Validator::make($request->all(), [
        'name' => 'required|max:255|unique:categories',
      ]);

      // if error
      if ($validator->fails()) {
        return response()->json([
          'error' => [
            'title' => 'Validation error',
            'detail' => $validator->errors()
          ]], 400);
      } else {
        // Store the category
       Category::create($request->all());
       return response()->json(array(
         'status' => 201,
         'message' => 'Created'
       ), 201);
      }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //GET
        $cat = Category::findOrFail($id);
        $products = $cat->products;
        $pros = [];
        if(count($products) > 0) {
          foreach ($products as $key => $value) {
            $pros[$key]['id'] = $value->toArray()['id'];

            $pros[$key]['name'] = $value->toArray()['name'];

            $pros[$key]['price'] = $value->toArray()['price'];

            // $pros[$key]['quatity'] = $value->toArray()['quatity'];

            $cre = $value->toArray()['created_at'];
            $pros[$key]['created_at'] = date('Y-m-d\TH:i:s\Z', strtotime($cre));

            $upd = $value->toArray()['updated_at'];
            if($upd != null) {
              $pros[$key]['updated_at'] = date('Y-m-d\TH:i:s\Z', strtotime($upd));
            } else {
              $pros[$key]['updated_at'] = null;
            }

            $pros[$key]['href'] = "http://web-api.dev/api/products/".$pros[$key]['id'];
          }
        }

        $cat_array = $cat->toArray();
        $created = strtotime($cat['created_at']);
        $updated = strtotime($cat['updated_at']);

        $created = date('Y-m-d\TH:i:s\Z', $created);
        if($updated != null) {
          $updated = date('Y-m-d\TH:i:s\Z', $updated);
        } else {
          $updated = null;
        }

        return response(array(
          'meta' => [
            'status' => 200,
          ],
          'category' => [
            'id' => $cat_array['id'],
            'name' => $cat_array['name'],
            'description' => $cat_array['description'],
            'created_at' => $created,
            'updated_at' => $updated
            ],
          'include' => [
            'type' => 'product',
            'data' => $pros
            ],
          'links' => [
            'self' => "http://web-api.dev/api/categories/".$cat_array['id']
            ]
        ));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      // Validate
      $validator = Validator::make($request->all(), [
        'name' => 'required|max:255',
      ]);

      // if error
      if ($validator->fails()) {
        return response()->json([
          'error' => [
            'title' => 'Validation error',
            'detail' => $validator->errors()
          ]], 400);
      } else {
        // Update the category
       $cat = Category::find($id);
       $cat->name = $request->name;
       if(isset($request->description)) {
         $cat->description = $request->description;
       }
       $cat->updated_at = strtotime(date('Y-m-d H:i:s'));
       $cat->save();
       return response()->json(array(
         'status' => 200,
         'message' => 'OK',
         'updated_at' => date('Y-m-d\TH:i:s.u\Z')
       ), 200);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete
        $cat = Category::find($id);
        $cat->delete();

        return response()->json(array(
          'status' => 200,
          'message' => 'Deleted'
        ), 200);
    }
}
