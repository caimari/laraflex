<?php

namespace Caimari\LaraFlex\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use HasFactory;

    protected $fillable = ['name','versionable_type', 'versionable_id', 'data'];
    
    protected $casts = [
        'data' => 'array',
    ];
}
