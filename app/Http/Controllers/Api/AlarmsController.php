<?php

namespace App\Http\Controllers\Api;

use App\Handlers\JPushHandler;
use App\Models\Alarm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AlarmsController extends Controller
{
    // 接收告警信息， 推送任务到该设备区域所有人
    public function alarm(Request $request, Alarm $alarm, JPushHandler $JPushHandler)
    {
        $data = [
            'alarm_id' => $request->alarmId,
            'channel_name' => $request->channelName,
            'alarm_type' => $request->alarmType,
            'alarm_start' => $request->alarmStart,
            'device_serial' => $request->deviceSerial,
            'alarm_pic_url' => $request->alarmPicUrl
        ];
        DB::beginTransaction();
        $alarm->fill($data);
        $alarm->save();

        // 报警信息
        $alarm = $alarm->where('alarm_id', $request->alarmId)->first();
        // 网格
        $wangge = DB::table('parts') ->where('num', $alarm->device_serial)->first();
        // 根据网格查找该网格所有的人
        $users = DB::table('users')->where('responsible_area', $wangge->coordinate_id)->get();
        $info = [];
        $time = date('Y-m-d H:i:s', time());
        foreach ($users as $value){
            $array = [
                'user_id' => $value->id,
                'alarm_id' => $alarm->id,
                'created_at' => $time,
                'updated_at' => $time
            ];
            array_push($info, $array);
        }
        $ret = DB::table('alarm_users')->insert($info);
        // 推送
        foreach ($users as $value) {
            $JPushHandler->testJpush($value->reg_id);
        }
        if ($ret) {
            DB::commit();
        } else {
            DB::rollBack();
        }
        return response()->json(['status' => '1', 'msg' => '上传成功']);
    }

    // 分配告警任务到人员
    public function userHasAlarms()
    {
        
    }
}