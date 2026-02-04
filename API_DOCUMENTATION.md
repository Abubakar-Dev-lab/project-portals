# RESTful API Documentation - V1

## Overview

This document describes the comprehensive RESTful API layer for the Project Portals application. The API follows Laravel 12 best practices with:

- **Service-Repository Pattern**: Business logic in Services, data access in Repositories
- **Sanctum Authentication**: Token-based API authentication
- **Role-Based Authorization**: Policies and gate checks for resource access
- **Form Requests**: Input validation and authorization
- **Eloquent Resources**: Safe data transformation
- **Standardized Responses**: Consistent JSON response structure
- **Error Handling**: Comprehensive exception handling with appropriate HTTP status codes

---

## Base URL

```
http://localhost/api/v1
```

## Authentication

All protected endpoints require a Sanctum API token in the `Authorization` header:

```
Authorization: Bearer {token}
```

### Obtain Token

**POST** `/v1/auth/login`

```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "manager",
      "is_active": true,
      "created_at": "2026-01-15 10:30:00",
      "updated_at": "2026-01-15 10:30:00"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "Bearer"
  }
}
```

### Register New User

**POST** `/v1/auth/register`

```json
{
  "name": "Jane Smith",
  "email": "jane@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201 Created):**

```json
{
  "status": "success",
  "message": "Registration successful",
  "data": {
    "user": { ... },
    "token": "...",
    "token_type": "Bearer"
  }
}
```

### Get Current User Profile

**GET** `/v1/auth/profile`

**Headers:** `Authorization: Bearer {token}`

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Profile retrieved successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "manager",
    "is_active": true,
    "tasks_count": 5,
    "created_at": "2026-01-15 10:30:00",
    "updated_at": "2026-01-15 10:30:00"
  }
}
```

### Logout

**POST** `/v1/auth/logout`

**Headers:** `Authorization: Bearer {token}`

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Logout successful",
  "data": null
}
```

---

## Projects

### List All Projects

**GET** `/v1/projects`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `page` (optional, default: 1) - Pagination page number
- `per_page` (optional, default: 10) - Items per page

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Projects retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "Website Redesign",
      "description": "Complete redesign of the company website",
      "status": "active",
      "manager": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "manager",
        "is_active": true
      },
      "tasks_count": 5,
      "created_at": "2026-01-15 10:30:00",
      "updated_at": "2026-01-15 10:30:00"
    }
  ]
}
```

### Create Project

**POST** `/v1/projects`

**Headers:** `Authorization: Bearer {token}`

**Payload:**

```json
{
  "title": "Mobile App Development",
  "description": "Build native iOS and Android apps",
  "status": "pending",
  "manager_id": 2
}
```

**Note:** Non-admins automatically become project managers; `manager_id` is prohibited for non-admins.

**Response (201 Created):**

```json
{
  "status": "success",
  "message": "Project created successfully",
  "data": {
    "id": 2,
    "title": "Mobile App Development",
    "description": "Build native iOS and Android apps",
    "status": "pending",
    "manager": { ... },
    "tasks_count": 0,
    "created_at": "2026-01-20 14:20:00",
    "updated_at": "2026-01-20 14:20:00"
  }
}
```

### Get Project Details

**GET** `/v1/projects/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Project retrieved successfully",
  "data": {
    "id": 1,
    "title": "Website Redesign",
    "description": "...",
    "status": "active",
    "manager": { ... },
    "tasks_count": 5,
    "tasks": [
      {
        "id": 1,
        "title": "Design mockups",
        "description": "Create UI mockups",
        "status": "in_progress",
        "assigned_to": { ... },
        "project": { ... },
        "created_at": "2026-01-15 10:30:00",
        "updated_at": "2026-01-15 10:30:00"
      }
    ],
    "created_at": "2026-01-15 10:30:00",
    "updated_at": "2026-01-15 10:30:00"
  }
}
```

### Update Project

**PUT** `/v1/projects/{id}` or **PATCH** `/v1/projects/{id}`

**Headers:** `Authorization: Bearer {token}`

**Payload:**

```json
{
  "title": "Website Redesign - Phase 2",
  "status": "completed"
}
```

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Project updated successfully",
  "data": { ... }
}
```

### Delete (Soft Delete) Project

**DELETE** `/v1/projects/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Project moved to trash successfully",
  "data": null
}
```

**Error Response (422 Unprocessable)** - When project has pending tasks:

```json
{
  "status": "error",
  "message": "Cannot delete project with active tasks",
  "errors": {
    "tasks": "Project has pending tasks"
  }
}
```

---

## Tasks

### List All Tasks

**GET** `/v1/tasks`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `page` (optional, default: 1)
- `per_page` (optional, default: 10)

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Tasks retrieved successfully",
  "data": [
    {
      "id": 1,
      "title": "Design mockups",
      "description": "Create UI mockups for desktop and mobile",
      "status": "in_progress",
      "assigned_to": {
        "id": 2,
        "name": "Jane Smith",
        "email": "jane@example.com",
        "role": "worker",
        "is_active": true
      },
      "project": {
        "id": 1,
        "title": "Website Redesign",
        "description": "...",
        "status": "active"
      },
      "created_at": "2026-01-15 10:30:00",
      "updated_at": "2026-01-15 10:30:00"
    }
  ]
}
```

### Create Task

**POST** `/v1/tasks`

**Headers:** `Authorization: Bearer {token}`

**Payload:**

```json
{
  "project_id": 1,
  "assigned_to": 2,
  "title": "Frontend Development",
  "description": "Implement responsive design using Tailwind CSS",
  "status": "todo"
}
```

**Validation Rules:**
- `project_id`: Required, must exist. Admins can assign to any project; others only their own.
- `assigned_to`: Required, must exist in users table
- `title`: Required, max 255 characters
- `description`: Required, string
- `status`: Nullable, must be one of: `todo`, `in_progress`, `done`

**Response (201 Created):**

```json
{
  "status": "success",
  "message": "Task created successfully",
  "data": { ... }
}
```

### Get Task Details

**GET** `/v1/tasks/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Task retrieved successfully",
  "data": { ... }
}
```

### Update Task

**PUT** `/v1/tasks/{id}` or **PATCH** `/v1/tasks/{id}`

**Headers:** `Authorization: Bearer {token}`

**Payload:**

```json
{
  "status": "done",
  "title": "Updated Task Title"
}
```

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Task updated successfully",
  "data": { ... }
}
```

### Delete (Soft Delete) Task

**DELETE** `/v1/tasks/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Task moved to trash successfully",
  "data": null
}
```

---

## User Management (Admin Only)

### List All Users

**GET** `/v1/admin/users`

**Headers:** `Authorization: Bearer {token}`

**Access:** Admin only

**Query Parameters:**
- `page` (optional)
- `per_page` (optional)

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Users retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "manager",
      "is_active": true,
      "tasks_count": 5,
      "created_at": "2026-01-15 10:30:00",
      "updated_at": "2026-01-15 10:30:00"
    }
  ]
}
```

### Get Specific User

**GET** `/v1/admin/users/{id}`

**Headers:** `Authorization: Bearer {token}`

**Access:** Admin or self

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "User retrieved successfully",
  "data": { ... }
}
```

### Update User

**PATCH** `/v1/admin/users/{id}`

**Headers:** `Authorization: Bearer {token}`

**Access:** Admin or self (admins can change role; users cannot change their own)

**Payload:**

```json
{
  "name": "John Smith",
  "email": "john.smith@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123",
  "role": "admin",
  "is_active": true
}
```

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "User updated successfully",
  "data": { ... }
}
```

**Error Response (422)** - When trying to modify super admin or self-role:

```json
{
  "status": "error",
  "message": "Update failed",
  "errors": {
    "authorization": "Cannot modify super admin or your own role"
  }
}
```

### Deactivate User

**DELETE** `/v1/admin/users/{id}`

**Headers:** `Authorization: Bearer {token}`

**Access:** Admin only

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "User deactivated to preserve historical records.",
  "data": null
}
```

**Error Response (422)** - When user has unfinished work:

```json
{
  "status": "error",
  "message": "User has unfinished work(Project or Task).",
  "errors": {}
}
```

### Activate User

**PATCH** `/v1/admin/users/{id}/activate`

**Headers:** `Authorization: Bearer {token}`

**Access:** Admin only

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "User activated successfully",
  "data": { ... }
}
```

### Get Available Roles

**GET** `/v1/admin/users/roles`

**Headers:** `Authorization: Bearer {token}`

**Access:** Admin only

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Roles retrieved successfully",
  "data": {
    "worker": "Worker",
    "manager": "Manager",
    "admin": "Admin",
    "super_admin": "Super Admin"
  }
}
```

---

## Trash Management (Admin Only)

### View Trash

**GET** `/v1/admin/trash`

**Headers:** `Authorization: Bearer {token}`

**Access:** Admin only

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Trashed items retrieved successfully",
  "data": {
    "projects": [
      {
        "id": 5,
        "title": "Old Project",
        "description": "...",
        "status": "active",
        "manager": { ... },
        "deleted_at": "2026-01-20 15:45:00"
      }
    ],
    "tasks": [
      {
        "id": 10,
        "title": "Archived Task",
        "description": "...",
        "status": "done",
        "assigned_to": { ... },
        "project": { ... },
        "deleted_at": "2026-01-20 15:40:00"
      }
    ]
  }
}
```

### Restore Project

**PATCH** `/v1/admin/trash/projects/{id}/restore`

**Headers:** `Authorization: Bearer {token}`

**Access:** Admin only

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Project restored successfully",
  "data": { ... }
}
```

### Restore Task (with Safety Check)

**PATCH** `/v1/admin/trash/tasks/{id}/restore`

**Headers:** `Authorization: Bearer {token}`

**Access:** Admin only

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Task restored successfully.",
  "data": null
}
```

**Error Response (409 Conflict)** - When parent project is trashed:

```json
{
  "status": "error",
  "message": "Cannot restore task. Please restore the project 'Project Name' first.",
  "errors": {
    "constraint": "Cannot restore task. Please restore the project 'Project Name' first."
  }
}
```

### Permanently Delete Project

**DELETE** `/v1/admin/trash/projects/{id}`

**Headers:** `Authorization: Bearer {token}`

**Access:** Admin only

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Project and all associated tasks permanently deleted",
  "data": null
}
```

### Permanently Delete Task

**DELETE** `/v1/admin/trash/tasks/{id}`

**Headers:** `Authorization: Bearer {token}`

**Access:** Admin only

**Response (200 OK):**

```json
{
  "status": "success",
  "message": "Task permanently deleted",
  "data": null
}
```

---

## Response Format

### Success Response

All successful API responses follow this structure:

```json
{
  "status": "success",
  "message": "Descriptive success message",
  "data": {}
}
```

### Error Response

```json
{
  "status": "error",
  "message": "User-friendly error message",
  "errors": {
    "field_name": ["Error details"],
    "another_field": ["More errors"]
  }
}
```

---

## HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Request succeeded |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Invalid request format |
| 401 | Unauthorized - Authentication required or invalid token |
| 403 | Forbidden - Authenticated but unauthorized for this resource |
| 404 | Not Found - Resource does not exist |
| 409 | Conflict - Business logic conflict (e.g., cannot restore orphaned task) |
| 422 | Unprocessable Entity - Validation error or business logic rejection |
| 500 | Internal Server Error - Server error |

---

## Error Handling

The API provides comprehensive error responses for various scenarios:

### Validation Error (422)

```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."],
    "email": ["The email must be a valid email address."]
  }
}
```

### Authorization Error (403)

```json
{
  "status": "error",
  "message": "Access denied",
  "errors": {
    "authorization": "Unauthorized to view this project"
  }
}
```

### Business Logic Error (422/409)

```json
{
  "status": "error",
  "message": "Cannot delete project with active tasks",
  "errors": {
    "tasks": "Project has pending tasks"
  }
}
```

---

## Role-Based Access Control

### User Roles

- **Super Admin**: Full system access
- **Admin**: Manage users, trash, system-wide access to projects/tasks
- **Manager**: Create/manage own projects, assign tasks, manage team
- **Worker**: View assigned tasks and projects, update task status

### Resource Access Rules

**Projects:**
- Admins: View/edit/delete all
- Managers: View/edit/delete own; view assigned projects
- Workers: View projects they have tasks in

**Tasks:**
- Admins: View/edit/delete all
- Managers: Create/edit/delete own project's tasks
- Workers: View/edit assigned tasks only

**Users:**
- Admins: Full CRUD access
- Others: View own profile, edit own profile (cannot change role)

**Trash:**
- Admins only

---

## Filtering & Pagination

### Pagination

All list endpoints support pagination:

```
GET /v1/projects?page=2&per_page=20
```

Response includes pagination metadata:

```json
{
  "data": [...],
  "links": {
    "first": "...",
    "last": "...",
    "prev": "...",
    "next": "..."
  },
  "meta": {
    "current_page": 2,
    "from": 11,
    "last_page": 5,
    "per_page": 20,
    "to": 30,
    "total": 100
  }
}
```

### Filtering

Repository-level filtering automatically applies:

```
GET /v1/projects
```

- **Admins**: See all projects
- **Managers**: See own + assigned projects
- **Workers**: See projects they have tasks in

---

## Examples

### Complete Login Flow

```bash
# 1. Register
curl -X POST http://localhost/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Response includes token
TOKEN="eyJ0eXAiOiJKV1QiLCJhbGc..."

# 2. Get profile with token
curl -X GET http://localhost/api/v1/auth/profile \
  -H "Authorization: Bearer $TOKEN"

# 3. Create a project
curl -X POST http://localhost/api/v1/projects \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "New Project",
    "description": "Project description",
    "status": "pending"
  }'

# 4. Logout
curl -X POST http://localhost/api/v1/auth/logout \
  -H "Authorization: Bearer $TOKEN"
```

---

## Architecture

### Service-Repository Pattern

```
Controller → Service → Repository → Database
   ↑                                    ↓
   └────── Response (Resource) ────────┘
```

- **Controllers**: Handle HTTP requests, call services, return responses
- **Services**: Business logic, authorization checks, orchestration
- **Repositories**: Data access layer, query building
- **Resources**: Transform models to safe JSON output
- **Form Requests**: Validation and input filtering

### Key Files

```
app/Http/Controllers/Api/V1/
├── AuthController.php
├── ProjectController.php
├── TaskController.php
├── UserController.php
└── Admin/
    └── TrashController.php

app/Http/Requests/
├── StoreProjectRequest.php
├── UpdateProjectRequest.php
├── StoreTaskRequest.php
├── UpdateTaskRequest.php
├── StoreUserRequest.php
└── UpdateUserRequest.php

app/Http/Resources/V1/
├── ProjectResource.php
├── TaskResource.php
└── UserResource.php

app/Services/
├── AuthService.php
├── ProjectService.php
├── TaskService.php
├── UserService.php
└── TrashService.php

app/Repositories/
├── ProjectRepository.php
├── TaskRepository.php
└── UserRepository.php
```

---

## Version History

### V1.0.0 (Current)

- Full RESTful API implementation
- Service-Repository architecture
- Role-based authorization
- Eloquent Resources for data transformation
- Comprehensive error handling
- Sanctum authentication
- Trash/soft delete management
- User management for admins
