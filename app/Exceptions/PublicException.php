<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class PublicException extends Exception
{
    public function render($message = '', $statusCode = STATUS_OK)
    {

        return response()->json([
            'success' => FALSE,
            'status' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }


    public static function Validator(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = Validator::make($data, $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            // dd($validator->errors()->first());
            throw new PublicException($validator->errors()->first(), STATUS_OK);
        }
    }

    public static function Error(string $localizationKey = '', $statusCode = STATUS_OK)
    {
        if ($localizationKey) {
            throw new PublicException(__("message." . $localizationKey), $statusCode);
        }
    }

    public static function NotSave($stateStatus , string $localizationKey = 'SOMETHING_WENT_WRONG', $statusCode = STATUS_OK)
    {
        if (!$stateStatus) {
            throw new PublicException(__("message." . $localizationKey), $statusCode);
        }
    }

    public static function CustomError(string $localizationKey = '', array $data = [], $statusCode = STATUS_OK)
    {
        if ($localizationKey) {
            throw new PublicException(trans("message." . $localizationKey,$data), $statusCode);
        }
    }

    public static function Empty($object, string $localizationKey = '', $statusCode = STATUS_OK)
    {
        if (IsEmpty($object)) {
            $localizationKey = $localizationKey ? $localizationKey : 'NOT_FOUND';
            throw new PublicException(__("message." . $localizationKey), $statusCode);
        }
    }

    public static function ErrorWebPage(string $localizationKey = '', $statusCode = '')
    {
        if($localizationKey && $statusCode)
        {
            $response = new Response(view('web.error.error' , ['title'=>__("message.RESET_LINK_EXPIRED.title"), 'message'=>__("message.RESET_LINK_EXPIRED.message"), 'status_code' => STATUS_LINK_EXPIRED]));
            throw new HttpResponseException($response);
        }
    }

    public static function SaveAndCommit(object $object, string $localizationKey = 'SOMETHING_WENT_WRONG', $statusCode = STATUS_OK)
    {
        // Begin database transaction
        DB::beginTransaction();

        self::NotSave($object->save());

        // Commit the transaction
        DB::commit();
    }
}
