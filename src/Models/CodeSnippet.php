<?php

namespace Caimari\LaraFlex\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CodeSnippet extends Model
{
    use HasFactory;

    protected $fillable = ['name','version_name','status','description','content'];

    public static function boot() 
    {
        parent::boot();

        static::deleting(function($codeSnippet) {
            $codeSnippet->versions()->forceDelete();
        });
    }
    
    public function versions()
    {
        return $this->morphMany(Version::class, 'versionable');
    }
}