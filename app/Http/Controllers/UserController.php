<?php

namespace App\Http\Controllers;

use App\Mail\Activation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $page = intval($page);
        $perPage = $request->get('perPage', 10);
        $perPage = intval($perPage);
        $total = User::where('active', 1)->count();
        $sortBy = $request->get('sortBy');
        $dir = $request->get('dir');
        if ($perPage == -1) {
            $users = User::where('active', 1);
        } else {
            $users = User::where('active', 1)->skip(($page - 1) * $perPage)->take($perPage);
        }
        if ($sortBy && $dir) {
            $users = $users->orderBy($sortBy, $dir);
        }
        $users = $users->get(['id', 'name', 'email', 'isCanContact', 'experience_year', 'experience_history', 'phone', 'group', 'avatar', 'date_of_birth', 'active', 'address', 'flag', 'country', 'zipcode', 'created_at']);
        $users->toArray();
        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'page' => $page,
                'perPage' => $perPage,
                'total' => $total,
                'users' => $users,
            ],
        ]);
    }

    public function newUsers(Request $request)
    {
        $page = $request->get('page', 1);
        $page = intval($page);
        $perPage = $request->get('perPage', 10);
        $perPage = intval($perPage);
        $total = User::where('active', 0)->count();
        if ($perPage == -1) {
            $users = User::where('active', 0);
        } else {
            $users = User::where('active', 0)->skip(($page - 1) * $perPage)->take($perPage)
                ->get(['id', 'name', 'email', 'phone', 'avatar', 'date_of_birth', 'active', 'address', 'flag', 'country', 'zipcode', 'created_at']);
        }

        $users->toArray();
        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'page' => $page,
                'perPage' => $perPage,
                'total' => $total,
                'users' => $users,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id', $id)->get(['id', 'name', 'email', 'isCanContact', 'experience_year', 'experience_history', 'phone', 'avatar', 'group', 'active', 'date_of_birth', 'country', 'flag', 'address', 'zipcode', 'created_at'])->first();
        $info = DB::select("SELECT
                            IFNULL( providers.providers, 0 ) AS providers,
                            IFNULL( copiers.copiers, 0 ) AS copiers,
                            IFNULL( followers.followers, 0 ) AS followers
                            FROM
                            (
                            SELECT
                                COUNT( 1 ) AS providers
                            FROM
                                tbl_account
                                INNER JOIN tbl_user_account ON tbl_user_account.account_id = tbl_account.id
                            WHERE
                                tbl_user_account.user_id = $id
                                AND tbl_account.`status` = 'PROVIDE'
                            ) AS providers,
                            (
                            SELECT
                                COUNT( 1 ) AS copiers
                            FROM
                                `tbl_copy`
                                INNER JOIN tbl_account ON tbl_account.id = tbl_copy.slave_id
                                INNER JOIN tbl_user_account ON tbl_user_account.account_id = tbl_account.id
                            WHERE
                                tbl_user_account.user_id = $id
                            ) AS copiers,
                            (
                            SELECT
                                COUNT( 1 ) AS followers
                            FROM
                                `tbl_copy`
                                INNER JOIN tbl_account ON tbl_account.id = tbl_copy.master_id
                                INNER JOIN tbl_user_account ON tbl_user_account.account_id = tbl_account.id
                            WHERE
                            tbl_user_account.user_id = $id
                            ) AS followers");

        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'user' => $user,
                'trading_info' => $info[0],
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "User not found",
                ],
            ], 400);
        }
        $name = $request->get('name');
        $email = $request->get('email');
        $isCanContact = $request->get('isCanContact');
        $experience_year = $request->get('experienceYear');
        $experience_history = $request->get('experienceHistory');
        $phone = $request->get('phone');
        $country = $request->get('country');
        $address = $request->get('address');
        $flag = $request->get('flag');
        $zipcode = $request->get('zipcode');
        $password = $request->get('password');
        $active = $request->get('active');
        $group = $request->get('group');
        if ($name) {
            $user['name'] = $name;
        }
        if ($email) {
            $user['email'] = $email;
        }
        if ($phone) {
            $user['phone'] = $phone;
        }
        if ($address) {
            $user['address'] = $address;
        }
        if ($country) {
            $user['country'] = $country;
            $user['flag'] = $flag;
        }
        if ($zipcode) {
            $user['zipcode'] = $zipcode;
        }
        if ($password) {
            $user['password'] = bcrypt($password);
        }
        if ($active) {
            $user['active'] = $active;
        }
        if ($group) {
            $user['group'] = $group;
        }
        if ($isCanContact) {
            $user['isCanContact'] = $isCanContact;
        }
        if ($experience_year) {
            $user['experience_year'] = $experience_year;
        }
        if ($experience_history) {
            $user['experience_history'] = $experience_history;
        }
        $user->save();
        if ($active == 1) {
            Mail::to($user['email'])->send(new Activation($user['name']));
        }

        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'message' => "Successfully updated.",
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $me = Auth::user();
        if ($me['id'] == $id) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "You can't delete your account.",
                ],
            ], 400);
        }
        $user = User::where('id', $id)->first();

        if ($user) {
            $userAccounts = $user->user_accounts;
            foreach ($userAccounts as $userAccount) {
                $userAccount->account->delete();
                $userAccount->delete();
            }
            $user->delete();
            return response()->json([
                'response' => [
                    'code' => 200,
                    'api_status' => 1,
                    'message' => "Successfully deleted.",
                ],
            ]);
        }
        return response()->json([
            'response' => [
                'code' => 400,
                'api_status' => 0,
                'message' => "User doesn't exist.",
            ],
        ], 400);
    }

    public function myProfile(Request $request)
    {
        $me = Auth::user();
        if (!$me) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "User doesn't exist.",
                ],
            ], 400);
        }
        $id = $me['id'];
        $info = DB::select("SELECT
                            IFNULL( providers.providers, 0 ) AS providers,
                            IFNULL( copiers.copiers, 0 ) AS copiers,
                            IFNULL( followers.followers, 0 ) AS followers
                            FROM
                            (
                            SELECT
                                COUNT( 1 ) AS providers
                            FROM
                                tbl_account
                                INNER JOIN tbl_user_account ON tbl_user_account.account_id = tbl_account.id
                            WHERE
                                tbl_user_account.user_id = $id
                                AND tbl_account.`status` = 'PROVIDE'
                            ) AS providers,
                            (
                            SELECT
                                COUNT( 1 ) AS copiers
                            FROM
                                `tbl_copy`
                                INNER JOIN tbl_account ON tbl_account.id = tbl_copy.slave_id
                                INNER JOIN tbl_user_account ON tbl_user_account.account_id = tbl_account.id
                            WHERE
                                tbl_user_account.user_id = $id
                            ) AS copiers,
                            (
                            SELECT
                                COUNT( 1 ) AS followers
                            FROM
                                `tbl_copy`
                                INNER JOIN tbl_account ON tbl_account.id = tbl_copy.master_id
                                INNER JOIN tbl_user_account ON tbl_user_account.account_id = tbl_account.id
                            WHERE
                            tbl_user_account.user_id = $id
                            ) AS followers");

        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'profile' => [
                    'name' => $me['name'],
                    'email' => $me['email'],
                    'isCanContact' => $me['isCanContact'],
                    'experience_year' => $me['experience_year'],
                    'experience_history' => $me['experience_history'],
                    'group' => $me['group'],
                    'client_id' => $me['client_id'],
                    'client_secret' => $me['client_secret'],
                    'phone' => $me['phone'],
                    'country' => $me['country'],
                    'flag' => $me['flag'],
                    'address' => $me['address'],
                    'zipcode' => $me['zipcode'],
                    'avatar' => $me['avatar'],
                    'roles' => $me['roles'],
                    'date_of_birth' => $me['date_of_birth'],
                    'created_at' => $me['created_at'],
                ],
                'trading_info' => $info[0],
            ],
        ]);
    }

    public function updateMyProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "User not found",
                ],
            ], 400);
        }
        $name = $request->get('name');
        $email = $request->get('email');
        $isCanContact = $request->get('isCanContact');
        $experience_year = $request->get('experienceYear');
        $experience_history = $request->get('experienceHistory');
        $phone = $request->get('phone');
        $password = $request->get('password');
        $country = $request->get('country');
        $flag = $request->get('flag');
        $address = $request->get('address');
        $zipcode = $request->get('zipcode');
        // $active = $request->get('active');
        if ($name) {
            $user['name'] = $name;
        }
        if ($email) {
            $user['email'] = $email;
        }
        if ($phone) {
            $user['phone'] = $phone;
        }
        if ($country) {
            $user['country'] = $country;
            $user['flag'] = $flag;
        }
        if ($address) {
            $user['address'] = $address;
        }
        if ($zipcode) {
            $user['zipcode'] = $zipcode;
        }
        if ($password) {
            $user['password'] = bcrypt($password);
        }
        // if ($active) {
        //     $user['active'] = $active;
        // }
        if ($isCanContact) {
            $user['isCanContact'] = $isCanContact;
        }
        if ($experience_year) {
            $user['experience_year'] = $experience_year;
        }
        if ($experience_history) {
            $user['experience_history'] = $experience_history;
        }

        $user->save();

        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'message' => "Successfully updated.",
            ],
        ]);
    }

    public function updateMyAvatar(Request $request)
    {
        $file = $request->file('avatar');
        $destinationPath = 'uploads';
        $filename = "Avatar_" . date('Y-m-d_H-i-s') . "." . $file->getClientOriginalExtension();

        try {
            $file->move($destinationPath, $filename);
            $user = Auth::user();
            $user['avatar'] = "/uploads/$filename";
            $user->save();
            return response()->json([
                'response' => [
                    'code' => 200,
                    'api_status' => 1,
                    'filename' => "/uploads/$filename",
                ],
            ]);
        } catch (Exception $error) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Upload failed",
                ],
            ]);
        }
    }

    public function updateUserAvatar(Request $request, $id)
    {
        $file = $request->file('avatar');
        $destinationPath = 'uploads';
        $filename = "Avatar_" . date('Y-m-d_H-i-s') . "." . $file->getClientOriginalExtension();

        try {
            $file->move($destinationPath, $filename);
            $user = User::where('id', $id)->first();
            $user['avatar'] = "/uploads/$filename";
            $user->save();
            return response()->json([
                'response' => [
                    'code' => 200,
                    'api_status' => 1,
                    'filename' => "/uploads/$filename",
                ],
            ]);
        } catch (Exception $error) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Upload failed",
                ],
            ]);
        }
    }
}
