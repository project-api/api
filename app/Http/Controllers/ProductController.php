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
        //
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

      return response(array(
        'meta' => [
          'status' => 200,
        ],
        'product' => $product_array,
        'relationships' => [
          'category' => [
            'id' => $product->category['id'],
            'name' => $product->category['name'],
            'created_at' => $cat_created,
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
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
