<?php

namespace App;

use App\Scopes\CurrentSessionScope;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Committee
 * @package App
 */
class Committee extends Model
{
    /**
     * @var string
     */
    protected $table = "Committee";
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
        'Name',
        'Link',
        'Chamber'
    ];

    /**
     * @var array
     */
    protected $appends = [
        'id',
        'Chamber',
        'IgaSiteLink',
    ];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bills() {
        return $this->belongsToMany(Bill::class, 'BillCommittee', 'CommitteeId', 'BillId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session() {
        return $this->belongsTo(Session::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members() {
        return $this->belongsToMany(Legislator::class, 'LegislatorCommittee', 'CommitteeId', 'LegislatorId');
    }

    /**
     * @return mixed
     */
    public function getIdAttribute() {
        return $this->attributes['Id'];
    }

    /**
     * @return mixed
     */
    public function getChamberAttribute() {
        $types = config('enums.Chamber');
        return $types[$this->attributes['Chamber']];
    }

    public function getIgaSiteLinkAttribute() {
        // derive committee path from API Link attribute
        $committee = str_replace("/committee_", "/", $this->Link);

        return env('IGA_SITE_ROOT', 'http://iga.in.gov')."/legislative$committee";
    }
}
