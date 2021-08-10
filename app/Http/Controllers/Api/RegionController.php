<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{
    public function provinces(Request $request) 
    {

    	$data = DB::table('provinces')
	            ->select('*')
	            ->get();

	    if ($data == false) {
	    	$error = true;
			$code = 404;
			$message = "Data not found";
	    } else {
	    	$error = false;
			$code = 200;
			$message = "Data found";
	    }

	    return response()->json([
			'error' => $error,
			'code' => $code,
			'message' => $message,
			'data' => $data,
		]);
    }

    public function regencies(Request $request, $name) 
    {

    	$data = DB::table('regencies')
    			->join('provinces', 'regencies.province_id', '=', 'provinces.id')
	            ->select('regencies.*', 'provinces.name as province_name')
	            ->where('regencies.province_id', 'like', '%')
	            ->where('provinces.name', '=', $name)
	            ->get();

	    if ($data == false) {
	    	$error = true;
			$code = 404;
			$message = "Data not found";
	    } else {
	    	$error = false;
			$code = 200;
			$message = "Data found";
	    }

	    return response()->json([
			'error' => $error,
			'code' => $code,
			'message' => $message,
			'data' => $data,
		]);
    }

    public function districts(Request $request, $name) 
    {

    	$data = DB::table('districts')
    			->join('regencies', 'districts.regency_id', '=', 'regencies.id')
	            ->select('districts.*', 'regencies.name as regency_name')
	            ->where('districts.regency_id', 'like', '%')
	            ->where('regencies.name', '=', $name)
	            ->get();

	    if ($data == false) {
	    	$error = true;
			$code = 404;
			$message = "Data not found";
	    } else {
	    	$error = false;
			$code = 200;
			$message = "Data found";
	    }

	    return response()->json([
			'error' => $error,
			'code' => $code,
			'message' => $message,
			'data' => $data,
		]);
    }
}
