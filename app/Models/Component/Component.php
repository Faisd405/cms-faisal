<?php

namespace App\Models\Component;

use App\Models\ContentType\ContentType;
use App\Models\SeoMeta;
use App\Traits\Model\UseTrackUserActions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory, UseTrackUserActions;

    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'content_type_id',
        'title',
        'slug',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        self::bootUseTrackUserActions();
    }

    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
    }

    public function contentValue()
    {
        return $this->hasMany(ComponentContent::class);
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
