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
