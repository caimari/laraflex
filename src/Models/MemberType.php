<?php

namespace Caimari\LaraFlex\Models;

use Illuminate\Database\Eloquent\Model;

class MemberType extends Model
{
    protected $table = 'member_types';

    protected $fillable = ['name', 'status'];

    // Define las relaciones
    public function members()
    {
        return $this->belongsToMany(Member::class, 'members_member_type', 'member_type_id', 'member_id');
    }
}
