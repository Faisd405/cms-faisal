<?php

namespace App\Models\Content;

use App\Models\ContentType\ContentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentPost extends Model
{
    use HasFactory;

    protected $table = 'content_posts';

    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'content_section_id',
        'content_category_id',
        'title',
        'slug',
        'order',
    ];

    public function section()
    {
        return $this->belongsTo(ContentSection::class, 'content_section_id');
    }

    public function category()
    {
        return $this->belongsTo(ContentCategory::class, 'content_category_id');
    }

    public function contentType()
    {
        return $this->hasOneThrough(ContentType::class, ContentSection::class, 'id', 'id', 'content_section_id', 'post_content_type_id');
    }

    public function contentValue()
    {
        return $this->hasMany(ContentPostContent::class, 'content_post_id');
    }
}
