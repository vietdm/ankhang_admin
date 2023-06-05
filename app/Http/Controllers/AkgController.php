<?php

namespace App\Http\Controllers;

use App\Helpers\Format;
use App\Helpers\Response;
use App\Models\Configs;
use App\Models\TotalAkgLog;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Illuminate\Support\Str;
use PDOException;

class AkgController extends Controller
{
    public function all()
    {
        $akgLog = TotalAkgLog::select(DB::raw('SUM(amount) as total'))->first();

        $dashboard = new stdClass;
        $dashboard->total = 60000000;
        $dashboard->akg_used = $akgLog->total;
        $dashboard->akg_total_in_config = Configs::get('total_akg', Format::Double);
        $dashboard->akg_compare = $dashboard->total - $dashboard->akg_used - $dashboard->akg_total_in_config;

        $histories = TotalAkgLog::with(['user'])->orderByDesc('created_at')->get();

        return view('akg.index', compact('dashboard', 'histories'));
    }

    public function transfer()
    {
        $types = TotalAkgLog::listType(['mua_hang', 'su_kien_1905']);
        return view('akg.transfer', compact('types'));
    }

    public function transferPost(Request $request)
    {
        $username = $request->username ?? '';
        $point = $request->point ?? '';
        $contentSelect = $request->content_select ?? '';
        $contentNew = $request->content_new ?? '';

        if (empty($username) || empty($point) || (empty($contentSelect) && empty($contentNew))) {
            return Response::badRequest('Dữ liệu không hợp lệ!');
        }

        $user = Users::with('user_money')->whereUsername($username)->first();
        if ($user == null) {
            return Response::badRequest('Username không tồn tại!');
        }

        $types = TotalAkgLog::listType(['mua_hang', 'su_kien_1905']);
        $type = $content = '';

        if (!empty($contentSelect)) {
            $index = array_search($contentSelect, array_column($types, 'type'));
            if ($index === false) {
                return Response::badRequest('Nội dung chuyển điểm không hợp lệ!');
            }
            $type = $types[$index]['type'];
            $content = $types[$index]['content'];
        } else {
            $content = $contentNew;
            $type = Str::slug($contentNew);
        }

        DB::beginTransaction();
        try {
            $user->user_money->akg_point += (float)$point;
            $user->user_money->save();

            TotalAkgLog::insert([
                'user_id' => $user->id,
                'date' => Carbon::now()->format('Y-m-d H:i:s'),
                'amount' => (float)$point,
                'type' => $type,
                'content' => $content
            ]);

            DB::commit();
            return Response::success('Thành công!');
        } catch (Exception | PDOException $e) {
            ReportHandle($e);
            DB::rollBack();
            return Response::badRequest('Thất bại, hãy liên hệ với bộ phận IT!');
        }
    }
}
