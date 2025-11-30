<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    public function index()
    {
        $schoolYears = SchoolYear::withCount('applications')
            ->orderBy('created_at', 'desc')
            ->get();
        $currentActive = SchoolYear::active()->first();
        
        return view('admin.school-years.index', compact('schoolYears', 'currentActive'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_year' => [
                'required', 
                'string', 
                'unique:school_years,school_year',
                'regex:/^\d{4}-\d{4}$/'
            ],
            'application_deadline' => 'required|date|after:today'
        ]);

        // Validate school year format (e.g., "2025-2026")
        if (!SchoolYear::validateSchoolYearFormat($request->school_year)) {
            return redirect()->back()
                ->with('error', 'School year must be in format: YYYY-YYYY (e.g., 2025-2026)');
        }

        // Validate that the second year is greater than the first
        $years = explode('-', $request->school_year);
        if ($years[1] <= $years[0]) {
            return redirect()->back()
                ->with('error', 'End year must be greater than start year');
        }

        // Deactivate all other school years
        SchoolYear::where('is_active', true)->update(['is_active' => false]);

        SchoolYear::create([
            'school_year' => $request->school_year,
            'application_deadline' => $request->application_deadline,
            'is_active' => true
        ]);

        return redirect()->route('admin.application.periods')
            ->with('success', 'School year ' . $request->school_year . ' created and activated successfully!');
    }

    public function update(Request $request, SchoolYear $schoolYear)
    {
        $request->validate([
            'application_deadline' => 'required|date'
        ]);

        $schoolYear->update([
            'application_deadline' => $request->application_deadline
        ]);

       return redirect()->route('admin.application.periods')
    ->with('success', 'Deadline updated successfully for ' . $schoolYear->school_year . '!');
    }

    public function activate(SchoolYear $schoolYear)
    {
        // Deactivate all other school years
        SchoolYear::where('is_active', true)->update(['is_active' => false]);
        
        $schoolYear->update(['is_active' => true]);

        return redirect()->route('admin.application.periods')
            ->with('success', $schoolYear->school_year . ' school year activated successfully!');
    }

    public function destroy(SchoolYear $schoolYear)
    {
        if ($schoolYear->is_active) {
            return redirect()->back()
                ->with('error', 'Cannot delete active school year! Deactivate it first.');
        }

        if ($schoolYear->applications()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete school year with applications!');
        }

        $schoolYear->delete();

        return redirect()->route('admin.application.periods')
            ->with('success', 'School year deleted successfully!');
    }

    public function closeApplications(SchoolYear $schoolYear)
    {
        $schoolYear->update(['is_active' => false]);

        return redirect()->route('admin.application.periods')
            ->with('success', 'Applications closed for ' . $schoolYear->school_year . '!');
    }
}