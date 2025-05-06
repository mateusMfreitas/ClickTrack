<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClickTrackerController extends Controller
{
    public function store(Request $request){
      $clicks = $request->input('clicks');

      if(!is_array($clicks)){
        return response()->json(['error' => 'Formato inválido'], 422);
      }

      $data = [
        'ip' => $request->input('ip'),
        'navegador' => $request->input('navegador'),
        'timestamp' => now(),
        'clicks' => $clicks,
        'coordenadas' => $request->input('coordinates'),
      ];

      $response = Http::post('http://172.26.192.1:5984/clicks', $data);
      Log::info($response->body());
      return response()->json(['status' => 'ok'], $response->successful() ? 200 : 500);
    }
}
