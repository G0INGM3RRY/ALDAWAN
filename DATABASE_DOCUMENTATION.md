# ALDAWAN Job Portal - Complete Database Documentation

## Overview
The ALDAWAN database contains **40 tables** supporting a dual-track job portal system for both **formal workers** (professional/office jobs) and **informal workers** (household/manual labor). Total database size: **1.27 MB**.

---

## Core System Tables

### 1. **users**
**Purpose**: Central authentication and user management table for all system users (jobseekers, employers, admins)

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique user identifier |
| `name` | varchar | Full name of the user |
| `email` | varchar (unique) | Email address for login and communication |
| `email_verified_at` | timestamp | When email was verified (null if unverified) |
| `password` | varchar | Hashed password using bcrypt |
| `role` | varchar | User role: 'seeker' (jobseeker), 'employer', or 'admin' |
| `remember_token` | varchar | Token for "remember me" functionality |
| `deleted_at` | timestamp | Soft delete timestamp (user deactivation) |
| `created_at` | timestamp | Account creation timestamp |
| `updated_at` | timestamp | Last profile update timestamp |

**Relationships**:
- Has one `jobseeker_profiles` (if role = seeker)
- Has one `employers` (if role = employer)
- Has many `formal_job_applications`
- Has many `job_preferences`
- Has many `messages` (as sender/receiver)
- Has many `notifications`

**Usage**: Every user must have an account in this table before accessing any system features. The `role` column determines which dashboard and features they can access.

---

### 2. **password_reset_tokens**
**Purpose**: Store temporary tokens for password reset requests

| Column | Type | Description |
|--------|------|-------------|
| `email` | varchar (PK) | Email address requesting password reset |
| `token` | varchar | Unique reset token sent via email |
| `created_at` | timestamp | When reset was requested (expires after 60 minutes) |

**Usage**: When users click "Forgot Password", a token is generated and emailed. Token is validated and deleted after successful password reset.

---

### 3. **sessions**
**Purpose**: Track active user sessions for web-based authentication

| Column | Type | Description |
|--------|------|-------------|
| `id` | varchar (PK) | Unique session identifier |
| `user_id` | bigint (FK) | Reference to logged-in user |
| `ip_address` | varchar(45) | User's IP address (IPv4/IPv6) |
| `user_agent` | text | Browser and device information |
| `payload` | longtext | Serialized session data |
| `last_activity` | integer (indexed) | Unix timestamp of last activity |

**Usage**: Laravel's session management. Tracks who is logged in, from where, and when. Cleaned up periodically to remove expired sessions.

---

## Employer Tables

### 4. **employers**
**Purpose**: Extended profile information for employer accounts (companies and households)

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique employer profile ID |
| `user_id` | bigint (FK) | References `users.id` (one-to-one) |
| `employer_type` | enum | 'formal' (company) or 'informal' (household/individual) |
| `company_name` | varchar | Business/household name (e.g., "Santos Family") |
| `company_type_id` | bigint (FK) | References `company_types.id` (for formal employers) |
| `company_description` | text | Business overview or household details |
| `website` | varchar | Company website (null for informal) |
| `contact_number` | varchar | Primary contact phone number |
| `street` | varchar | Street address |
| `barangay` | varchar | Barangay (smallest administrative division) |
| `municipality` | varchar | City/Municipality |
| `province` | varchar | Province |
| `company_logo` | varchar | Path to uploaded logo image |
| `created_at` | timestamp | Profile creation date |
| `updated_at` | timestamp | Last profile update |

**Relationships**:
- Belongs to `users`
- Belongs to `company_types` (nullable for informal)
- Has many `job_listings`
- Has one `company_verifications` (formal employers)
- Has one `informal_employer_verifications` (informal employers)

**Usage**: Stores employer profile details. Formal employers (companies) provide full business information. Informal employers (households) provide simpler information for hiring domestic workers.

---

### 5. **company_types**
**Purpose**: Reference table for formal company classifications

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique type identifier |
| `name` | varchar (unique) | Company type name |
| `description` | text | Detailed explanation of company type |
| `is_active` | boolean | Whether this type is selectable (default: true) |
| `created_at` | timestamp | Record creation |
| `updated_at` | timestamp | Last update |

**Examples**: 
- "Corporation" - Large registered corporations
- "Small & Medium Enterprise (SME)" - Small/medium businesses
- "Non-Profit Organization" - NGOs and foundations
- "Government Agency" - Public sector employers
- "Startup" - New technology companies

**Usage**: Used in employer registration to classify formal companies. Helps jobseekers understand employer type and size.

---

### 6. **company_verifications**
**Purpose**: Verification system for formal employer accounts (companies)

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique verification record ID |
| `employer_id` | bigint (FK) | References `employers.id` |
| `status` | enum | 'pending', 'approved', 'rejected', 'requires_info' |
| `business_registration_number` | varchar | DTI/SEC/CDA registration number |
| `tax_id` | varchar | Tax Identification Number (TIN) |
| `verification_document_path` | varchar | Path to uploaded business permit/registration |
| `verification_notes` | text | Admin's private notes about verification |
| `verified_by` | bigint (FK) | Admin user who approved/rejected |
| `verified_at` | timestamp | When verification was completed |
| `submitted_at` | timestamp | When documents were submitted |
| `rejection_reason` | text | Reason shown to employer if rejected |
| `created_at` | timestamp | Record creation |
| `updated_at` | timestamp | Last status update |

**Relationships**:
- Belongs to `employers`
- Belongs to `users` (via `verified_by`)

**Usage**: Companies must submit business documents for admin verification before posting jobs. Prevents fake employers and ensures legitimacy.

---

### 7. **informal_employer_verifications**
**Purpose**: Simplified verification for informal employers (households hiring domestic workers)

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique verification record ID |
| `employer_id` | bigint (FK) | References `employers.id` |
| `status` | enum | 'pending', 'approved', 'rejected', 'requires_info' |
| `valid_id_path` | varchar | Path to government-issued ID (e.g., National ID, Driver's License) |
| `proof_of_address_path` | varchar | Utility bill, barangay certificate showing address |
| `barangay_clearance_path` | varchar | Optional barangay clearance document |
| `verification_notes` | text | Admin's private notes |
| `verified_by` | bigint (FK) | Admin user who verified |
| `verified_at` | timestamp | Verification completion timestamp |
| `submitted_at` | timestamp | Document submission timestamp |
| `rejection_reason` | text | Feedback if rejected |
| `created_at` | timestamp | Record creation |
| `updated_at` | timestamp | Last update |

**Relationships**:
- Belongs to `employers`
- Belongs to `users` (via `verified_by`)

**Usage**: Simpler verification for households. Requires basic ID and address proof instead of business documents. Protects domestic workers from fraudulent employers.

---

## Job Seeker Tables

### 8. **jobseeker_profiles**
**Purpose**: Extended profile information for job seekers (both formal and informal workers)

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique profile ID |
| `user_id` | bigint (FK) | References `users.id` (one-to-one) |
| `job_seeker_type` | enum | 'formal' (professional) or 'informal' (domestic/manual) |
| `first_name` | varchar | Legal first name |
| `middle_name` | varchar | Middle name (optional) |
| `last_name` | varchar | Family name |
| `suffix` | varchar | Name suffix (Jr., Sr., III, etc.) |
| `birthday` | date | Date of birth (for age verification) |
| `sex` | varchar | 'Male', 'Female', or other |
| `photo` | varchar | Path to profile photo |
| `civilstatus` | varchar | Marital status (Single, Married, Widowed, etc.) |
| `street` | varchar | Street address |
| `barangay` | varchar | Barangay location |
| `municipality` | varchar | City/Municipality |
| `province` | varchar | Province |
| `religion` | varchar | Religious affiliation (optional) |
| `contactnumber` | varchar | Mobile/phone number |
| `email` | varchar | Alternative email (besides user.email) |
| `is_4ps` | boolean | Pantawid Pamilyang Pilipino Program beneficiary (default: false) |
| `employmentstatus` | varchar | 'employed', 'unemployed', 'self-employed', 'student' |
| `professional_title` | varchar | Job title (for formal workers, e.g., "Senior Software Engineer") |
| `bio` | text | Personal summary/bio (for formal workers) |
| `education_json` | json | Educational background array (for informal workers) |
| `created_at` | timestamp | Profile creation |
| `updated_at` | timestamp | Last profile update |

**Relationships**:
- Belongs to `users`
- Has many `work_experiences`
- Has many `jobseeker_skills` (through pivot)
- Has many `jobseeker_disabilities` (through pivot)
- Has many `formal_job_applications`
- Has one `formal_jobseeker_verifications` (if formal)
- Has one `informal_jobseeker_verifications` (if informal)

**Usage**: Core profile for all jobseekers. Contains personal info, contact details, and current employment status. Formal workers fill out more detailed professional info. Informal workers focus on basic qualifications and availability.

---

### 9. **work_experiences**
**Purpose**: Employment history for formal jobseekers (for professional resume building)

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique experience entry ID |
| `jobseeker_profile_id` | bigint (FK) | References `jobseeker_profiles.id` |
| `job_title` | varchar | Position held (e.g., "Marketing Manager") |
| `company_name` | varchar | Employer name |
| `description` | text | Responsibilities and achievements |
| `start_date` | date | Employment start date |
| `end_date` | date | Employment end date (null if current) |
| `is_current` | boolean | Whether currently employed here (default: false) |
| `created_at` | timestamp | Entry creation |
| `updated_at` | timestamp | Last update |

**Relationships**:
- Belongs to `jobseeker_profiles`

**Usage**: Formal workers can add multiple work experiences to build their professional resume. Employers use this to evaluate candidate's career progression. Not used by informal workers.

---

### 10. **job_preferences**
**Purpose**: Job search criteria and preferences for matching algorithm

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique preference ID |
| `user_id` | bigint (FK) | References `users.id` |
| `preferred_job_title` | varchar | Desired job title/position |
| `preferred_classification` | varchar | Industry/field preference |
| `min_salary` | decimal(10,2) | Minimum acceptable salary |
| `max_salary` | decimal(10,2) | Maximum salary expectation |
| `preferred_location` | varchar | Desired work location (barangay/city/province) |
| `preferred_employment_type` | enum | 'full-time', 'part-time', 'contract', 'freelance', 'internship' |
| `created_at` | timestamp | Preference setup date |
| `updated_at` | timestamp | Last preference update |

**Relationships**:
- Belongs to `users`

**Usage**: Powers the job recommendation algorithm. System matches jobs to seekers based on these preferences. Used for both formal and informal job matching (30% weight in matching score).

---

### 11. **formal_jobseeker_verifications**
**Purpose**: Document verification for formal workers (professional jobseekers)

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique verification ID |
| `jobseeker_id` | bigint (FK) | References `jobseeker_profiles.id` |
| `status` | enum | 'pending', 'approved', 'rejected' |
| `government_id_path` | varchar | **Required**: Government-issued ID (UMID, Passport, Driver's License) |
| `educational_document_path` | varchar | **Required**: Diploma, transcript, or degree certificate |
| `skills_certificate_path` | varchar | Optional: Professional certifications (PRC license, TESDA, etc.) |
| `nbi_clearance_path` | varchar | **Required**: NBI clearance for background check |
| `verification_notes` | text | Admin's private notes |
| `verified_by` | bigint (FK) | Admin who verified |
| `verified_at` | timestamp | Verification completion |
| `submitted_at` | timestamp | Document submission |
| `rejection_reason` | text | Reason for rejection |
| `created_at` | timestamp | Record creation |
| `updated_at` | timestamp | Last update |

**Relationships**:
- Belongs to `jobseeker_profiles`
- Belongs to `users` (via `verified_by`)

**Usage**: Formal workers must verify their identity, education, and background check before applying to jobs. Ensures qualified candidates and protects employers from fraudulent applications.

---

### 12. **informal_jobseeker_verifications**
**Purpose**: Simplified verification for informal workers (domestic/manual labor)

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique verification ID |
| `jobseeker_id` | bigint (FK) | References `jobseeker_profiles.id` |
| `status` | enum | 'pending', 'approved', 'rejected' |
| `basic_id_path` | varchar | **Required**: Any government-issued ID |
| `barangay_clearance_path` | varchar | **Required**: Community endorsement/clearance |
| `health_certificate_path` | varchar | Optional: Medical certificate (especially for household workers) |
| `verification_notes` | text | Admin's private notes |
| `verified_by` | bigint (FK) | Admin who verified |
| `verified_at` | timestamp | Verification completion |
| `submitted_at` | timestamp | Document submission |
| `rejection_reason` | text | Reason for rejection |
| `created_at` | timestamp | Record creation |
| `updated_at` | timestamp | Last update |

**Relationships**:
- Belongs to `jobseeker_profiles`
- Belongs to `users` (via `verified_by`)

**Usage**: Lighter verification for domestic workers. Requires basic ID and barangay clearance for community trust. Health certificate is optional but recommended for household workers.

---

## Job Posting Tables

### 13. **job_listings**
**Purpose**: All job postings from employers (formal and informal jobs)

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique job listing ID |
| `company_id` | bigint (FK) | References `users.id` (employer who posted) |
| `job_type` | enum | 'formal' (professional) or 'informal' (domestic/manual) |
| `job_title` | varchar | Position title (e.g., "Software Developer", "Household Helper") |
| `description` | text | Full job description and responsibilities |
| `classification` | varchar | Industry/category (references `classifications.name`) |
| `location` | varchar | Job location (address/city/municipality) |
| `salary` | decimal(10,2) | Monthly salary offered |
| `employment_type` | varchar | 'full-time', 'part-time', 'contract', 'freelance' |
| `requirements` | text | Qualifications and requirements |
| `status` | enum | 'open' (accepting applications), 'closed' (no longer hiring) |
| `disability_restrictions` | json | Array of disability IDs that cannot apply (for safety reasons) |
| `posted_at` | timestamp | When job was published |
| `created_at` | timestamp | Record creation |
| `updated_at` | timestamp | Last update |

**Relationships**:
- Belongs to `users` (employer)
- Has many `job_skills` (through pivot)
- Has many `job_classifications` (through pivot)
- Has many `formal_job_applications`

**Usage**: Central table for all job postings. Employers create listings, jobseekers browse and apply. Job matching algorithm analyzes these fields to find best matches.

---

### 14. **formal_job_applications**
**Purpose**: Track all job applications submitted by jobseekers

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique application ID |
| `user_id` | bigint (FK) | References `users.id` (applicant) |
| `job_id` | bigint (FK) | References `job_listings.id` |
| `status` | enum | 'pending', 'under_review', 'shortlisted', 'accepted', 'rejected' |
| `cover_letter` | text | Optional cover letter from applicant |
| `resume_file_path` | varchar | Path to uploaded resume/CV file |
| `additional_documents` | json | Array of paths to certificates, portfolios, etc. |
| `applied_at` | timestamp | Application submission timestamp |
| `reviewed_at` | timestamp | When employer first viewed application |
| `status_updated_at` | timestamp | Last status change timestamp |
| `employer_notes` | text | Private employer notes about candidate |
| `rejection_reason` | text | Feedback provided to rejected applicant |
| `created_at` | timestamp | Record creation |
| `updated_at` | timestamp | Last update |

**Relationships**:
- Belongs to `users` (applicant)
- Belongs to `job_listings`

**Unique Constraint**: `['user_id', 'job_id']` - Prevents duplicate applications to same job

**Usage**: Each application creates a record. Employers review applications and update status. System sends notifications on status changes. Download feature generates readable filenames for documents.

---

## Skills & Qualifications Tables

### 15. **skills**
**Purpose**: Master list of all available skills for job matching

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique skill ID |
| `name` | varchar (unique) | Skill name (e.g., "Microsoft Excel", "Cooking", "Childcare") |
| `description` | text | Detailed skill explanation |
| `category` | varchar | Skill type: 'technical', 'soft', 'language', 'trade', etc. |
| `is_active` | boolean | Whether skill is selectable (default: true) |
| `usage_count` | integer | Number of jobseekers with this skill (for popularity tracking) |
| `created_at` | timestamp | Skill added to system |
| `updated_at` | timestamp | Last update |

**Usage**: Centralized skill database. Both formal and informal workers select skills. Job postings require skills. Used in matching algorithm (40% weight - highest priority).

---

### 16. **jobseeker_skills**
**Purpose**: Pivot table linking jobseekers to their skills with proficiency levels

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique link ID |
| `jobseeker_profile_id` | bigint (FK) | References `jobseeker_profiles.id` |
| `skill_id` | bigint (FK) | References `skills.id` |
| `proficiency_level` | enum | 'beginner', 'intermediate', 'advanced', 'expert' |
| `years_experience` | integer | Years of experience with this skill |
| `created_at` | timestamp | Skill added to profile |
| `updated_at` | timestamp | Last update |

**Relationships**:
- Belongs to `jobseeker_profiles`
- Belongs to `skills`

**Unique Constraint**: `['jobseeker_profile_id', 'skill_id']` - Each skill listed once per profile

**Usage**: Jobseekers build their skill portfolio. Matching algorithm compares jobseeker skills against job requirements to calculate 40% of match score.

---

### 17. **job_skills**
**Purpose**: Pivot table linking job listings to required skills

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique link ID |
| `job_listing_id` | bigint (FK) | References `job_listings.id` |
| `skill_id` | bigint (FK) | References `skills.id` |
| `required_level` | enum | Minimum proficiency: 'beginner', 'intermediate', 'advanced', 'expert' |
| `is_required` | boolean | True = must-have, False = nice-to-have (default: true) |
| `created_at` | timestamp | Skill requirement added |
| `updated_at` | timestamp | Last update |

**Relationships**:
- Belongs to `job_listings`
- Belongs to `skills`

**Unique Constraint**: `['job_listing_id', 'skill_id']` - Each skill listed once per job

**Usage**: Employers specify required skills when posting jobs. Algorithm filters candidates based on skill matches. Required skills are weighted higher than optional skills.

---

### 18. **classifications**
**Purpose**: Job industry/category reference table

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique classification ID |
| `name` | varchar (unique) | Industry name (e.g., "Information Technology", "Healthcare", "Domestic Services") |
| `description` | text | Industry description |
| `code` | varchar(10) | Optional classification code (e.g., "IT-001") |
| `is_active` | boolean | Whether selectable (default: true) |
| `created_at` | timestamp | Classification added |
| `updated_at` | timestamp | Last update |

**Examples**:
- "Information Technology & Software"
- "Healthcare & Medical Services"
- "Construction & Skilled Trades"
- "Domestic & Household Services"
- "Education & Training"
- "Sales & Marketing"

**Usage**: Used in job postings, job preferences, and matching algorithm. Helps categorize jobs and filter searches (10% weight in matching algorithm via preferences).

---

### 19. **job_classifications**
**Purpose**: Pivot table for many-to-many relationship between jobs and classifications

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique link ID |
| `job_listing_id` | bigint (FK) | References `job_listings.id` |
| `classification_id` | bigint (FK) | References `classifications.id` |
| `created_at` | timestamp | Link created |
| `updated_at` | timestamp | Last update |

**Unique Constraint**: `['job_listing_id', 'classification_id']` - Each classification listed once per job

**Usage**: Jobs can belong to multiple classifications (e.g., a healthcare IT job fits both "Healthcare" and "IT"). Used in search filters and matching.

---

### 20. **education_levels**
**Purpose**: Reference table for educational attainment levels

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique level ID |
| `name` | varchar (unique) | Education level name |
| `description` | text | Level description |
| `level_order` | integer | Numerical order for ranking (0 = lowest, higher = more advanced) |
| `is_active` | boolean | Whether selectable (default: true) |
| `created_at` | timestamp | Level added |
| `updated_at` | timestamp | Last update |

**Examples** (by level_order):
- 0: "No Formal Education"
- 1: "Elementary Graduate"
- 2: "High School Graduate"
- 3: "Vocational/Technical Graduate"
- 4: "Some College"
- 5: "Bachelor's Degree"
- 6: "Master's Degree"
- 7: "Doctorate Degree"

**Usage**: Used in job requirements and jobseeker profiles. Matching algorithm uses this (5% weight in score calculation). Higher education levels can apply to jobs requiring lower levels.

---

## Disability Support Tables

### 21. **disabilities**
**Purpose**: Master list of recognized disabilities for inclusive hiring

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique disability ID |
| `name` | varchar (unique) | Disability name (e.g., "Visual Impairment", "Hearing Impairment") |
| `description` | text | Detailed description and characteristics |
| `category` | varchar | Type: 'physical', 'cognitive', 'sensory', 'developmental', etc. |
| `is_active` | boolean | Whether selectable (default: true) |
| `created_at` | timestamp | Disability added to system |
| `updated_at` | timestamp | Last update |

**Examples**:
- "Visual Impairment / Blindness" (sensory)
- "Hearing Impairment / Deafness" (sensory)
- "Mobility Impairment" (physical)
- "Intellectual Disability" (cognitive)
- "Speech Impairment" (physical)

**Usage**: Jobseekers can declare disabilities. Some jobs restrict certain disabilities for safety (e.g., construction jobs may restrict mobility impairments). Supports PWD (Persons with Disabilities) hiring initiatives.

---

### 22. **jobseeker_disabilities**
**Purpose**: Pivot table linking jobseekers to their disabilities with accommodation needs

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique link ID |
| `jobseeker_profile_id` | bigint (FK) | References `jobseeker_profiles.id` |
| `disability_id` | bigint (FK) | References `disabilities.id` |
| `accommodation_needs` | text | Specific workplace accommodations required (e.g., "Screen reader software", "Wheelchair-accessible workspace") |
| `created_at` | timestamp | Disability declared |
| `updated_at` | timestamp | Last update |

**Relationships**:
- Belongs to `jobseeker_profiles`
- Belongs to `disabilities`

**Unique Constraint**: `['jobseeker_profile_id', 'disability_id']` - Each disability listed once per profile

**Usage**: Optional disclosure for jobseekers. Helps employers prepare accommodations. System filters out jobs with restrictions that match jobseeker's disabilities.

---

## Communication Tables

### 23. **messages**
**Purpose**: Direct messaging system between employers and jobseekers

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique message ID |
| `sender_id` | bigint (FK) | References `users.id` (who sent the message) |
| `receiver_id` | bigint (FK) | References `users.id` (who receives the message) |
| `message_content` | text | Message body text |
| `sent_at` | timestamp | When message was sent |
| `read_at` | timestamp | When receiver opened the message (null if unread) |
| `is_deleted_by_sender` | boolean | Sender deleted from their view (default: false) |
| `is_deleted_by_receiver` | boolean | Receiver deleted from their view (default: false) |
| `message_type` | varchar | 'text' (regular), 'system' (automated), etc. (default: 'text') |
| `created_at` | timestamp | Record creation |
| `updated_at` | timestamp | Last update |

**Relationships**:
- Belongs to `users` (sender)
- Belongs to `users` (receiver)

**Indexes**: 
- `['sender_id', 'receiver_id']` - Fast conversation retrieval
- `['sent_at']` - Sort by timestamp

**Usage**: Employers can message applicants, jobseekers can ask questions about jobs. Soft delete allows users to hide conversations without deleting from database.

---

### 24. **notifications**
**Purpose**: System-generated notifications for important events

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique notification ID |
| `user_id` | bigint (FK) | References `users.id` (notification recipient) |
| `type` | varchar | Notification category: 'job_application', 'message', 'job_match', 'verification', etc. |
| `title` | varchar | Notification headline (e.g., "Application Accepted!") |
| `message` | text | Full notification text |
| `data` | json | Additional structured data (e.g., job_id, application_id) |
| `read_at` | timestamp | When user marked as read (null if unread) |
| `is_actionable` | boolean | Whether notification has an action button (default: false) |
| `action_url` | varchar | URL to navigate when notification is clicked |
| `created_at` | timestamp | Notification created |
| `updated_at` | timestamp | Last update |

**Relationships**:
- Belongs to `users`

**Indexes**: 
- `['user_id', 'read_at']` - Query unread notifications
- `['user_id', 'created_at']` - Sort by newest

**Usage**: Sent when:
- Application status changes
- New message received
- Verification approved/rejected
- Job recommendations available
- New job matching preferences found

---

## Laravel System Tables

### 25. **cache**
**Purpose**: Laravel's cache store for temporary data

| Column | Type | Description |
|--------|------|-------------|
| `key` | varchar (PK) | Unique cache key |
| `value` | mediumtext | Serialized cached data |
| `expiration` | integer | Unix timestamp when cache expires |

**Usage**: Speeds up application by caching frequently accessed data (job listings, user sessions, query results). Automatically cleaned when expired.

---

### 26. **cache_locks**
**Purpose**: Distributed locking mechanism for cache operations

| Column | Type | Description |
|--------|------|-------------|
| `key` | varchar (PK) | Lock identifier |
| `owner` | varchar | Process/server that owns the lock |
| `expiration` | integer | When lock expires |

**Usage**: Prevents race conditions when multiple servers access same cache keys. Used in queue processing and concurrent operations.

---

### 27. **jobs** (Queue Table)
**Purpose**: Laravel's queue system for background jobs

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique job ID |
| `queue` | varchar (indexed) | Queue name ('default', 'emails', 'notifications') |
| `payload` | longtext | Serialized job data and class |
| `attempts` | tinyint | Number of execution attempts |
| `reserved_at` | integer | When job was picked up by worker |
| `available_at` | integer | When job should be processed |
| `created_at` | integer | Job queued timestamp |

**Usage**: Handles asynchronous tasks:
- Sending emails (application confirmations, password resets)
- Processing notifications
- Generating reports
- Running job matching algorithm (for heavy calculations)

---

### 28. **job_batches**
**Purpose**: Track batch operations for grouped queue jobs

| Column | Type | Description |
|--------|------|-------------|
| `id` | varchar (PK) | Unique batch ID |
| `name` | varchar | Batch name for identification |
| `total_jobs` | integer | Total jobs in batch |
| `pending_jobs` | integer | Jobs not yet processed |
| `failed_jobs` | integer | Jobs that failed |
| `failed_job_ids` | longtext | IDs of failed jobs |
| `options` | mediumtext | Batch configuration |
| `cancelled_at` | integer | When batch was cancelled |
| `created_at` | integer | Batch created |
| `finished_at` | integer | When all jobs completed |

**Usage**: Used for bulk operations like:
- Sending notification to all matched jobseekers for a new job
- Batch email campaigns
- Mass data exports

---

### 29. **failed_jobs**
**Purpose**: Store queue jobs that failed processing

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Unique failed job ID |
| `uuid` | varchar (unique) | Unique identifier for the job |
| `connection` | text | Database connection used |
| `queue` | text | Queue name |
| `payload` | longtext | Job data |
| `exception` | longtext | Error message and stack trace |
| `failed_at` | timestamp | When failure occurred |

**Usage**: Logs jobs that couldn't complete. Admins can review errors and retry jobs. Common failures: email server down, database deadlock, invalid data.

---

### 30. **migrations**
**Purpose**: Track which database migrations have been executed

| Column | Type | Description |
|--------|------|-------------|
| `id` | integer (PK, auto-increment) | Migration execution order |
| `migration` | varchar | Migration filename (e.g., "2025_08_02_000000_create_jobseeker_profiles_table") |
| `batch` | integer | Batch number (migrations run together get same batch) |

**Usage**: Laravel uses this to prevent re-running migrations. When you run `php artisan migrate`, only new migrations are executed.

---

## Summary: Table Categories

### Authentication & Security (5 tables)
- `users` - User accounts
- `password_reset_tokens` - Password recovery
- `sessions` - Login sessions
- `cache`, `cache_locks` - Performance caching

### Employer System (7 tables)
- `employers` - Employer profiles
- `company_types` - Company classifications
- `company_verifications` - Formal company verification
- `informal_employer_verifications` - Household employer verification
- `job_listings` - Job postings
- `job_classifications` - Job-to-category mapping
- `job_skills` - Job skill requirements

### Job Seeker System (9 tables)
- `jobseeker_profiles` - Job seeker profiles
- `work_experiences` - Employment history
- `job_preferences` - Job search preferences
- `jobseeker_skills` - Job seeker skills
- `jobseeker_disabilities` - Disability declarations
- `formal_jobseeker_verifications` - Professional worker verification
- `informal_jobseeker_verifications` - Domestic worker verification
- `formal_job_applications` - Job applications
- `education_levels` - Education reference data

### Skills & Classifications (4 tables)
- `skills` - Master skill list
- `classifications` - Industry categories
- `disabilities` - Disability types
- `company_types` - Company types

### Communication (2 tables)
- `messages` - Direct messaging
- `notifications` - System notifications

### Laravel System (5 tables)
- `jobs` - Queue jobs
- `job_batches` - Batch operations
- `failed_jobs` - Failed queue jobs
- `migrations` - Migration tracking
- `verification_documents` - (Legacy/unused)

---

## Key Relationships Diagram

```
users (central hub)
├── jobseeker_profiles (1:1 if role=seeker)
│   ├── work_experiences (1:many)
│   ├── jobseeker_skills (many:many with skills)
│   ├── jobseeker_disabilities (many:many with disabilities)
│   ├── formal_jobseeker_verifications (1:1)
│   └── informal_jobseeker_verifications (1:1)
│
├── employers (1:1 if role=employer)
│   ├── company_verifications (1:1 if formal)
│   ├── informal_employer_verifications (1:1 if informal)
│   └── job_listings (1:many)
│       ├── formal_job_applications (1:many)
│       ├── job_skills (many:many with skills)
│       └── job_classifications (many:many with classifications)
│
├── job_preferences (1:many)
├── formal_job_applications (1:many)
├── messages (1:many as sender/receiver)
└── notifications (1:many)
```

---

## Database Usage Statistics (Current)

| Table | Row Count | Purpose |
|-------|-----------|---------|
| users | 14 | All registered accounts |
| employers | 4 | Company/household accounts |
| jobseeker_profiles | 6 | Job seeker profiles |
| job_listings | 3 | Active/closed job postings |
| formal_job_applications | 2 | Submitted applications |
| classifications | 15 | Job categories |
| company_types | 12 | Employer types |
| disabilities | 9 | Disability types |
| education_levels | 9 | Education levels |
| skills | 49 | Available skills |
| messages | 6 | Sent messages |
| job_preferences | 2 | Saved preferences |
| jobseeker_skills | 3 | Skills assigned to seekers |
| jobseeker_disabilities | 2 | Disabilities declared |
| informal_employer_verifications | 1 | Pending verification |

**Note**: Cache, queue, and session tables are transient and counts vary constantly.

---

## Important Business Rules

1. **Dual-Track System**:
   - Job seekers choose 'formal' or 'informal' during registration (cannot change)
   - Employers choose 'formal' (company) or 'informal' (household) during registration
   - Formal seekers see only formal jobs; informal seekers see only informal jobs

2. **Verification Requirements**:
   - **Formal Employers**: Business registration, TIN, verification documents
   - **Informal Employers**: Government ID, proof of address
   - **Formal Seekers**: Government ID, education docs, NBI clearance
   - **Informal Seekers**: Basic ID, barangay clearance

3. **Job Matching Algorithm** (JobRecommendationService):
   - **Skills Match**: 40% (highest weight)
   - **Job Preferences**: 30% (title + classification + employment type)
   - **Location Match**: 15% (Remote > Municipality > Barangay > Province)
   - **Salary Match**: 10% (within range = full points)
   - **Education Match**: 5% (meets requirement = full points)
   - **Total**: 100% match score

4. **Application Workflow**:
   - Jobseeker applies → status: 'pending'
   - Employer views → status: 'under_review'
   - Employer shortlists → status: 'shortlisted'
   - Employer accepts → status: 'accepted'
   - Employer rejects → status: 'rejected' (with optional feedback)

5. **Document Downloads**:
   - Filenames use format: `FirstName_LastName_DocumentType.ext`
   - Example: `Gerald_Oraller_Resume.pdf`, `Maria_Santos_Government_ID.jpg`
   - Authorization: Admins download verifications, Employers download their applicants only

---

## Performance Indexes

**Critical Indexes for Fast Queries**:
- `users.email` (unique) - Login lookups
- `sessions.user_id` - Active session checks
- `sessions.last_activity` - Session cleanup
- `job_listings.company_id` - Employer's job listings
- `job_listings.status` - Open jobs filter
- `formal_job_applications.user_id` - User's applications
- `formal_job_applications.job_id` - Job's applicants
- `notifications.user_id + read_at` - Unread notifications
- `messages.sender_id + receiver_id` - Conversation threads
- `company_verifications.employer_id + status` - Pending verifications

---

## Database Maintenance Notes

1. **Soft Deletes**: Only `users` table has soft deletes (`deleted_at` column)
2. **Cascade Deletes**: Most foreign keys use `onDelete('cascade')` - deleting a user removes all related data
3. **Null on Delete**: Verification `verified_by` uses `nullOnDelete()` - if admin is deleted, verification remains but verified_by becomes null
4. **Unique Constraints**: 
   - `users.email` - One account per email
   - `formal_job_applications.user_id + job_id` - No duplicate applications
   - `jobseeker_skills.jobseeker_profile_id + skill_id` - No duplicate skills per profile

---

*Generated: November 28, 2025*  
*Database Version: ALDAWAN v1.0*  
*Total Tables: 40 | Total Size: 1.27 MB*
