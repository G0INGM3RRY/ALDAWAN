<?php

namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use App\Models\JobseekerProfile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Employer;

class EmployerProfileController extends Controller
{


    public function complete(){
        $user = Auth::user();
        $profile= $user->employerProfile;
        return view('users.employers.complete', compact('user', 'profile'));
    }
   
    public function update(Request $request)
    { 
        //Validate and save employer profile
        $user = Auth::user();

        $request->validate([
            'company_name' => 'required|string|max:255',
            'street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['company_name', 'street', 'barangay', 'municipality', 'province']);
        
        // Handle file upload
        if ($request->hasFile('company_logo')) {
            $file = $request->file('company_logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('company_logos', $filename, 'public');
            $data['company_logo'] = $filePath;
        }

        $user->employerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

         return redirect()->route('dashboard')->with('success', 'Profile saved!');
    }

    public function edit()
    {
        $user = Auth::user();
        $profile= $user->employerProfile;
        return view('users.employers.edit', compact('user', 'profile'));
    }

    
}
