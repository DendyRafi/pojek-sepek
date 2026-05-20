<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCriteriaRequest;
use App\Http\Requests\UpdateCriteriaRequest;
use App\Models\Criteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CriteriaController extends Controller
{
    public function index(): View
    {
        return view('kriteria.index', [
            'criterias' => Criteria::orderBy('id')->get(),
        ]);
    }

    public function store(StoreCriteriaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['p'] = $data['p'] ?? 0;
        $data['q'] = $data['q'] ?? 0;
        $data['s'] = $data['s'] ?? 0;

        Criteria::create($data);

        return redirect()->route('kriteria.index')->with('success', 'Kriteria baru berhasil ditambahkan!');
    }

    public function update(UpdateCriteriaRequest $request, Criteria $criteria): RedirectResponse
    {
        $data = $request->validated();
        $data['p'] = $data['p'] ?? 0;
        $data['q'] = $data['q'] ?? 0;
        $data['s'] = $data['s'] ?? 0;

        $criteria->update($data);

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil diperbarui!');
    }

    public function destroy(Criteria $criteria): RedirectResponse
    {
        $criteria->delete();

        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil dihapus!');
    }
}
