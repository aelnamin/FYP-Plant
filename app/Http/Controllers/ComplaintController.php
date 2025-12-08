<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        return Complaint::with(['buyer', 'admin'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'buyer_id'    => 'required|exists:users,id',
            'description' => 'required|string',
        ]);

        return Complaint::create($validated);
    }

    public function show($id)
    {
        return Complaint::with(['buyer', 'admin'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'buyer_id'    => 'sometimes|exists:users,id',
            'handled_by'  => 'sometimes|exists:admins,id',
            'description' => 'sometimes|string',
            'status'      => 'sometimes|string'
        ]);

        $complaint->update($validated);

        return $complaint;
    }

    public function destroy($id)
    {
        return Complaint::destroy($id);
    }
}
