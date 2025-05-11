<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClicksPorAreaChart extends ChartWidget
{
    protected static ?string $heading = 'Cliques por coordenada';

    protected function getData(): array
    {
        $response = Http::get('http://env(IP_ADDRESS):5984/clicks/_all_docs?include_docs=true');
        $docs = $response->json()['rows'] ?? [];
        $cliquesPorArea = [];

        foreach ($docs as $row) {
            $coordenadas = $row['doc']['coordenadas'] ?? [];
            if ($coordenadas && !empty($coordenadas)) {
                foreach($coordenadas as $coordenada){
                    $cliquesPorArea[] = ["x" => $coordenada['x'], "y" => $coordenada['y']];
                }

            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cliques',
                    'data' => array_values($cliquesPorArea),
                ],
            ]
        ];
    }

    protected function getType(): string
    {
        return 'scatter';
    }
}
