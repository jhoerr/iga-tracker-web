<?php

namespace App\Http\Controllers;

use App\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Class BillApiController
 * @package App\Http\Controllers
 */
class BillApiController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all(Request $request)
    {
        return response()->json([
            'bills' => Bill::all(),
            'user' => Auth::user()
        ]);
    }

    public function initialChunk() {
        if(Cache::has('bills--initial')) {
            $bills = Cache::get('bills--initial');
        } else {
            $bills = Bill::take(10)->get();
            Cache::put('bills--initial', $bills);
        }

        return response()->json([
            'bills' => $bills,
            'user' => Auth::user()
        ]);
    }

    public function remainingChunk() {
        if(Cache::has('billsRemaining')) {
            $bills = Cache::get('billsRemaining');
        } else {
            $bills = Bill::skip(10)->take(5000)->get();
            Cache::put('billsRemaining', $bills, 600);
        }

        return response()->json([
            'bills' => $bills,
        ]);
    }

    /**
     * @return mixed
     */
    public function mine() {
        $user = Auth::user();
        return response()->json([
            'bills' => $user->bills,
            'user' => $user
        ]);
    }
}
