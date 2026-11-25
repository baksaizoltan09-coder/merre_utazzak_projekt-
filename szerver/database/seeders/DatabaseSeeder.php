<?php

namespace Database\Seeders;

use App\Models\Cimke;
use App\Models\Orszag;
use App\Models\UtiCel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $utak = [
            [
                'nev' => 'Róma', 'orszag' => 'Olaszország', 'kod' => 'IT',
                'ar' => 35000, 'ido' => 100, 'idojaras' => '24 °C', 'nepszeru' => true,
                'szezon' => 'ápr–jún, szept–okt', 'koltseg' => '€€',
                'cimkek' => ['Város', 'Kultúra'],
                'kviz' => ['varos', 'kultura', 'enyhe', 'arkozepes', 'gasztro', 'rovid'],
            ],
            [
                'nev' => 'Barcelona', 'orszag' => 'Spanyolország', 'kod' => 'ES',
                'ar' => 38000, 'ido' => 150, 'idojaras' => '26 °C', 'nepszeru' => true,
                'szezon' => 'máj–okt', 'koltseg' => '€€',
                'cimkek' => ['Város', 'Tengerpart'],
                'kviz' => ['varos', 'tenger', 'kultura', 'meleg', 'arkozepes', 'buli', 'kozepes'],
            ],
            [
                'nev' => 'Párizs', 'orszag' => 'Franciaország', 'kod' => 'FR',
                'ar' => 42000, 'ido' => 125, 'idojaras' => '19 °C', 'nepszeru' => true,
                'szezon' => 'ápr–jún, szept–okt', 'koltseg' => '€€€',
                'cimkek' => ['Város', 'Kultúra'],
                'kviz' => ['varos', 'kultura', 'enyhe', 'dragabb', 'gasztro', 'kozepes'],
            ],
            [
                'nev' => 'Santorini', 'orszag' => 'Görögország', 'kod' => 'GR',
                'ar' => 45000, 'ido' => 180, 'idojaras' => '27 °C', 'nepszeru' => true,
                'szezon' => 'máj–okt', 'koltseg' => '€€€',
                'cimkek' => ['Tengerpart', 'Romantikus'],
                'kviz' => ['tenger', 'pihenes', 'meleg', 'dragabb', 'nyugi', 'kozepes'],
            ],
            [
                'nev' => 'Prága', 'orszag' => 'Csehország', 'kod' => 'CZ',
                'ar' => 30000, 'ido' => 80, 'idojaras' => '18 °C', 'nepszeru' => true,
                'szezon' => 'ápr–okt', 'koltseg' => '€',
                'cimkek' => ['Város', 'Kultúra'],
                'kviz' => ['varos', 'kultura', 'enyhe', 'olcso', 'buli', 'rovid'],
            ],
            [
                'nev' => 'Zermatt', 'orszag' => 'Svájc', 'kod' => 'CH',
                'ar' => 60000, 'ido' => 110, 'idojaras' => '14 °C', 'nepszeru' => false,
                'szezon' => 'jún–szept', 'koltseg' => '€€€',
                'cimkek' => ['Hegy', 'Természet'],
                'kviz' => ['hegy', 'kaland', 'hideg', 'dragabb', 'nyugi', 'rovid'],
            ],
            [
                'nev' => 'Dolomitok', 'orszag' => 'Olaszország', 'kod' => 'IT',
                'ar' => 40000, 'ido' => 90, 'idojaras' => '16 °C', 'nepszeru' => false,
                'szezon' => 'jún–szept', 'koltseg' => '€€',
                'cimkek' => ['Hegy', 'Túra'],
                'kviz' => ['hegy', 'kaland', 'hideg', 'arkozepes', 'gasztro', 'rovid'],
            ],
            [
                'nev' => 'Isztambul', 'orszag' => 'Törökország', 'kod' => 'TR',
                'ar' => 25000, 'ido' => 150, 'idojaras' => '21 °C', 'nepszeru' => false,
                'szezon' => 'ápr–máj, szept–nov', 'koltseg' => '€',
                'cimkek' => ['Város', 'Kultúra'],
                'kviz' => ['varos', 'kultura', 'enyhe', 'olcso', 'gasztro', 'kozepes'],
            ],
            [
                'nev' => 'Kréta', 'orszag' => 'Görögország', 'kod' => 'GR',
                'ar' => 40000, 'ido' => 170, 'idojaras' => '27 °C', 'nepszeru' => false,
                'szezon' => 'máj–okt', 'koltseg' => '€€',
                'cimkek' => ['Tengerpart', 'Gasztro'],
                'kviz' => ['tenger', 'pihenes', 'meleg', 'arkozepes', 'gasztro', 'kozepes'],
            ],
        ];

        foreach ($utak as $adat) {
            $orszag = Orszag::firstOrCreate(
                ['nev' => $adat['orszag']],
                ['kod' => $adat['kod'], 'kontinens' => 'Európa']
            );

            $ut = UtiCel::updateOrCreate(
                ['nev' => $adat['nev']],
                [
                    'orszag_id' => $orszag->id,
                    'napi_ar' => $adat['ar'],
                    'repulesi_ido_perc' => $adat['ido'],
                    'idojaras' => $adat['idojaras'],
                    'cimke' => 'Népszerű',
                    'nepszeru' => $adat['nepszeru'],
                    'szezon' => $adat['szezon'],
                    'koltseg' => $adat['koltseg'],
                    'kviz_cimkek' => $adat['kviz'],
                ]
            );

            $cimkeIdk = collect($adat['cimkek'])
                ->map(fn ($nev) => Cimke::firstOrCreate(['nev' => $nev])->id)
                ->all();

            $ut->cimkek()->syncWithoutDetaching($cimkeIdk);
        }
    }
}