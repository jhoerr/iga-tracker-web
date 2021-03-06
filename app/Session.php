<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Session
 * @package App
 */
class Session extends Model
{
    /**
     * @var string
     */
    protected $table = "Session";
    /**
     * @var string
     */
    protected $primaryKey = "Id";
    
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'Name', 'Link',
    ];

    /**
     * @var array
     */
    protected $appends = ['id'];

    /**
     * @param $builder
     * @return mixed
     */
    public function scopeCurrent($builder)
    {
        return $builder->where('Active', true)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bills()
    {
        return $this->hasMany(Bill::class, 'SessionId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function committees()
    {
        return $this->hasMany(Committee::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * @return mixed
     */
    public function getIdAttribute() {
        return $this->attributes['Id'];
    }
}
