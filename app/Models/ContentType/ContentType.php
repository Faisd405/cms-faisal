<?php

namespace App\Models\ContentType;

use App\Base\BaseModel;
use App\Traits\Model\UseTrackUserActions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentType extends BaseModel
{
    use HasFactory, UseTrackUserActions;

    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'name',
        'slug',
        'description',
        'type',
    ];

    public static function boot()
    {
        parent::boot();

        self::bootUseTrackUserActions();
    }

    public function fields()
    {
        return $this->hasMany(ContentTypeField::class)->orderBy('order');
    }
}
