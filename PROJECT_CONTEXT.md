# Project Overview
- Laravel application providing a web UI and a JSON API for projects and tasks.
- Role-based access patterns (super_admin, admin, manager, worker).

# Tech Stack
- PHP with Laravel framework.
- Blade templates for server-rendered views.
- Laravel Sanctum tokens for API authentication (HasApiTokens + personal_access_tokens table).
- Database-backed notifications (notifications table + Notifiable usage).
- Broadcasting notifications (database + broadcast channels) and queued notifications.

# Architecture Summary
- MVC structure with Controllers for HTTP entry points.
- Service layer for business logic (e.g., ProjectService, TaskService, UserService).
- Repository layer for data access and query rules.
- Policies enforce role/ownership rules for projects, tasks, and users.
- Middleware `CheckIsAdmin` guards admin routes via `admin` alias.
- Middleware `CheckIfActive` is appended to the web middleware group and logs out deactivated users.
- Observers handle side effects for projects and tasks.
- API responses use JsonResources and a shared `HttpResponses` trait.
- API exceptions: ModelNotFoundException returns JSON 404 for `/api/*`.
- Request flow: Request -> Controller -> Service -> Repository -> Model -> Response or View.

# Confirmed Features
- Web authentication: login, register, logout.
- Project management: list, create, view, edit, update, delete.
- Task management: list, create, view, edit, update, delete.
- Admin user management: list users, edit/update user, delete user, activate user.
- Admin trash management: view trashed projects/tasks, restore, and force delete.
- Profile management: view/edit own profile and update profile data.
- Notifications page: list notifications and mark unread as read.
- Task assignment notifications on create or reassignment (database + broadcast, queued).
- API v1 auth: login, register, logout (Sanctum token based).
- API v1 tasks: CRUD plus trash endpoints (list trashed, restore, force delete).
- Soft deletes for projects and tasks.
- Authorization checks on project/task web actions via policies.
- Global web check for deactivated users (forced logout).
- API 404 JSON response for missing models.

# Data Models (Confirmed Only)
- User fields: name, email, role, is_active, password, remember_token, email_verified_at.
- User relations: managedProjects (hasMany Project by manager_id), tasks (hasMany Task by assigned_to).
- User roles: super_admin, admin, manager, worker.
- Project fields: manager_id, title, description, status, deleted_at.
- Project relations: manager (belongsTo User by manager_id), tasks (hasMany Task).
- Project soft deletes enabled.
- Task fields: project_id, assigned_to, title, description, status, deleted_at.
- Task relations: project (belongsTo Project, includes trashed), user (belongsTo User by assigned_to).
- Task soft deletes enabled.

# Assumptions (Clearly Marked)
- Assumption: Database is a relational DB supported by Laravel (exact engine mysql).

# Unknown / Needs Confirmation
- API project endpoints: routes reference `Api\\V1\\ProjectController` but no controller file found.
- Broadcasting configuration for notifications (driver/provider not reviewed).
