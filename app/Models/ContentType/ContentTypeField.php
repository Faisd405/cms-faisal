<?php

namespace App\Models\ContentType;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentTypeField extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_type_id',
        'name',
        'label',
        'type',
        'options',
        'validation',
        'placeholder',
        'default_value',
        'order',
        'is_localizable',
        'is_required',
        'is_unique',
        'is_searchable',
    ];

    protected $casts = [
        'validation' => 'array',
        'is_localizable' => 'boolean',
        'is_required' => 'boolean',
        'is_unique' => 'boolean',
        'is_searchable' => 'boolean',
        'options' => 'array',
    ];

    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
    }
}
