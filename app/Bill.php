<?php

namespace App;

use App\Scopes\CurrentSessionScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Bill
 * @package App
 */
class Bill extends Model
{
    /**
     * @var string
     */
    protected $table = "Bill";
    /**
     * @var string
     */
    protected $primaryKey = 'Id';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'Name','Link','Title', 'Description','Authors', 'Chamber', 'ActionType'
    ];

    /**
     * @var array
     */
    protected $appends = [
        'id',
        'Chamber',
        'Name',
        'IgaSiteLink'
    ];

    /**
     * @var array
     */
    protected $with = [
        "subjects",
        "committees"
    ];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        //static::addGlobalScope(new CurrentSessionScope);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany(User::class, 'UserBill', 'BillId', 'UserId')->withPivot('ReceiveAlertEmail', 'ReceiveAlertSms');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function committees() {
        return $this->belongsToMany(Committee::class, 'BillCommittee', 'BillId', 'CommitteeId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subjects() {
        return $this->belongsToMany(Subject::class, 'BillSubject', 'BillId', 'SubjectId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session() {
        return $this->belongsTo(Session::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actions() {
        return $this->hasMany(Action::class, 'BillId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scheduledActions() {
        return $this->hasMany(ScheduledAction::class, 'BillId');
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

    public function getNameAttribute() {
        // names are stored without a space after their SB/HB prefix, and with leading zeroes on bill numbers
        // e.g. SB0025, HB0189

        $name = $this->attributes['Name'];
        // get the chamber prefix
        $chamber = substr($name, 0, 2);
        $number = intval(substr($name, 2)); // casting to an intval removes leading zeroes

        return "$chamber $number";
    }

    // wow this is complicated
    public function getIgaSiteLinkAttribute() {
        $year = $this->session()->first() ? $this->session()->first()->Name : date('Y');
        $legislationType = 'bills';
        $chamber = 'lobby';
        // use title to determine bill/resolution type
        $name = $this->attributes['Name'];
        if(substr($name, 0, 2) == "HB") {
            $chamber = 'house';
        }
        if(substr($name, 0, 2) == "SB") {
            $chamber = 'senate';
        }
        if(substr($name, 0, 2) == "HR") {
            $legislationType = 'resolutions';
            $chamber = 'house/simple';
        }
        if(substr($name, 0, 2) == "HC") {
            $legislationType = 'resolutions';
            $chamber = 'house/concurrent';
        }
        if(substr($name, 0, 2) == "HJ") {
            $legislationType = 'resolutions';
            $chamber = 'house/joint';
        }
        if(substr($name, 0, 2) == "SR") {
            $legislationType = 'resolutions';
            $chamber = 'senate/simple';
        }
        if(substr($name, 0, 2) == "SC") {
            $legislationType = 'resolutions';
            $chamber = 'senate/concurrent';
        }
        if(substr($name, 0, 2) == "SJ") {
            $legislationType = 'resolutions';
            $chamber = 'senate/joint';
        }

        $billNumber = intval(substr($name, 2));

        return env('IGA_SITE_ROOT', 'http://iga.in.gov')."/legislative/$year/$legislationType/$chamber/$billNumber";
    }

    public function toRowArray() {
        $subjects = implode(", ", $this->subjects->map(function($s) {return $s->Name;})->toArray());

        return [
            $this->Name,
            $this->Title,
            $this->Description,
            $this->Authors,
            $this->Chamber,
            $subjects
        ];
    }
}
