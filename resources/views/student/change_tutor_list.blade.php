@extends('layouts.app')
@section('content')
<div class="container">
    <h3 class="fw-bold mb-5">Change Tutor for {{ $class->subject->name }}</h3>
    <p class="text-muted">Select a new tutor to replace your current one. Your current tutor is hidden from this list.</p>

    <div class="row g-6">
        @foreach($tutors as $tutor)
            <div class="col-md-4">
                <div class="card shadow-sm border-primary border-dashed">
                    <div class="card-body text-center">
                        <div class="symbol symbol-60px symbol-circle mb-3">
                            <div class="symbol-label fs-2 bg-light-primary text-primary">{{ substr($tutor->name, 0, 1) }}</div>
                        </div>
                        <h5 class="fw-bold">{{ $tutor->name }}</h5>

                        <a href="{{ route('enrollment.tutor-profile', ['class_id' => $class->id, 'tutor_id' => $tutor->id]) }}"
                           class="btn btn-sm btn-primary mt-3">
                           View Profile & Request Swap
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
