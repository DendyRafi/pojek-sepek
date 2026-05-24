<?php

namespace App\Http\Controllers;

use App\Libraries\Promethee;
use App\Models\Criteria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class SkinRecommendationController extends Controller
{
    public function hitungRekomendasi(Request $request, Promethee $promethee): JsonResponse
    {
        $criteria = Criteria::query()
            ->orderBy('id')
            ->get(['id', 'name', 'type', 'weight', 'preference_function', 'p', 'q', 's']);

        $rules = [
            'alternatives' => ['required', 'array', 'min:2'],
            'alternatives.*.name' => ['required', 'string', 'max:100'],
            'alternatives.*.scores' => ['required', 'array'],
        ];

        foreach ($criteria as $criterion) {
            $nameLower = strtolower($criterion->name);
            if (str_contains($nameLower, 'harga')) {
                $rules["alternatives.*.scores.{$criterion->id}"] = ['required', 'numeric', 'min:0'];
            } elseif (str_contains($nameLower, 'rarity') || str_contains($nameLower, 'kategori')) {
                $rules["alternatives.*.scores.{$criterion->id}"] = ['required', 'numeric', 'between:1,6'];
            } elseif (str_contains($nameLower, 'ketersediaan')) {
                $rules["alternatives.*.scores.{$criterion->id}"] = ['required', 'numeric', 'between:1,2'];
            } else {
                $rules["alternatives.*.scores.{$criterion->id}"] = ['required', 'numeric', 'between:1,7'];
            }
        }

        $validator = Validator::make($request->all(), $rules, [
            'alternatives.min' => 'Bandingkan minimal 2 skin agar sistem bisa menghitung peringkatnya.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $criteriaData = $criteria->map(function ($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'direction' => $c->type === 'minimize' ? 'min' : 'max',
                'weight' => $c->weight,
                'preference_function' => $c->preference_function,
                'p' => $c->p,
                'q' => $c->q,
                's' => $c->s,
            ];
        })->toArray();

        try {
            $hasilPeringkat = $promethee->calculate(
                $validator->validated()['alternatives'],
                $criteriaData,
            );
        } catch (InvalidArgumentException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'rekomendasi' => $hasilPeringkat,
        ]);
    }
}
