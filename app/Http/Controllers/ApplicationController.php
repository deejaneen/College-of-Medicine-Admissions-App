<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeSchoolYear = SchoolYear::active()->first();
    
        return inertia('ApplicantIndex', [
            'deadline' => $activeSchoolYear ? $activeSchoolYear->application_deadline : null,
        ]);
    }

    // public function form(Application $application)
    // {
    //     $user = Auth::user();

    //     return inertia('Application', [
    //         'activeEmail' => $user ? $user->email : null,
    //     ]);
    // }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', "Logged out successfully!");
    }

    public function store(Request $request)
    {
        // Check if application period is open
        $activeSchoolYear = SchoolYear::active()->first();
        
        if (!$activeSchoolYear) {
            return redirect()->route('applications.create')
                ->with('error', 'No active application period found.');
        }
        
        if (!$activeSchoolYear->is_open) {
            return redirect()->route('applications.create')
                ->with('error', 'Application period for ' . $activeSchoolYear->school_year . ' has closed!');
        }
        $user = Auth::user();
        // Prevent duplicate applications for the same user and school year
        $existingApplication = Application::where('user_id', $user->id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->first();

        if ($existingApplication) {
            return back()
                ->with('error', 'You have already submitted an application for the ' . $activeSchoolYear->school_year . ' school year.')
                ->withInput();
        }
        $rules = [
            // Step 1: Personal Information
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'lastName' => 'required|string|max:255',
            'emailAddress' => [
                'required',
                'email',
                Rule::unique('applications', 'email_address')
                    ->where('school_year_id', $activeSchoolYear->id),
            ],
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
            'indigenousGroup' => 'required|in:YES,NO,yes,no',
            'indigenousGroupSpecify' => 'nullable|required_if:indigenousGroup,YES|required_if:indigenousGroup,yes|string|max:255',
            'physicalDisability' => 'required|in:YES,NO,yes,no',
            'physicalDisabilitySpecify' => 'nullable|required_if:physicalDisability,YES|required_if:physicalDisability,yes|string|max:255',
            'communityHealthVolunteer' => 'required|in:YES,NO,yes,no',
            'communityHealthOrganization' => 'nullable|required_if:communityHealthVolunteer,YES|required_if:communityHealthVolunteer,yes|string|max:255',

            // Step 2: Bachelor's Degree
            'bachelorsDegree' => 'required|string|max:255',
            'nameOfSchoolBachelorsDegree' => 'required|string|max:255',
            'gwaforBachelorsDegree' => 'required|numeric',
            'bachelorsTranscriptOfRecords' => 'required|array|min:1',
            'bachelorsTranscriptOfRecords.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',

            // Graduate Studies
            'graduateStudies' => 'nullable|string|max:255',
            'nameOfSchoolGraduateStudies' => 'nullable|string|max:255',
            'dateOfGraduation' => 'nullable|date',
            'graduateStudiesTranscriptOfRecords' => 'nullable|array',
            'graduateStudiesTranscriptOfRecords.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',

            // Step 3: Family Information
            'fatherFirstName' => 'required|string|max:255',
            'fatherMiddleName' => 'nullable|string|max:255',
            'fatherLastName' => 'required|string|max:255',
            'fatherOccupation' => 'nullable|string|max:255',
            'motherFirstName' => 'required|string|max:255',
            'motherMiddleName' => 'nullable|string|max:255',
            'motherLastName' => 'required|string|max:255',
            'motherOccupation' => 'nullable|string|max:255',
            'guardianFirstName' => 'nullable|string|max:255',
            'guardianMiddleName' => 'nullable|string|max:255',
            'guardianLastName' => 'nullable|string|max:255',
            'guardianOccupation' => 'nullable|string|max:255',
            'combinedAnnualIncomeOfFamily' => 'required|string',

            // Step 4: NMAT Information
            'nmatNationalMedicalAdmissionTestScore' => 'required|integer',
            'nmatNationalMedicalAdmissionTestCertification' => 'required|array|min:1',
            'nmatNationalMedicalAdmissionTestCertification.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'dateOfNmatNationalMedicalAdmissionTestExamination' => 'required|date',

            // Step 5: Essay
            'whyChooseSouthernLuzonStateUniversity' => 'required|string|min:50',
        ];

        // Custom error messages
        $messages = [
            'emailAddress.unique' => 'An application has already been submitted with this email address. Each email can only submit one application.',
            'emailAddress.required' => 'Email address is required.',
            'emailAddress.email' => 'Please enter a valid email address.',
        ];

        $validated = $request->validate($rules, $messages);

        // Handle file uploads
        $filePaths = [];
        
        $fileFields = [
            'bachelorsTranscriptOfRecords',
            'graduateStudiesTranscriptOfRecords',
            'nmatNationalMedicalAdmissionTestCertification'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $paths = [];
                foreach ($request->file($field) as $file) {
                    $path = $file->store("applications/{$field}", 'public');
                    $paths[] = $path;
                }
                $validated[$field] = $paths;
            }
        }

        // MAP camelCase to snake_case for database
        $applicationData = [
            // Personal Information
            'user_id' => $user->id,
            'email_address' => $validated['emailAddress'],
            'last_name' => $validated['lastName'],
            'first_name' => $validated['firstName'],
            'middle_name' => $validated['middleName'] ?? null,
            'date_of_birth' => $validated['dateOfBirth'],
            'citizenship' => $validated['citizenship'],
            'permanent_home_address' => $validated['permanentHomeAddress'],
            'municipality' => $validated['municipality'],
            'province' => $validated['province'],
            'zip_code' => $validated['zipCode'],
            'active_gmail_account' => $validated['activeGmailAccount'],
            'cellphone_number' => $validated['cellphoneNumber'],
            'civil_status' => $validated['civilStatus'],
            'sex' => $validated['sex'],
            'religion' => $validated['religion'],
            'indigenous_group' => $validated['indigenousGroup'],
            'indigenous_group_specify' => $validated['indigenousGroupSpecify'] ?? null,
            'physical_disability' => $validated['physicalDisability'],
            'physical_disability_specify' => $validated['physicalDisabilitySpecify'] ?? null,
            'community_health_volunteer' => $validated['communityHealthVolunteer'],
            'community_health_organization' => $validated['communityHealthOrganization'] ?? null,

            // Educational Background
            'bachelors_degree' => $validated['bachelorsDegree'],
            'name_of_school_bachelors_degree' => $validated['nameOfSchoolBachelorsDegree'],
            'gwa_for_bachelors_degree' => $validated['gwaforBachelorsDegree'],
            'bachelors_transcript_of_records' => $validated['bachelorsTranscriptOfRecords'] ?? [],

            'graduate_studies' => $validated['graduateStudies'] ?? null,
            'name_of_school_graduate_studies' => $validated['nameOfSchoolGraduateStudies'] ?? null,
            'date_of_graduation' => $validated['dateOfGraduation'] ?? null,
            'graduate_studies_transcript_of_records' => $validated['graduateStudiesTranscriptOfRecords'] ?? [],

            // Family Information
            'father_first_name' => $validated['fatherFirstName'],
            'father_middle_name' => $validated['fatherMiddleName'] ?? null,
            'father_last_name' => $validated['fatherLastName'],
            'father_occupation' => $validated['fatherOccupation'] ?? null,
            'mother_first_name' => $validated['motherFirstName'],
            'mother_middle_name' => $validated['motherMiddleName'] ?? null,
            'mother_last_name' => $validated['motherLastName'],
            'mother_occupation' => $validated['motherOccupation'] ?? null,
            'guardian_first_name' => $validated['guardianFirstName'] ?? null,
            'guardian_middle_name' => $validated['guardianMiddleName'] ?? null,
            'guardian_last_name' => $validated['guardianLastName'] ?? null,
            'guardian_occupation' => $validated['guardianOccupation'] ?? null,
            'combined_annual_income_of_family' => $validated['combinedAnnualIncomeOfFamily'],

            // NMAT Information
            'nmat_score' => $validated['nmatNationalMedicalAdmissionTestScore'],
            'nmat_certification' => $validated['nmatNationalMedicalAdmissionTestCertification'] ?? [],
            'date_of_nmat_examination' => $validated['dateOfNmatNationalMedicalAdmissionTestExamination'],

            // Essay
            'why_choose_southern_luzon_state_university' => $validated['whyChooseSouthernLuzonStateUniversity'],

            // System fields
            'school_year_id' => $activeSchoolYear->id,
            'application_status' => 'For Review',
        ];

        try {
            $application = Application::create($applicationData);
            
            return redirect()
                ->route('dashboard')
                ->with('success', 'Application submitted successfully for ' . $activeSchoolYear->school_year . ' school year.');
                
        } catch (\Exception $e) {
            // This will catch any database errors including unique constraint violations
            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), '1062')) {
                return back()
                    ->with('error', 'An application has already been submitted with this email address.')
                    ->withInput();
            }
            
            return back()
                ->with('error', 'Failed to submit application. Please try again.')
                ->withInput();
        }
    }
 public function create()
{
    $user = Auth::user();
    $activeSchoolYear = SchoolYear::active()->first();

    $existingApplication = null;
    if ($activeSchoolYear) {
        $existingApplication = Application::where('user_id', $user->id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->first();
    }

    return inertia('Application', [
        'userEmail' => $user->email,
        'userId' => $user->id,
        'schoolYear' => $activeSchoolYear ? [
            'school_year' => $activeSchoolYear->school_year,
            'application_deadline' => $activeSchoolYear->application_deadline,
        ] : null,
        'isApplicationOpen' => $activeSchoolYear && $activeSchoolYear->is_open,
        'hasSubmitted' => $existingApplication ? true : false,
    ]);
}

}