<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'parent_id',
        'description',
        'is_active',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }
    public function canBeDeleted(): bool
    {
        return !$this->children()->exists() && !$this->users()->exists();
    }
}

