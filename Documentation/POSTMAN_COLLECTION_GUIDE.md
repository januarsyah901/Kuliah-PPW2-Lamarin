# Panduan Postman Collection - LAMARIN API

## 📥 Cara Import Collection ke Postman

### Method 1: Import dari File JSON
1. Buka Postman
2. Klik **File** → **Import**
3. Pilih tab **Upload Files**
4. Browse dan pilih file: `Lamarin_API_Collection.json`
5. Klik **Import**

### Method 2: Import dari Link (Jika sudah di-host)
1. Klik **File** → **Import**
2. Pilih tab **Link**
3. Paste URL collection
4. Klik **Import**

---

## ⚙️ Setup Environment Variables

Setelah import, setup environment variables:

### Langkah 1: Buat Environment Baru
1. Klik ikon **Environment** (di sidebar kiri)
2. Klik **Create New Environment**
3. Nama: `LAMARIN Development`

### Langkah 2: Tambahkan Variables
```
base_url          = http://localhost:8000
applicant_token   = (auto-filled saat login)
admin_token       = (auto-filled saat login)
job_id            = 1
application_id    = (auto-filled saat submit aplikasi)
```

### Langkah 3: Save dan Select Environment
1. Klik **Save**
2. Pilih environment dari dropdown (kanan atas)

---

## 🚀 TESTING FLOW - Urutan Eksekusi

### PHASE 1: AUTHENTICATION (Harus dilakukan dulu!)

#### Step 1: Register Applicant User
```
Request: POST /api/register
Body:
{
  "name": "John Applicant",
  "email": "applicant{{$timestamp}}@test.com",
  "password": "password123",
  "password_confirmation": "password123"
}

Response Status: 201
Auto-save: applicant_token, applicant_user_id
```

✅ Klik request **1.1 Register - Applicant User** di folder AUTHENTICATION

#### Step 2: Register Admin User
```
Request: POST /api/register
Response Status: 201
Auto-save: admin_token, admin_user_id
```

✅ Klik request **1.2 Register - Admin User**

#### Step 3: Get My Profile (Verify Token Works)
```
Request: GET /api/me
Header: Authorization: Bearer {{applicant_token}}
Response Status: 200
```

✅ Klik request **1.4 Get My Profile**

---

### PHASE 2: JOBS MANAGEMENT

#### Step 4: List All Jobs
```
Request: GET /api/jobs?page=1&per_page=10
Response: Array of jobs dengan pagination metadata
Status: 200
```

✅ Klik **2.1 List All Jobs**

#### Step 5: Search Jobs (LATIHAN 1)
```
Request: GET /api/jobs?keyword=Engineer&page=1&per_page=10
Response: Jobs filtered by keyword "Engineer"
Status: 200
```

✅ Klik **2.2 Search Jobs - Keyword Filter**
- **Note**: Ganti "Engineer" dengan keyword lain untuk test

#### Step 6: Get Job Detail
```
Request: GET /api/jobs/{{job_id}}
Response: Single job object
Status: 200
```

✅ Klik **2.3 Get Job Detail**

#### Step 7: Public Jobs (No Auth)
```
Request: GET /api/public/jobs
Header: TIDAK perlu Authorization
Response: Array of public jobs
Status: 200
```

✅ Klik **2.4 Public Jobs (No Auth Required)**

#### Step 8: Create Job (ADMIN ONLY)
```
Request: POST /api/jobs
Header: Authorization: Bearer {{admin_token}}
Body:
{
  "title": "Software Engineer - NodeJS",
  "description": "...",
  "department": "IT"
}
Response Status: 201
Auto-save: created_job_id
```

✅ Klik **3.1 Create Job** (di folder JOBS - CRUD)

#### Step 9: Create Another Job
```
Request: POST /api/jobs
```

✅ Klik **3.2 Create Another Job**

#### Step 10: Update Job (ADMIN ONLY)
```
Request: PUT /api/jobs/{{created_job_id}}
```

✅ Klik **3.3 Update Job**

#### Step 11: Delete Job (ADMIN ONLY)
```
Request: DELETE /api/jobs/{{created_job_id}}
Response Status: 200
```

✅ Klik **3.4 Delete Job**

---

### PHASE 3: APPLICATIONS

#### Step 12: Submit Application (LATIHAN - File Upload)
```
Request: POST /api/jobs/1/apply
Header: Authorization: Bearer {{applicant_token}}
Body: Form data dengan file cv.pdf
Response Status: 201
Auto-save: application_id
```

✅ Klik **4.3 Submit Application**
- **PENTING**: Sesuaikan path file CV Anda
- Atau skip langkah ini jika tidak ada file CV

#### Step 13: List My Applications
```
Request: GET /api/applications
Header: Authorization: Bearer {{applicant_token}}
Response: Applications milik user yang login
Status: 200
```

✅ Klik **4.1 List My Applications**

#### Step 14: List All Applications (Admin)
```
Request: GET /api/applications
Header: Authorization: Bearer {{admin_token}}
Response: ALL applications (admin dapat melihat semua)
Status: 200
```

✅ Klik **4.2 List All Applications (Admin)**

#### Step 15: Approve Application (LATIHAN 2 - ADMIN ONLY)
```
Request: PATCH /api/applications/{{application_id}}/status
Header: Authorization: Bearer {{admin_token}}
Body:
{
  "status": "Accepted"
}
Response Status: 200
```

✅ Klik **5.1 Approve Application**

#### Step 16: Reject Application (LATIHAN 2 - ADMIN ONLY)
```
Request: PATCH /api/applications/{{application_id}}/status
Body:
{
  "status": "Rejected"
}
```

✅ Klik **5.2 Reject Application**

---

### PHASE 4: ERROR TESTING (Optional - untuk belajar error handling)

#### Test 401 - No Token
```
Request: GET /api/me
Header: TIDAK ada Authorization
Expected: 401 Unauthorized
```

✅ Klik **6.1 Test 401 - No Token**

#### Test 403 - Forbidden (Applicant trying Admin endpoint)
```
Request: POST /api/jobs
Header: Authorization: Bearer {{applicant_token}}
Expected: 403 Forbidden
```

✅ Klik **6.2 Test 403 - Applicant Accessing Admin Endpoint**

#### Test 422 - Validation Error
```
Request: POST /api/jobs
Body: Missing "description" field
Expected: 422 Unprocessable Entity
```

✅ Klik **6.3 Test 422 - Validation Error**

---

## 📊 Response Examples

### Successful Response (200 OK)
```json
{
  "data": [...],
  "current_page": 1,
  "per_page": 10,
  "total": 50,
  "last_page": 5
}
```

### Created Response (201 Created)
```json
{
  "message": "Job created",
  "job": {
    "id": 1,
    "title": "Software Engineer",
    "description": "...",
    "created_at": "2025-11-25T..."
  }
}
```

### Authentication Response (200 OK)
```json
{
  "message": "Login successful",
  "token": "1|abc123def456...",
  "user": {
    "id": 1,
    "name": "John Applicant",
    "email": "applicant@test.com",
    "role": "applicant"
  }
}
```

### Error Response (401 Unauthorized)
```json
{
  "message": "Unauthenticated"
}
```

### Error Response (403 Forbidden)
```json
{
  "message": "Forbidden"
}
```

### Error Response (422 Validation)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "description": ["The description field is required."]
  }
}
```

---

## 🔧 Tips & Tricks

### 1. Auto-Save Tokens
Collection sudah configured dengan test scripts yang otomatis save tokens:
- Setelah register/login → token otomatis di-save ke environment
- Tidak perlu copy-paste token manual

### 2. View Saved Variables
1. Klik ikon **Environment** (sidebar)
2. Klik environment yang aktif
3. Lihat semua variables yang tersimpan

### 3. Create Custom Tests
Tambahkan test custom di tab **Tests** setiap request:
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has token", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.token).to.exist;
});
```

### 4. Use Pre-request Scripts
Tambahkan di tab **Pre-request Script** untuk generate random data:
```javascript
var timestamp = new Date().getTime();
pm.environment.set("random_email", "user" + timestamp + "@test.com");
```

### 5. Test dalam Mode Batch
Klik folder → **Run** untuk jalankan semua requests dalam folder sekaligus.

---

## ❌ Troubleshooting

### Problem: "Invalid token"
**Solusi:**
- Pastikan sudah run **1.1 Register - Applicant User** dulu
- Cek environment variable `applicant_token` sudah terisi
- Klik request lagi jika token expired

### Problem: "Forbidden" saat create job
**Solusi:**
- Pastikan menggunakan `admin_token`, bukan `applicant_token`
- Sudah run **1.2 Register - Admin User** untuk generate admin token

### Problem: File upload gagal
**Solusi:**
- Sesuaikan path file di request **4.3**
- Pastikan file benar-benar ada di lokasi yang ditunjuk
- Gunakan format yang supported: PDF, DOC, DOCX

### Problem: "404 Not Found"
**Solusi:**
- Pastikan job/application ID benar di environment variables
- Run request untuk create job dulu sebelum update/delete

---

## 📋 Checklist Testing

- [ ] ✓ Register applicant user
- [ ] ✓ Register admin user
- [ ] ✓ Get my profile
- [ ] ✓ List jobs (dengan pagination)
- [ ] ✓ Search jobs dengan keyword
- [ ] ✓ Get job detail
- [ ] ✓ Public jobs (no auth)
- [ ] ✓ Create job (admin)
- [ ] ✓ Update job (admin)
- [ ] ✓ Delete job (admin)
- [ ] ✓ List my applications
- [ ] ✓ List all applications (admin)
- [ ] ✓ Approve application (admin)
- [ ] ✓ Reject application (admin)
- [ ] ✓ Test error 401
- [ ] ✓ Test error 403
- [ ] ✓ Test error 422

---

## 📚 Reference Quick

| Endpoint | Method | Auth | Folder |
|----------|--------|------|--------|
| /api/register | POST | No | 1. AUTHENTICATION |
| /api/login | POST | No | 1. AUTHENTICATION |
| /api/me | GET | Yes | 1. AUTHENTICATION |
| /api/logout | POST | Yes | 1. AUTHENTICATION |
| /api/jobs | GET | Yes | 2. JOBS - LIST & SEARCH |
| /api/jobs?keyword=... | GET | Yes | 2. JOBS - LIST & SEARCH |
| /api/public/jobs | GET | No | 2. JOBS - LIST & SEARCH |
| /api/jobs | POST | Admin | 3. JOBS - CRUD |
| /api/jobs/{id} | PUT | Admin | 3. JOBS - CRUD |
| /api/jobs/{id} | DELETE | Admin | 3. JOBS - CRUD |
| /api/applications | GET | Yes | 4. APPLICATIONS |
| /api/jobs/{id}/apply | POST | Yes | 4. APPLICATIONS |
| /api/applications/{id}/status | PATCH | Admin | 5. APPLICATIONS - ADMIN |

---

## 🎯 Koneksi ke Laporan

Requests ini sesuai dengan **5 Latihan** di laporan:

1. **Latihan 1: Search Filter** → Request **2.2 Search Jobs**
2. **Latihan 2: Update Status** → Request **5.1 & 5.2**
3. **Latihan 3: Pagination** → Request **2.1 dengan per_page parameter**
4. **Latihan 4: Public Endpoint** → Request **2.4 Public Jobs**
5. **Latihan 5: Swagger UI** → Akses http://localhost:8000/api/documentation

---

Semua requests sudah siap digunakan! 🚀

