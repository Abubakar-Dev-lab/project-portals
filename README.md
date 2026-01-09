Project Portals
Db Mysql project_portals_db

git main done 
laravel new project psuhed to github lab main brach

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

