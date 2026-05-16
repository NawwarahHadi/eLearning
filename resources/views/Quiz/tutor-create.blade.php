@extends('layouts.app')

@section('content')
<div class="container-xxl">
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-primary py-5">
            <h3 class="card-title text-white fw-bolder">Create New Quiz</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('quiz.store') }}" method="POST">
                @csrf
                {{-- Hidden Fields to link back to the Class and Material --}}
                <input type="hidden" name="class_id" value="{{ $class_id }}">
                <input type="hidden" name="learning_material_id" value="{{ $material->id }}">


                <div class="mb-8">
                    <label class="form-label fw-bold">Quiz Title</label>
                    <input type="text" name="title" class="form-control form-control-solid" placeholder="e.g. Chapter 1: Introduction to Grammar" required>
                </div>

                <hr class="my-10">

                {{-- Questions Container --}}
                <div id="questions-wrapper">
                    <div class="card border border-dashed p-5 mb-5 question-item" data-index="0">
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="fw-bold text-primary">Question #1</h5>
                        </div>

                        <textarea name="questions[0][text]" class="form-control mb-4" placeholder="Enter your question here..." required></textarea>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">A</span>
                                    <input type="text" name="questions[0][a]" class="form-control" placeholder="Option A" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">B</span>
                                    <input type="text" name="questions[0][b]" class="form-control" placeholder="Option B" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">C</span>
                                    <input type="text" name="questions[0][c]" class="form-control" placeholder="Option C" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">D</span>
                                    <input type="text" name="questions[0][d]" class="form-control" placeholder="Option D" required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-bold text-success">Select Correct Answer</label>
                            <select name="questions[0][correct]" class="form-select border-success">
                                <option value="A">Option A</option>
                                <option value="B">Option B</option>
                                <option value="C">Option C</option>
                                <option value="D">Option D</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-light-primary mb-10" onclick="addQuestion()">
                    <i class="ki-duotone ki-plus fs-2"></i> Add Another Question
                </button>

                <div class="separator my-5"></div>

                <button type="submit" class="btn btn-primary w-100 fs-3">
                    <i class="ki-duotone ki-send fs-2"></i> Publish Quiz
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    let questionCount = 1;

    function addQuestion() {
        const wrapper = document.getElementById('questions-wrapper');
        const newQuestion = `
            <div class="card border border-dashed p-5 mb-5 question-item" data-index="${questionCount}">
                <div class="d-flex justify-content-between mb-4">
                    <h5 class="fw-bold text-primary">Question #${questionCount + 1}</h5>
                </div>
                <textarea name="questions[${questionCount}][text]" class="form-control mb-4" placeholder="Enter your question here..." required></textarea>
                <div class="row g-3">
                    <div class="col-md-6"><input type="text" name="questions[${questionCount}][a]" class="form-control" placeholder="Option A" required></div>
                    <div class="col-md-6"><input type="text" name="questions[${questionCount}][b]" class="form-control" placeholder="Option B" required></div>
                    <div class="col-md-6"><input type="text" name="questions[${questionCount}][c]" class="form-control" placeholder="Option C" required></div>
                    <div class="col-md-6"><input type="text" name="questions[${questionCount}][d]" class="form-control" placeholder="Option D" required></div>
                </div>
                <div class="mt-4">
                    <label class="form-label fw-bold text-success">Select Correct Answer</label>
                    <select name="questions[${questionCount}][correct]" class="form-select border-success">
                        <option value="A">Option A</option>
                        <option value="B">Option B</option>
                        <option value="C">Option C</option>
                        <option value="D">Option D</option>
                    </select>
                </div>
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', newQuestion);
        questionCount++;
    }
</script>
@endsection
