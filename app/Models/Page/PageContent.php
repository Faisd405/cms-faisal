<?php

namespace App\Models\Page;

use App\Models\ContentType\ContentTypeField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'content_type_field_id',
        'localization_id',
        'value',
        'order',
    ];

    protected $casts = [
        'value' => 'array',
    ];

    protected $appends = ['provide'];

    protected $filepath = 'uploads/page';

    public function page()
    {
        return $this->belongsTo(Page::class);
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
