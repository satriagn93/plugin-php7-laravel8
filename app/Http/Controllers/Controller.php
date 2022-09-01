<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Covid;
use Illuminate\Support\Facades\File;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Mail\Emailku;
use Illuminate\Support\Facades\Mail;

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
        flash()->addInfo('Semangat ygy');
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

    public function getkecamatan(Request $request)
    {
        $kab = $request->filters['kabupatenIndex'];
        if (empty($kab)) {
            $kodekab = "3171";
        } else {
            $kodekab = $request->filters['kabupatenIndex'];
        }
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://www.emsifa.com/api-wilayah-indonesia/api/districts/$kodekab.json",
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
        $kecamatan = json_decode($response);
        if (request()->ajax()) {
            return DataTables::of($kecamatan)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function cetakpdf(Request $request)
    {
        $pdf = PDF::loadView('contohpdf')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function sendmail(Request $request)
    {
        $email = $request->email;
        Mail::to('gogonjikotoram@gmail.com')->send(new Emailku());
        return redirect('/');
    }
}
