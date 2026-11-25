<?php

namespace App\Http\Controllers;

use App\Models\UtiCel;
use Illuminate\Http\Request;

class UtiCelController extends Controller
{
    public function index(Request $request)
    {
        $lekerdezes = UtiCel::with(['orszag', 'cimkek']);

        if ($request->boolean('nepszeru')) {
            $lekerdezes->where('nepszeru', true);
        }

        if ($request->filled('kereses')) {
            $szo = '%' . $request->input('kereses') . '%';
            $lekerdezes->where(function ($q) use ($szo) {
                $q->where('nev', 'like', $szo)
                  ->orWhereHas('orszag', fn ($o) => $o->where('nev', 'like', $szo));
            });
        }

        return $lekerdezes
            ->limit($request->integer('limit', 50))
            ->get()
            ->map(fn (UtiCel $u) => $this->alak($u));
    }

    public function show(UtiCel $utiCel)
    {
        return $this->alak($utiCel->load(['orszag', 'cimkek']));
    }

    private function alak(UtiCel $u): array
    {
        return [
            'id' => $u->id,
            'nev' => $u->nev,
            'orszag' => $u->orszag->nev,
            'leiras' => $u->leiras,
            'kep' => $u->kep,
            'cimke' => $u->cimke,
            'idojaras' => $u->idojaras,
            'ar' => $u->napi_ar,
            'repulesi_ido_perc' => $u->repulesi_ido_perc,
            'szezon' => $u->szezon,
            'koltseg' => $u->koltseg,
            'kviz_cimkek' => $u->kviz_cimkek ?? [],
            'cimkek' => $u->cimkek->pluck('nev'),
        ];
    }
}