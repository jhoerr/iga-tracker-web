<?php

namespace App\Http\Controllers;

use App\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function start() {
        $user = Auth::user();
        return view('start', compact('user'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function myBills() {
        $user = Auth::user();
        return view('default', compact('user'));
    }

    /**
     *
     */
    public function allBills() {
        $user = Auth::user();
        return view('all-bills', compact('user'));
    }

    public function trackBill(Request $request, $id) {
        $bill = Bill::findOrFail($id);
        $user = Auth::user();

        if(!$user->bills->pluck('Id')->contains($bill->Id)) {
            $user->bills()->attach($bill->Id);
        } else {
            $user->bills()->detach($bill->Id);
        }
        $user->save();

        return back();
    }

    public function account(Request $request) {
        if(!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        return view('account', compact('user'));
    }

    public function saveAccount(Request $request) {
        $this->validate($request, [
                'Name' => 'required',
                'Email' => 'required',
            ]);

        $user = Auth::user();
        $user->Name = $request->input('Name');
        $user->Email = $request->input('Email');
        $user->save();
        return redirect('/account');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}
