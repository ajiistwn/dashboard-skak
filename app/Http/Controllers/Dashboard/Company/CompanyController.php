<?php

namespace App\Http\Controllers\Dashboard\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class CompanyController extends Controller
{
    //

    public function index()
    {
        $users = User::select('id', 'name', 'job_title', 'image')
            ->whereNotNull('job_title') // hanya user yg punya jabatan
            ->get();

        // dd($users);
        return Inertia::render('company', [
            'team' => $users,
        ]);
    }
}
