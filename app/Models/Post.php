<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category () {
        return $this->belongsTo(Category::class);
    }

    public function tags () {
        return $this->belongsToMany(Tag::class);
    }

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function scopeActive ($query) {
        return $query->where('active', 1);
    }
    //? untuk local scoping dalam pemanggilannya tidak perlu mengetikkan prefix "scope"nya
    //? baca di https://laravel.com/docs/8.x/eloquent#local-scopes

    public function withAllRelation () {
        return self::with('category', 'tags', 'user');
    }

    public function getPostWithFilter ($filter) {
        $post = $this->withAllRelation()->where('title', 'LIKE' ,'%'.$filter.'%')
                    ->orWhere('body', 'LIKE' ,'%'.$filter.'%')
                    ->orWhereHas('category', function($item) use ($filter){
                        $item->where('name', 'LIKE' , '%'.$filter.'%');
                    })
                    ->orWhereHas('tags', function($item) use ($filter){
                        $item->where('name', 'LIKE' , '%'.$filter.'%');
                    })->paginate(5);

        return $post;
    }

    public function getPostByCategory ($category) {
        $post = $this->withAllRelation()
                    ->whereHas('category', function ($q) use ($category){
                        $q->where('name', 'LIKE', '%'.$category.'%');
                    })->paginate(5);

        return $post;
    }

    public function getPostByTag ($tag) {
        $post = $this->withAllRelation()
                    ->whereHas('tags', function ($q) use ($tag){
                        $q->where('name', 'LIKE', '%'.$tag.'%');
                    })->paginate(5);

        return $post;
    }
}
