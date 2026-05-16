<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch students who have active enrollments (as seen in your database sidebar)
        $students = User::whereHas('enrollments')
                    ->withCount('enrollments')
                    ->get();

        return view('payment.index', compact('students'));
    }

    public function generateMonthlyBill(int $student_id)
    {
        // 1. Get all enrollments with the associated class data
        $enrollments = Enrollment::with('class')->where('student_id', $student_id)->get();

        $hourlyRate = 50.00; // Updated to your new rate
        $weeksInMonth = 4;
        $totalMonthlyHours = 0;

        foreach ($enrollments as $enrollment) {
            // Dynamically get hours from the CreateClass model
            // This replaces the ($enrollment->class_id == 'math_id') check
            $hoursPerWeek = $enrollment->class->hours_per_week ?? 1.0;

            $totalMonthlyHours += ($hoursPerWeek * $weeksInMonth);
        }

        $monthlyTotal = $totalMonthlyHours * $hourlyRate;

        // 2. Create the invoice
        Payment::create([
            'student_id' => $student_id,
            // Using 'addMonth' if you are billing for the upcoming month
            'billing_month' => now()->addMonth()->format('F Y'),
            'total_amount' => $monthlyTotal,
            'status' => 'unpaid'
        ]);

        return back()->with('success', 'Monthly invoice generated based on enrolled class hours!');
    }



   // PaymentController.php

    public function generateAllMonthlyBills()
    {
        // 1. Get unique student IDs from your enrollment table
        $studentIds = Enrollment::distinct()->pluck('student_id');
        $billingMonth = now()->addMonth()->format('F Y');
        $hourlyRate = 50.00; // Your standardized fee

        foreach ($studentIds as $id) {
            // Prevent duplicate billing records
            $exists = Payment::where('student_id', $id)
                            ->where('billing_month', $billingMonth)
                            ->exists();

            if (!$exists) {
                // 2. Use the 'class' relationship from your Enrollment model
                $enrollments = Enrollment::with('class')->where('student_id', $id)->get();
                $totalMonthlyHours = 0;

                foreach ($enrollments as $enrollment) {
                    // Accessing hours_per_week from your CreateClass model dynamically
                    $hours = $enrollment->class->hours_per_week ?? 1.0;
                    $totalMonthlyHours += ($hours * 4); // Calculate for 4 weeks
                }

                // 3. Save the total amount for all classes combined
                Payment::create([
                    'student_id' => $id,
                    'billing_month' => $billingMonth,
                    'total_amount' => $totalMonthlyHours * $hourlyRate,
                    'status' => 'unpaid'
                ]);
            }
        }

        return back()->with('success', 'Monthly invoices generated for all enrolled classes.');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function processPayment(Request $request, int $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update([
            'payment_method' => $request->method,
            'transaction_id' => $request->transaction_id, // Add this line
            'status' => 'paid'
        ]);
        return back()->with('success', 'Payment successful via ' . strtoupper($request->method));
    }

    /**
     * Store a newly created resource in storage.
     */
   // In PaymentController.php

    public function history()
    {
        // If Admin, see all. If Student, see only theirs.
        if (Auth::user()->role == 'admin') {
            $payments = Payment::with('student')->latest()->get();
        } else {
            $payments = Payment::where('student_id', auth::id())->latest()->get();
        }

        $data =
        [
            'payments'=>$payments,
        ];

        return view('payment.history', $data);
    }

    public function showStudentTotalBill()
    {
        // Sum the 'fee' from the 'class' table for all of this student's approved enrollments
        $totalBill = Enrollment::where('student_id', Auth::id())
            ->where('status', 'approved')
            ->join('class', 'enrollments.class_id', '=', 'class.id')
            ->sum('class.fee');

        return view('payments.index', compact('totalBill'));
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
