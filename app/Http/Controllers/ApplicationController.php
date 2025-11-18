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

        return inertia('Application', [
            'activeEmail' => $user ? $user->email : null,
        ]);
    }

    public function show(Application $application)
    {
        return inertia('Show');
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
            'emailAddress' => 'required|email',
            'verifyEmailAddress' => 'required|same:emailAddress',
            'lastName' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
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
            'memberOfReligiousGroup' => 'required|in:yes,no',
            'isMemberOfReligiousGroup' => 'nullable|string|max:255',
            'physicalDisability' => 'required|in:yes,no',
            'hasPhysicalDisability' => 'nullable|string|max:255',
            'bachelorsDegree' => 'required|string|max:255',
            'nameOfSchoolBachelorsDegree' => 'required|string|max:255',
            'gwaforBachelorsDegree' => 'required|numeric',
            'trascriptOfRecords' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'graduateStudies' => 'nullable|string|max:255',
            'graduateStudiesSchool' => 'nullable|string|max:255',
            'dateOrPossibleDateOfGrad' => 'nullable|string|max:100',
            'trascriptOfRecordsGraduate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'fatherLastName' => 'required|string|max:255',
            'fatherMiddleName' => 'nullable|string|max:255',
            'fatherFirstName' => 'required|string|max:255',
            'fatherOccupation' => 'nullable|string|max:255',
            'motherLastName' => 'required|string|max:255',
            'motherMiddleName' => 'nullable|string|max:255',
            'motherFirstName' => 'required|string|max:255',
            'motherOccupation' => 'nullable|string|max:255',
            'guardianLastName' => 'nullable|string|max:255',
            'guardianMiddleName' => 'nullable|string|max:255',
            'guardianFirstName' => 'nullable|string|max:255',
            'guardianOccupation' => 'nullable|string|max:255',
            'combinedAnnualIncomeOfFamily' => 'required|string',
            'nmatNationalMedicalAdmissionTestScore' => 'nullable|integer',
            'nmatNationalMedicalAdmissionTestCertification' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'dateOfNmatNationalMedicalAdmissionTestExamination' => 'nullable|date',
            'parentCommunityHealthVolunteer' => 'required|in:yes,no',
            'isParentCommunityHealthVolunteer' => 'nullable|string|max:255',
            'whyChooseSouthernLuzonStateUniversity' => 'required|string|min:50',
        ];

        $validated = $request->validate($rules);

        if ($request->hasFile('trascriptOfRecords')) {
            $validated['trascriptOfRecords'] = $request->file('trascriptOfRecords')->store('applications/tor', 'public');
        }

        if ($request->hasFile('trascriptOfRecordsGraduate')) {
            $validated['trascriptOfRecordsGraduate'] = $request->file('trascriptOfRecordsGraduate')->store('applications/graduate_tor', 'public');
        }

        if ($request->hasFile('nmatNationalMedicalAdmissionTestCertification')) {
            $validated['nmatNationalMedicalAdmissionTestCertification'] = $request->file('nmatNationalMedicalAdmissionTestCertification')->store('applications/nmat', 'public');
        }

        Application::create($validated);

        return redirect()->route('dashboard')->with('success', 'Application submitted successfully.');
    }

    public function create()
{
    // If user is logged in via Google or normal login
    $userEmail = Auth::check() ? Auth::user()->email : '';

    return Inertia::render('Application', [
        'userEmail' => $userEmail, // <-- pass email to Vue
    ]);
}
}
