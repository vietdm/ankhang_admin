<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\BankInfo;
use App\Models\HistoryBonus;
use App\Models\UserMoney;
use App\Models\Users;
use App\Models\Withdraw;
use App\Utils\UserUtil;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class UserController extends Controller
{
    public function presentName(Request $request)
    {
        $phone = $request->phone ?? '';
        $userWithPhone = Users::wherePhone($phone)->first();
        if (!$userWithPhone) {
            return Response::badRequest([
                'message' => 'Not found!'
            ]);
        }
        return Response::success([
            'message' => 'Success!',
            'name' => $userWithPhone->fullname
        ]);
    }

    public function getTree(Request $request)
    {
        $userId = $request->user->id;
        $user = Users::select(['id', 'phone', 'username'])->whereId($userId)->first()->toArray();
        $userTree = [...$user];

        //get level 1
        $userLevel1 = Users::select(['id', 'phone', 'username'])->wherePresentPhone($user['phone'])->get()->toArray();

        //get level2
        foreach ($userLevel1 as $key1 => $userlv1) {
            $userLevel2 = Users::select(['id', 'phone', 'username'])->wherePresentPhone($userlv1['phone'])->get()->toArray();

            //get level 3
            foreach ($userLevel2 as $key2 => $userlv2) {
                $userLevel3 = Users::select(['id', 'phone', 'username'])->wherePresentPhone($userlv2['phone'])->get()->toArray();
                $userLevel2[$key2]['children'] = [...$userLevel3];
            }

            $userLevel1[$key1]['children'] = [...$userLevel2];
        }

        $userTree['children'] = $userLevel1;

        return Response::success([
            'tree' => $userTree
        ]);
    }

    public function getChild($id)
    {
        $user = Users::select(['phone'])->whereId($id)->first();
        if (!$user) {
            return Response::success(['child' => []]);
        }
        $child = Users::select(['id', 'fullname'])->wherePresentPhone($user->phone)->get()->toArray();
        return Response::success(['child' => $child]);
    }

    public function getDashboardData(Request $request)
    {
        $userId = $request->user->id;
        $userMoney = UserMoney::whereUserId($userId)->first();
        $historyBonus = HistoryBonus::select([
            DB::raw('SUM(money_bonus) AS money_bonus_day')
        ])->whereUserId($userId)
            ->whereDateBonus(Carbon::now()->format('Y-m-d'))
            ->groupBy('user_id')
            ->first();

        UserUtil::getTotalChildAndSale($request->user->phone, $total, $totalSale);

        return Response::success([
            'money_bonus' => $userMoney->money_bonus,
            'money_bonus_day' => $historyBonus->money_bonus_day ?? 0,
            'total_child' => $total,
            'total_sale' => $totalSale,
        ]);
    }

    public function moneyCanWithdraw(Request $request)
    {
        $user = Users::with(['user_money'])->whereId($request->user->id)->first();
        $priceOfOneProduct = 3000000;
        $productKeep = [
            Users::LEVEL_NOMAL => 0,
            Users::LEVEL_CHUYEN_VIEN => 2,
            Users::LEVEL_TRUONG_PHONG => 3,
            Users::LEVEL_PHO_GIAM_DOC => 4,
            Users::LEVEL_GIAM_DOC => 5,
            Users::LEVEL_GIAM_DOC_CAP_CAO => 6
        ];
        $priceKeep = $priceOfOneProduct * $productKeep[$user->level];
        $priceCanWithdraw = $user->user_money->money_bonus - $priceKeep;
        return Response::success([
            'money' => $priceCanWithdraw
        ]);
    }

    public function withdrawRequest(Request $request)
    {
        $user = Users::with(['user_money'])->whereId($request->user->id)->first();
        $moneyWithdraw = (int)$request->money;
        $minWithdraw = 100000;
        if ($moneyWithdraw < $minWithdraw) {
            return Response::badRequest([
                'message' => 'Số tiền rút tối thiểu là 100.000đ'
            ]);
        }

        $priceOfOneProduct = 3000000;
        if ($user->level == Users::LEVEL_CHUYEN_VIEN) {
            $priceKeep = $priceOfOneProduct * 2;
            $priceCanWithdraw = $user->user_money->money_bonus - $priceKeep;
            if ($moneyWithdraw > $priceCanWithdraw) {
                return Response::badRequest([
                    'message' => 'Không đủ tiền rút vì: Cấp bậc chuyên viên phải giữ tiền quay vòng tối thiểu 2 gói!'
                ]);
            }
        } else if ($user->level == Users::LEVEL_TRUONG_PHONG) {
            $priceKeep = $priceOfOneProduct * 3;
            $priceCanWithdraw = $user->user_money->money_bonus - $priceKeep;
            if ($moneyWithdraw > $priceCanWithdraw) {
                return Response::badRequest([
                    'message' => 'Không đủ tiền rút vì: Cấp bậc trưởng phòng phải giữ tiền quay vòng tối thiểu 3 gói!'
                ]);
            }
        } else if ($user->level == Users::LEVEL_PHO_GIAM_DOC) {
            $priceKeep = $priceOfOneProduct * 4;
            $priceCanWithdraw = $user->user_money->money_bonus - $priceKeep;
            if ($moneyWithdraw > $priceCanWithdraw) {
                return Response::badRequest([
                    'message' => 'Không đủ tiền rút vì: Cấp bậc phó giám đốc phải giữ tiền quay vòng tối thiểu 4 gói!'
                ]);
            }
        } else if ($user->level == Users::LEVEL_GIAM_DOC) {
            $priceKeep = $priceOfOneProduct * 5;
            $priceCanWithdraw = $user->user_money->money_bonus - $priceKeep;
            if ($moneyWithdraw > $priceCanWithdraw) {
                return Response::badRequest([
                    'message' => 'Không đủ tiền rút vì: Cấp bậc phó giám đốc phải giữ tiền quay vòng tối thiểu 5 gói!'
                ]);
            }
        } else if ($user->level == Users::LEVEL_GIAM_DOC_CAP_CAO) {
            $priceKeep = $priceOfOneProduct * 6;
            $priceCanWithdraw = $user->user_money->money_bonus - $priceKeep;
            if ($moneyWithdraw > $priceCanWithdraw) {
                return Response::badRequest([
                    'message' => 'Không đủ tiền rút vì: Cấp bậc phó giám đốc phải giữ tiền quay vòng tối thiểu 6 gói!'
                ]);
            }
        } else {
            if ($moneyWithdraw > $user->user_money->money_bonus) {
                return Response::badRequest([
                    'message' => 'Không đủ tiền rút vì: Số tiền rút tối đang lớn hơn số tiền có thể rút'
                ]);
            }
        }

        DB::beginTransaction();
        try {
            $user->user_money->money_bonus -= $moneyWithdraw;
            $user->user_money->save();

            Withdraw::insert([
                "user_id" => $user->id,
                "money" => $moneyWithdraw,
                "date" => Carbon::now()->format('Y-m-d')
            ]);

            DB::commit();
            return Response::success([
                'message' => 'Yêu cầu rút tiền thành công!'
            ]);
        } catch (Exception|PDOException $e) {
            DB::rollBack();
            return Response::badRequest([
                'message' => 'Có lỗi khi tạo yêu cầu rút tiền. Vui lòng liên hệ quản trị viên!'
            ]);
        }
    }

    public function withdrawHistory(Request $request)
    {
        $histories = Withdraw::whereUserId($request->user->id)->orderBy('id', 'DESC')->get()->toArray();
        return Response::success([
            'histories' => $histories
        ]);
    }

    public function updateNomalInfo(Request $request)
    {
        $fullname = trim($request->fullname ?? '');
        if (empty($fullname)) {
            return Response::badRequest(['message' => "Họ và tên không được trống"]);
        }
        $request->user->fullname = $fullname;
        $request->user->save();
        return Response::success(['message' => 'Cập nhật thông tin thành công!']);
    }

    public function updateBankInfo(Request $request)
    {
        if (empty($request->bin) || empty($request->account_number) || empty($request->branch)) {
            return Response::badRequest([
                'message' => 'Data update không đầy đủ!'
            ]);
        }
        $bankInfo = BankInfo::whereUserId($request->user->id)->first();
        $bankInfo->bin = $request->bin;
        $bankInfo->account_number = $request->account_number;
        $bankInfo->branch = $request->branch;
        $bankInfo->save();
        return Response::success([
            'message' => 'Cập nhật thành công!'
        ]);
    }
}
