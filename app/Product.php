<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
  protected $table = 'products';
  protected $primaryKey = 'id';
  protected $fillable = array(
    'name',
    'price',
    'quatity',
    'cat_id',
    'description',
    'created_at',
    'updated_at'
  );
  public function category()
  {
  	return $this->belongsTo('App\Category', 'cat_id');
  }
}
