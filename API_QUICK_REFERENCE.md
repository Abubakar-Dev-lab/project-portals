# API Quick Reference Guide

## Base URL
```
http://localhost/api/v1
```

## Authentication
```
Authorization: Bearer {token}
```

---

## Authentication Endpoints

### Register
```http
POST /auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login
```http
POST /auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

### Get Profile
```http
GET /auth/profile
Authorization: Bearer {token}
```

### Logout
```http
POST /auth/logout
Authorization: Bearer {token}
```

---

## Projects

### List Projects
```http
GET /projects?page=1&per_page=20
Authorization: Bearer {token}
```

### Create Project
```http
POST /projects
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Project Title",
  "description": "Project description",
  "status": "pending"
}
```

### Get Project
```http
GET /projects/{id}
Authorization: Bearer {token}
```

### Update Project
```http
PATCH /projects/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Updated Title",
  "status": "active"
}
```

### Delete Project
```http
DELETE /projects/{id}
Authorization: Bearer {token}
```

---

## Tasks

### List Tasks
```http
GET /tasks?page=1&per_page=20
Authorization: Bearer {token}
```

### Create Task
```http
POST /tasks
Authorization: Bearer {token}
Content-Type: application/json

{
  "project_id": 1,
  "assigned_to": 2,
  "title": "Task Title",
  "description": "Task description",
  "status": "todo"
}
```

### Get Task
```http
GET /tasks/{id}
Authorization: Bearer {token}
```

### Update Task
```http
PATCH /tasks/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "in_progress"
}
```

### Delete Task
```http
DELETE /tasks/{id}
Authorization: Bearer {token}
```

---

## Admin: Users

### List Users
```http
GET /admin/users?page=1&per_page=20
Authorization: Bearer {token}
```

### Get User
```http
GET /admin/users/{id}
Authorization: Bearer {token}
```

### Update User
```http
PATCH /admin/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Jane Smith",
  "email": "jane@example.com",
  "role": "manager",
  "is_active": true
}
```

### Delete User
```http
DELETE /admin/users/{id}
Authorization: Bearer {token}
```

### Activate User
```http
PATCH /admin/users/{id}/activate
Authorization: Bearer {token}
```

### Get Roles
```http
GET /admin/users/roles
Authorization: Bearer {token}
```

---

## Admin: Trash

### View Trash
```http
GET /admin/trash
Authorization: Bearer {token}
```

### Restore Project
```http
PATCH /admin/trash/projects/{id}/restore
Authorization: Bearer {token}
```

### Restore Task
```http
PATCH /admin/trash/tasks/{id}/restore
Authorization: Bearer {token}
```

### Delete Project Permanently
```http
DELETE /admin/trash/projects/{id}
Authorization: Bearer {token}
```

### Delete Task Permanently
```http
DELETE /admin/trash/tasks/{id}
Authorization: Bearer {token}
```

---

## Common Response Formats

### Success (200)
```json
{
  "status": "success",
  "message": "Operation successful",
  "data": { ... }
}
```

### Created (201)
```json
{
  "status": "success",
  "message": "Resource created",
  "data": { ... }
}
```

### Validation Error (422)
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### Unauthorized (401)
```json
{
  "status": "error",
  "message": "Unauthorized",
  "errors": {}
}
```

### Forbidden (403)
```json
{
  "status": "error",
  "message": "Access denied",
  "errors": {
    "authorization": "You don't have permission"
  }
}
```

### Conflict (409)
```json
{
  "status": "error",
  "message": "Business logic conflict",
  "errors": {
    "constraint": "Cannot perform this action"
  }
}
```

---

## Status Values

**Projects:** `pending`, `active`, `completed`
**Tasks:** `todo`, `in_progress`, `done`
**Users:** `worker`, `manager`, `admin`, `super_admin`
**Active:** `true` or `false`

---

## Roles & Permissions

### Super Admin
- All operations on all resources
- Cannot be deleted by others
- Cannot be modified by non-super-admins

### Admin
- Manage all projects and tasks
- Manage users (create, update, deactivate)
- Access trash and restoration

### Manager
- Create and manage own projects
- Create and manage tasks in own projects
- View projects they manage and have tasks in

### Worker
- View assigned tasks
- Update task status
- View projects they have tasks in
- Update own profile

---

## Typical API Flow

```
1. Register user
   POST /auth/register
   ↓
2. Login
   POST /auth/login
   ↓ (get token)
3. Create project (if manager/admin)
   POST /projects
   ↓
4. Create task
   POST /tasks
   ↓
5. Update task status
   PATCH /tasks/{id}
   ↓
6. View results
   GET /projects/{id} (with tasks)
   ↓
7. Logout
   POST /auth/logout
```

---

## Error Codes

| Code | Meaning |
|------|---------|
| 200 | OK |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized (no token) |
| 403 | Forbidden (no access) |
| 404 | Not Found |
| 409 | Conflict |
| 422 | Unprocessable Entity |
| 500 | Server Error |

---

## Testing with cURL

```bash
# Save token
TOKEN=$(curl -s -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}' \
  | jq -r '.data.token')

# Use token in requests
curl -X GET http://localhost/api/v1/projects \
  -H "Authorization: Bearer $TOKEN"

# Create project
curl -X POST http://localhost/api/v1/projects \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title":"New Project","description":"Test","status":"pending"}'
```

---

## Testing with Postman

1. Create environment variable: `base_url = http://localhost/api/v1`
2. Create pre-request script:
   ```javascript
   // Store token from login response
   pm.environment.set("token", pm.response.json().data.token);
   ```
3. Add to Authorization header: `Bearer {{token}}`
4. All requests can now use: `{{base_url}}/projects`

---

## Rate Limiting

Currently no rate limiting is implemented. Consider adding:
```php
// In routes/api.php
Route::middleware('throttle:60,1')->group(function () {
    // API routes
});
```

---

## Additional Resources

- Full Documentation: See `API_DOCUMENTATION.md`
- Implementation Summary: See `API_IMPLEMENTATION_SUMMARY.md`
- Laravel Docs: https://laravel.com/docs/12
- Sanctum: https://laravel.com/docs/12/sanctum
- Eloquent Resources: https://laravel.com/docs/12/eloquent-resources
