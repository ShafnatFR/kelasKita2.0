# Postman Raw Payloads (Admin Module)

Copy and paste these JSON snippets into the **Body -> raw (JSON)** section of Postman.

## 1. Admin Authentication

**POST** `{{base_url}}/admin/login`
```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

---

## 2. User Management

**POST** `{{base_url}}/admin/users` (Create User)
```json
{
    "fullname": "New User Name",
    "email": "newuser@example.com",
    "password": "password123",
    "role": "mentor" 
}
```
*(Role options: "mentor", "student")*

**PATCH** `{{base_url}}/admin/users/{id}/status` (Ban/Unban)
```json
{
    "status": "banned"
}
```
*(Status options: "active", "banned")*

**PATCH** `{{base_url}}/admin/users/{id}/catatan` (Add Note to User)
```json
{
    "catatan": "User ini dicurigai melakukan spam."
}
```

**PATCH** `{{base_url}}/admin/users/{id}/activate` (Force Activate)
*(No Body required, or empty JSON)*
```json
{}
```

---

## 3. Kelas Management

**PATCH** `{{base_url}}/admin/kelas/{id}/status` (Approve/Reject Class)
```json
{
    "status": "published"
}
```
*(Status options: "published", "rejected", "draft")*

**PATCH** `{{base_url}}/admin/kelas/{id}/catatan` (Add Note to Class)
```json
{
    "catatan": "Konten kurang lengkap di bab 3."
}
```

---

## 4. Report / Complaints Management

**PATCH** `{{base_url}}/admin/reports/{id}/status` (Update Report Status)
```json
{
    "status": "resolved"
}
```
*(Status options: "pending", "resolved", "dismissed")*

**PATCH** `{{base_url}}/admin/reports/{id}/catatan` (Internal Note for Report)
```json
{
    "catatan": "Masalah sudah diselesaikan via email."
}
```
