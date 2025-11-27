<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ApplicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return inertia('ApplicantIndex', [
            'activeUser' => $user ? $user->name : null,
        ]);
    }

    public function form(Application $application)
    {
        $user = Auth::user();

        return inertia('Application', [
            'activeEmail' => $user ? $user->email : null,
        ]);
    }
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', "Logged out successfully!");
    }
   public function store(Request $request)
    {
        $rules = [
            // Step 1: Personal Information
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'lastName' => 'required|string|max:255',
            'emailAddress' => 'required|email',
            'verifyEmailAddress' => 'required|same:emailAddress',
            'dateOfBirth' => 'required|date',
            'citizenship' => 'required|string|max:255',
            'permanentHomeAddress' => 'required|string',
            'municipality' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'zipCode' => 'required|string|max:20',
            'activeGmailAccount' => 'required|email',
            'cellphoneNumber' => 'required|string|max:30',
            'civilStatus' => 'required|string|max:50',
            'sex' => 'required|string|max:20',
            'religion' => 'required|string|max:100',
            'indigenousGroup' => 'required|in:yes,no',
            'indigenousGroupSpecify' => 'nullable|required_if:indigenousGroup,yes|string|max:255',
            'physicalDisability' => 'required|in:yes,no',
            'physicalDisabilitySpecify' => 'nullable|required_if:physicalDisability,yes|string|max:255',

            // Step 2: Bachelor’s Degree
            'bachelorsDegree' => 'required|string|max:255',
            'nameOfSchoolBachelorsDegree' => 'required|string|max:255',
            'gwaforBachelorsDegree' => 'required|numeric',
            'bachelorsTranscriptOfRecords' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',

            // Graduate Studies (Optional)
            'graduateStudies' => 'nullable|string|max:255',
            'nameOfSchoolGraduateStudies' => 'nullable|string|max:255',
            'dateOfGraduation' => 'nullable|date',
            'graduateStudiesTranscriptOfRecords' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',

            // Step 3: Family Information
            'fatherFirstName' => 'required|string|max:255',
            'fatherMiddleName' => 'nullable|string|max:255',
            'fatherLastName' => 'required|string|max:255',
            'fatherOccupation' => 'nullable|string|max:255',

            'motherFirstName' => 'required|string|max:255',
            'motherMiddleName' => 'nullable|string|max:255',
            'motherLastName' => 'required|string|max:255',
            'motherOccupation' => 'nullable|string|max:255',

            // Guardian (optional)
            'guardianFirstName' => 'nullable|string|max:255',
            'guardianMiddleName' => 'nullable|string|max:255',
            'guardianLastName' => 'nullable|string|max:255',
            'guardianOccupation' => 'nullable|string|max:255',

            'combinedAnnualIncomeOfFamily' => 'required|string',
            'communityHealthVolunteer' => 'required|in:yes,no',
            'communityHealthOrganization' => 'nullable|required_if:communityHealthVolunteer,yes|string|max:255',

            // Step 4: NMAT Information
            'nmatNationalMedicalAdmissionTestScore' => 'nullable|integer',
            'nmatNationalMedicalAdmissionTestCertification' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'dateOfNmatNationalMedicalAdmissionTestExamination' => 'nullable|date',

            // Step 5: Essay
            'whyChooseSouthernLuzonStateUniversity' => 'required|string|min:50',

            'applicationStatus' => 'required|string|max:255',

        ];

        $validated = $request->validate($rules);

        // File Handling
        if ($request->hasFile('bachelorsTranscriptOfRecords')) {
            $validated['bachelorsTranscriptOfRecords'] =
                $request->file('bachelorsTranscriptOfRecords')->store('applications/tor_bachelors', 'public');
        }

        if ($request->hasFile('graduateStudiesTranscriptOfRecords')) {
            $validated['graduateStudiesTranscriptOfRecords'] =
                $request->file('graduateStudiesTranscriptOfRecords')->store('applications/tor_graduate', 'public');
        }

        if ($request->hasFile('nmatNationalMedicalAdmissionTestCertification')) {
            $validated['nmatNationalMedicalAdmissionTestCertification'] =
                $request->file('nmatNationalMedicalAdmissionTestCertification')->store('applications/nmat', 'public');
        }

        $validated['applicationStatus'] = 'For Review';

        Application::create($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Application submitted successfully.');
    }


    public function create()
    {
        // If user is logged in via Google or normal login
       $userEmail = Auth::check() ? Auth::user()->email : null;

        return Inertia::render('Application', [
            'userEmail' => $userEmail,
        ]);
    }
}
