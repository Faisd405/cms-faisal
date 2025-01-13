<?php

namespace App\Models\Component;

use App\Models\ContentType\ContentTypeField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'component_id',
        'content_type_field_id',
        'localization_id',
        'value',
        'order',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    protected $appends = ['provide'];

    protected $filepath = 'uploads/component';

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    public function contentTypeField()
    {
        return $this->belongsTo(ContentTypeField::class);
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
