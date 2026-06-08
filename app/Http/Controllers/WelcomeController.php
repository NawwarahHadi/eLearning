<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class WelcomeController extends Controller
{
    public function index()
    {


        return view('welcome');
    }

}
