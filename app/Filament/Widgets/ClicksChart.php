<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Http;

class ClicksChart extends ChartWidget
{
    protected static ?string $heading = 'Cliques por seção do site';

    protected function getData(): array
    {
        $response = Http::get('http://env(IP_ADDRESS):5984/clicks/_all_docs?include_docs=true');
        $docs = $response->json()['rows'] ?? [];

        $clicksPorDiv = [];

        foreach ($docs as $row) {
            $clicks = $row['doc']['clicks'] ?? [];
            foreach ($clicks as $div => $qtd) {
                $clicksPorDiv[$div] = ($clicksPorDiv[$div] ?? 0) + $qtd;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total de Clicks',
                    'data' => array_values($clicksPorDiv),
                ],
            ],
            'labels' => array_keys($clicksPorDiv),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
