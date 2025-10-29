# ALDAWAN Job Portal - Entity Relationship Diagram

## 📊 Generated ERD Files

### **Main ERD Files:**
- `ALDAWAN_ERD_final.png` - **Main ERD diagram** (high-resolution PNG)
- `ALDAWAN_ERD.svg` - Vector format (scalable)
- `ALDAWAN_ERD.dot` - Source DOT file (editable)

### **Alternative Generated Files:**
- `graph.png` - Laravel ERD generator output
- `graph.dot` - Laravel ERD generator DOT source

---

## 🎨 **ERD Layout Design**

### **Left Side - Main Core Tables:**
- 🔵 **users** - Central user authentication
- 🟢 **jobseeker_profiles** - Job seeker information  
- 🟠 **employers** - Company/employer profiles
- 🟡 **job_listings** - Job postings
- 🔴 **formal_job_applications** - Application submissions

### **Center/Right - Supporting Tables:**
- 🟢 **work_experiences** - Job seeker work history
- 🟢 **jobseeker_skills** - Skills assignments (pivot)
- 🟠 **company_verifications** - Employer verification
- 🟡 **job_classifications** - Job categorization (pivot)
- 🟡 **job_skills** - Job skill requirements (pivot)
- 🔴 **messages** - User communication

### **Far Right - Lookup/Reference Tables:**
- 🟣 **skills** - Master skills catalog
- 🟣 **education_levels** - Education level definitions
- 🟣 **classifications** - Job categories
- 🟣 **disabilities** - Disability types
- 🟣 **company_types** - Company classifications
- 🔵 **admin_users** - Admin user roles

---

## 🔗 **Key Relationships**

### **1:1 Relationships (One-to-One):**
- users ↔ jobseeker_profiles
- users ↔ employers  
- users ↔ admin_users
- employers ↔ company_verifications

### **1:N Relationships (One-to-Many):**
- users → job_listings (employer posts jobs)
- users → formal_job_applications (jobseeker applies)
- users → messages (sender/receiver)
- jobseeker_profiles → work_experiences
- jobseeker_profiles → jobseeker_skills
- job_listings → formal_job_applications
- job_listings → job_classifications
- job_listings → job_skills

### **N:1 Relationships (Many-to-One):**
- jobseeker_profiles → education_levels
- employers → company_types
- job_listings → education_levels (minimum requirement)

---

## 🎯 **Color Coding**

- 🔵 **Blue** - User management (users, admin_users)
- 🟢 **Green** - Job seeker system (profiles, skills, experience)
- 🟠 **Orange** - Employer system (companies, verification)
- 🟡 **Yellow** - Job listing system (jobs, applications)
- 🔴 **Red** - Application/communication (applications, messages)
- 🟣 **Purple** - Lookup/reference tables (skills, education, etc.)

---

## 🛠 **Technical Details**

### **Generated Using:**
- Laravel ERD Generator package
- Custom DOT file configuration
- Graphviz rendering engine

### **Layout Features:**
- Left-to-Right (LR) orientation
- Orthogonal edge routing
- Color-coded entity grouping
- Relationship cardinality labels
- High-resolution output (150 DPI)

### **File Specifications:**
- **PNG**: 652KB, high-resolution bitmap
- **SVG**: 46KB, vector graphics (scalable)
- **DOT**: 7KB, source code (editable)

---

## 📝 **Usage Instructions**

### **Viewing the ERD:**
1. Open `ALDAWAN_ERD_final.png` for best quality
2. Use `ALDAWAN_ERD.svg` for web/document embedding
3. Edit `ALDAWAN_ERD.dot` to customize layout

### **Regenerating ERD:**
```bash
# Regenerate PNG
dot -Tpng ALDAWAN_ERD.dot -o ALDAWAN_ERD_final.png

# Regenerate SVG  
dot -Tsvg ALDAWAN_ERD.dot -o ALDAWAN_ERD.svg

# Regenerate with Laravel generator
php artisan generate:erd --format=png
```

### **Customization:**
- Edit colors in DOT file `fillcolor` attributes
- Modify layout with `rank=same` groupings
- Adjust spacing with `nodesep` and `ranksep`
- Change orientation with `rankdir` (LR, TB, BT, RL)

---

## 📋 **Database Statistics**

- **Total Tables**: 16 main entities
- **Lookup Tables**: 6 reference tables  
- **Pivot Tables**: 3 many-to-many relationships
- **Total Migrations**: 33 migration files
- **Relationship Types**: 1:1, 1:N, N:1, M:N

---

## 🎯 **Next Steps**

1. **Review ERD** - Validate all relationships are correct
2. **Data Dictionary** - Create detailed field documentation  
3. **Database Optimization** - Consider indexing strategy
4. **Documentation** - Update system documentation with ERD

The ERD is now ready for presentation, documentation, and database design validation! 🚀