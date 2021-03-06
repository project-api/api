<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $table = 'categories';
  protected $primaryKey = 'id';
  protected $fillable = array(
    'name',
    'description',
    'created_at',
    'updated_at'
  );
  public function products()
  {
    return $this->hasMany('App\Product', 'cat_id');
  }
}
