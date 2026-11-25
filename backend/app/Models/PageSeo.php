<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSeo extends Model
{
    protected $table = 'page_seo';

    protected $fillable = [
        'page_key',
        'title_id',
        'title_en',
        'description_id',
        'description_en',
        'featured_image',
    ];
}
