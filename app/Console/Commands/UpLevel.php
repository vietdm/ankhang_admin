<?php

namespace App\Console\Commands;

use App\Models\Users;
use App\Utils\UserUtil;
use Illuminate\Console\Command;

class UpLevel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:up-level {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Up Level For User';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('id');
        if ($userId == null) {
            $this->error('Sử dụng thêm cờ --id={user id}. Sử dụng --id=0 để duyệt toàn bộ!');
            return;
        }
        
        if ($userId != '0') {
            $userId = (int)$userId;
            $user = Users::whereId($userId)->first();
            if ($user != null) {
                $this->loopUpLevel($user);
            }
            return;
        }

        $users = Users::all();
        $this->info("Total user: " . $users->count());

        $userNotHasChild = [];
        foreach ($users as $user) {
            if (Users::whereParentId($user->id)->first() == null) {
                $userNotHasChild[] = $user;
            }
        }

        $this->info("Total user not has child: " . count($userNotHasChild));

        foreach ($userNotHasChild as $user) {
            $this->loopUpLevel($user);
        }
    }

    private function loopUpLevel($user)
    {
        $this->info("Loop user id: $user->id. Level: $user->level");

        $listChild = Users::whereParentId($user->id)->get();

        if ($listChild->count() == 0) {
            goto _loop;
        }

        if ($user->level == Users::LEVEL_NOMAL) {
            $this->upLevelChuyenVien($user, $listChild);
        }
        if ($user->level == Users::LEVEL_CHUYEN_VIEN) {
            $this->upLevelTruongPhong($user, $listChild);
        }
        if ($user->level == Users::LEVEL_TRUONG_PHONG) {
            $this->upLevelPhoGiamDoc($user, $listChild);
        }
        if ($user->level == Users::LEVEL_PHO_GIAM_DOC) {
            $this->upLevelGiamDoc($user, $listChild);
        }
        if ($user->level == Users::LEVEL_GIAM_DOC) {
            $this->upLevelGiamDocCapCao($user, $listChild);
        }

        _loop:
        $parent = Users::whereId($user->parent_id)->first();
        if ($parent != null) {
            $this->loopUpLevel($parent);
        }
    }

    private function upLevelChuyenVien($user, $listChild)
    {
        if ($user->total_buy < 3000000) return;
        if ($listChild->count() < 3) return;

        $countUserBuyStar = 0;

        foreach ($listChild as $child) {
            if ($child->total_buy >= 3000000) {
                $countUserBuyStar += 1;
            }
        }

        if ($countUserBuyStar < 3) return;

        UserUtil::getTotalSale($user->username, $totalSale);

        $totalSale += $user->total_buy;

        if ($totalSale < 30000000) return;

        $user->level = Users::LEVEL_CHUYEN_VIEN;
        $user->save();
    }

    private function upLevelTruongPhong($user, $listChild)
    {
        if ($listChild->count() < 3) return;
        $countUserChuyenVien = 0;
        foreach ($listChild as $child) {
            if ($child->level == Users::LEVEL_CHUYEN_VIEN) {
                $countUserChuyenVien += 1;
            }
        }

        if ($countUserChuyenVien < 3) return;

        $user->level = Users::LEVEL_TRUONG_PHONG;
        $user->save();
    }

    private function upLevelPhoGiamDoc($user, $listChild)
    {
        if ($listChild->count() < 3) return;

        $countUserTruongPhong = 0;

        foreach ($listChild as $child) {
            if ($child->level == Users::LEVEL_TRUONG_PHONG) {
                $countUserTruongPhong += 1;
            }
        }

        if ($countUserTruongPhong < 3) return;

        UserUtil::getTotalSale($user->username, $totalSale);

        $totalSale += $user->total_buy;

        if ($totalSale < 1000000000) return;

        $user->level = Users::LEVEL_PHO_GIAM_DOC;
        $user->save();
    }

    private function upLevelGiamDoc($user, $listChild)
    {
        if ($listChild->count() < 2) return;

        $countUserPhoGiamDoc = 0;

        foreach ($listChild as $child) {
            if ($child->level == Users::LEVEL_PHO_GIAM_DOC) {
                $countUserPhoGiamDoc += 1;
            }
        }

        if ($countUserPhoGiamDoc < 2) return;

        UserUtil::getTotalSale($user->username, $totalSale);

        $totalSale += $user->total_buy;

        if ($totalSale < 3000000000) return;

        $user->level = Users::LEVEL_GIAM_DOC;
        $user->save();
    }

    private function upLevelGiamDocCapCao($user, $listChild)
    {
        if ($listChild->count() < 2) return;

        $countUserGiamDoc = 0;

        foreach ($listChild as $child) {
            if ($child->level == Users::LEVEL_GIAM_DOC) {
                $countUserGiamDoc += 1;
            }
        }

        if ($countUserGiamDoc < 2) return;

        UserUtil::getTotalSale($user->username, $totalSale);

        $totalSale += $user->total_buy;

        if ($totalSale < 6000000000) return;

        $user->level = Users::LEVEL_GIAM_DOC_CAP_CAO;
        $user->save();
    }
}
