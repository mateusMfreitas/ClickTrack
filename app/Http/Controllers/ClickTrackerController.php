<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClickTrackerController extends Controller
{
    public function store(Request $request){
      $clicks = $request->input('clicks');
      $coordinates = [];
      if(!is_array($clicks)){
        return response()->json(['error' => 'Formato inválido'], 422);
      }
      $contagemCliques = [];

      foreach($clicks as $index => $click){
        if(!isset($contagemCliques[$click['id']])){
            $contagemCliques[$click['id']] = 0;
        }
        $coordinates[] = $click['coords'];
        $contagemCliques[$click['id']]++;
      }

      $data = [
        'ip' => 'calma',
        'navegador' => $request->input('navegador'),
        'timestamp' => now(),
        'clicks' => $contagemCliques,
        'coordenadas' => $coordinates,
      ];

      $response = Http::post('http://172.26.192.1:5984/clicks', $data);
      Log::info($response->body());
      return response()->json(['status' => 'ok'], $response->successful() ? 200 : 500);
    }
}
