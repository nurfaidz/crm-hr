<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Announcement extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $primaryKey = 'announcement_id';
    protected $guarded = [];
    protected $with = ['user','media'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static $rules = [
        'announcement_title' => 'required',
        'announcement_content' => 'required',
        'announcement_image' => 'mimes:jpg,jpeg,png'
    ];
}
