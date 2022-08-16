<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Covid;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    public function index()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://kodepos-2d475.firebaseio.com/kota_kab/k69.json?print=pretty",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            )
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response);
        if (request()->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        // dd($data);
        return view('welcome', ['data' => $data]);

        // $data = Covid::orderBy('id', 'DESC')->get();
        // if (request()->ajax()) {
        //     return DataTables::of($data)
        //         ->addIndexColumn()
        //         ->make(true);
        // }
        // return view('welcome', ['data' => $data]);
    }
}
