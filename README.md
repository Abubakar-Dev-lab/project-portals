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
cheking routes make manuall not resource!
