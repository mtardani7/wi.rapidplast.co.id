<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\ParticipantVideoProgress;
use Illuminate\Http\Request;

class ResultAdminController extends Controller
{
    public function index()
    {
        $rows = ParticipantVideoProgress::query()
            ->with([
                'participant:id,nik,name,plan',
                'video:id,work_instruction_id,title,sort_order',
                'video.workInstruction:id,title'
            ])
            ->orderByDesc('updated_at')
            ->get();

        return view('admin.results.index', compact('rows'));
    }

    public function show(Participant $participant)
    {
        $progress = ParticipantVideoProgress::query()
            ->where('participant_id', $participant->id)
            ->with([
                'video:id,work_instruction_id,title,sort_order',
                'video.workInstruction:id,title'
            ])
            ->orderByDesc('updated_at')
            ->get();

        return view('admin.results.show', compact('participant', 'progress'));
    }
}
