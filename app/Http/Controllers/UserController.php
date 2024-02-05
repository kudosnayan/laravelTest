<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function showProfile($id)
    {
        // $user = Redis::get('user:profile:' . $id);

        // return $user;
        // $value = Cache::store('file')->get('foo'); 

        $value = Cache::remember('users', $id, function () {
            return DB::table('users')->get();
        });
        # Create a random srring of natural number

    }

    public function getUserDetails($userId)
    {
        // Attempt to retrieve user details from cache
        $userDetails = Cache::remember("user:{$userId}", 60, function () use ($userId) {
            // Cache miss, fetch user details from the database
            return User::find($userId);
        });

        return response()->json(['user_details' => $userDetails]);
    }

    public function store(Request $request)
    {
        // Validate and store user data

        // Generate spreadsheet
        // $data= [
        //     'name'=>$request->name,
        //     'email'=>$request->email,
        //     'password'=>'12345678',
        // ];
        // $user = User::create($data);
        $user = User::find(2);
        return Excel::download(new UserExport($user), 'user_sheet.xlsx');
    }
    public function createForm()
    {
        return view('userForm');
    }
}
