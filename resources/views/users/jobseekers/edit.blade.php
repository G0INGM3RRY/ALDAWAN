
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Personal Information</title>
</head>
<body>
    <div class="container mt-5">
        <h1>Manage your personal profile</h1>
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-md-3">
                <ul class="list-group">
                    <li class="list-group-item active">Applicant name</li>
                    <li class="list-group-item">Personal information</li>
                    <li class="list-group-item">Employment status</li>
                    <li class="list-group-item">Job preferences</li>
                    <li class="list-group-item">Educational background</li>
                    <li class="list-group-item">Certification/training</li>
                    <li class="list-group-item">Eligibility/License</li>
                    <li class="list-group-item">Work experience</li>
                    <li class="list-group-item">Other skills</li>
                    
                </ul>
            </div>
            <!-- Main Content Area -->
            <div class="col-md-9">
              
                <form action="{{ route('jobseekers.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                
                        <!-- Personal information -->
                        <div id="section-personal-information">
                            <div class="mb-3">
                                <label for="job_seeker_type" class="form-label">Job Seeker Type</label>
                                <select name="job_seeker_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="formal" {{ (old('job_seeker_type', $profile->job_seeker_type ?? '') == 'formal') ? 'selected' : '' }}>Formal</option>
                                    <option value="informal" {{ (old('job_seeker_type', $profile->job_seeker_type ?? '') == 'informal') ? 'selected' : '' }}>Informal</option>
                                </select>
                                @error('job_seeker_type')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $profile->first_name ?? '') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $profile->middle_name ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $profile->last_name ?? '') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="suffix" class="form-label">Suffix</label>
                                <select name="suffix" class="form-control">
                                    <option value="">None</option>
                                    <option value="Jr." {{ (old('suffix', $profile->suffix ?? '') == 'Jr.') ? 'selected' : '' }}>Jr.</option>
                                    <option value="Sr." {{ (old('suffix', $profile->suffix ?? '') == 'Sr.') ? 'selected' : '' }}>Sr.</option>
                                    <option value="III" {{ (old('suffix', $profile->suffix ?? '') == 'III') ? 'selected' : '' }}>III</option>
                                    <option value="IV" {{ (old('suffix', $profile->suffix ?? '') == 'IV') ? 'selected' : '' }}>IV</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="birthday" class="form-label">Date of birth</label>
                                <input type="date" name="birthday" id="birthday" class="form-control" value="{{ old('birthday', $profile->birthday ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="sex" class="form-label">Sex</label>
                                <select name="sex" class="form-control">
                                    <option value="">None</option>
                                    <option value="male" {{ (old('sex', $profile->sex ?? '') == 'male') ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ (old('sex', $profile->sex ?? '') == 'female') ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" name="photo" id="photo" class="form-control">
                                @if($profile && $profile->photo)
                                    <small class="text-muted">Current: {{ $profile->photo }}</small>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="civilstatus" class="form-label">Civil Status</label>
                                <select name="civilstatus" class="form-control">
                                    <option value="">Select</option>
                                    <option value="single" {{ (old('civilstatus', $profile->civilstatus ?? '') == 'single') ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ (old('civilstatus', $profile->civilstatus ?? '') == 'married') ? 'selected' : '' }}>Married</option>
                                    <option value="widowed" {{ (old('civilstatus', $profile->civilstatus ?? '') == 'widowed') ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="street"  class="form-label">Street</label>
                                <input type="text" name="street" id="street" class="form-control" value="{{ old('street', $profile->street ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="barangay"  class="form-label">Barangay</label>
                                <input type="text" name="barangay" id="barangay" class="form-control" value="{{ old('barangay', $profile->barangay ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="municipality"  class="form-label">Municipality</label>
                                <input type="text" name="municipality" id="municipality" class="form-control" value="{{ old('municipality', $profile->municipality ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="province"  class="form-label">Province</label>
                                <input type="text" name="province" id="province" class="form-control" value="{{ old('province', $profile->province ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="religion"  class="form-label">Religion</label>
                                <input type="text" name="religion" id="religion" class="form-control" value="{{ old('religion', $profile->religion ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="contactnumber"  class="form-label">Contact Number</label>
                                <input type="text" name="contactnumber" id="contactnumber" class="form-control" value="{{ old('contactnumber', $profile->contactnumber ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="email"  class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $profile->email ?? '') }}">
                            </div>
                        </div>


                        <!-- Employment status -->
                        <div id="section-employment-status">
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:600;letter-spacing:1px;">Disability</label><br>
                                @php
                                    $disabilities = $profile && $profile->disability ? json_decode($profile->disability, true) : [];
                                    $disabilities = is_array($disabilities) ? $disabilities : [];
                                @endphp
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_visual" value="visual" {{ in_array('visual', $disabilities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disability_visual">Visual</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_hearing" value="hearing" {{ in_array('hearing', $disabilities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disability_hearing">Hearing</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_speech" value="speech" {{ in_array('speech', $disabilities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disability_speech">Speech</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_physical" value="physical" {{ in_array('physical', $disabilities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disability_physical">Physical</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_mental" value="mental" {{ in_array('mental', $disabilities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disability_mental">Mental</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_others" value="others">
                                    <label class="form-check-label" for="disability_others">Others</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:600;letter-spacing:1px;">4PS Benificiary?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_4ps" id="is_4ps" value="yes" {{ (old('is_4ps', $profile->is_4ps ?? false)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_4ps">Yes</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="employmentstatus" class="form-label">Employment Status</label>
                                <select name="employmentstatus" id="employmentstatus" class="form-control">
                                    <option value="">Select</option>
                                    <option value="employed" {{ (old('employmentstatus', $profile->employmentstatus ?? '') == 'employed') ? 'selected' : '' }}>Employed</option>
                                    <option value="unemployed" {{ (old('employmentstatus', $profile->employmentstatus ?? '') == 'unemployed') ? 'selected' : '' }}>Unemployed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Educational background -->
                        <div id="section-educational-background">
                            <h3>Educational Background</h3>
                            <p class="text-muted">Please fill in your educational attainment from elementary up to the highest level attained.</p>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 18%">Level</th>
                                            <th style="width: 32%">School Attended</th>
                                            <th style="width: 15%">Year Graduated</th>
                                            <th style="width: 20%">Honors/Remarks</th>
                                            <th style="width: 15%">Highest Level/Units Earned</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Elementary</td>
                                            <td><input type="text" name="education[elementary][school]" class="form-control" placeholder="School name"></td>
                                            <td><input type="text" name="education[elementary][year]" class="form-control" placeholder="Year graduated"></td>
                                            <td><input type="text" name="education[elementary][honors]" class="form-control" placeholder="Honors/Remarks"></td>
                                            <td><input type="text" name="education[elementary][units]" class="form-control" placeholder="N/A"></td>
                                        </tr>
                                        <tr>
                                            <td>High School</td>
                                            <td><input type="text" name="education[high_school][school]" class="form-control" placeholder="School name"></td>
                                            <td><input type="text" name="education[high_school][year]" class="form-control" placeholder="Year graduated"></td>
                                            <td><input type="text" name="education[high_school][honors]" class="form-control" placeholder="Honors/Remarks"></td>
                                            <td><input type="text" name="education[high_school][units]" class="form-control" placeholder="N/A"></td>
                                        </tr>
                                        <tr>
                                            <td>College</td>
                                            <td><input type="text" name="education[college][school]" class="form-control" placeholder="School name"></td>
                                            <td><input type="text" name="education[college][year]" class="form-control" placeholder="Year graduated"></td>
                                            <td><input type="text" name="education[college][honors]" class="form-control" placeholder="Honors/Remarks"></td>
                                            <td><input type="text" name="education[college][units]" class="form-control" placeholder="If undergraduate, highest year/units"></td>
                                        </tr>
                                        <tr>
                                            <td>Vocational</td>
                                            <td><input type="text" name="education[vocational][school]" class="form-control" placeholder="School name"></td>
                                            <td><input type="text" name="education[vocational][year]" class="form-control" placeholder="Year graduated"></td>
                                            <td><input type="text" name="education[vocational][honors]" class="form-control" placeholder="Honors/Remarks"></td>
                                            <td><input type="text" name="education[vocational][units]" class="form-control" placeholder="Course/Units"></td>
                                        </tr>
                                        <tr>
                                            <td>Graduate Studies</td>
                                            <td><input type="text" name="education[graduate][school]" class="form-control" placeholder="School name"></td>
                                            <td><input type="text" name="education[graduate][year]" class="form-control" placeholder="Year graduated"></td>
                                            <td><input type="text" name="education[graduate][honors]" class="form-control" placeholder="Honors/Remarks"></td>
                                            <td><input type="text" name="education[graduate][units]" class="form-control" placeholder="Course/Units"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Work experience -->
                        <div id="section-work-experience">
                            <h3>Work Experience</h3>
                            <p class="text-muted">List your previous work experiences. Leave blank if not applicable.</p>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 22%">Employer Name</th>
                                            <th style="width: 20%">Address</th>
                                            <th style="width: 16%">Position Held</th>
                                            <th style="width: 14%">Date From</th>
                                            <th style="width: 14%">Date To</th>
                                            <th style="width: 14%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="work[0][employer]" class="form-control" placeholder="Employer name"></td>
                                            <td><input type="text" name="work[0][address]" class="form-control" placeholder="Address"></td>
                                            <td><input type="text" name="work[0][position]" class="form-control" placeholder="Position held"></td>
                                            <td><input type="date" name="work[0][date_from]" class="form-control"></td>
                                            <td><input type="date" name="work[0][date_to]" class="form-control"></td>
                                            <td>
                                                <select name="work[0][status]" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="permanent">Permanent</option>
                                                    <option value="contractual">Contractual</option>
                                                    <option value="probationary">Probationary</option>
                                                    <option value="part_time">Part-time</option>
                                                    <option value="casual">Casual</option>
                                                    <option value="project_based">Project-based</option>
                                                    <option value="seasonal">Seasonal</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="work[1][employer]" class="form-control" placeholder="Employer name"></td>
                                            <td><input type="text" name="work[1][address]" class="form-control" placeholder="Address"></td>
                                            <td><input type="text" name="work[1][position]" class="form-control" placeholder="Position held"></td>
                                            <td><input type="date" name="work[1][date_from]" class="form-control"></td>
                                            <td><input type="date" name="work[1][date_to]" class="form-control"></td>
                                            <td>
                                                <select name="work[1][status]" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="permanent">Permanent</option>
                                                    <option value="contractual">Contractual</option>
                                                    <option value="probationary">Probationary</option>
                                                    <option value="part_time">Part-time</option>
                                                    <option value="casual">Casual</option>
                                                    <option value="project_based">Project-based</option>
                                                    <option value="seasonal">Seasonal</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="work[2][employer]" class="form-control" placeholder="Employer name"></td>
                                            <td><input type="text" name="work[2][address]" class="form-control" placeholder="Address"></td>
                                            <td><input type="text" name="work[2][position]" class="form-control" placeholder="Position held"></td>
                                            <td><input type="date" name="work[2][date_from]" class="form-control"></td>
                                            <td><input type="date" name="work[2][date_to]" class="form-control"></td>
                                            <td>
                                                <select name="work[2][status]" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="permanent">Permanent</option>
                                                    <option value="contractual">Contractual</option>
                                                    <option value="probationary">Probationary</option>
                                                    <option value="part_time">Part-time</option>
                                                    <option value="casual">Casual</option>
                                                    <option value="project_based">Project-based</option>
                                                    <option value="seasonal">Seasonal</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <small class="text-muted">Add more rows if needed.</small>
                            </div>
                        </div>

                        <!-- Skills -->
                        <div id="section-skills">
                            <h3>Skills</h3>
                            <label class="form-label">Select your skills</label>
                            <div class="mb-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_gardening" value="gardening">
                                    <label class="form-check-label" for="skill_gardening">Gardening</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_carpentry" value="carpentry">
                                    <label class="form-check-label" for="skill_carpentry">Carpentry</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_computer_repair" value="computer repair">
                                    <label class="form-check-label" for="skill_computer_repair">Computer Repair</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_sewing" value="sewing">
                                    <label class="form-check-label" for="skill_sewing">Sewing</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_driving" value="driving">
                                    <label class="form-check-label" for="skill_driving">Driving</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_cooking" value="cooking">
                                    <label class="form-check-label" for="skill_cooking">Cooking</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_cleaning" value="cleaning">
                                    <label class="form-check-label" for="skill_cleaning">Cleaning</label>
                                </div>
                                <!-- Add more common skills as needed -->
                            </div>
                            <div class="mb-2">
                                <label for="skills_other" class="form-label">Other skills (not listed above):</label>
                                <input type="text" name="skills_other" id="skills_other" class="form-control" placeholder="Type other skills here, separated by commas">
                            </div>
                            <small class="form-text text-muted">
                                Please check all that apply and add any other skills not listed. Use common terms to help employers find you more easily.
                            </small>
                        </div>


                       
                      
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
</body>
</html>