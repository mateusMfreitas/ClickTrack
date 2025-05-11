<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class ClicksPorDiaDaSemana extends ChartWidget
{
    protected static ?string $heading = 'Cliques por dia da semana';

    protected function getData(): array
    {
        $response = Http::get('http://env(IP_ADDRESS):5984/clicks/_all_docs?include_docs=true');
        $docs = $response->json()['rows'] ?? [];

        $dias = [
            'Domingo' => 0,
            'Segunda-feira' => 0,
            'Terça-feira' => 0,
            'Quarta-feira' => 0,
            'Quinta-feira' => 0,
            'Sexta-feira' => 0,
            'Sábado' => 0,
        ];

        foreach ($docs as $row) {
            $timestamp = $row['doc']['timestamp'] ?? null;
            $clicks = $row['doc']['clicks'] ?? [];

            if ($timestamp) {
                $dia = Carbon::parse($timestamp)->locale('pt_BR')->dayName;

                foreach ($clicks as $div => $qtd) {
                    $dias[ucfirst($dia)] += $qtd;
                }
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cliques',
                    'data' => array_values($dias),
                ],
            ],
            'labels' => array_keys($dias),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
