<?php

namespace App\Http\Controllers;

use App\Models\TrialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrialRequestController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:trial_requests,email|unique:users,email',
            'plan' => 'required|in:basic,premium,enterprise',
        ], [
            'email.unique' => 'Este email ya tiene una solicitud pendiente o ya está registrado.',
            'plan.in' => 'El plan seleccionado no es válido.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $trialRequest = TrialRequest::create([
            'name' => $request->name,
            'email' => $request->email,
            'plan' => $request->plan,
            'status' => 'pending',
        ]);

        return redirect()->route('plans')
            ->with('success', '¡Solicitud enviada! Te notificaremos por email cuando sea aprobada.');
    }
}
