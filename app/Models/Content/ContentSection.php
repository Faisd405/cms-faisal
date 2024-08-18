<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_content_type_id',
        'section_content_type_id',
        'title',
        'slug',
        'description',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function postContentType()
    {
        return $this->belongsTo(ContentType::class, 'post_content_type_id');
    }

    public function sectionContentType()
    {
        return $this->belongsTo(ContentType::class, 'section_content_type_id');
    }

    public function posts()
    {
        return $this->hasMany(ContentPost::class);
    }
}
