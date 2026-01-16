<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FacultyController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->isProdi()) {
                return redirect()->route('prodi.dashboard');
            }
            return $next($request);
        });
    }

    // Display list of faculties
    public function index()
    {
        $faculties = Faculty::with('studyPrograms')->paginate(10);
        return view('admin.faculties.index', compact('faculties'));
    }

    // Show form to create new faculty
    public function createFaculty()
    {
        return view('admin.faculties.create');
    }

    // Store new faculty
    public function storeFaculty(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:faculties,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Faculty::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.faculties.index')->with('success', 'Faculty created successfully.');
    }

    // Show form to edit faculty
    public function editFaculty($id)
    {
        $faculty = Faculty::findOrFail($id);
        return view('admin.faculties.edit', compact('faculty'));
    }

    // Update faculty
    public function updateFaculty(Request $request, $id)
    {
        $faculty = Faculty::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:faculties,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $faculty->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.faculties.index')->with('success', 'Faculty updated successfully.');
    }

    // Delete faculty
    public function deleteFaculty($id)
    {
        $faculty = Faculty::findOrFail($id);

        // Check if faculty has associated study programs
        if ($faculty->studyPrograms()->count() > 0) {
            return redirect()->route('admin.faculties.index')->with('error', 'Cannot delete faculty with associated study programs.');
        }

        $faculty->delete();
        return redirect()->route('admin.faculties.index')->with('success', 'Faculty deleted successfully.');
    }

    // Display list of study programs
    public function studyProgramsIndex()
    {
        $studyPrograms = StudyProgram::with('faculty')->paginate(10);
        $faculties = Faculty::all();
        return view('admin.study-programs.index', compact('studyPrograms', 'faculties'));
    }

    // Show form to create new study program
    public function createStudyProgram()
    {
        $faculties = Faculty::all();
        return view('admin.study-programs.create', compact('faculties'));
    }

    // Store new study program
    public function storeStudyProgram(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'passing_grade' => 'required|integer|min:0|max:677',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        StudyProgram::create([
            'name' => $request->name,
            'level' => $request->level,
            'passing_grade' => $request->passing_grade,
            'faculty_id' => $request->faculty_id,
        ]);

        return redirect()->route('admin.study-programs.index')->with('success', 'Study Program created successfully.');
    }

    // Show form to edit study program
    public function editStudyProgram($id)
    {
        $studyProgram = StudyProgram::findOrFail($id);
        $faculties = Faculty::all();
        return view('admin.study-programs.edit', compact('studyProgram', 'faculties'));
    }

    // Update study program
    public function updateStudyProgram(Request $request, $id)
    {
        $studyProgram = StudyProgram::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'passing_grade' => 'required|integer|min:0|max:677',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $studyProgram->update([
            'name' => $request->name,
            'level' => $request->level,
            'passing_grade' => $request->passing_grade,
            'faculty_id' => $request->faculty_id,
        ]);

        return redirect()->route('admin.study-programs.index')->with('success', 'Study Program updated successfully.');
    }

    // Delete study program
    public function deleteStudyProgram($id)
    {
        $studyProgram = StudyProgram::findOrFail($id);
        $studyProgram->delete();

        return redirect()->route('admin.study-programs.index')->with('success', 'Study Program deleted successfully.');
    }
}
