<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TopNavegadoresChart extends ChartWidget
{
    protected static ?string $heading = 'Cliques por navegador';

    protected function getData(): array
    {
        $response = Http::get('http://' . env('IP_ADDRESS') . ':5984/clicks/_all_docs?include_docs=true');
        $docs = $response->json()['rows'] ?? [];

        $navegadores = [];

        foreach ($docs as $row) {
            $navegador = $row['doc']['navegador'] ?? [];
            if(isset($navegador) && !is_array($navegador) && isset($navegadores[$navegador])){
                $navegadores[$navegador] += 1;
            }else if(isset($navegador) && !is_array($navegador)){
                $navegadores[$navegador] = 1;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cliques por navegador',
                    'data' => array_values($navegadores),
                ],
            ],
            'labels' => array_keys($navegadores),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
