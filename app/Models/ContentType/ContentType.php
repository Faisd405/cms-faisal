<?php

namespace App\Models\ContentType;

use App\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentType extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function fields()
    {
        return $this->hasMany(ContentTypeField::class)->orderBy('order');
    }
}
