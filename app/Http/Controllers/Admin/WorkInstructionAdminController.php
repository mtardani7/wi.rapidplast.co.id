<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkInstruction;
use Illuminate\Http\Request;

class WorkInstructionAdminController extends Controller
{
    public function index()
    {
        $wis = WorkInstruction::withCount('videos')->latest()->get();
        return view('admin.wi.index', compact('wis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        WorkInstruction::create([
            'title' => $request->title,
            'description' => $request->description,
            'is_published' => $request->boolean('is_published'),
        ]);

        return back()->with('success', 'Work Instruction berhasil ditambahkan.');
    }

    public function update(Request $request, WorkInstruction $wi)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $wi->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_published' => $request->boolean('is_published'),
        ]);

        return back()->with('success', 'Work Instruction berhasil diupdate.');
    }

    public function destroy(WorkInstruction $wi)
    {
        $wi->delete();
        return back()->with('success', 'Work Instruction berhasil dihapus.');
    }
}
