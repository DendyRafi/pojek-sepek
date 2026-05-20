<?php

namespace App\Http\Controllers;

use App\Libraries\Promethee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkinRecommendationController extends Controller
{
    public function hitungRekomendasi(Request $request, Promethee $promethee): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'alternatives' => ['required', 'array', 'min:2'],
            'alternatives.*.name' => ['required', 'string', 'max:100'],
            'alternatives.*.scores' => ['required', 'array'],
            'alternatives.*.scores.1' => ['required', 'numeric', 'min:0'],
            'alternatives.*.scores.2' => ['required', 'numeric', 'between:1,6'],
            'alternatives.*.scores.3' => ['required', 'numeric', 'between:1,7'],
            'alternatives.*.scores.4' => ['required', 'numeric', 'between:1,7'],
            'alternatives.*.scores.5' => ['required', 'numeric', 'between:1,7'],
            'alternatives.*.scores.6' => ['required', 'numeric', 'between:1,7'],
            'alternatives.*.scores.7' => ['required', 'numeric', 'between:1,7'],
            'alternatives.*.scores.8' => ['required', 'numeric', 'between:1,2'],
        ], [
            'alternatives.min' => 'Bandingkan minimal 2 skin agar sistem bisa menghitung peringkatnya.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $hasilPeringkat = $promethee->calculate(
            $validator->validated()['alternatives'],
            Promethee::skinCriteria(),
        );

        return response()->json([
            'status' => 'success',
            'rekomendasi' => $hasilPeringkat,
        ]);
    }
}
