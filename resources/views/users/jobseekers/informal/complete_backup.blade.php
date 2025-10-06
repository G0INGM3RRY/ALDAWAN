@extends('layouts.dashboard')

@section('content')
    <h1>Complete Your Profile - Informal Worker</h1>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 text-center">Basic Profile Information</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('jobseeker.informal.complete') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                   
                        <!-- Personal Information -->
                        <div id="section-personal-information">
                            <h4 class="mb-3">Personal Information</h4>
                            
                            <!-- Display job seeker type as readonly -->
                            <div class="mb-3">
                                <label class="form-label">Job Seeker Type</label>
                                <p class="text-muted">Informal Worker - Selected during registration</p>
                                <input type="hidden" name="job_seeker_type" value="informal">
                            </div>
                            
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control w-75" required>
                                @error('first_name')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control w-75">
                            </div>
                            
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control w-75" required>
                                @error('last_name')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="suffix" class="form-label">Suffix</label>
                                <select name="suffix" class="form-control w-75">
                                    <option value="">None</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="birthday" class="form-label">Date of Birth</label>
                                <input type="date" name="birthday" id="birthday" class="form-control w-75">
                            </div>
                            
                            <div class="mb-3">
                                <label for="sex" class="form-label">Sex</label>
                                <select name="sex" class="form-control w-75">
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" name="photo" id="photo" class="form-control w-75">
                                @if(isset($profile) && $profile && $profile->photo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $profile->photo) }}" alt="Current Photo" style="max-width: 200px;">
                                        <small class="text-muted d-block">Current: {{ $profile->photo }}</small>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <label for="civilstatus" class="form-label">Civil Status</label>
                                <select name="civilstatus" class="form-control w-75">
                                    <option value="">Select</option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    <option value="widowed">Widowed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Contact & Location Information -->
                        <div id="section-contact-location">
                            <h4 class="mb-3 mt-4">Contact & Location</h4>
                            
                            <div class="mb-3">
                                <label for="street" class="form-label">Street</label>
                                <input type="text" name="street" id="street" class="form-control w-75">
                            </div>
                            
                            <div class="mb-3">
                                <label for="barangay" class="form-label">Barangay</label>
                                <input type="text" name="barangay" id="barangay" class="form-control w-75">
                            </div>
                            
                            <div class="mb-3">
                                <label for="municipality" class="form-label">Municipality</label>
                                <input type="text" name="municipality" id="municipality" class="form-control w-75">
                            </div>
                            
                            <div class="mb-3">
                                <label for="province" class="form-label">Province</label>
                                <input type="text" name="province" id="province" class="form-control w-75">
                            </div>
                            
                            <div class="mb-3">
                                <label for="contactnumber" class="form-label">Contact Number</label>
                                <input type="text" name="contactnumber" id="contactnumber" class="form-control w-75">
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control w-75">
                            </div>
                        </div>

                        <!-- Basic Work Information -->
                        <div id="section-work-status">
                            <h4 class="mb-3 mt-4">Work Status & Benefits</h4>
                            
                            <div class="mb-3">
                                <label class="form-label">Disability (if any)</label><br>
                                @foreach($disabilities as $disability)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="disabilities[]" 
                                               id="disability_{{ $disability->id }}" value="{{ $disability->id }}">
                                        <label class="form-check-label" for="disability_{{ $disability->id }}">{{ $disability->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">4PS Beneficiary?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_4ps" id="is_4ps" value="yes">
                                    <label class="form-check-label" for="is_4ps">Yes</label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="employmentstatus" class="form-label">Current Employment Status</label>
                                <select name="employmentstatus" id="employmentstatus" class="form-control w-75">
                                    <option value="">Select</option>
                                    <option value="employed">Currently Working</option>
                                    <option value="unemployed">Looking for Work</option>
                                </select>
                            </div>
                        </div>

                        <!-- Basic Skills -->
                        <div id="section-skills">
                            <h4 class="mb-3 mt-4">Skills & Experience</h4>
                            
                            <div class="mb-3">
                                <label class="form-label">Basic Skills (Select all that apply)</label><br>
                                <div class="row">
                                    @foreach($informalSkills as $skill)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="informal_skills[]" 
                                                       id="informal_skill_{{ $skill->id }}" value="{{ $skill->id }}">
                                                <label class="form-check-label" for="informal_skill_{{ $skill->id }}">{{ $skill->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="skills_other" class="form-label">Other Skills (separate by comma)</label>
                                <textarea name="skills_other" id="skills_other" class="form-control w-75" rows="3" placeholder="e.g., sewing, massage therapy, tutoring"></textarea>
                            </div>
                        </div>

                        <!-- Simple Work Experience -->
                        <div id="section-work-experience">
                            <h4 class="mb-3 mt-4">Work Experience (Optional)</h4>
                            
                            <div class="mb-3">
                                <label for="work_experience" class="form-label">Previous Work Experience</label>
                                <textarea name="work_experience" id="work_experience" class="form-control w-75" rows="4" placeholder="Briefly describe your previous work experience, including what type of work you did and for how long."></textarea>
                            </div>
                        </div>

                        <!-- Job Preferences - Simplified -->
                        <div id="section-job-preferences">
                            <h4 class="mb-3 mt-4">Work Preferences</h4>
                            
                            <div class="mb-3">
                                <label for="preferred_work_type" class="form-label">Preferred Type of Work</label>
                                <select name="preferred_work_type" class="form-control w-75">
                                    <option value="">Select preferred work type</option>
                                    <option value="household_services">Household Services</option>
                                    <option value="construction_labor">Construction/Labor</option>
                                    <option value="food_service">Food Service</option>
                                    <option value="retail_sales">Retail/Sales</option>
                                    <option value="delivery_transport">Delivery/Transport</option>
                                    <option value="care_services">Care Services</option>
                                    <option value="repair_maintenance">Repair/Maintenance</option>
                                    <option value="agriculture">Agriculture</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="preferred_schedule" class="form-label">Preferred Work Schedule</label>
                                <select name="preferred_schedule" class="form-control w-75">
                                    <option value="">Select schedule preference</option>
                                    <option value="full_time">Full Time (8 hours/day)</option>
                                    <option value="part_time">Part Time (4-6 hours/day)</option>
                                    <option value="flexible">Flexible Hours</option>
                                    <option value="weekends_only">Weekends Only</option>
                                    <option value="project_based">Project-based</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="preferred_salary_range" class="form-label">Expected Daily Rate (PHP)</label>
                                <select name="preferred_salary_range" class="form-control w-75">
                                    <option value="">Select expected daily rate</option>
                                    <option value="200-400">₱200 - ₱400</option>
                                    <option value="400-600">₱400 - ₱600</option>
                                    <option value="600-800">₱600 - ₱800</option>
                                    <option value="800-1000">₱800 - ₱1,000</option>
                                    <option value="1000+">₱1,000 and above</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Complete Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
