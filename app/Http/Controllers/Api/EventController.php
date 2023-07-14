<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Configs;
use App\Models\JoinCashbackEvent;
use App\Models\LuckyEvent;
use App\Models\Orders;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class EventController extends Controller
{
    public function joinCashback(Request $request)
    {
        if ($request->user->total_buy < 3000000) {
            return Response::badRequest('Phải mua gói combo Star trở lên mới có thể tham gia!');
        }

        $loopTime = $request->user->total_buy >= 30000000 ? 10 : 1;
        $aryIdInsert = [];

        DB::beginTransaction();
        for ($i = 1; $i <= $loopTime; $i++) {
            $cashbackEvent = JoinCashbackEvent::insert([
                'user_id' => $request->user->id,
                'datetime_join' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            $aryIdInsert[] = $cashbackEvent->id;
        }
        DB::commit();

        DB::beginTransaction();
        try {
            foreach ($aryIdInsert as $id) {
                if ($id % 11 !== 0) {
                    continue;
                }
                $idMakeCashback = $id / 11;
                $rowMakeCashback = JoinCashbackEvent::whereId($idMakeCashback)->first();
                $user = Users::with(['user_money'])->whereId($rowMakeCashback->user_id)->first();
                $user->user_money->cashback_point += 3000000;
                $rowMakeCashback->cashbacked = 1;

                $user->user_money->save();
                $rowMakeCashback->save();
            }
            DB::commit();
        } catch (Exception | PDOException $e) {
            ReportHandle($e);
            DB::rollBack();
        }

        return Response::success('Tham gia thành công!');
    }

    public function getDatetimeCountdown()
    {
        $datetimeCountdown = Configs::get('datetime_countdown', '0');
        return Response::success([
            'datetime' => $datetimeCountdown
        ]);
    }

    public function getNumberLuckyEvent(Request $request) {
        $userId = $request->user->id;
        $events = LuckyEvent::whereUserId($userId)->whereSpinned(0)->get();
        return Response::success(['count' => $events->count()]);
    }

    public function randomLuckyEvent(Request $request)
    {
        $userId = $request->user->id;
        $event = LuckyEvent::with('order')->whereUserId($userId)->whereSpinned(0)->first();
        if (!$event) {
            return Response::badRequest('Bạn không còn lượt quay nào!');
        }
        if (!$event->order) {
            return Response::badRequest('Bạn còn lượt quay nhưng đơn hàng không hợp lệ!');
        }
        $moneyPayed = $event->order->total_price_pay;
        $rand = (float)rand() / (float)getrandmax();

        $currentPersent = 0;
        $successGift = null;
        $eventConfig = config('event.lucky');

        if ($moneyPayed < 3000000) {
            $config = $eventConfig['<3000000'];
        } elseif ($moneyPayed < 30000000) {
            $config = $eventConfig['<30000000'];
        } else {
            $config = $eventConfig['>=30000000'];
        }

        foreach ($config as $gift => $persent) {
            $currentPersent += $persent;
            if ($rand <= $currentPersent) {
                $successGift = $gift;
                break;
            }
        }

        logger("\$rand: $rand");
        logger("\$successGift: $successGift");

        return Response::success(['gift' => $successGift, 'event_id' => $event->id]);
    }

    public function updateLuckyEvent(Request $request)
    {
        $eventId = $request->event_id;
        $gift = $request->gift;

        $event = LuckyEvent::whereId($eventId)->whereSpinned(0)->first();
        if (!$event) return Response::badRequest('Thông tin cập nhật không chính xác!');

        DB::beginTransaction();
        try {
            $event->gift = $gift;
            $event->spinned = 1;
            $event->save();
            DB::commit();
            return Response::success('Thành công!');
        } catch (Exception | PDOException $e) {
            DB::rollBack();
            ReportHandle($e);
            return Response::badRequest('Có lỗi xảy ra trong quá trình cập nhật phần thưởng. Vui lòng liên hệ quản trị viên!');
        }
    }
}
