<?php

namespace App\Classes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class ApiResponseClass
{
    /**
     * Create a new class instance.
     */
    public static function rollback($e, $message="Something went wrong! Process not completed"){
        DB::rollBack();
        self::throw($e, $message);
    }

    public static function throw($e, $message ="Something went wrong! Process not completed"){
        Log::info($e);
        throw new HttpResponseException(response()->json(["error"=>$e,"message"=> $message], 500));
    }

    public static function dataTables($data){
        return response()->json([
            'draw' => request('draw', 1),
            'total' => $data['total'],
            'filtered' => $data['filtered'],
            'data' => $data['data']
        ]);
    }

    public static function sendResponse($result, $message, $code=200){
        $response = [
            'success' => true,
            'data' => $result
        ];
        if(!empty($message)){
            $response['message'] = $message;
        }
        return response()->json($response, $code);
    }
}
