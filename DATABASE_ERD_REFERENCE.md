# ALDAWAN Job Portal - Database ERD Reference
## 33 Total Migrations | Generated: October 22, 2025

---

## 📊 **CORE ENTITIES & RELATIONSHIPS**

### **1. USER MANAGEMENT**
```sql
users
├── id (PK)
├── name, email, password
├── role (JobSeeker, FormalEmployer, InformalEmployer, Admin)
├── email_verified_at
└── timestamps

admin_users
├── id (PK) 
├── user_id (FK → users.id)
├── admin_level (super_admin, admin, moderator)
├── permissions (JSON)
└── is_active, department, notes
```

### **2. JOBSEEKER SYSTEM**
```sql
jobseeker_profiles
├── id (PK)
├── user_id (FK → users.id) 
├── job_seeker_type (formal, informal)
├── first_name, middle_name, last_name, suffix
├── birthday, sex, photo, civilstatus, religion
├── street, barangay, municipality, province
├── contactnumber, email, is_4ps, employmentstatus
├── education_level_id (FK → education_levels.id)
├── institution_name, graduation_year, gpa, degree_field
└── timestamps

work_experiences
├── id (PK)
├── jobseeker_profile_id (FK → jobseeker_profiles.id)
├── job_title, company_name, description
├── start_date, end_date, is_current
└── timestamps

jobseeker_skills (PIVOT)
├── id (PK)
├── jobseeker_profile_id (FK → jobseeker_profiles.id)
├── skill_id (FK → skills.id)
├── proficiency_level (beginner, intermediate, advanced, expert)
├── years_experience
└── timestamps

jobseeker_disabilities (PIVOT)
├── id (PK)
├── jobseeker_profile_id (FK → jobseeker_profiles.id) 
├── disability_id (FK → disabilities.id)
├── accommodation_needs
└── timestamps

jobseeker_verifications [PENDING]
├── id (PK)
├── jobseeker_id (FK → jobseeker_profiles.id)
├── status (pending, approved, rejected, requires_info)
├── government_id_type, government_id_number, government_id_path
├── barangay_clearance_path, proof_of_address_path
├── skill_certificates (JSON), nbi_clearance_path
├── verified_by (FK → users.id), verified_at
└── timestamps

job_preferences
├── id (PK)
├── user_id (FK → users.id)
├── preferred_job_title, preferred_classification
├── min_salary, max_salary, preferred_location
├── preferred_employment_type
└── timestamps
```

### **3. EMPLOYER SYSTEM**
```sql
employers
├── id (PK)
├── user_id (FK → users.id)
├── company_name, company_logo
├── street, barangay, municipality, province
├── employer_type (formal, informal)
├── company_type_id (FK → company_types.id)
└── timestamps

company_verifications
├── id (PK)
├── employer_id (FK → employers.id)
├── status (pending, approved, rejected, requires_info)
├── business_registration_number, tax_id
├── verification_document_path, verification_notes
├── verified_by (FK → users.id), verified_at
├── rejection_reason
└── timestamps
```

### **4. JOB LISTING SYSTEM**
```sql
job_listings
├── id (PK)
├── job_title, description, requirements
├── company_id (FK → users.id)
├── location, salary
├── classification (string - legacy)
├── job_type (formal, informal)
├── employment_type (full_time, part_time, contract, temporary, internship)
├── status (open, closed)
├── posted_at
├── minimum_education_level_id (FK → education_levels.id)
├── minimum_experience_years, benefits
├── remote_work_available, positions_available
├── disability_restrictions (JSON), accessibility_notes
└── timestamps

job_classifications (PIVOT)
├── id (PK)
├── job_listing_id (FK → job_listings.id)
├── classification_id (FK → classifications.id)
└── timestamps

job_skills (PIVOT)
├── id (PK)
├── job_listing_id (FK → job_listings.id)
├── skill_id (FK → skills.id)
├── is_required (boolean)
└── timestamps

formal_job_applications
├── id (PK)
├── user_id (FK → users.id)
├── job_id (FK → job_listings.id)
├── status (pending, under_review, shortlisted, accepted, rejected)
├── cover_letter, resume_file_path
├── additional_documents (JSON)
├── applied_at, reviewed_at, status_updated_at
├── employer_notes, rejection_reason
└── timestamps
```

### **5. LOOKUP/REFERENCE TABLES**
```sql
skills
├── id (PK)
├── name (unique), description, category
├── is_active, usage_count, show_in_list, is_custom
└── timestamps

education_levels
├── id (PK)
├── name (unique), description
├── level_order, is_active
└── timestamps

classifications
├── id (PK)
├── name (unique), description, code
├── is_active
└── timestamps

disabilities
├── id (PK)
├── name (unique), description, category
├── is_active
└── timestamps

company_types
├── id (PK)
├── name (unique), description
├── is_active
└── timestamps
```

### **6. COMMUNICATION SYSTEM**
```sql
messages
├── id (PK)
├── sender_id (FK → users.id)
├── receiver_id (FK → users.id)
├── message_content, message_type
├── sent_at, read_at
├── is_deleted_by_sender, is_deleted_by_receiver
└── timestamps

notifications
├── id (PK)
├── user_id (FK → users.id)
├── type, title, message
├── data (JSON), read_at
├── is_actionable, action_url
└── timestamps
```

### **7. SYSTEM TABLES**
```sql
cache, sessions, password_reset_tokens
└── Laravel framework tables
```

---

## 🔗 **KEY RELATIONSHIPS FOR ERD**

### **One-to-One (1:1)**
- users ←→ jobseeker_profiles
- users ←→ employers  
- users ←→ admin_users
- employers ←→ company_verifications

### **One-to-Many (1:N)**
- users → job_listings (employer posts jobs)
- users → formal_job_applications (jobseeker applies)
- users → messages (as sender)
- users → messages (as receiver)
- users → notifications
- jobseeker_profiles → work_experiences
- education_levels → jobseeker_profiles
- education_levels → job_listings

### **Many-to-Many (M:N)**
- jobseeker_profiles ←→ skills (via jobseeker_skills)
- jobseeker_profiles ←→ disabilities (via jobseeker_disabilities)
- job_listings ←→ skills (via job_skills)
- job_listings ←→ classifications (via job_classifications)

---

## 📋 **ERD DESIGN NOTES**

### **Entity Colors Suggestion:**
- 🔵 **Core Users**: users, admin_users (Blue)
- 🟢 **Jobseekers**: jobseeker_profiles, work_experiences, verifications (Green)
- 🟡 **Employers**: employers, company_verifications (Yellow)
- 🟠 **Jobs**: job_listings, applications (Orange)
- 🟣 **Lookups**: skills, education_levels, classifications, disabilities (Purple)
- 🔴 **Communication**: messages, notifications (Red)

### **Key Constraints:**
- All FKs have cascade deletes where appropriate
- Unique constraints on pivot tables
- Enum fields for status tracking
- JSON fields for flexible data (documents, permissions)

### **Migration Status:** 
- ✅ 32/33 Completed
- ⏳ 1 Pending: jobseeker_verifications

---

## 🎯 **USAGE:**
This reference provides complete table structures and relationships for creating your ERD diagram. Each section represents logical groupings you can use in your ERD tool of choice.