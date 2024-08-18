<?php

namespace App\Models\Collection;

use App\Models\ContentType\ContentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_content_type_id',
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

    public function posts()
    {
        return $this->hasMany(CollectionPost::class);
    }
}
