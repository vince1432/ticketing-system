<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
    protected $fillable = [ 'name', 'filetype', 'size', 'url' ];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
