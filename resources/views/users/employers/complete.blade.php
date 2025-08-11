
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Employer Information</title>
</head>
<body>
    <div class="container mt-5">
        <h1>Manage your Company profile</h1>
        <div class="row">        
            <!-- Main Content Area -->
            <div class="col-md-9">
              
                <form action="{{ route('employers.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                   @method('PUT')
                        <!-- Personal information -->
                        <div id="section-personal-information">
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control" required>
                            </div>
                           
                            <div class="mb-3">
                                <label for="company_logo" class="form-label">Company Logo</label>
                                <input type="file" name="company_logo" id="photo" class="form-control">
                            </div>

                            <h3>Location</h3>
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
                                <label for="contactnumber"  class="form-label">Contact Number</label>
                                <input type="text" name="contactnumber" id="contactnumber"class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="email"  class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                        </div>

                                                              
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
</body>
</html>