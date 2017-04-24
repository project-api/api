<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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
        $created = strtotime($value['created_at']);
        $created = date('Y-m-d\TH:i:s.u\Z', $created);
        $products_array['data'][$key]['created_at'] = $created;

        $updated = strtotime($value['updated_at']);
        $updated = date('Y-m-d\TH:i:s.u\Z', $updated);
        $products_array['data'][$key]['updated_at'] = $updated;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
          'quatity' => 'required|integer',
          'price' => 'required|integer',
          'cat_id' => 'required'
        );
        $validator = Validator::make($request->all(),$rules);
        // if error Validate
        if($validator->fails()) {
          return response(array(
            'error' => $validator,
            'input' => $request,
          ));
        } else {
          // Store
          Product::create($request->all());
          return response(array(
            'status' => 201,
            'message' => 'Created'
          ));
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
      $created = strtotime($product['created_at']);
      $updated = strtotime($product['updated_at']);

      $created = date('Y-m-d\TH:i:s.u\Z', $created);
      $updated = date('Y-m-d\TH:i:s.u\Z', $updated);

      $product_array['created_at'] = $created;
      $product_array['updated_at'] = $updated;

      $cat_created = strtotime($product->category['created_at']);
      $cat_created = date('Y-m-d\TH:i:s.u\Z', $cat_created);

      $cat_updated = strtotime($product->category['updated_at']);
      $cat_updated = date('Y-m-d\TH:i:s.u\Z', $cat_updated);

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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
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
          return response(array(
            'error' => $validator,
            'input' => $request,
          ));
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
          return response(array(
            'status' => 201,
            'message' => 'Created',
            'updated_at' => date('Y-m-d\TH:i:s.u\Z')
          ));
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
        $pro = Product::find($id);
        $pro->delete();
        return response(array(
          'status' => 200,
          'message' => 'Deleted'
        ));
    }
}
