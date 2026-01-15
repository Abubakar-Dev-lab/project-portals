Project Portals
Db Mysql project_portals_db
using sail ,dbeaver

git main done 
laravel new project pushed to github lab main branch

now branch CRUD+Form-Request

created table projects 
Columns: id, title, description, status (default: 'pending'), manager_id (foreign key to users).

Model  Project

created validation form request StoreProjectRequest
title is required, max 255.
description is required.

ProjectController
store method

Refactoring

ProjectRepository
Inside this class, create a method: public function create(array $data).
This method should simply return Project::create($data);

ProjectService
This class needs a Constructor that "Injects" the ProjectRepository.
Inside this class, create a method: public function createProject(array $data).
This method should:
Add the manager_id to the $data array.
Call the Repository's create method.

made observer ProjectObserver
Inside the created method (not creating, but created):
Use Log::info("..."); to log the message. (You will need use Illuminate\Support\Facades\Log;).
Register the Observer: In a modern Laravel app, you usually do this in the boot method of your app/Providers/AppServiceProvider.php.
Code: Project::observe(ProjectObserver::class);

in project model defined relationship with user using manager method
and in ProjectObserver mentioned project title and manager name
also user relation with project through managedProjects method

tasks table,model and relationship with project and users
project_id (constrained)
assigned_to (constrained to users table)
title
status (default 'todo')

AssignTaskAction,taskcontroler,taskresouce,taskrepositry

refactoring

Day 2
git checkout -b feature/CRUD-Completing
removing auth and just focusing on CRUD completion
ProjectReposirty and ProjectService file adding RUD of CRUD
now project controllertask
CRUD for project done

next CRUD for task repo,service ,controller 
done next is refactoring 

Project update refactoring done by using paginate and with and views and update request form

now Task performace update similar way 
done 

next ui and routes
we have resource routes 

Frontend & UI (The "Body")
The UI is designed for Code Reusability and Scalability using Blade Components and Partials:
Master Layout: A centralized app.blade.php manages the responsive shell, navigation, and global flash messaging system.
Component-Driven Forms: Built a "Form Factory" using reusable Blade components:
<x-form-input>, <x-form-textarea>, and <x-form-select>.
These handle labels, validation errors, and data persistence (old() values) automatically.
Modular Table Partials: To keep the code DRY, the table logic is split into two specialized partial files:
projects/_table.blade.php: Manages the display of project lists with manager details and status badges.
tasks/_table.blade.php: A flexible task table used in both the global Tasks index and the Project details page. It uses a showProject flag to toggle context-aware columns.
📱 Responsive Design & UX
Mobile-Responsive Data: Used overflow-x-auto and min-w-full wrappers on both table partials to ensure readability on mobile devices.
Dynamic UI States:
Implemented the @class directive for status-based styling (e.g., color-coded badges for 'Pending', 'In Progress', and 'Done').
Used request()->routeIs('projects.*') wildcards to keep navigation links active across all sub-pages of a module.
Data Safety: Managed complex relationships with cascadeOnDelete for projects and nullOnDelete for tasks, ensuring the database stays clean while preserving historical data.


next add comments etc on code and then add auth manually for single user  then handle for multiple user like admin,mangers,users etc 
new branch for auth+user-profile
3 types of users worker,manager,admin 
by registering every user is a worker we make admin thorugh dev mode suing tinker or seeders etc and admin can change roles of users only thorugh dashboard

Dev →→Runs Seeder →→Admin #1 exists.
Admin #1 →→Logs in →→Uses UI to create Manager #1.
Manager #1 →→Logs in →→Creates Project A.
Public User →→Registers →→Automatically becomes Worker #1.
Manager #1 →→Edits Project A →→Assigns Worker #1 to it.

regiter ui and bankend done using authservice,regiter controller userreop regiter blade in auth folder

login ui and bakend


# 📂 Project Portal: Technical Documentation

## 1. Data Layer & ERD Logic
The application utilizes a strictly defined relational database schema with a focus on **Data Integrity** and **Role-Based Access Control (RBAC)**.

### Models & Relationships
- **User:** The core entity. Utilizes **Constants** for roles (`ROLE_ADMIN`, `ROLE_MANAGER`, `ROLE_WORKER`) to eliminate "magic strings" and ensure type safety.
    - `managedProjects()`: One-to-Many relationship with Projects (as a Manager).
    - `tasks()`: One-to-Many relationship with Tasks (as an Assignee).
- **Project:** 
    - `manager()`: BelongsTo a User (Foreign Key: `manager_id`). 
    - `tasks()`: HasMany Tasks. Implements `cascadeOnDelete` at the database level to ensure orphans are not created.
- **Task:** 
    - `project()`: BelongsTo a Project.
    - `user()`: BelongsTo a User (Foreign Key: `assigned_to`). Implements `nullOnDelete` to preserve historical task data even if a user is removed from the system.

### Security Features
- **Mass Assignment Protection:** Strictly defined `$fillable` arrays to prevent injection attacks.
- **Modern Hashing:** Leverages Laravel 11's Model Casting for automatic password hashing.


## 🏗️ 2. Data Access Layer (Repositories)
The application implements the **Repository Pattern** to decouple business logic from Eloquent ORM. This ensures the codebase is "Storage Agnostic" and highly testable.

### Key Implementation Details:
- **N+1 Query Prevention:** Every repository method utilizes Eager Loading (`with()`) and Nested Eager Loading (`tasks.user`) to ensure constant-time database performance.
- **Memory Optimization:** Utilizes `pluck()` for data-heavy dropdowns and `withCount()` for relationship aggregates, minimizing RAM usage on the application server.
- **Fail-Safe Retrieval:** Leverages `findOrFail()` to ensure the application handles missing resources gracefully via 404 responses rather than fatal errors.

## 🧠 3. Business Logic Layer (Services)
The Service Layer acts as the orchestrator of the application, ensuring that business rules are enforced before data reaches the persistence layer.

### Core Logic Implementation:
- **Auth Management:** Handles hashing, session lifecycle (regeneration/invalidation), and secure role assignment during registration.
- **Data Guarding:** The `UserService` implements self-protection logic, preventing users from modifying their own roles or accidentally overwriting passwords with empty values.
- **Integrity Checks:** Implements complex deletion rules; for instance, the system prevents the deletion of Managers who are currently assigned to active Projects, ensuring no orphaned data exists.
- **Decoupling:** Controllers are kept "Thin" (less than 5 lines per method) by delegating all multi-step processes to these specialized Service classes.

## 🛡️ 4. Security Architecture (The Guard)
Security is implemented through a multi-layered approach, combining global middleware with granular authorization policies.

### Routing & Middleware
- **Guest Protection:** Utilizes the `guest` middleware to prevent authenticated users from accessing login/registration pages.
- **Role-Based Access Control (RBAC):** A custom `CheckIsAdmin` middleware protects the `/admin` route prefix, ensuring only users with the `admin` role can access user management features.
- **RESTful Resource Grouping:** Routes are strictly organized by Controller and Prefix, following standard HTTP verb conventions (GET, POST, PUT, DELETE).

### Internal Authorization (Upcoming)
- **Layered Defense:** While middleware handles the "Front Door" (Authentication), the system is being upgraded with **Laravel Policies** to handle "Internal Doors" (Ownership).
- **Ownership Verification:** Logic will ensure that Managers can only modify projects they created, and Workers can only update tasks assigned specifically to them.
