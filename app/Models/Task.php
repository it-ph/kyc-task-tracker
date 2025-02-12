<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'tasks';
    protected $guarded = [];
    protected $dates = ['shift_date', 'date_received', 'start_date', 'end_date', 'created_at', 'updated_at', 'deleted_at'];

    public function scopeOMPermission($query)
    {
        return $query->where('cluster_id',auth()->user()->cluster_id);
    }

    public function scopeTLPermission($query)
    {
        return $query->where('tl_id',auth()->user()->id)
            ->where('cluster_id',auth()->user()->cluster_id)
            ->orwhere('agent_id',auth()->user()->id);
    }

    public function scopeAccountantPermission($query)
    {
        return $query->where('agent_id',auth()->user()->id);
    }

    public function thecluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id')->withTrashed();
    }

    public function theclient()
    {
        return $this->belongsTo(Client::class, 'client_id')->withTrashed();
    }

    public function theagent()
    {
        return $this->belongsTo(User::class, 'agent_id', 'id')->withTrashed();
    }

    public function thepermission()
    {
        return $this->hasOne(Permission::class, 'user_id', 'agent_id')->withTrashed();
    }

    public function theclientactivity()
    {
        return $this->belongsTo(ClientActivity::class, 'client_activity_id')->withTrashed();
    }

    public function thecreatedby()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function thetasklogs()
    {
        return $this->hasMany(TaskLog::class, 'task_id');
    }
}
