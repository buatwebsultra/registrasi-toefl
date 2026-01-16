<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdiController extends Controller
{
    /**
     * Display the prodi dashboard with participants from their study program.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ensure user has a study program assigned
        if (!$user->study_program_id) {
            return redirect()->route('home')->with('error', 'Akun Anda tidak memiliki Program Studi yang ditugaskan.');
        }

        $studyProgram = $user->studyProgram;
        
        // Query participants from this study program, showing only the latest record for each NIM (Score terupdate)
        $query = Participant::whereIn('id', function($q) use ($user) {
            $q->selectRaw('MAX(id)')
              ->from('participants')
              ->where('study_program_id', $user->study_program_id)
              ->groupBy('nim');
        });

        // Filter by NIM if provided
        if ($request->filled('search_nim')) {
            $query->where('nim', 'like', '%' . $request->search_nim . '%');
        }

        // Filter by Name if provided
        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        $participants = $query->with(['schedule'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('prodi.dashboard', compact('participants', 'studyProgram'));
    }
}
