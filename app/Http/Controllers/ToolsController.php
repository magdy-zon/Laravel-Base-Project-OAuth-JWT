<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolsController extends Controller {

    public static function returnResponse($message, $success, $data, $code, $idiom = null ) {
  	     $text = config('messages_es_mx.'.$message);
         header('Content-Type: application/json');

      	return response()->json(["success" => $success, "message" => $text ,"data" =>  $data ], $code, [] ,
      		JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES |
      		JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
