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
        'validation',
        'placeholder',
        'default_value',
        'order',
        'is_required',
        'is_unique',
        'is_searchable',
    ];

    protected $casts = [
        'validation' => 'array',
        'is_required' => 'boolean',
        'is_unique' => 'boolean',
        'is_searchable' => 'boolean',
    ];
}
