<?php

namespace App\Models\Content;

use App\Models\ContentType\ContentTypeField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentPostContent extends Model
{
    use HasFactory;

    protected $table = 'content_post_contents';

    protected $fillable = [
        'content_post_id',
        'content_type_field_id',
        'localization_id',
        'value',
        'order'
    ];

    public function post()
    {
        return $this->belongsTo(ContentPost::class, 'content_post_id');
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
