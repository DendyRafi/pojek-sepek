<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWelcomeInputsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WelcomeInputController extends Controller
{
    public function store(StoreWelcomeInputsRequest $request): Response
    {
        $request->session()->put('welcome_inputs', [
            'alternatives' => $request->validated('alternatives'),
        ]);

        return response()->noContent();
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->session()->forget('welcome_inputs');

        return response()->json(['status' => 'cleared']);
    }
}
