<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request){
        $car = Car::count();
        $user = User::where('is_admin', '!=', 1)->count();
        $transaction = Transaction::count();
        return view('dashboard.index', compact('car', 'user', 'transaction'));
        }

}
