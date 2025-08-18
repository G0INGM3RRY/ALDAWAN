
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
                    <li class="list-group-item">History of SPES/GIP Engagement</li>
                </ul>
            </div>
            <!-- Main Content Area -->
            <div class="col-md-9">
              
                <form action="{{ route('jobseeker.complete') }}" method="POST">
                    @csrf
                   
                        <!-- Personal information -->
                        <div id="section-personal-information">
                            <div class="mb-3">
                                <label for="job_seeker_type" class="form-label">Job Seeker Type</label>
                                <select name="job_seeker_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="formal" {{ isset($job_seeker_type) && $job_seeker_type == 'formal' ? 'selected' : '' }}>Formal</option>
                                    <option value="informal" {{ isset($job_seeker_type) && $job_seeker_type == 'informal' ? 'selected' : '' }}>Informal</option>
                                </select>
                                @error('job_seeker_type')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="suffix" class="form-label">Suffix</label>
                                <select name="suffix" class="form-control">
                                    <option value="">None</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="birthday" class="form-label">Date of birth</label>
                                <input type="date" name="birthday" id="birthday" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="sex" class="form-label">Sex</label>
                                <select name="sex" class="form-control">
                                    <option value="">None</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" name="photo" id="photo" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="civilstatus" class="form-label">Civil Status</label>
                                <select name="civilstatus" class="form-control">
                                    <option value="">Select</option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    <option value="widowed">Widowed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="street"  class="form-label">Street</label>
                                <input type="text" name="street" id="street" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="barangay"  class="form-label">Barangay</label>
                                <input type="text" name="barangay" id="barangay" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="municipality"  class="form-label">Municipality</label>
                                <input type="text" name="municipality" id="municipality"class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="province"  class="form-label">Province</label>
                                <input type="text" name="province" id="province"class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="religion"  class="form-label">Religion</label>
                                <input type="text" name="religion" id="religion"class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="contactnumber"  class="form-label">Contact Number</label>
                                <input type="text" name="contactnumber" id="contactnumber"class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="email"  class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                        </div>


                        <!-- Employment status -->
                        <div id="section-employment-status">
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:600;letter-spacing:1px;">Disability</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_visual" value="visual">
                                    <label class="form-check-label" for="disability_visual">Visual</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_hearing" value="hearing">
                                    <label class="form-check-label" for="disability_hearing">Hearing</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_speech" value="speech">
                                    <label class="form-check-label" for="disability_speech">Speech</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_physical" value="physical">
                                    <label class="form-check-label" for="disability_physical">Physical</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_mental" value="mental">
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
                                    <input class="form-check-input" type="checkbox" name="is_4ps" id="is_4ps" value="yes">
                                    <label class="form-check-label" for="is_4ps">Yes</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="employmentstatus" class="form-label">Employment Status</label>
                                <select name="employmentstatus" id="employmentstatus" class="form-control">
                                    <option value="">Select</option>
                                    <option value="employed">Employed</option>
                                    <option value="unemployed">Unemployed</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight:600;letter-spacing:1px;">4PS Benificiary?</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_4ps" id="is_4ps" value="yes">
                                <label class="form-check-label" for="is_4ps">Yes</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="employmentstatus" class="form-label">Employment Status</label>
                            <select name="employmentstatus" id="employmentstatus" class="form-control">
                                <option value="">Select</option>
                                <option value="employed">Employed</option>
                                <option value="unemployed">Unemployed</option>
                            </select>
                        </div>

                        <!-- Job Preferences -->
                        <div id="section-job-preferences">
                            <h3>Job Preferences</h3>
                            <p class="text-muted">Specify your preferred job types and requirements. You can add multiple preferences.</p>
                            
                            <div id="job-preferences-container">
                                <div class="job-preference-item border p-3 mb-3 rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Preferred Job Title</label>
                                                <input type="text" name="job_preferences[0][preferred_job_title]" class="form-control" placeholder="e.g., Software Developer">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Job Classification</label>
                                                <select name="job_preferences[0][preferred_classification]" class="form-control">
                                                    <option value="">Select Classification</option>
                                                    <option value="Information Technology">Information Technology</option>
                                                    <option value="Customer Service">Customer Service</option>
                                                    <option value="Marketing">Marketing</option>
                                                    <option value="Administrative">Administrative</option>
                                                    <option value="Creative">Creative</option>
                                                    <option value="Sales">Sales</option>
                                                    <option value="Finance">Finance</option>
                                                    <option value="Healthcare">Healthcare</option>
                                                    <option value="Education">Education</option>
                                                    <option value="Manufacturing">Manufacturing</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Min Salary (PHP)</label>
                                                <input type="number" name="job_preferences[0][min_salary]" class="form-control" step="0.01" placeholder="15000">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Max Salary (PHP)</label>
                                                <input type="number" name="job_preferences[0][max_salary]" class="form-control" step="0.01" placeholder="25000">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Preferred Location</label>
                                                <input type="text" name="job_preferences[0][preferred_location]" class="form-control" placeholder="e.g., Makati, Remote">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Employment Type</label>
                                                <select name="job_preferences[0][preferred_employment_type]" class="form-control">
                                                    <option value="">Select Type</option>
                                                    <option value="full-time">Full-time</option>
                                                    <option value="part-time">Part-time</option>
                                                    <option value="contract">Contract</option>
                                                    <option value="freelance">Freelance</option>
                                                    <option value="internship">Internship</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-flex align-items-end">
                                            <button type="button" class="btn btn-outline-danger remove-preference" style="display: none;">Remove Preference</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-outline-primary" id="add-job-preference">
                                + Add Another Job Preference
                            </button>
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

    <script>
        let preferenceCount = 1;
        
        document.getElementById('add-job-preference').addEventListener('click', function() {
            const container = document.getElementById('job-preferences-container');
            const newPreference = createJobPreferenceItem(preferenceCount);
            container.insertAdjacentHTML('beforeend', newPreference);
            
            // Show remove buttons if more than one preference
            const removeButtons = document.querySelectorAll('.remove-preference');
            if (removeButtons.length > 1) {
                removeButtons.forEach(btn => btn.style.display = 'block');
            }
            
            preferenceCount++;
        });
        
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-preference')) {
                e.target.closest('.job-preference-item').remove();
                
                // Hide remove buttons if only one preference remains
                const remainingItems = document.querySelectorAll('.job-preference-item');
                if (remainingItems.length === 1) {
                    document.querySelector('.remove-preference').style.display = 'none';
                }
            }
        });
        
        function createJobPreferenceItem(index) {
            return `
                <div class="job-preference-item border p-3 mb-3 rounded">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Preferred Job Title</label>
                                <input type="text" name="job_preferences[${index}][preferred_job_title]" class="form-control" placeholder="e.g., Software Developer">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Job Classification</label>
                                <select name="job_preferences[${index}][preferred_classification]" class="form-control">
                                    <option value="">Select Classification</option>
                                    <option value="Information Technology">Information Technology</option>
                                    <option value="Customer Service">Customer Service</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Administrative">Administrative</option>
                                    <option value="Creative">Creative</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Education">Education</option>
                                    <option value="Manufacturing">Manufacturing</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Min Salary (PHP)</label>
                                <input type="number" name="job_preferences[${index}][min_salary]" class="form-control" step="0.01" placeholder="15000">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Max Salary (PHP)</label>
                                <input type="number" name="job_preferences[${index}][max_salary]" class="form-control" step="0.01" placeholder="25000">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Preferred Location</label>
                                <input type="text" name="job_preferences[${index}][preferred_location]" class="form-control" placeholder="e.g., Makati, Remote">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Employment Type</label>
                                <select name="job_preferences[${index}][preferred_employment_type]" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="contract">Contract</option>
                                    <option value="freelance">Freelance</option>
                                    <option value="internship">Internship</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger remove-preference">Remove Preference</button>
                        </div>
                    </div>
                </div>
            `;
        }
    </script>
    
</body>
</html>