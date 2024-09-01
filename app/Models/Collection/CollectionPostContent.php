<?php

namespace App\Models\Collection;

use App\Models\ContentType\ContentTypeField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionPostContent extends Model
{
    use HasFactory;

    protected $table = 'collection_post_contents';

    protected $fillable = [
        'post_id',
        'content_type_field_id',
        'localization_id',
        'value',
        'order'
    ];

    public function post()
    {
        return $this->belongsTo(CollectionPost::class, 'post_id');
    }

    public function contentTypeField()
    {
        return $this->belongsTo(ContentTypeField::class, 'content_type_field_id');
    }

    public function getProvideAttribute()
    {
        $provide = [];

        if ($this->contentTypeField->type === 'file' || $this->contentTypeField->type === 'image') {
            $provide['filepath'] = $this->value ? asset('/storage/' . $this->filepath . '/' . $this->value) : null;
        }

        return $provide;
    }
}
