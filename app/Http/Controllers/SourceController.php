<?php

namespace App\Http\Controllers;

use App\Accounts;
use App\Copy;
use App\EquityHistory;
use App\History;
use App\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SourceController extends Controller
{
    public function addSource(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'account_number' => 'required',
            'broker' => 'required',
            'symbol' => 'required',
            'lots' => 'required',
            'ticket' => 'required',
            'direction' => 'required',
            'type' => 'required',
            'magic' => 'required',
            'openPrice' => 'required',
            'stopLossPrice' => 'required',
            'takeProfitPrice' => 'required',
            'openTime' => 'required',
            'openTimeGMT' => 'required',
            'expiration' => 'required',
            'expirationGMT' => 'required',
            'sourceTicket' => 'required',
            'sourceLots' => 'required',
            'sourceType' => 'required',
            'originalTicket' => 'required',
            'originalLots' => 'required',
            'sourceOriginalTicket' => 'required',
            'sourceOriginalLots' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => 'Data is not in the proper format.',
                ],
            ]);
        }
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "User not found.",
                ],
            ], 400);
        }

        $account_number = $input['account_number'];
        $broker = $input['broker'];
        $account = Accounts::where(['account_number' => $account_number, 'broker' => $broker])->first();
        if (!$account) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account doesn't exist.",
                ],
            ], 400);
        }

        $user_account = $account->user_account->first();
        if ($user_account['user_id'] != $user['id']) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account is not yours.",
                ],
            ], 400);
        }
        $account_status = $account['status'];

        if ($account_status != Accounts::STATUS_PROVIDE) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account isn't for provide.",
                ],
            ], 400);
        }

        $input['account_id'] = $account['id'];
        unset($input['account_number']);
        unset($input['broker']);

        Source::where([
            'account_id' => $account['id'],
            'symbol' => $input['symbol'],
            'ticket' => $input['ticket'],
            'magic' => $input['magic'],
        ])->delete();
        Source::create($input);

        History::create($input);

        return response()->json([
            'response' => [
                'code' => 201,
                'api_status' => 1,
                'message' => "success.",
            ],
        ], 201);
    }

    public function getProvideSourceDetail(Request $request)
    {
        $me = Auth::user();
        if (!$me) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "User not found.",
                ],
            ], 400);
        }

        $account_number = $request->get('account_number');
        $broker = $request->get('broker');
        if (!$account_number || !$broker) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account number and Broker are required.",
                ],
            ], 400);
        }
        $account = Accounts::where(['account_number' => $account_number, 'broker' => $broker])->first();
        if (!$account) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account doesn't exist.",
                ],
            ], 400);
        }

        if ($account->status == Accounts::STATUS_NONE) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Signal doesn't exist.",
                ],
            ], 400);
        }

        $account_id = $account->id;
        $user_account = $account->user_account->first();
        if (!$user_account) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account doesn't exist.",
                ],
            ], 400);
        }

        $user = $user_account->user->first();
        if (!$user) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "User doesn't exist.",
                ],
            ], 400);
        }

        $detail = DB::select("SELECT * FROM
                            (SELECT COUNT( 1 ) AS copier_number FROM tbl_copy WHERE master_id = $account_id) AS copier,
                            (SELECT MIN(openTime) as openTime FROM tbl_history WHERE account_id = $account_id) AS source");
        $detail = $detail[0];

        $page = $request->get('page', 1);
        $page = intval($page);
        $perPage = $request->get('perPage', 10);
        $perPage = intval($perPage);
        $offset = ($page - 1) * $perPage;

        $query = "SELECT
                tbl_history.account_id,
                tbl_history.symbol,
                tbl_history.openTime,
                tbl_history.lots,
                tbl_history.type,
                tbl_history.openPrice,
                tbl_history.takeProfitPrice,
                tbl_history.stopLossPrice,
                tbl_history.ticket,
                tbl_history.created_at
                FROM
                tbl_history
                WHERE
                tbl_history.account_id = $account_id
                ORDER BY created_at DESC ";
        $total = DB::select("SELECT COUNT(1) as total from
                            ( " . $query . ") as result");
        $total = $total[0]->total;
        $provideSignalDetail = DB::select($query . "LIMIT $offset, $perPage");
        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'information' => [
                    'provider' => $user->name,
                    'account_number' => $account->account_number,
                    'broker' => $account->broker,
                    'status' => $account->status,
                    'openTime' => $detail->openTime,
                    'copier_number' => $detail->copier_number,
                ],
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'signalDetail' => $provideSignalDetail,
            ],
        ], 200);
    }

    public function getProvideCopyDetail(Request $request)
    {
        $me = Auth::user();
        if (!$me) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "User not found.",
                ],
            ], 400);
        }

        $account_number = $request->get('account_number');
        $broker = $request->get('broker');
        if (!$account_number || !$broker) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account number and Broker are required.",
                ],
            ], 400);
        }
        $account = Accounts::where(['account_number' => $account_number, 'broker' => $broker])->first();
        if (!$account) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account doesn't exist.",
                ],
            ], 400);
        }

        if ($account->status == Accounts::STATUS_NONE) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Signal doesn't exist.",
                ],
            ], 400);
        }

        $account_id = $account->id;
        $user_account = $account->user_account->first();
        if (!$user_account) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account doesn't exist.",
                ],
            ], 400);
        }

        $user = $user_account->user->first();
        if (!$user) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "User doesn't exist.",
                ],
            ], 400);
        }

        // $detail = DB::select("SELECT * FROM
        //                     (SELECT COUNT( 1 ) AS copier_number FROM tbl_copy WHERE master_id = $account_id) AS copier,
        //                     (SELECT MIN(openTime) as openTime FROM tbl_history WHERE account_id = $account_id) AS source");
        // $detail = $detail[0];

        $page = $request->get('page', 1);
        $page = intval($page);
        $perPage = $request->get('perPage', 10);
        $perPage = intval($perPage);
        $offset = ($page - 1) * $perPage;

        $query = "SELECT tbl.*, tbl_users.name, tbl_users.country, tbl_users.flag, tbl_users.avatar FROM (SELECT copiers.*, tbl_user_account.user_id FROM (SELECT tbl_account.* FROM tbl_copy LEFT JOIN tbl_account ON tbl_copy.slave_id = tbl_account.id WHERE tbl_copy.master_id = '$account_id') as copiers LEFT JOIN tbl_user_account ON copiers.id = tbl_user_account.account_id) as tbl LEFT JOIN tbl_users ON tbl.user_id = tbl_users.id";
        $total = DB::select("SELECT COUNT(1) as total from
                            ( " . $query . ") as result");
        $total = $total[0]->total;
        $provideCopyDetail = DB::select($query . " LIMIT $offset, $perPage");
        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'information' => [
                    'provider' => $user->name,
                    'account_number' => $account->account_number,
                    'broker' => $account->broker,
                    'status' => $account->status,
                    // 'openTime' => $detail->openTime,
                    // 'copier_number' => $detail->copier_number,
                ],
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'copyDetail' => $provideCopyDetail,
            ],
        ], 200);
    }

    public function updateEquityDetails(Request $request)
    {
        $me = Auth::user();
        if (!$me) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "User not found.",
                ],
            ], 400);
        }

        $account_number = $request->get('account_number');
        $broker = $request->get('broker');
        $drawdown = $request->get('drawdown');
        $equity = $request->get('equity');
        $openposition_profit = $request->get('openposition_profit');
        $dayprofit = $request->get('dayprofit');
        $balance = $request->get('balance');
        // $date = $request->get('date');
        if (!$account_number || !$broker) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account number and Broker are required.",
                ],
            ], 400);
        }
        $account = Accounts::where(['account_number' => $account_number, 'broker' => $broker])->first();
        if (!$account) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account doesn't exist.",
                ],
            ], 400);
        }

        if ($account->status != Accounts::STATUS_PROVIDE) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Signal doesn't provide.",
                ],
            ], 400);
        }

        $account_id = $account->id;
        $user_account = $account->user_account->first();
        if (!$user_account) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account doesn't exist.",
                ],
            ], 400);
        }

        $user = $user_account->user->first();
        if (!$user) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "User doesn't exist.",
                ],
            ], 400);
        }

        $date = date("Y-m-d");
        $d = strtotime($date);

        if ($equity != 0) {
            $equity_history = EquityHistory::where(['account_id' => $account_id, 'date' => $date])->first();
            if ($equity_history) {
                $equity_history->equity = $equity;
                $equity_history->balance = $balance;
                $equity_history->openposition_profit = $openposition_profit;
                $equity_history->dayprofit = $dayprofit;
                $equity_history->save();
            } else {
                $equity_history = EquityHistory::create(['account_id' => $account_id, 'equity' => $equity, 'openpostion_profit' => $openposition_profit, 'dayprofit' => $dayprofit, 'balance' => $balance, 'date' => $date]);
            }
            $lastEquity = EquityHistory::where('account_id', $account_id)->whereYear('date', date('Y', strtotime($date . ' -1 month')))->whereMonth('date', date('m', strtotime($date . '-1 month')))->orderBy('date', 'desc')->first();
            $equity_history->month = EquityHistory::where('account_id', $account_id)->whereYear('date', date('Y', $d))->whereMonth('date', date('m', $d))->where('date', '<=', $date)->sum('dayprofit') / $equity * 100;
            $equity_history->lastMonth = EquityHistory::where('account_id', $account_id)->whereYear('date', date('Y', strtotime($date . ' -1 month')))->whereMonth('date', date('m', strtotime($date . '-1 month')))->sum('dayprofit') / ($lastEquity ? $lastEquity->equity : 1) * 100;
            $equity_history->year = EquityHistory::where('account_id', $account_id)->whereYear('date', date('Y', $d))->where('date', '<=', $date)->sum('dayprofit') / $equity * 100;
            $equity_history->save();
            $equity_history->drawdown = $this->maxDrawdown(EquityHistory::where('account_id', $account_id)->orderBy('date')->get());
            $equity_history->save();

        }

        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'message' => "success.",
            ],
        ]);

    }

    public function getProvideSourceDetailWithTime(Request $request)
    {
        $me = Auth::user();
        if (!$me) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "User not found.",
                ],
            ], 400);
        }

        $account_number = $request->get('account_number');
        $broker = $request->get('broker');
        $delaytime = $request->get('delaytime');
        if (!$account_number || !$broker || !$delaytime) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account number, Broker and Delay time are required.",
                ],
            ], 400);
        }
        $account = Accounts::where(['account_number' => $account_number, 'broker' => $broker])->first();
        if (!$account) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account doesn't exist.",
                ],
            ], 400);
        }

        if ($account->status == Accounts::STATUS_COPY) {
            $account_id = $account->id;
            $user_account = $account->user_account->first();
            //check account
            if (!$user_account) {
                return response()->json([
                    'response' => [
                        'code' => 400,
                        'api_status' => 0,
                        'message' => "Account doesn't exist.",
                    ],
                ], 400);
            }

            //check user
            $me = Auth::user();
            if ($me['id'] != $user_account['user_id']) {
                return response()->json([
                    'response' => [
                        'code' => 400,
                        'api_status' => 0,
                        'message' => "Account is not yours.",
                    ],
                ], 400);
            }

            $copies = Copy::where('slave_id', $account_id)->get('master_id');
            $account_ids = array();
            for ($i = 0; $i < count($copies); $i++) {
                $account_ids[] = $copies[$i]['master_id'];
            }

            if ($delaytime != -1) {
                $date = date_create();
                $date_stamp = date_timestamp_get($date);
                $date_stamp -= $delaytime;
                $date_str = gmdate("Y-m-d H:i:s", $date_stamp);

                $copyingSignalDetailWithTime = Source::where([
                    ['tbl_source.created_at', '>=', $date_str],
                ])
                    ->whereIn('tbl_source.account_id', $account_ids)
                    ->orderBy('tbl_source.created_at', 'DESC')
                    ->join('tbl_account', 'tbl_source.account_id', '=', 'tbl_account.id')
                    ->get(
                        [
                            "tbl_account.account_number",
                            "tbl_account.broker",
                            "symbol",
                            "lots",
                            "ticket",
                            "direction",
                            "type",
                            "magic",
                            "openPrice",
                            "stopLossPrice",
                            "takeProfitPrice",
                            "openTime",
                            "openTimeGMT",
                            "expiration",
                            "expirationGMT",
                            "comment_str",
                            "sourceTicket",
                            "sourceLots",
                            "sourceType",
                            "originalLots",
                            "originalTicket",
                            "sourceOriginalLots",
                            "sourceOriginalTicket",
                        ]
                    );
            } else {
                $copyingSignalDetailWithTime = Source::whereIn('tbl_source.account_id', $account_ids)
                    ->orderBy('tbl_source.created_at', 'DESC')
                    ->join('tbl_account', 'tbl_source.account_id', '=', 'tbl_account.id')
                    ->get(
                        [
                            "tbl_account.account_number",
                            "tbl_account.broker",
                            "symbol",
                            "lots",
                            "ticket",
                            "direction",
                            "type",
                            "magic",
                            "openPrice",
                            "stopLossPrice",
                            "takeProfitPrice",
                            "openTime",
                            "openTimeGMT",
                            "expiration",
                            "expirationGMT",
                            "comment_str",
                            "sourceTicket",
                            "sourceLots",
                            "sourceType",
                            "originalLots",
                            "originalTicket",
                            "sourceOriginalLots",
                            "sourceOriginalTicket",
                        ]
                    );
            }

            return response()->json([
                'response' => [
                    'code' => 200,
                    'api_status' => 1,
                    'signalDetail' => $copyingSignalDetailWithTime,
                    'Count' => count($copyingSignalDetailWithTime),
                ],
            ], 200);
        } else {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Signal doesn't exist.",
                ],
            ], 400);
        }
    }

    public function deleteSources(Request $request)
    {
        $me = Auth::user();
        if (!$me) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "User not found.",
                ],
            ], 400);
        }

        $account_number = $request->get('account_number');
        $broker = $request->get('broker');
        if (!$account_number || !$broker) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account number and Broker are required.",
                ],
            ], 400);
        }
        $account = Accounts::where(['account_number' => $account_number, 'broker' => $broker])->first();
        if (!$account) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account doesn't exist.",
                ],
            ], 400);
        }

        $user_account = $account->user_account->first();
        if ($user_account['user_id'] != $me['id']) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account is not yours.",
                ],
            ], 400);
        }

        Source::where('account_id', $account['id'])->delete();
        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'message' => "success.",
            ],
        ], 200);
    }

    public function addMultipleSources(Request $request)
    {
        $me = Auth::user();
        if (!$me) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "User not found.",
                ],
            ], 400);
        }

        $Signal = $request->get('Signal');
        $count = $request->get('Count');
        if (!$Signal) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Signals are required.",
                ],
            ], 400);
        }
        if (!count($Signal)) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Signals are required.",
                ],
            ], 400);
        }
        if (count($Signal) != $count) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Signal Count doesn't match.",
                ],
            ], 400);
        }

        $account_number = $Signal[0]['account_number'];
        $broker = $Signal[0]['broker'];
        if (!$account_number || !$broker) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account number and Broker are required.",
                ],
            ], 400);
        }
        $account = Accounts::where(['account_number' => $account_number, 'broker' => $broker])->first();
        if (!$account) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account doesn't exist.",
                ],
            ], 400);
        }

        $user_account = $account->user_account->first();
        if ($user_account['user_id'] != $me['id']) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Account is not yours.",
                ],
            ], 400);
        }

        for ($i = 0; $i < count($Signal); $i++) {
            $Signal[$i]['account_id'] = $account['id'];
            unset($Signal[$i]['account_number']);
            unset($Signal[$i]['broker']);
            $Signal[$i]['created_at'] = date('Y-m-d H:i:s');
            $Signal[$i]['updated_at'] = $Signal[$i]['created_at'];
        }

        // DB::transaction(function () use ($account, &$Signal) {
        //     Source::where('account_id', $account['id'])->delete();
        //     Source::insert($Signal);
        // });

        History::insert($Signal);

        DB::beginTransaction();
        try {
            Source::where('account_id', $account['id'])->delete();
            Source::insert($Signal);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "transaction failed.",
                ],
            ], 400);
        }

        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'message' => "success.",
            ],
        ], 200);
    }

    public function maxDrawdown($data)
    {
        if (count($data) <= 1) {
            return 0;
        }

        $maxPrice = $data[0]->equity;
        $maxDd = 0;

        for ($i = 1; $i < count($data); $i++) {
            if ($data[$i]->equity > $maxPrice) {
                $maxPrice = $data[$i]->equity;
            } else if ($data[$i]->equity < $maxPrice) {
                $maxDd = min($maxDd, ($data[$i]->equity / $maxPrice - 1) * 100);
            }

        }

        return $maxDd;
    }
}