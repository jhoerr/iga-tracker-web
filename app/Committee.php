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

        /* stubbed out relationships */
        'Members'
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

    /*
     * stubbed out Members relationship
     * TODO many-to-many relationship to Legislator model
     */
    public function getMembersAttribute() {
        return collect([
            [
                'Name'=>'Jane Doe',
                'Slug'=>'jane-doe',
                'Chamber'=>1,
                'District'=>'IN09'
            ],
            [
                'Name'=>'Rick Deckard',
                'Slug'=>'rick-deckard',
                'Chamber'=>1,
                'District'=>'IN05'
            ]
        ]);
    }
}
