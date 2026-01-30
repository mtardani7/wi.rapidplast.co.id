<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantAuthController extends Controller
{

    public function showNikForm()
    {
        return view('participant.nik');
    }

    public function submitNik(Request $request)
    {
        $request->validate([
            'nik' => ['required', 'string', 'max:50'],
        ]);

        $nik = trim($request->nik);

        $participant = Participant::where('nik', $nik)->first();
        if ($participant) {
            session([
                'participant_id'   => $participant->id,
                'participant_nik'  => $participant->nik,
                'participant_name' => $participant->name, // ✅ FIX
                'participant_plan' => $participant->plan, // ✅ FIX
            ]);

            return redirect()->route('wi.index');
        }

        session([
            'pending_nik' => $nik,
        ]);

        return redirect()->route('participant.register.form');
    }

    public function showRegisterForm()
    {
        if (!session('pending_nik')) {
            return redirect()->route('nik.form');
        }

        $plans = ['rx00','rx01','rx02','rx03','rx04','rx05','rx06'];

        return view('participant.register', [
            'nik'   => session('pending_nik'),
            'plans' => $plans,
        ]);
    }

    public function submitRegister(Request $request)
    {
        if (!session('pending_nik')) {
            return redirect()->route('nik.form');
        }

        $plans = ['rx00','rx01','rx02','rx03','rx04','rx05','rx06'];

        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'plan' => ['required', 'in:' . implode(',', $plans)],
        ]);

        $nik = session('pending_nik');
        $participant = Participant::firstOrCreate(
            ['nik' => $nik],
            [
                'name' => $request->name,
                'plan' => $request->plan,
            ]
        );
        if (!$participant->name) {
            $participant->update([
                'name' => $request->name,
                'plan' => $request->plan,
            ]);
        }
        session()->forget('pending_nik');
        session([
            'participant_id'   => $participant->id,
            'participant_nik'  => $participant->nik,
            'participant_name' => $participant->name, // ✅ FIX
            'participant_plan' => $participant->plan, // ✅ FIX
        ]);

        return redirect()->route('wi.index');
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'participant_id',
            'participant_nik',
            'participant_name',
            'participant_plan',
            'pending_nik',
        ]);

        return redirect()->route('nik.form');
    }
}
