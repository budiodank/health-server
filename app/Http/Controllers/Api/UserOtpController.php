<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Http;

class UserOtpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error = false;
        $code = 200;
        $data = NULL;
        try {
            $validatedData = $request->validate([
                'user_id' => 'required',
            ]);

            $codedetail = new User;

            $user_otp = UserOtp::create([
                'user_id' => $validatedData['user_id'],
                'otp_code' => $codedetail->getNumber(6),
                'active' => "N",
            ]);

            $checkOtp = UserOtp::where('user_otps.user_id', $validatedData['user_id'])
                                ->join('users', 'users.user_id', '=', 'user_otps.user_id')
                                ->orderBy('id', 'desc')
                                ->first();

            if ($checkOtp == false) {
                $error = true;
                $code = 500;
                $message = "Your OTP code failed to generate!";
            } else {
                $error = false;
                $code = 200;
                $message = "Code OTP: ".$checkOtp->otp_code;
                $data = $checkOtp;

                $response = Http::withHeaders([
                    'x-api-key' => '5d22em4CfZko8gOzvE4R2OnzQ5F8j1h5'
                ])->post('https://api.thebigbox.id/sms-notification/2.0.0/messages', [
                    'msisdn' => $checkOtp->telephone,
                    'content' => $message,
                ]);
            }

            return response()->json([
                'error' => $error,
                'code' => $code,
                'message' => $message,
                'data' => $data,
                'response' => $response,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 505,
                'message' => 'Exception : '.$e,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $error = false;
        $code = 200;
        $data = NULL;
        $token = NULL;

        try {

            $checkOtp = UserOtp::where('otp_code', $id)
                                ->where('active', 'N')
                                ->first();

            if ($checkOtp == false) {
                $error = true;
                $code = 500;
                $message = "Your OTP code not found!";
            } else {
                $userOtp = UserOtp::find($checkOtp->id);

                if ($userOtp == false) {
                    $error = true;
                    $code = 500;
                    $message = "Data not found!";
                } else {
                    $userOtp->active = 'Y';


                    if ($userOtp->save()) {
                        $user = User::where('user_id', $checkOtp->user_id)->firstOrFail();
                        $token = $user->createToken('auth_token')->plainTextToken;

                        $message = "Your OTP code has been active!";
                        $data = $userOtp;
                    } else {
                        $error = true;
                        $code = 500;
                        $message = "Data not found!";
                    }
                }
                
            }

            return response()->json([
                'error' => $error,
                'code' => $code,
                'message' => $message,
                'data' => $data,
                'access_token' => $token,
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 505,
                'message' => 'Exception : '.$e,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
