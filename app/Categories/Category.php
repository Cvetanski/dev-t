<?php

namespace App\Categories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name'
    ];

    public function setName(string $name)
    {
        return $this->setAttribute('name',$name);
    }

    public function  getName():string
    {
        return $this->getAttribute('name');
    }

    public function expense()
    {
        return $this->hasOne(Expense::class,'category_id','id');
    }

}
