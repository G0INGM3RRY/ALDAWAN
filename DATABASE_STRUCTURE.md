# ALDAWAN Database Structure for ERD

This document provides a comprehensive overview of the database structure for creating an Entity Relationship Diagram (ERD).

## Table of Contents
1. [Core Tables](#core-tables)
2. [Job Seeker Tables](#job-seeker-tables)
3. [Employer Tables](#employer-tables)
4. [Job Listing Tables](#job-listing-tables)
5. [Lookup/Reference Tables](#lookupreference-tables)
6. [Junction/Pivot Tables](#junctionpivot-tables)
7. [System Tables](#system-tables)

---

## Core Tables

### users
**Purpose:** Main user authentication and account information

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique user identifier |
| name | VARCHAR(255) | NOT NULL | User's full name |
| email | VARCHAR(255) | NOT NULL, UNIQUE | User's email address |
| email_verified_at | TIMESTAMP | NULLABLE | Email verification timestamp |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| role | VARCHAR(255) | NOT NULL, DEFAULT 'JobSeeker' | User role (JobSeeker, Employer, Admin) |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |
| deleted_at | TIMESTAMP | NULLABLE | Soft delete timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE on `email`

---

### password_reset_tokens
**Purpose:** Password reset token management

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| email | VARCHAR(255) | PRIMARY KEY | User email address |
| token | VARCHAR(255) | NOT NULL | Reset token |
| created_at | TIMESTAMP | NULLABLE | Token creation timestamp |

**Indexes:**
- PRIMARY KEY on `email`

---

### sessions
**Purpose:** User session management

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | VARCHAR(255) | PRIMARY KEY | Session identifier |
| user_id | BIGINT UNSIGNED | NULLABLE, INDEX | Foreign key to users table |
| ip_address | VARCHAR(45) | NULLABLE | User's IP address |
| user_agent | TEXT | NULLABLE | Browser user agent |
| payload | LONGTEXT | NOT NULL | Session data |
| last_activity | INTEGER | NOT NULL, INDEX | Last activity timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- INDEX on `user_id`
- INDEX on `last_activity`

**Foreign Keys:**
- `user_id` → `users(id)`

---

## Job Seeker Tables

### jobseeker_profiles
**Purpose:** Extended profile information for job seekers

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Profile identifier |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to users table |
| job_seeker_type | VARCHAR(255) | NULLABLE | Type of job seeker (formal/informal) |
| first_name | VARCHAR(255) | NOT NULL | Job seeker's first name |
| middle_name | VARCHAR(255) | NULLABLE | Job seeker's middle name |
| last_name | VARCHAR(255) | NOT NULL | Job seeker's last name |
| suffix | VARCHAR(255) | NULLABLE | Name suffix (Jr., Sr., etc.) |
| birthday | DATE | NULLABLE | Date of birth |
| sex | VARCHAR(255) | NULLABLE | Gender |
| photo | VARCHAR(255) | NULLABLE | Profile photo path |
| civilstatus | VARCHAR(255) | NULLABLE | Civil status (Single, Married, etc.) |
| street | VARCHAR(255) | NULLABLE | Street address |
| barangay | VARCHAR(255) | NULLABLE | Barangay |
| municipality | VARCHAR(255) | NULLABLE | Municipality |
| province | VARCHAR(255) | NULLABLE | Province |
| religion | VARCHAR(255) | NULLABLE | Religion |
| contactnumber | VARCHAR(255) | NULLABLE | Contact phone number |
| email | VARCHAR(255) | NULLABLE | Contact email |
| is_4ps | BOOLEAN | NOT NULL, DEFAULT FALSE | 4Ps beneficiary status |
| employmentstatus | VARCHAR(255) | NULLABLE | Current employment status |
| education | JSON | NULLABLE | Education records (for formal jobseekers) |
| education_level_id | BIGINT UNSIGNED | NULLABLE, FOREIGN KEY | Foreign key to education_levels |
| institution_name | VARCHAR(255) | NULLABLE | Educational institution name |
| graduation_year | YEAR | NULLABLE | Year of graduation |
| gpa | DECIMAL(3,2) | NULLABLE | Grade point average |
| degree_field | VARCHAR(255) | NULLABLE | Field of study |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Indexes:**
- PRIMARY KEY on `id`

**Foreign Keys:**
- `user_id` → `users(id)` ON DELETE CASCADE
- `education_level_id` → `education_levels(id)` ON DELETE SET NULL

---

### work_experiences
**Purpose:** Job seeker work experience records

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Experience identifier |
| jobseeker_profile_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to jobseeker_profiles |
| job_title | VARCHAR(255) | NOT NULL | Position title |
| company_name | VARCHAR(255) | NOT NULL | Company name |
| description | TEXT | NULLABLE | Job description |
| start_date | DATE | NOT NULL | Start date of employment |
| end_date | DATE | NULLABLE | End date (NULL for current) |
| is_current | BOOLEAN | NOT NULL, DEFAULT FALSE | Currently employed flag |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Indexes:**
- PRIMARY KEY on `id`

**Foreign Keys:**
- `jobseeker_profile_id` → `jobseeker_profiles(id)` ON DELETE CASCADE

---

### formal_jobseeker_verifications
**Purpose:** Verification documents for formal job seekers (professional/office jobs)

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Verification identifier |
| jobseeker_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to jobseeker_profiles |
| status | ENUM | NOT NULL, DEFAULT 'pending' | Verification status |
| government_id_path | VARCHAR(255) | NULLABLE | Government ID document path |
| educational_document_path | VARCHAR(255) | NULLABLE | Diploma/certificate path |
| skills_certificate_path | VARCHAR(255) | NULLABLE | Professional certification path |
| nbi_clearance_path | VARCHAR(255) | NULLABLE | NBI clearance document path |
| verification_notes | TEXT | NULLABLE | Admin notes |
| verified_by | BIGINT UNSIGNED | NULLABLE, FOREIGN KEY | Admin user who verified |
| verified_at | TIMESTAMP | NULLABLE | Verification timestamp |
| submitted_at | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Submission timestamp |
| rejection_reason | TEXT | NULLABLE | Reason for rejection |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Enums:**
- `status`: 'pending', 'approved', 'rejected'

**Indexes:**
- PRIMARY KEY on `id`
- INDEX on (`jobseeker_id`, `status`)

**Foreign Keys:**
- `jobseeker_id` → `jobseeker_profiles(id)` ON DELETE CASCADE
- `verified_by` → `users(id)` ON DELETE SET NULL

---

### informal_jobseeker_verifications
**Purpose:** Verification documents for informal job seekers (household/manual labor jobs)

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Verification identifier |
| jobseeker_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to jobseeker_profiles |
| status | ENUM | NOT NULL, DEFAULT 'pending' | Verification status |
| basic_id_path | VARCHAR(255) | NULLABLE | Any government-issued ID path |
| barangay_clearance_path | VARCHAR(255) | NULLABLE | Barangay clearance path |
| health_certificate_path | VARCHAR(255) | NULLABLE | Health certificate path |
| verification_notes | TEXT | NULLABLE | Admin notes |
| verified_by | BIGINT UNSIGNED | NULLABLE, FOREIGN KEY | Admin user who verified |
| verified_at | TIMESTAMP | NULLABLE | Verification timestamp |
| submitted_at | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Submission timestamp |
| rejection_reason | TEXT | NULLABLE | Reason for rejection |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Enums:**
- `status`: 'pending', 'approved', 'rejected'

**Indexes:**
- PRIMARY KEY on `id`
- INDEX on (`jobseeker_id`, `status`)

**Foreign Keys:**
- `jobseeker_id` → `jobseeker_profiles(id)` ON DELETE CASCADE
- `verified_by` → `users(id)` ON DELETE SET NULL

---

### job_preferences
**Purpose:** Job seeker preferences for job matching

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Preference identifier |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to users table |
| preferred_job_title | VARCHAR(255) | NOT NULL | Desired job title |
| preferred_classification | VARCHAR(255) | NOT NULL | Preferred job classification |
| min_salary | DECIMAL(10,2) | NULLABLE | Minimum salary expectation |
| max_salary | DECIMAL(10,2) | NULLABLE | Maximum salary expectation |
| preferred_location | VARCHAR(255) | NULLABLE | Preferred work location |
| preferred_employment_type | ENUM | NULLABLE | Preferred employment type |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Enums:**
- `preferred_employment_type`: 'full-time', 'part-time', 'contract', 'freelance', 'internship'

**Indexes:**
- PRIMARY KEY on `id`

**Foreign Keys:**
- `user_id` → `users(id)` ON DELETE CASCADE

---

## Employer Tables

### employers
**Purpose:** Employer/company information

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Employer identifier |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to users table |
| company_name | VARCHAR(255) | NOT NULL | Company name |
| street | VARCHAR(255) | NULLABLE | Street address |
| barangay | VARCHAR(255) | NULLABLE | Barangay |
| municipality | VARCHAR(255) | NULLABLE | Municipality |
| province | VARCHAR(255) | NULLABLE | Province |
| company_logo | VARCHAR(255) | NULLABLE | Logo image path |
| employer_type | ENUM | NOT NULL, DEFAULT 'formal' | Employer type |
| company_type_id | BIGINT UNSIGNED | NULLABLE, FOREIGN KEY | Foreign key to company_types |
| company_description | TEXT | NULLABLE | Company description |
| website_url | VARCHAR(255) | NULLABLE | Company website URL |
| linkedin_url | VARCHAR(255) | NULLABLE | LinkedIn profile URL |
| company_size_min | INTEGER | NULLABLE | Minimum company size |
| company_size_max | INTEGER | NULLABLE | Maximum company size |
| founded_year | YEAR | NULLABLE | Year company was founded |
| is_verified | BOOLEAN | NOT NULL, DEFAULT FALSE | Verification status |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Enums:**
- `employer_type`: 'formal', 'informal'

**Indexes:**
- PRIMARY KEY on `id`

**Foreign Keys:**
- `user_id` → `users(id)` ON DELETE CASCADE
- `company_type_id` → `company_types(id)` ON DELETE SET NULL

---

### company_verifications
**Purpose:** Verification documents for formal employers

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Verification identifier |
| employer_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to employers table |
| status | ENUM | NOT NULL, DEFAULT 'pending' | Verification status |
| business_registration_number | VARCHAR(255) | NULLABLE | Business registration number |
| tax_id | VARCHAR(255) | NULLABLE | Tax identification number |
| verification_document_path | VARCHAR(255) | NULLABLE | Verification document path |
| verification_notes | TEXT | NULLABLE | Admin notes |
| verified_by | BIGINT UNSIGNED | NULLABLE, FOREIGN KEY | Admin user who verified |
| verified_at | TIMESTAMP | NULLABLE | Verification timestamp |
| submitted_at | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Submission timestamp |
| rejection_reason | TEXT | NULLABLE | Reason for rejection |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Enums:**
- `status`: 'pending', 'approved', 'rejected', 'requires_info'

**Indexes:**
- PRIMARY KEY on `id`
- INDEX on (`employer_id`, `status`)

**Foreign Keys:**
- `employer_id` → `employers(id)` ON DELETE CASCADE
- `verified_by` → `users(id)` ON DELETE SET NULL

---

### informal_employer_verifications
**Purpose:** Verification documents for informal employers (households)

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Verification identifier |
| employer_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to employers table |
| status | ENUM | NOT NULL, DEFAULT 'pending' | Verification status |
| valid_id_path | VARCHAR(255) | NULLABLE | Valid ID document path |
| proof_of_address_path | VARCHAR(255) | NULLABLE | Proof of address path |
| barangay_clearance_path | VARCHAR(255) | NULLABLE | Barangay clearance path |
| verification_notes | TEXT | NULLABLE | Admin notes |
| verified_by | BIGINT UNSIGNED | NULLABLE, FOREIGN KEY | Admin user who verified |
| verified_at | TIMESTAMP | NULLABLE | Verification timestamp |
| submitted_at | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Submission timestamp |
| rejection_reason | TEXT | NULLABLE | Reason for rejection |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Enums:**
- `status`: 'pending', 'approved', 'rejected', 'requires_info'

**Indexes:**
- PRIMARY KEY on `id`
- INDEX on (`employer_id`, `status`)

**Foreign Keys:**
- `employer_id` → `employers(id)` ON DELETE CASCADE
- `verified_by` → `users(id)` ON DELETE SET NULL

---

## Job Listing Tables

### job_listings
**Purpose:** Job posting information

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Job listing identifier |
| job_title | VARCHAR(255) | NOT NULL | Job title |
| description | TEXT | NOT NULL | Job description |
| company_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to users (employer) |
| location | VARCHAR(255) | NOT NULL | Job location |
| salary | DECIMAL(10,2) | NOT NULL | Salary amount |
| employment_type | ENUM | NOT NULL, DEFAULT 'full_time' | Type of employment |
| requirements | TEXT | NOT NULL | Job requirements |
| posted_at | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Posting timestamp |
| status | ENUM | NOT NULL, DEFAULT 'open' | Job status |
| classification | VARCHAR(255) | NOT NULL | Job classification |
| job_type | ENUM | NOT NULL, DEFAULT 'formal' | Job type |
| minimum_education_level_id | BIGINT UNSIGNED | NULLABLE, FOREIGN KEY | Minimum required education |
| minimum_experience_years | INTEGER | NOT NULL, DEFAULT 0 | Minimum years of experience |
| benefits | TEXT | NULLABLE | Job benefits |
| remote_work_available | BOOLEAN | NOT NULL, DEFAULT FALSE | Remote work option |
| positions_available | INTEGER | NOT NULL, DEFAULT 1 | Number of positions |
| disability_restrictions | JSON | NULLABLE | Disability restrictions |
| accessibility_notes | TEXT | NULLABLE | Accessibility information |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Enums:**
- `employment_type`: 'full_time', 'part_time', 'contract', 'temporary', 'internship'
- `status`: 'open', 'closed', 'filled'
- `job_type`: 'formal', 'informal'

**Indexes:**
- PRIMARY KEY on `id`

**Foreign Keys:**
- `company_id` → `users(id)` ON DELETE CASCADE
- `minimum_education_level_id` → `education_levels(id)` ON DELETE SET NULL

---

### formal_job_applications
**Purpose:** Job applications from job seekers

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Application identifier |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Job seeker who applied |
| job_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Job being applied to |
| status | ENUM | NOT NULL, DEFAULT 'pending' | Application status |
| cover_letter | TEXT | NULLABLE | Cover letter text |
| resume_file_path | VARCHAR(255) | NULLABLE | Resume file path |
| additional_documents | JSON | NULLABLE | Other document paths |
| applied_at | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Application submission time |
| reviewed_at | TIMESTAMP | NULLABLE | First review timestamp |
| status_updated_at | TIMESTAMP | NULLABLE | Last status change time |
| employer_notes | TEXT | NULLABLE | Private employer notes |
| rejection_reason | TEXT | NULLABLE | Rejection feedback |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Enums:**
- `status`: 'pending', 'under_review', 'shortlisted', 'accepted', 'rejected'

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE on (`user_id`, `job_id`) with name 'unique_user_job_application'

**Foreign Keys:**
- `user_id` → `users(id)` ON DELETE CASCADE
- `job_id` → `job_listings(id)` ON DELETE CASCADE

---

## Lookup/Reference Tables

### skills
**Purpose:** Master list of skills

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Skill identifier |
| name | VARCHAR(255) | NOT NULL, UNIQUE | Skill name |
| description | TEXT | NULLABLE | Skill description |
| category | VARCHAR(255) | NULLABLE | Skill category (technical, soft, language) |
| is_active | BOOLEAN | NOT NULL, DEFAULT TRUE | Active status |
| usage_count | INTEGER | NOT NULL, DEFAULT 0 | Number of times used |
| is_custom | BOOLEAN | NOT NULL, DEFAULT FALSE | Custom skill flag |
| show_in_list | BOOLEAN | NOT NULL, DEFAULT TRUE | Show in dropdown lists |
| last_used_at | TIMESTAMP | NULLABLE | Last usage timestamp |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE on `name`
- INDEX on (`category`, `show_in_list`, `usage_count`)
- INDEX on (`category`, `is_custom`, `created_at`)

---

### education_levels
**Purpose:** Education level reference data

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Level identifier |
| name | VARCHAR(255) | NOT NULL, UNIQUE | Level name |
| description | TEXT | NULLABLE | Level description |
| level_order | INTEGER | NOT NULL, DEFAULT 0 | Ordering sequence |
| is_active | BOOLEAN | NOT NULL, DEFAULT TRUE | Active status |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE on `name`

---

### classifications
**Purpose:** Job classification/industry categories

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Classification identifier |
| name | VARCHAR(255) | NOT NULL, UNIQUE | Classification name |
| description | TEXT | NULLABLE | Classification description |
| code | VARCHAR(10) | UNIQUE, NULLABLE | Classification code |
| is_active | BOOLEAN | NOT NULL, DEFAULT TRUE | Active status |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE on `name`
- UNIQUE on `code`

---

### disabilities
**Purpose:** Types of disabilities reference data

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Disability identifier |
| name | VARCHAR(255) | NOT NULL, UNIQUE | Disability name |
| description | TEXT | NULLABLE | Disability description |
| category | VARCHAR(255) | NULLABLE | Category (physical, cognitive, sensory) |
| is_active | BOOLEAN | NOT NULL, DEFAULT TRUE | Active status |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE on `name`

---

### company_types
**Purpose:** Company type categories

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Type identifier |
| name | VARCHAR(255) | NOT NULL, UNIQUE | Type name |
| description | TEXT | NULLABLE | Type description |
| is_active | BOOLEAN | NOT NULL, DEFAULT TRUE | Active status |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE on `name`

---

## Junction/Pivot Tables

### jobseeker_skills
**Purpose:** Many-to-many relationship between job seekers and skills

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Record identifier |
| jobseeker_profile_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to jobseeker_profiles |
| skill_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to skills |
| proficiency_level | ENUM | NULLABLE | Skill proficiency level |
| years_experience | INTEGER | NULLABLE | Years of experience with skill |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Enums:**
- `proficiency_level`: 'beginner', 'intermediate', 'advanced', 'expert'

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE on (`jobseeker_profile_id`, `skill_id`)

**Foreign Keys:**
- `jobseeker_profile_id` → `jobseeker_profiles(id)` ON DELETE CASCADE
- `skill_id` → `skills(id)` ON DELETE CASCADE

---

### jobseeker_disabilities
**Purpose:** Many-to-many relationship between job seekers and disabilities

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Record identifier |
| jobseeker_profile_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to jobseeker_profiles |
| disability_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to disabilities |
| accommodation_needs | TEXT | NULLABLE | Accommodation requirements |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE on (`jobseeker_profile_id`, `disability_id`)

**Foreign Keys:**
- `jobseeker_profile_id` → `jobseeker_profiles(id)` ON DELETE CASCADE
- `disability_id` → `disabilities(id)` ON DELETE CASCADE

---

### job_skills
**Purpose:** Many-to-many relationship between job listings and required skills

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Record identifier |
| job_listing_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to job_listings |
| skill_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to skills |
| required_level | ENUM | NOT NULL, DEFAULT 'intermediate' | Required skill level |
| is_required | BOOLEAN | NOT NULL, DEFAULT TRUE | Required vs nice-to-have |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Enums:**
- `required_level`: 'beginner', 'intermediate', 'advanced', 'expert'

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE on (`job_listing_id`, `skill_id`)

**Foreign Keys:**
- `job_listing_id` → `job_listings(id)` ON DELETE CASCADE
- `skill_id` → `skills(id)` ON DELETE CASCADE

---

### job_classifications
**Purpose:** Many-to-many relationship between job listings and classifications

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Record identifier |
| job_listing_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to job_listings |
| classification_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Foreign key to classifications |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE on (`job_listing_id`, `classification_id`)

**Foreign Keys:**
- `job_listing_id` → `job_listings(id)` ON DELETE CASCADE
- `classification_id` → `classifications(id)` ON DELETE CASCADE

---

## Communication Tables

### messages
**Purpose:** User-to-user messaging

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Message identifier |
| sender_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Message sender |
| receiver_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Message receiver |
| message_content | TEXT | NOT NULL | Message text |
| sent_at | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Sent timestamp |
| read_at | TIMESTAMP | NULLABLE | Read timestamp |
| is_deleted_by_sender | BOOLEAN | NOT NULL, DEFAULT FALSE | Sender deletion flag |
| is_deleted_by_receiver | BOOLEAN | NOT NULL, DEFAULT FALSE | Receiver deletion flag |
| message_type | VARCHAR(255) | NOT NULL, DEFAULT 'text' | Message type (text, system) |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- INDEX on (`sender_id`, `receiver_id`)
- INDEX on `sent_at`

**Foreign Keys:**
- `sender_id` → `users(id)` ON DELETE CASCADE
- `receiver_id` → `users(id)` ON DELETE CASCADE

---

### notifications
**Purpose:** User notifications

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Notification identifier |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Notification recipient |
| type | VARCHAR(255) | NOT NULL | Notification type |
| title | VARCHAR(255) | NOT NULL | Notification title |
| message | TEXT | NOT NULL | Notification message |
| data | JSON | NULLABLE | Additional notification data |
| read_at | TIMESTAMP | NULLABLE | Read timestamp |
| is_actionable | BOOLEAN | NOT NULL, DEFAULT FALSE | Requires action flag |
| action_url | VARCHAR(255) | NULLABLE | Action URL |
| created_at | TIMESTAMP | NULLABLE | Record creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Record update timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- INDEX on (`user_id`, `read_at`)
- INDEX on (`user_id`, `created_at`)

**Foreign Keys:**
- `user_id` → `users(id)` ON DELETE CASCADE

---

## System Tables

### cache
**Purpose:** Application cache storage

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| key | VARCHAR(255) | PRIMARY KEY | Cache key |
| value | MEDIUMTEXT | NOT NULL | Cached value |
| expiration | INTEGER | NOT NULL | Expiration timestamp |

**Indexes:**
- PRIMARY KEY on `key`

---

### cache_locks
**Purpose:** Cache locking mechanism

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| key | VARCHAR(255) | PRIMARY KEY | Lock key |
| owner | VARCHAR(255) | NOT NULL | Lock owner |
| expiration | INTEGER | NOT NULL | Lock expiration |

**Indexes:**
- PRIMARY KEY on `key`

---

### jobs
**Purpose:** Laravel queue jobs

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Job identifier |
| queue | VARCHAR(255) | NOT NULL, INDEX | Queue name |
| payload | LONGTEXT | NOT NULL | Job payload |
| attempts | TINYINT UNSIGNED | NOT NULL | Attempt count |
| reserved_at | INTEGER UNSIGNED | NULLABLE | Reserved timestamp |
| available_at | INTEGER UNSIGNED | NOT NULL | Available timestamp |
| created_at | INTEGER UNSIGNED | NOT NULL | Creation timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- INDEX on `queue`

---

### job_batches
**Purpose:** Laravel job batch tracking

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | VARCHAR(255) | PRIMARY KEY | Batch identifier |
| name | VARCHAR(255) | NOT NULL | Batch name |
| total_jobs | INTEGER | NOT NULL | Total job count |
| pending_jobs | INTEGER | NOT NULL | Pending job count |
| failed_jobs | INTEGER | NOT NULL | Failed job count |
| failed_job_ids | LONGTEXT | NOT NULL | Failed job IDs |
| options | MEDIUMTEXT | NULLABLE | Batch options |
| cancelled_at | INTEGER | NULLABLE | Cancellation timestamp |
| created_at | INTEGER | NOT NULL | Creation timestamp |
| finished_at | INTEGER | NULLABLE | Completion timestamp |

**Indexes:**
- PRIMARY KEY on `id`

---

### failed_jobs
**Purpose:** Failed queue jobs

| Column | Data Type | Constraints | Description |
|--------|-----------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Failed job identifier |
| uuid | VARCHAR(255) | NOT NULL, UNIQUE | Job UUID |
| connection | TEXT | NOT NULL | Connection name |
| queue | TEXT | NOT NULL | Queue name |
| payload | LONGTEXT | NOT NULL | Job payload |
| exception | LONGTEXT | NOT NULL | Exception details |
| failed_at | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Failure timestamp |

**Indexes:**
- PRIMARY KEY on `id`
- UNIQUE on `uuid`

---

## Entity Relationships Summary

### One-to-One Relationships
- `users` → `jobseeker_profiles` (one user has one job seeker profile)
- `users` → `employers` (one user has one employer profile)

### One-to-Many Relationships
- `users` → `job_preferences` (one user has many preferences)
- `users` → `notifications` (one user has many notifications)
- `users` → `messages` (as sender)
- `users` → `messages` (as receiver)
- `users` → `job_listings` (one employer creates many job listings)
- `users` → `formal_job_applications` (one job seeker has many applications)
- `jobseeker_profiles` → `work_experiences` (one profile has many work experiences)
- `jobseeker_profiles` → `formal_jobseeker_verifications` (one profile has one or more verifications)
- `jobseeker_profiles` → `informal_jobseeker_verifications` (one profile has one or more verifications)
- `employers` → `company_verifications` (one employer has one or more verifications)
- `employers` → `informal_employer_verifications` (one employer has one or more verifications)
- `job_listings` → `formal_job_applications` (one job has many applications)
- `education_levels` → `jobseeker_profiles` (one level has many profiles)
- `education_levels` → `job_listings` (one level required by many jobs)
- `company_types` → `employers` (one type has many employers)

### Many-to-Many Relationships
- `jobseeker_profiles` ↔ `skills` (through `jobseeker_skills`)
- `jobseeker_profiles` ↔ `disabilities` (through `jobseeker_disabilities`)
- `job_listings` ↔ `skills` (through `job_skills`)
- `job_listings` ↔ `classifications` (through `job_classifications`)

---

## Key Notes for ERD Creation

1. **User Roles**: The `users` table has a `role` column that determines whether a user is a JobSeeker, Employer, or Admin.

2. **Formal vs Informal**: The system distinguishes between:
   - Formal job seekers (professional/office jobs) and informal job seekers (household/manual labor)
   - Formal employers (companies) and informal employers (households)
   - Formal and informal job types

3. **Verification System**: Separate verification tables exist for:
   - Formal job seekers
   - Informal job seekers
   - Formal employers (companies)
   - Informal employers (households)

4. **Soft Deletes**: The `users` table implements soft deletes with a `deleted_at` timestamp.

5. **JSON Fields**: Some tables use JSON columns for flexible data storage:
   - `jobseeker_profiles.education` (formal job seeker education records)
   - `job_listings.disability_restrictions`
   - `formal_job_applications.additional_documents`
   - `notifications.data`

6. **Cascade Deletes**: Most foreign keys use `ON DELETE CASCADE` to maintain referential integrity.

7. **Indexes**: Tables have indexes on foreign keys and frequently queried columns for performance.

8. **Timestamps**: Most tables include `created_at` and `updated_at` timestamps for audit trails.

---

## Database Design Patterns

### Profile Pattern
- User authentication is separated from profile data
- `users` table for authentication
- `jobseeker_profiles` and `employers` tables for extended profile information

### Verification Pattern
- Separate verification tables for different user types
- Track verification status, documents, and admin actions
- Include timestamps for audit trail

### Skill Management Pattern
- Central `skills` table with usage tracking
- Junction tables for many-to-many relationships
- Support for custom skills and proficiency levels

### Messaging Pattern
- Direct user-to-user messaging
- Soft delete flags for both sender and receiver
- Read receipts with timestamps

### Job Application Pattern
- Track application lifecycle with status enum
- Store application documents
- Include employer feedback and notes
- Prevent duplicate applications with unique constraint
