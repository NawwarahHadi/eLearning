<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ClassSchedule;
use App\Models\CreateClass;
use App\Models\Day;
use App\Models\Language;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ClassManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $listClass = CreateClass::with(['schedules', 'subject'])
            ->where('tutor_id', Auth::id())
            ->get();

        return view('class-management.index', compact('listClass'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $data = [
            'class'        => new CreateClass(),
            'listSubject'  => Subject::all(),
            'listCategory' => Category::all(),
            'listDay'      => Day::all(),
            'listLanguage' => Language::all(),
        ];

        return view('class-management.create-class', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'subject_id'           => ['required', 'exists:subjects,id'],
            'class_schedules'      => ['required', 'array'],
            'category_code'        => ['required'],
            'language_code'        => ['required'],
            'learning_objective' => ['required'],
            'class_schedules.*.day'        => ['required'],
            'class_schedules.*.start_time' => ['required'],
            'class_schedules.*.end_time'   => ['required'],
            // 'fee'                  => ['required', 'numeric'], // Still validated if needed
            'max_students'         => ['required', 'integer'],
        ]);

        // 1. Calculate Total Hours Per Week
        $totalHours = 0;
        foreach ($request->class_schedules as $schedule) {
            $start = \Carbon\Carbon::parse($schedule['start_time']);
            $end = \Carbon\Carbon::parse($schedule['end_time']);

            // Calculate difference in hours (e.g., 2:00 PM to 3:30 PM = 1.5)
            $totalHours += $start->diffInMinutes($end) / 60;
        }

        // 2. Create the Class with calculated hours
        $class = CreateClass::create([
            'subject_id'           => $request['subject_id'],
            'tutor_id'             => Auth::id(), // Automatically uses logged-in tutor
            'category_code'        => $request['category_code'],
            'language_code'        => $request['language_code'],
            'learning_objective' => $request['learning_objective'],
            // 'fee'                  => $request['fee'],
            'max_students'         => $request['max_students'],
            'hours_per_week'       => $totalHours, // SAVING DYNAMIC HOURS
        ]);

        // 3. Save Schedules
        foreach ($request->class_schedules as $schedule) {
            ClassSchedule::create([
                'class_id'   => $class->id,
                'day'        => $schedule['day'],
                'start_time' => $schedule['start_time'],
                'end_time'   => $schedule['end_time'],
            ]);
        }

        // 4. Alert & Redirect
        if ($class) {
            Alert::success('Success', 'Class created successfully! Total hours/week: ' . $totalHours);
        } else {
            Alert::error('Error', 'Information class cannot be saved');
        }

        return redirect()->route('class.index');
    }
    /**
     * Show the form for editing the specified class.
     */
    public function edit(string $id)
    {
        $class = CreateClass::with('schedules')->findOrFail($id);

        $data = [
            'class'        => $class,
            'listSubject'  => Subject::all(),
            'listCategory' => Category::all(),
            'listDay'      => Day::all(),
            'listLanguage' => Language::all(),
        ];

        return view('class-management.edit', $data);
    }

    /**
     * Update the specified class in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Find the existing class
        $class = CreateClass::findOrFail($id);

        // 2. Validate (identical to store)
        $request->validate([
            'subject_id'           => ['required', 'exists:subjects,id'],
            'class_schedules'      => ['required', 'array'],
            'category_code'        => ['required'],
            'language_code'        => ['required'],
            'learning_objective' => ['required'],
            'fee'                  => ['required', 'numeric'],
            'max_students'         => ['required', 'integer'],
        ]);

        // 3. UPDATE the existing record (Don't use 'create')
        $class->update([
            'subject_id'           => $request->subject_id,
            'category_code'        => $request->category_code,
            'language_code'        => $request->language_code,
            'learning_objective' => $request->learning_objective,
            'fee'                  => $request->fee,
            'max_students'         => $request->max_students,
        ]);

        // 4. Refresh Schedules (Delete old and replace)
        $class->schedules()->delete();
        foreach ($request->class_schedules as $schedule) {
            ClassSchedule::create([
                'class_id'   => $class->id,
                'day'        => $schedule['day'],
                'start_time' => $schedule['start_time'],
                'end_time'   => $schedule['end_time'],
            ]);
        }

        return redirect()->route('class.index')->with('success', 'Class updated successfully!');
    }
    /**
     * Remove the specified class from storage.
     */
    public function destroy(string $id)
    {
        $class = CreateClass::findOrFail($id);
        $class->schedules()->delete(); // Clean up child records
        $class->delete();

        return redirect()->route('class.index')->with('success', 'Class deleted successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $class = CreateClass::with(['subject', 'students'])->findOrFail($id);

        return view('class-management.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     */

}
