<?php

namespace App\Models\Page;

use App\Models\SeoMeta;
use App\Traits\Model\UseTrackUserActions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, UseTrackUserActions;

    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'content_type_id',
        'title',
        'slug',
        'template',
        'is_active',
        'published_at',
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

    public function pageContents()
    {
        return $this->hasMany(PageContent::class);
    }

    public function pageMetas()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }
}
