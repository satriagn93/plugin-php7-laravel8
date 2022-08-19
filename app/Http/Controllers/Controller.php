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
        // $a = 11;
        // $b = "http://www.emsifa.com/api-wilayah-indonesia/api/regencies/$a.json";
        // dd($b);

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

        // get provinsi
        $curlprov = curl_init();
        curl_setopt_array($curlprov, [
            CURLOPT_URL => "http://www.emsifa.com/api-wilayah-indonesia/api/provinces.json",
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
        $responseprov = curl_exec($curlprov);
        curl_close($curlprov);
        $dprovinsi = json_decode($responseprov);
        if (request()->ajax()) {
            return DataTables::of($dprovinsi)
                ->addIndexColumn()
                ->make(true);
        }
        // dd($dprovinsi);
        return view('welcome', ['data' => $data], ['provinsi' => $dprovinsi]);

        // $data = Covid::orderBy('id', 'DESC')->get();
        // if (request()->ajax()) {
        //     return DataTables::of($data)
        //         ->addIndexColumn()
        //         ->make(true);
        // }
        // return view('welcome', ['data' => $data]);
    }

    public function kabupaten(Request $request, $id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://www.emsifa.com/api-wilayah-indonesia/api/regencies/$id.json",
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
        $kabupaten = json_decode($response);

        // $covid = Covid::orderBy('id', 'DESC')->get();

        return response()->json([
            'success' => true,
            'kabupaten' => $kabupaten,
        ], 200);
    }
}
