<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'roles';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function thecreatedby()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function theroleactivities()
    {
        return $this->hasMany(RoleActivity::class, 'role_id');
    }
}
