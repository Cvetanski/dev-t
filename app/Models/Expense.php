<?php

namespace App\Models;

use App\Categories\Category;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expenses';

    protected $fillable =[
        'amount',
        'user_id',
        'category_id',
        'description',
        'category',
        'created_ad',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function setAmount(string $amount)
    {
        return $this->setAttribute('amount',$amount);
    }

    public function setCategory(string $category)
    {
        return $this->setAttribute('category',$category);
    }


    public function setUser(User $user)
    {
        $this->user()->associate($user);
    }

    public function setCategoryId(Category $categoryId)
    {
        $this->category()->associate($categoryId);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'Amount' => $this->amount,
            'Description' => $this->description,
            'Category'=>$this->category,
            'Category_id'=>$this->category()->get(['id','name'])
        ];
    }

}
