# ALDAWAN Database ERD - Quick Summary

This is a quick reference guide for creating an ERD. For complete details, see [DATABASE_STRUCTURE.md](DATABASE_STRUCTURE.md).

## All Tables (32 total)

### Core Tables (3)
1. **users** - User authentication and accounts
2. **password_reset_tokens** - Password reset tokens
3. **sessions** - User sessions

### Job Seeker Tables (6)
4. **jobseeker_profiles** - Job seeker profile information
5. **work_experiences** - Job seeker work history
6. **formal_jobseeker_verifications** - Verification for formal job seekers
7. **informal_jobseeker_verifications** - Verification for informal job seekers
8. **job_preferences** - Job seeker job preferences

### Employer Tables (4)
9. **employers** - Employer/company profiles
10. **company_verifications** - Verification for formal employers
11. **informal_employer_verifications** - Verification for informal employers

### Job Listing Tables (2)
12. **job_listings** - Job postings
13. **formal_job_applications** - Job applications

### Lookup/Reference Tables (5)
14. **skills** - Master skills list
15. **education_levels** - Education level reference
16. **classifications** - Job classifications/industries
17. **disabilities** - Disability types
18. **company_types** - Company type categories

### Junction/Pivot Tables (4)
19. **jobseeker_skills** - Job seeker ↔ Skills
20. **jobseeker_disabilities** - Job seeker ↔ Disabilities
21. **job_skills** - Job listings ↔ Skills
22. **job_classifications** - Job listings ↔ Classifications

### Communication Tables (2)
23. **messages** - User-to-user messages
24. **notifications** - User notifications

### System Tables (6)
25. **cache** - Application cache
26. **cache_locks** - Cache locks
27. **jobs** - Queue jobs
28. **job_batches** - Job batches
29. **failed_jobs** - Failed jobs

## Key Relationships

### One-to-One
- users → jobseeker_profiles
- users → employers

### One-to-Many
- users → job_preferences
- users → job_listings (as employer)
- users → formal_job_applications (as job seeker)
- users → messages (as sender/receiver)
- users → notifications
- jobseeker_profiles → work_experiences
- jobseeker_profiles → formal_jobseeker_verifications
- jobseeker_profiles → informal_jobseeker_verifications
- employers → company_verifications
- employers → informal_employer_verifications
- job_listings → formal_job_applications
- education_levels → jobseeker_profiles
- education_levels → job_listings (minimum_education_level_id)
- company_types → employers

### Many-to-Many
- jobseeker_profiles ↔ skills (via jobseeker_skills)
- jobseeker_profiles ↔ disabilities (via jobseeker_disabilities)
- job_listings ↔ skills (via job_skills)
- job_listings ↔ classifications (via job_classifications)

## ERD Diagram Organization Suggestions

### Core User System
```
users (center)
├── jobseeker_profiles (left branch)
│   ├── work_experiences
│   ├── formal_jobseeker_verifications
│   ├── informal_jobseeker_verifications
│   ├── jobseeker_skills → skills
│   └── jobseeker_disabilities → disabilities
│
├── employers (right branch)
│   ├── company_verifications
│   ├── informal_employer_verifications
│   └── company_types
│
├── job_listings (center-right)
│   ├── formal_job_applications
│   ├── job_skills → skills
│   ├── job_classifications → classifications
│   └── education_levels (minimum requirement)
│
├── job_preferences (center-left)
├── messages (bottom-left)
└── notifications (bottom-right)
```

### Reference Data (separate section)
- skills
- education_levels
- classifications
- disabilities
- company_types

### System Tables (separate section)
- cache, cache_locks
- jobs, job_batches, failed_jobs
- sessions, password_reset_tokens

## Important Enum Values

### users.role
- JobSeeker
- Employer
- Admin

### jobseeker_profiles.job_seeker_type
- formal
- informal

### employers.employer_type
- formal
- informal

### job_listings.status
- open
- closed
- filled

### job_listings.employment_type
- full_time
- part_time
- contract
- temporary
- internship

### job_listings.job_type
- formal
- informal

### formal_job_applications.status
- pending
- under_review
- shortlisted
- accepted
- rejected

### jobseeker_skills.proficiency_level / job_skills.required_level
- beginner
- intermediate
- advanced
- expert

### All verification tables status
- pending
- approved
- rejected
- requires_info (for company verifications only)

## Color Coding Suggestions for ERD

- **Blue**: Core user and authentication tables
- **Green**: Job seeker related tables
- **Orange**: Employer related tables
- **Purple**: Job listing and application tables
- **Yellow**: Reference/lookup tables
- **Gray**: Junction/pivot tables
- **Light Blue**: Communication tables
- **Dark Gray**: System tables

## Key Fields for Relationships

### Foreign Keys to users table
- jobseeker_profiles.user_id
- employers.user_id
- job_preferences.user_id
- messages.sender_id / receiver_id
- notifications.user_id
- formal_job_applications.user_id
- job_listings.company_id (employer)
- sessions.user_id

### Foreign Keys to jobseeker_profiles
- work_experiences.jobseeker_profile_id
- formal_jobseeker_verifications.jobseeker_id
- informal_jobseeker_verifications.jobseeker_id
- jobseeker_skills.jobseeker_profile_id
- jobseeker_disabilities.jobseeker_profile_id

### Foreign Keys to employers
- company_verifications.employer_id
- informal_employer_verifications.employer_id

### Foreign Keys to job_listings
- formal_job_applications.job_id
- job_skills.job_listing_id
- job_classifications.job_listing_id

### Foreign Keys to lookup tables
- jobseeker_profiles.education_level_id → education_levels.id
- employers.company_type_id → company_types.id
- job_listings.minimum_education_level_id → education_levels.id
- jobseeker_skills.skill_id → skills.id
- job_skills.skill_id → skills.id
- jobseeker_disabilities.disability_id → disabilities.id
- job_classifications.classification_id → classifications.id
