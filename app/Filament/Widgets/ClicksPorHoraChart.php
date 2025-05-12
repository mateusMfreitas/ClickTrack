<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class ClicksPorHoraChart extends ChartWidget
{
    protected static ?string $heading = 'Cliques por hora do dia';

    protected function getData(): array
    {
        $response = Http::get('http://' . env('IP_ADDRESS') . ':5984/clicks/_all_docs?include_docs=true');
        $docs = $response->json()['rows'] ?? [];

        $cliquesPorHora = array_fill(0, 24, 0); // 0h até 23h

        foreach ($docs as $row) {
            $clicks = $row['doc']['clicks'] ?? [];
            $timestamp = $row['doc']['timestamp'] ?? null;

            if ($timestamp) {
                $hora = Carbon::parse($timestamp)->format('H');

                foreach ($clicks as $div => $qtd) {
                    $cliquesPorHora[(int) $hora] += $qtd;
                }
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cliques',
                    'data' => array_values($cliquesPorHora),
                ],
            ],
            'labels' => range(0, 23),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
