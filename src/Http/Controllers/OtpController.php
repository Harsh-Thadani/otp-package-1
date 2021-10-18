<?php

namespace gl\otp\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{

    public function sendOtp(Request $request)
    {
        $phone = $request->phone;

        $authKey = config('otp.msg91.authKey');
        $templateId = config('otp.msg91.templateId');
        $senderId = config('otp.msg91.senderId');
        $phoneNumber = "91" . $phone;
        $otpLength = 6;

        $sendOtpUrl = "https://api.msg91.com/api/v5/otp?template_id=" . $templateId . "&authkey=" . $authKey . "&mobile=" . $phoneNumber . "&otp_length=" . $otpLength . "&sender=" . $senderId;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get($sendOtpUrl);

        return $this->sendResponse($response, 'Otp send successfully.', 200);
    }

    public function resendOtp($phone)
    {
        $authKey = config('otp.msg91.authKey');
        $phoneNumber = "91" . $phone;

        $resendOtpUrl = "http://api.msg91.com/api/retryotp.php?authkey=" . $authKey . "&mobile=" . $phoneNumber . "&retrytype=text";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get($resendOtpUrl);

        return $this->sendResponse($response, 'Otp resend successfully.', 200);
    }

    public function verifyOtp(Request $request)
    {
        $phone = $request->phone;

        $authKey = config('otp.msg91.authKey');
        $phoneNumber = "91" . $phone;
        $otp = str_replace(' ', '', $request->otp);

        $verifyOtpUrl = "http://api.msg91.com/api/verifyRequestOTP.php?authkey=" . $authKey . "&mobile=" . $phoneNumber . "&otp=" . $otp;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->get($verifyOtpUrl);

        $message = json_decode($response->body())->type;

        if ($message === "success") {
            return $this->sendResponse('', 'Your number verified successfully', 200);
        } else {
            return $this->sendResponse('', 'Wrong Otp Entered', 400);
        }
    }

    public function sendResponse($result, $message, $code)
    {
        $response = [

            'code' => $code,
            'message' => $message,
            'data' => $result,

        ];
        return response()->json($response, 200);
    }
}
