<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCriteriaRequest;
use App\Http\Requests\StoreCustomBackgroundRequest;
use App\Http\Requests\UpdateCriteriaRequest;
use App\Models\Criteria;
use App\Support\CustomBackground;
use App\Support\DefaultCriteria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    public function index(): View
    {
        return view('pengaturan.index', [
            'criterias' => Criteria::orderBy('id')->get(),
        ]);
    }

    public function store(StoreCriteriaRequest $request): RedirectResponse
    {
        Criteria::create($this->criteriaData($request->validated()));

        return redirect()->route('pengaturan.index')->with('success', 'Kriteria baru berhasil ditambahkan!');
    }

    public function update(UpdateCriteriaRequest $request, Criteria $criteria): RedirectResponse
    {
        $criteria->update($this->criteriaData($request->validated()));

        return redirect()->route('pengaturan.index')->with('success', 'Kriteria berhasil diperbarui!');
    }

    public function destroy(Criteria $criteria): RedirectResponse
    {
        $criteria->delete();

        return redirect()->route('pengaturan.index')->with('success', 'Kriteria berhasil dihapus!');
    }

    public function reset(): RedirectResponse
    {
        DB::transaction(function (): void {
            Criteria::query()->delete();
            Criteria::insert(DefaultCriteria::recordsWithTimestamps());
        });

        return redirect()->route('pengaturan.index')->with('success', 'Kriteria berhasil dikembalikan ke pengaturan awal.');
    }

    public function customBackground(): View
    {
        return view('pengaturan.custom-background');
    }

    public function storeCustomBackground(StoreCustomBackgroundRequest $request): JsonResponse|RedirectResponse
    {
        $background = $request->file('background');

        abort_if($background === null, 422, 'File background wajib diunggah.');

        $url = CustomBackground::store($background);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Background global berhasil disimpan.',
                'url' => $url,
                'style' => CustomBackground::style($url),
            ]);
        }

        return redirect()
            ->route('custom-background')
            ->with('success', 'Background global berhasil disimpan.');
    }

    public function destroyCustomBackground(Request $request): JsonResponse|RedirectResponse
    {
        CustomBackground::delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Background global berhasil dikembalikan ke default.',
                'url' => null,
                'style' => '',
            ]);
        }

        return redirect()
            ->route('custom-background')
            ->with('success', 'Background global berhasil dikembalikan ke default.');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function criteriaData(array $data): array
    {
        return array_replace([
            'p' => 0,
            'q' => 0,
            's' => 0,
        ], $data);
    }
}
