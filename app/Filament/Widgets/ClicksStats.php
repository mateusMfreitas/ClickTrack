<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClicksStats extends BaseWidget
{
    protected function getStats(): array
    {
        $response = Http::get('http://172.26.192.1:5984/clicks/_all_docs?include_docs=true');

        $docs = $response->json()['rows'] ?? [];
        $contagem = [];
        $contagem['total'] = 0;

        foreach ($docs as $row) {
            $clicks = $row['doc']['clicks'] ?? [];

            foreach ($clicks as $div => $qtd) {

                if (!isset($contagem['porDiv'][$div])) {
                    $contagem['porDiv'][$div] = 0;
                }

                $contagem['porDiv'][$div] += $qtd;
                $contagem['total'] += $qtd;
            }
        }
        $stats = [
            Stat::make('Total de cliques', $contagem['total']),
        ];

        foreach ($contagem['porDiv'] as $div => $qtd) {
            $stats[] = Stat::make("Clicks em {$div}", $qtd);
        }

        return $stats;
    }
}

