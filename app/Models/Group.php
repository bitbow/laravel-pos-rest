<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Group extends Model
{
    protected $fillable = [
        'name',
        'image',
        'status'        
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function getImageUrl()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return asset('images/img-placeholder.jpg');
    }

}
