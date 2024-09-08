<?php

namespace App\Models\Collection;

use App\Models\ContentType\ContentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionPost extends Model
{
    use HasFactory;

    protected $table = 'collection_posts';

    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'section_id',
        'category_id',
        'title',
        'slug',
        'order',
    ];

    public function section()
    {
        return $this->belongsTo(CollectionSection::class, 'section_id');
    }

    public function category()
    {
        return $this->belongsTo(CollectionCategory::class, 'category_id');
    }

    public function contentType()
    {
        return $this->hasOneThrough(ContentType::class, CollectionSection::class, 'id', 'id', 'section_id', 'post_content_type_id');
    }

    public function contentValue()
    {
        return $this->hasMany(CollectionPostContent::class, 'post_id');
    }

    public function scopeWhereContentLocalization($query, $localizationId)
    {
        return $query->with(['contentValue' => function ($query) use ($localizationId) {
            $query->where('localization_id', $localizationId);
        }]);
    }

    public function getValueAttribute()
    {
        $contentType = $this->contentType;
        $contentValues = $this->contentValue;

        if (!$contentType) {
            return [];
        }

        $data = [];

        foreach ($contentType->fields as $field) {
            $contentValue = $contentValues->where('content_type_field_id', $field->id)->first();

            if ($contentValue) {
                $data[$field->name] = $contentValue->value;
            } else {
                $data[$field->name] = null;
            }
        }

        return $data;
    }
}
