# EAP: Architecture Specification and Prototype

> ProjectSync aims to create an intuitive and streamlined project management platform, simplifying collaboration for organizations. Our vision is to optimize productivity through efficient project oversight, a user-friendly interface, and a built-in chat forum. The platform's roles cater to varied responsibilities, ensuring a seamless user experience. With a focus on simplicity and enhanced communication through a robust notification system, ProjectSync is committed to delivering a cohesive and efficient project management solution.

## A7: Web Resources Specification

This artifact documents the architecture of the web application to be developed, adhering to the OpenAPI standard using YAML. It specifies the catalog of resources, the properties of each resource, and the format of JSON responses.

This comprehensive document specifically covers the documentation for MediaLibrary, presenting the CRUD (create, read, update, delete) operations for each resource implemented in the vertical prototype.

### 1. Overview

> This section provides an overview of the complete web application to be implemented. It identifies and briefly describes the modules integral to the system. For detailed information on the web resources to be implemented in the vertical prototype, refer to the individual documentation of each module within the OpenAPI specification.

| Identifier | Description                                                                                                                               |
| ----------  | -----------------------------------------|
| M01: Static Pages |   Web resources with static content are associated with this module: dashboard homepage, about and faq. |
| M02: Authentication and Individual Profile |   Web resources associated with user authentication and individual profile management. Includes the following system features: login/logout, registration,  view and edit personal profile information. |
| M03: Project Area |   Web resources associated with project management. Includes creating a project and adding users to it, viewing the user's projects, creating a task, viewing, deleting and editing a task as well as browsing them.|
| M04: Administration |   Web resources associated with user management, specifically: view and search users, delete or block user accounts, view and change user information. Admins can also browse projects and view their details. |

### 2. Permissions

| Identifier | Name                     | Description                                                                                                                               |
| ---------- | ------------------------ | -----------------------------------------|
| PUB |  Public | Users without privileges |
| OWN |  Owner | The owner of the content |
| USR |  User | Authenticated users        |
| ADM |  Administrator | System administrators |
| PM |  Project Member | Member of a project |
| PC |  Project Coordinator | Coordinator of a project |


### 3. OpenAPI Specification


[Link to a7_openapi file](https://git.fe.up.pt/lbaw/lbaw2324/lbaw2363/-/blob/main/filesExtra/a7_openapi.yml)

```yaml
openapi: 3.0.0

info:
 version: '1.0'
 title: 'LBAW ProjectSync'
 description: 'Web Resources Specification (A7) for ProjectSync'

servers:
- url: http://lbaw2363.lbaw.fe.up.pt
  description: Production server

tags:
 - name: 'M01: Static Pages'
 - name: 'M02: Authentication and Individual Profile'
 - name: 'M03: Project Area'
 - name: 'M04: Administration'

paths:
#-------------------------------- M01 -----------------------------------------
  /:
    get:
      operationId: R101
      summary: 'R101: See the home page'
      description: 'See the home page when you access the website. Access: PUB'
      tags:
      - 'M01: Static Pages'
      responses:
       '200':
        description: 'Ok. Show homepage' 

  /about-us/:
    get:
      operationId: R102
      summary: 'R102: See About Us'
      description: 'Provide description about the website and its creators. Access: PUB'
      tags:
      - 'M01: Static Pages'
      responses:
       '200':
        description: 'Ok. Show About Us page' 
 
  /faq/:
    get:
      operationId: R103
      summary: 'R103: See Frequently Asked Questions (FAQ)'
      description: 'Provide frequently asked questions about our website. Access: PUB'
      tags:
      - 'M01: Static Pages'
      responses:
       '200':
        description: 'Ok. Show the FAQ page'

#-------------------------------- M02 -----------------------------------------

  /login:
    get:
     operationId: R201
     summary: 'R201: Login Form'
     description: 'Provide login form. Access: PUB'
     tags:
       - 'M02: Authentication and Individual Profile'
     responses:
       '200':
         description: 'Ok. Show Log-in UI'
    post:
     operationId: R202
     summary: 'R202: Login Action'
     description: 'Processes the login form submission. Access: PUB'
     tags:
       - 'M02: Authentication and Individual Profile'

     requestBody:
       required: true
       content:
         application/x-www-form-urlencoded:
           schema:
             type: object
             properties:
               email:          # <!--- form field name
                 type: string
               password:    # <!--- form field name
                 type: string
             required:
                  - email
                  - password

     responses:
       '302':
         description: 'Redirect after processing the login credentials.'
         headers:
           Location:
             schema:
               type: string
             examples:
               302Success:
                 description: 'Successful authentication. Redirect to projects page.'
                 value: '/projects'
               302Error:
                 description: 'Failed authentication. Redirect to login form.'
                 value: '/login'

  /logout:
    get:
      operationId: R203
      summary: 'R203: Logout'
      description: 'Endpoint to log out the user. Access: USR'
      tags:
        - 'M02: Authentication and Individual Profile'
      responses:
        '200':
          description: 'User successfully logged out.'

  /register:
    get:
     operationId: R204
     summary: 'R204: Register Form'
     description: 'Provide new user registration form. Access: PUB'
     tags:
       - 'M02: Authentication and Individual Profile'
     responses:
       '200':
         description: 'Ok. Show Sign-Up UI'

    post:
     operationId: R205
     summary: 'R205: Register Action'
     description: 'Processes the new user registration form submission. Access: PUB'
     tags:
       - 'M02: Authentication and Individual Profile'

     requestBody:
       required: true
       content:
         application/x-www-form-urlencoded:
           schema:
             type: object
             properties:
               name:
                 type: string
               username:
                 type: string
               email:
                 type: string
               phonenumber:
                 type: string
               password:
                 type: string
               repeat_password:
                 type: string
             required:
                - name
                - username
                - email
                - phonenumber
                - password
                - repeat_password

     responses:
       '302':
         description: 'Redirect after processing the new user information.'
         headers:
           Location:
             schema:
               type: string
             examples:
               302Success:
                 description: 'Successful authentication. Redirect to projects page.'
                 value: '/projects'
               302Failure:
                 description: 'Failed authentication. Redirect to register form.'
                 value: '/register'

  /profile/{username}:
   get:
     operationId: R206
     summary: 'R206: View user profile'
     description: 'Show the individual user profile. Access: USR'
     tags:
       - 'M02: Authentication and Individual Profile'

     parameters:
       - in: path
         name: username
         schema:
           type: string
         required: true

     responses:
       '200':
         description: 'Ok. Show User Profile UI'

  /profile/{username}/edit:
    get:
      operationId: R207
      summary: 'R207: Get user profile form'
      description: 'Show the individual user profile edit form. Access: ADM,OWN'
      tags:
        - 'M02: Authentication and Individual Profile'
      parameters:
        - in: path
          name: username
          schema:
            type: string
          required: true
      responses:
        '200':
          description: 'Ok. Show edit profile UI'

  /profile/{username}/update:
    post:
      operationId: R208
      summary: 'R208: Update Profile'
      description: 'Endpoint to update user profile. ADM,OWN'
      tags:
        - 'M02: Authentication and Individual Profile'
      parameters:
        - in: path
          name: username
          schema:
            type: string
          required: true
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                username:
                  type: string
                password:
                  type: string
                phonenumber:
                  type: string
        description: 'Fields to be updated in the profile.'
      responses:
        '200':
          description: 'Profile updated successfully.'

#-------------------------------- M03 -----------------------------------------

  /projects:
    get:
     operationId: R301
     summary: 'R301: Projects'
     description: 'Projects page. Access: USR'
     tags:
       - 'M03: Project Area'
     responses:
       '200':
         description: 'Ok. Show Projects page'
  
  /api/projects:
    put:
      operationId: R302
      summary: 'R302: Create a project'
      description: 'Create a project. Access: USR'
      tags:
        - 'M03: Project Area'
      requestBody:
       required: true
       content:
         application/x-www-form-urlencoded:
           schema:
             type: object
             properties:
               name:
                 type: string
               deliveryDate:
                 type: string
             required:
                - name
      responses:
       '302':
         description: 'Redirect after processing the project information.'
         headers:
           Location:
             schema:
               type: string
             examples:
               302Success:
                 description: 'Successful creation. Redirect to projects page.'
                 value: '/projects'
               302Failure:
                 description: 'Failed creation. Redirect to create project form.'
                 value: '/projects:'
  
  /projects/{project_id}/:
    get:
     operationId: R303
     summary: 'R303: Get Project Page'
     description: 'Get Projects page. Access: PM'
     tags:
       - 'M03: Project Area'
     parameters:
        - in: path
          name: projectId
          schema:
            type: string
          required: true
     responses:
       '200':
         description: 'Ok. Show project page'

  /api/projects/{project_id}/:
    put:
      operationId: R304
      summary: 'R304: Create a task'
      description: 'Create a task. Access: PM'
      tags:
        - 'M03: Project Area'
      parameters:
        - in: path
          name: projectId
          schema:
            type: string
          required: true
      requestBody:
       required: true
       content:
         application/x-www-form-urlencoded:
           schema:
             type: object
             properties:
               name:
                 type: string
               description:
                 type: string
               deliveryDate:
                 type: string
             required:
                - name
                - description
                - deliveryDate
      responses:
       '302':
         description: 'Redirect after processing the task information.'
         headers:
           Location:
             schema:
               type: string
             examples:
               302Success:
                 description: 'Successful creation. Redirect to project page.'
                 value: '/project/{project_id}/'
               302Failure:
                 description: 'Failed creation. Redirect to create project form.'
                 value: '/project/{project_id}/'

  /api/task/{id}:
    delete:
      operationId: R305
      summary: "R305: Delete task"
      description: "Delete a task. Access: PM"
      tags:
        - 'M03: Project Area'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
      responses:
        '200':
         description: 'Ok. Task deleted'
    post:
      operationId: R306
      summary: "R306: Update task info"
      description: "Update the task's information. Access: PM"
      tags:
        - 'M03: Project Area'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                name:
                  type: string
                description:
                  type: string
                members:
                  type: array
                deleveryDate:
                  type: string
      responses:
        '200':
          description: 'Task updated'

  /api/projects/{project_id}/task/{id}/:
    get:
     operationId: R307
     summary: 'R307: Get Task Page'
     description: 'Get Task page. Access: PM'
     tags:
       - 'M03: Project Area'
     parameters:
        - in: path
          name: project_id
          schema:
            type: integer
          required: true
        - in: path
          name: id
          schema:
            type: integer
          required: true
     responses:
       '200':
         description: 'Ok. Show Task page'

  /projects/{id}/search_user:
    get:
      operationId: R308
      summary: 'R308: Search Users in Project'
      description: 'Endpoint to search users in a project. Access: PM'
      tags:
        - 'M03: Project Area'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
        - in: query
          name: term
          schema:
            type: string
          description: 'The search term for users.'
      responses:
        '200':
          description: 'Successful response.'

  /projects/{id}/add_user:
    post:
      operationId: R309
      summary: 'R309: Add User to Project'
      description: 'Endpoint to add a user to a project. Access: PC'
      tags:
        - 'M03: Project Area'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                user_id:
                  type: integer
              required:
                - user_id
      responses:
        '200':
          description: 'User added successfully.'

  /projects/{id}/search_task:
    get:
      operationId: R310
      summary: 'R310: Search Tasks in Project'
      description: 'Endpoint to search tasks in a project. Access: PM'
      tags:
        - 'M03: Project Area'
      parameters:
        - in: path
          name: id
          schema:
            type: integer
          required: true
        - in: query
          name: term
          schema:
            type: string
          description: 'The search term for tasks.'
      responses:
        '200':
          description: 'Successful response.'
#-------------------------------- M04 -----------------------------------------

  /adminPage:
    get:
      operationId: R401
      summary: 'R401: AdminPage'
      description: 'Get admin page. Access: ADM'
      tags:
      - 'M04: Administration'
      responses:
       '200':
        description: 'Ok. Show About Us page' 

  /block_user:
    post:
      operationId: R402
      summary: 'R402: Block or Unblock user'
      description: 'Block or Unblock user. Access: ADM'
      tags:
        - 'M04: Administration'
      responses:
        '200':
          description: 'Ok. User has been blocker or unblocked'
  
  /adminPage/search:
    get:
      operationId: R403
      summary: 'R403: Search in Admin Page'
      description: 'Endpoint to perform a search in the admin page. Access: ADM'
      tags:
        - 'M04: Administration'
      parameters:
        - in: query
          name: term
          schema:
            type: string
          description: 'The search term.'
      responses:
        '200':
          description: 'Successful response.'
```

---


## A8: Vertical prototype


### 1. Implemented Features

#### 1.1. Implemented User Stories
  

| Identifier | Name        | Priority | Description                                                                                                             |
| ---------- | ----------- | -------- | ----------------------------------------------------------------------------------------------------------------------- |
| US101 | Log-in | High | As a visitor, I want to authenticate into my account so I can access it. |
| US102 | Sign-up | High | As a visitor, I want to create an account so I can authenticate into my account. |
| US103 | Home | High | As a visitor, I want to access home page, so I can see a presentation of the website. |
| US201 | Create Project | High | As a logged user, I want to be able to create a project, so that I can start working on a new project. |
| US202 | View Projects | High | As a logged user, I want to be able to view my projects so I can see all the projects I’m working on and switch between them. |
| US204 | Edit Profile | High | As a logged user, I want to be able to edit my profile so I can manage my personal information. |
| US205 | Logout | High | As a logged user, I want to be able to logout from my account. |
| US301 | Create task | High | As a project member, I want to be able to create tasks, so I can help organize the project. | 
| US302 | Manage tasks | High | As a project member, I want to be able to manage tasks by editing priority, labels, due date, etc, so I can adjust to current circumstances. | 
| US303 | Assign Users to Tasks | High | As a project member, I want to be able to assign other project members to tasks so everyone knows what they should be working on. | 
| US304 | View Task Details | High | As a project member,  I want to be able to view the details of each task so I can know the specifics of what I'm working on. |
| US306 | Complete an assigned task | High | As a project member, I want to be able to mark an assigned task as completed, so I can let the project managers know it's ready. |
| US307 | Leave Project | High | As a project member, I want to be able to leave a project, so I can pick which projects I'm working on. |
| US308 | View Project Team | High | As a  project member, I want to be able to view who I'm working with. | 
| US309 | View Team Members Profiles | High | As a project member, I want to be able to view the profile of my team members, so I can know more about them. |
| US310 |  Search Tasks | High | As a project member, I want to be able to search tasks,  so I can quickly see the work assigned to me. |
| US501 | Add user | High | As a project coordinator, I want to be able to add users into the project so that they can work on it. |
| US504 | Assign task to member | High | As a project coordinator, I want to be able to assign tasks to members so that members can see which tasks they must do. |
| US506 | Archive project | High | As a project coordinator, I want to be able to archive projects so I can store old projects that are concluded or deactivated. |
| US601 | Browse Projects | High | As an administrator, I want to be able to browse projects, so I can quickly find what is being worked on. |
| US603 | Block User Accounts | High | As an administrator, I want to be able to block users from accessing their account, so I can eliminate malicious users from the platform. |

#### 1.2. Implemented Web Resources

> Module M01: Static Pages

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R101: See the home page | GET/ |
| R102: See About Us      | GET/about-us |
| R103: See Frequently Asked Questions (FAQ)     | GET/faq |

> Module M02: Authentication and Individual Profile

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R201: Login Form | GET/login |
| R202: Login Action | POST/login |
| R203: Logout | GET/logout |
| R204: Register Form | GET/register |
| R205: Register Action | POST/register |
| R206: View user profile | GET/profile{username} |
| R207: Get user profile form | GET/profile/{username}/edit |

> Module M03: Project Area

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R301: Projects | GET/projects |
| R302: Create a project | PUT/api/projects |
| R303: Get Project Page | GET/projects/{project_id} |
| R304: Create a task | PUT/api/projects/{project_id} |
| R305: Delete task | DELETE/api/task/{id} |
| R306: Update task info | POST/api/task/{id} |
| R307: Get Task Page | GET/api/projects/{project_id}/task/{id} |
| R308: Search Users in Project | GET/projects/{id}/search_user |
| R309: Add User to Project | POST/projects/{id}/add_user|
| R310: Search Tasks in Project | GET/projects/{id}/search_task|

> Module M04: Administration

| Web Resource Reference | URL                            |
| ---------------------- | ------------------------------ |
| R401: Admin Page | GET/adminPage |
| R402: Block or Unblock user | POST/block_user |R402: Search in Admin Page
| R403: Search in Admin Page | POST/adminPage/search |


### 2. Prototype

#### 2.1. Credentials

| Email             | Password  | Position            |
| ------------------| ----------| --------------------|
| ruben@gmail.com   | 1234      | Admin               |
| marinho@gmail.com | 1234      | Project Coordinator |

Link:
https://lbaw2363.lbaw.fe.up.pt/
 
[Link to prototype src](https://git.fe.up.pt/lbaw/lbaw2324/lbaw2363/-/blob/main/ProjectSync)

---


## Revision history

No changes have yet been made to this document.

---

GROUP2363, 20/11/2023

| Name | E-Mail |
|------|--------|
| Alberto Serra | up202103627@up.pt |
| Emanuel Maia | up202107486@up.pt |
| Miguel Marinho | up202108822@up.pt |
| Rúben Fonseca (Editor) | up202108830@up.pt |
