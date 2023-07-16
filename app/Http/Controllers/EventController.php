<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Models\LuckyEvent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class EventController extends Controller
{
    public function lucky() {
        $events = LuckyEvent::with(['user'])->whereSpinned(1)->orderByDesc('id')->get();
        return view('event.lucky', compact('events'));
    }

    public function luckyUpdate(Request $request) {
        $eventId = $request->event_id ?? null;
        if (empty($eventId)) {
            return Response::badRequest('Không tồn tại yêu cầu này!');
        }
        $event = LuckyEvent::whereId($eventId)->whereSpinned(1)->first();
        if (!$event) {
            return Response::badRequest('Không tồn tại yêu cầu này!');
        }
        if ($event->is_given === 1 || $event->gift === 'MM') {
            return Response::badRequest('Yêu cầu này không thể xác nhận!');
        }
        DB::beginTransaction();
        try {
            $event->is_given = 1;
            $event->save();
            DB::commit();
            return Response::success('');
        } catch(Exception|PDOException $e) {
            DB::rollBack();
            ReportHandle($e);
            return Response::badRequest('Có lỗi xảy ra. Vui lòng liên hệ bộ phận IT!');
        }
    }
}
