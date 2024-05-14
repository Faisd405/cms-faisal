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

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function contentTypeField()
    {
        return $this->belongsTo(ContentTypeField::class);
    }
}
