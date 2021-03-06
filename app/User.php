<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $primaryKey = 'id';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Name',
        'Email',
        'AuthProviderId',
        'Mobile',
        'DigestType',
        'RepresentativeId',
        'SenatorId'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token'
    ];

    protected $with = ['trackedBills'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bills() {
        $session = Session::current();
        return $this->belongsToMany(Bill::class, 'UserBill', 'UserId', 'BillId')
            ->with(["actions", "scheduledActions",])
            ->withPivot('ReceiveAlertEmail', 'ReceiveAlertSms')
            ->where('SessionId', '=', $session->Id)
            ->orderBy('Name');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trackedBills() {
        $session = Session::current();
        return $this->hasMany(UserBill::class, 'UserId', 'id')
            ->join('Bill', 'UserBill.BillId', '=', 'Bill.Id')
            ->where('SessionId', '=', $session->Id)
            ->select('UserBill.*');
    }

    public function track($param, $startTracking=true) {
        // handle tracking single or multiple bills
        // TODO make this mess less ugly and handle multiple bills better
        if(is_numeric($param)) {
            $id = $param;

            if($startTracking) {
                if(!$this->bills()->pluck('Bill.Id')->contains($id)) {
                    $this->bills()->attach($id);

                    if(!empty($this->Email)) {
                        $this->bills()->updateExistingPivot($id, ['ReceiveAlertEmail'=>1]);
                    }
                    if(!empty($this->Mobile)) {
                        $this->bills()->updateExistingPivot($id, ['ReceiveAlertSms'=>1]);
                    }
                }
            } else {
                $this->bills()->detach($id);
            }

            return [
                'BillId' => $id,
                'UserId' => $this->id,
                'isTracking' => $startTracking
            ];
        } elseif(is_string($param)) {
            $bill = Bill::where('Name',$param)->first();
            return $this->track($bill->Id);
        }
    }

    /*
    * representative and senator
    */
    public function representative() {
        return $this->hasOne(Legislator::class, 'Id', 'RepresentativeId');
    }
    public function senator() {
        return $this->hasOne(Legislator::class, 'Id', 'SenatorId');
    }
}
