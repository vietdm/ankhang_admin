<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Kyc;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

class KycController extends Controller
{
    public function uploadKyc(Request $request)
    {
        $imageFront = $request->file('front');
        $imageBack = $request->file('back');

        if (empty($imageFront) && empty($imageBack)) {
            return Response::badRequest([
                'message' => 'Chưa chọn ảnh cccd/cmt để KYC!'
            ]);
        }

        DB::beginTransaction();
        try {
            //upload image front
            $extFront = $imageFront->extension();
            $newNameFront = sha1(Carbon::now()->format('Ymd_His'));
            $imageFront->move('__kyc', "$newNameFront.$extFront");

            //upload image back
            $extBack = $imageBack->extension();
            $newNameback = sha1(Carbon::now()->format('Ymd_His'));
            $imageBack->move('__kyc', "$newNameback.$extBack");

            //save
            Kyc::insert([
                'user_id' => $request->user->id,
                'image_front' => "/__kyc/$newNameFront.$extFront",
                'image_back' => "/__kyc/$newNameback.$extBack",
            ]);
            DB::commit();
            return Response::success('Thành công. Thông tin KYC sẽ được duyệt trong vòng 1 ngày làm việc.');
        } catch (Exception | PDOException $e) {
            ReportHandle($e);
            return Response::badRequest([
                'message' => 'Không thể upload ảnh để KYC. Vui lòng thoát app và thử lại hoặc liên hệ quản trị viên!'
            ]);
        }
    }
}
