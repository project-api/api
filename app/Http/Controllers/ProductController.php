<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
        //$this->middleware('jwt.refresh');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // GET
      $products = Product::orderBy('id', 'desc')->paginate(5);
      $products_array = $products->toArray();
      foreach ($products_array['data'] as $key => $value) {
        $created_at = strtotime($value['created_at']);
        $products_array['data'][$key]['created_at'] = date('Y-m-d\TH:i:s\Z',$created_at);

        if($value['updated_at'] != null) {
          $updated_at = strtotime($value['updated_at']);
          $products_array['data'][$key]['updated_at'] = date('Y-m-d\TH:i:s\Z', $updated_at);
        } else {
          $products_array['data'][$key]['updated_at'] = null;
        }
      }

      return response(array(
        'meta' => [
          'status' => 200,
          'total' => $products->total(),
          'total-pages' => round($products->total()/$products->perPage()),
          'per-page' => $products->perPage(),
          'count' => $products->count()
        ],
        'products' => $products_array['data'],
        'links' => [
          'self' => "http://web-api.dev/api/products?page=".$products->currentPage(),
          'first' => $products->url(1),
          'prev' => $products->previousPageUrl(),
          'next' => $products->nextPageUrl(),
          'last' => "http://web-api.dev/api/products?page=".$products->lastPage()
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
        $rules = array(
          'name' => 'required|max:255|unique:products',
          'quatity' => 'required|integer|min:1',
          'price' => 'required|integer|min:1',
          'cat_id' => 'required'
        );
        $validator = Validator::make($request->all(),$rules);

        // if error Validate
        if($validator->fails()) {
          return response()->json([
            'error' => [
              'title' => 'Validation error',
              'detail' => $validator->errors()
            ]], 400);
        } else {
          // Store
          Product::create($request->all());
          return response()->json(array(
            'status' => 201,
            'message' => 'Created'
          ), 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      //GET
      $product = Product::findOrFail($id);
      $product_array = $product->toArray();

      $product_array['created_at'] = date('Y-m-d\TH:i:s\Z', strtotime($product['created_at']));
      $product_array['updated_at'] = date('Y-m-d\TH:i:s\Z', strtotime($product['updated_at']));

      $cat_created = date('Y-m-d\TH:i:s\Z', strtotime($product->category['created_at']));

      $cat_updated = date('Y-m-d\TH:i:s\Z', strtotime($product->category['updated_at']));

      return response(array(
        'meta' => [
          'status' => 200,
        ],
        'product' => $product_array,
        'relationships' => [
          'category' => [
            'id' => $product->category['id'],
            'name' => $product->category['name'],
            'description' => $product->category['description'],
            'created_at' => $cat_created,
            'updated_at' => $cat_updated,
            'href' => "http://web-api.dev/api/categories/".$product->category['id']
        ]],
        'links' => [
          'self' => "http://web-api.dev/api/products/".$product_array['id']
          ]
      ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate
        $rules = array(
          'name' => 'required|max:255',
          'quatity' => 'required|integer',
          'price' => 'required|integer',
          'cat_id' => 'required'
        );
        $validator = Validator::make($request->all(),$rules);
        // if error Validate
        if($validator->fails()) {
          return response()->json([
            'error' => [
              'title' => 'Validation error',
              'detail' => $validator->errors()
            ]], 400);
        } else {
          // Update the Product
          $pro = Product::find($id);
          $pro->name = $request->name;
          $pro->quatity = $request->quatity;
          $pro->price = $request->price;
          $pro->cat_id = $request->cat_id;
          if(isset($request->description)) {
            $pro->description = $request->description;
          }
          $pro->updated_at = strtotime(date('Y-m-d H:i:s'));
          $pro->save();
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
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete
        try {
          $pro = Product::find($id);
          $pro->delete();
          return response(array(
            'status' => 200,
            'message' => 'Deleted'
          ));
        } catch (Exception $e) {
          return response()->json($e->getMessage(), 400);
        }
    }
}
