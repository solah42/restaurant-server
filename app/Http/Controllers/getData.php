<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
class getData extends Controller
{
    function getLocation(Request $request){
        //ตรวจสอบข้อมูลใน cache
        $cachedData = Redis::get($request);

        //ถ้ามีข้อมูลใน cache ทำเงือนไขนี้
        if (isset($cachedData)) {
            $data = json_decode($cachedData, FALSE);

            return response()->json([
                'status_code' => 201,
                'data' => $data,
            ]);
        }
        //ถ้าไม่มีข้อมูลใน cache ทำเงือนไขนี้
        else {
            $api_url = 'https://api.nostramap.com/Service/V2/Location/Search';

            $response = Http::get($api_url, [
                'key' => 'GupVoCYdZuBwVjptVbMmoq6CbOkGEdLMTGux95mI6CMYM0N(kmQEi9MGyrP(BtWpQdEWI4c8xWMyoc(qXjA19)m=====2',
                'CatCode' => 'FOOD-FOODSHOP',
                'keyword' => $request['keyword'],
            ]);

            $data = json_decode($response->body());

            //บันทึกข้อมูลลงใน cache
            Redis::set($request, json_encode($data->results));

            return response()->json([
                'status_code' => 201,
                'data' => $data,
            ]);
        }
    }
}
