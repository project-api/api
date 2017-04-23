<?php

namespace App\Http\Controllers;

use Validator;
use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
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
          $created = strtotime($value['created_at']);
          $created = date('Y-m-d\TH:i:s.u\Z', $created);
          $cats_array['data'][$key]['created_at'] = $created;

          $updated = strtotime($value['created_at']);
          $updated = date('Y-m-d\TH:i:s.u\Z', $updated);
          $cats_array['data'][$key]['updated_at'] = $updated;
        }

        return response(array(
          'meta' => [
            'status' => 200,
            'total-pages' => round($cats->total()/$cats->perPage()),
            'per-page' => $cats->perPage(),
            'count' => $cats->count()
          ],
          'categories' => $cats_array['data'],
          'links' => [
            'self' => "http://web-api.dev/api/categories?page=".$cats->currentPage(),
            'first' => $cats->url(1),
            'prev' => $cats->previousPageUrl(),
            'next' => $cats->nextPageUrl(),
            'last' => "http://web-api.dev/api/categories?page=".$cats->lastPage()
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
        'name' => 'required|max:255',
        'quatity' => 'required|integer'
      ]);

      // if error
      if ($validator->fails()) {
       return response(array('error' => $validator));
      } else {
        // Store the category
       $cat = new Category;
       $cat->name = $request->name;
       $cat->quatity = $request->quatity;
       $cat->description = $request->description;
       $cat->save();
       return response(array(
         'status' => 201,
         'message' => 'Created'
       ));
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
        $cat_array = $cat->toArray();
        $created = strtotime($cat['created_at']);
        $updated = strtotime($cat['updated_at']);

        $created = date('Y-m-d\TH:i:s.u\Z', $created);
        $updated = date('Y-m-d\TH:i:s.u\Z', $updated);

        $cat_array['created_at'] = $created;
        $cat_array['updated_at'] = $updated;

        return response(array(
          'meta' => [
            'status' => 200,
            'created_at' => $created
          ],
          'category' => $cat_array,
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
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
