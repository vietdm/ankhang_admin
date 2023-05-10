<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\MissionList;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MissionListController extends Controller
{
    public function list($type, Request $request): JsonResponse
    {
        $userId = $request->user->id;
        $missionWithTypeOfUser = Mission::whereType($type)
            ->whereUserId($userId)
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->get();
        logger(Carbon::now()->format('Y-m-d'));
        $missionList = MissionList::whereType($type)->get();
        return Response::success([
            'limit' => 5 - $missionWithTypeOfUser->count(),
            'mission' => $missionList
        ]);
    }
}
