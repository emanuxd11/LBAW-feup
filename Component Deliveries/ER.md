# ER: Requirements Specification Component

## A1: Project Management

This artefact aims to provide a comprehensive description of the project, including its objectives, key stakeholders, and the underlying motivations that drive its development.

### Project Description

The primary objective of **ProjectSync** is to develop an efficient information system, featuring an intuitive web interface tailored to meet intricate project requirements and attract organizations for enhanced productivity. Our core mission is to provide users with a robust yet user-friendly and reliable project management platform.

Within the application, organizations can seamlessly oversee multiple projects simultaneously. Each project includes tasks, team members, and well-defined deadlines. Crucially, our platform incorporates a chat forum that facilitates collaboration among users working on the same project.

Accessing the platform is straightforward; organizations simply need to register. Team coordinators or administrators initiate this process by extending invitations to team members to simplify account creation. This approach allows users to utilize the system across various professional contexts using the same email address and easily access it for effective collaboration after registration.

Our platform introduces a finely-tuned system of user roles, ensuring that individuals can interact with the application based on their specific responsibilities. Here's a breakdown of these roles:

Visitors are our basic users, with access to authentication and public information. Authenticated Users enjoy enhanced privileges, enabling them to create, explore, and manage projects seamlessly. Project Members are the backbone of project teams, wielding capabilities for task management, forum engagement, and collaborative roles. Project Coordinators, equipped with comprehensive permissions, take on supervisory responsibilities and can manage project memberships and details. To manage user access throughout the organization, our system appoints an Administrator with extensive authority, providing them with access to project details and specific project-related information.

Lastly, our platform includes an extensive notification system to keep users informed and engaged. These notifications encompass project invitations, task assignments, forum activities, and milestone updates, promoting efficient communication, heightened productivity, and overall project success.

In conclusion, our project aims to deliver a cohesive and efficient project management solution. We are committed to enhancing productivity, fostering collaboration, and simplifying project management processes.


---


## A2: Actors and User stories

The main goal of this artifact is to identify and describe our system's actors as well as enumerate the supplementary requirements.


### 1. Actors
![actor_diagram_def](images\diagram.png)

<br><br><br>

| Identifier          | Description                                                                                                                                   |
| ------------------- | --------------------------------------------------------------------------------------------------------------------------------------------- |
| Visitor             | User with no account. Can only access the homepage and the about us pages. Can also create an account. |
| Logged user  | Generic user that has an account and access to public information, can create projects and accept/decline invites.                                            |
| Post Author | Logged user that can delete or edit its own post. |
| Project Member         | Logged user that can manage tasks, has access to project information, can send messages to other project members whitin the same project and can be assigned or assign themselves to a task.                                                                                                                       |
| Project Coordinator | Team member user that can edit project details and is responsible for the management of users whitin their project. Can also assign tasks to other project members.                                               |
| Admin               | Authenticated user that can browse and view project details and manage users.                                                                                   |

### 2. User Stories

#### 2.1. Visitor

| Identifier | Name        | Priority | Description                                                                                                             |
| ---------- | ----------- | -------- | ----------------------------------------------------------------------------------------------------------------------- |
| US201 | Log-in | High |As a visitor, I want to authenticate into my account so I can access it. |
| US202 | Sign-up | High | As a visitor, I want to create an account so I can authenticate into my account. |
| US203 | Home | High | As a visitor, I want to access home page, so I can see a presentation of the website. |
              

#### 2.2. Logged User

| Identifier | Name                       | Priority | Description                                                                                                                                                    |
| ---------- | -------------------------- | -------- | ----------------------------------------|
| US101 | Create Project | High | As a logged user, I want to create a project, so that I can start working on a new project. |
| US102 | View Projects | High | As a logged user, I want to view my projects so I can see all my projects that I’m working on and switch between them. |
| US103 | Mark project as favorite | High | As a logged user, I want to mark projects as favorites so I can see all my favorite projects. |
| US104 | Edit Profile | High | As a logged user, I want to edit my profile so I can manage my personal information. |
| US105 | Logout | High | As a logged user, I want to logout from my account. |
| US106 | Notification When Assigned to Project | High | As a logged user, I want to receive a notification whenever I'm assigned to a new project, so I can be aware of the projects I'm working on. |

#### 2.3. Project Member

| Identifier | Name                           | Priority | Description                                                                                                                                   |
| ---------- | ------------------------------ | -------- | --------------------------------------------------------------------------------------------------------------------------------------------- |
| US301 | Create task | High | As a project member, I want to be able to create tasks, so I can help organize the project. | 
| US302 | Manage tasks | High | As a project member, I want to be able to manage tasks by editing priority, labels, due date, etc, so I can adjust to current circumstances. | 
| US303 | Assign Users to Tasks | High | As a project member, I want to be able to assign other project members to tasks so everyone knows what they should be working on. | 
| US304 | View Task Details | High | As a project member,  I want to be able to view the details of each task so I can know the specifics of what I'm working on. | 
| US305 | Comment on task | High | As a project member, I want to be able to comment on tasks so as to share my thoughts on the work assigned with the rest of the team. |
| US306 | Complete an assigned task | High | As a project member, I want to be able to mark an assigned task as completed, so I can let the project managers know it's ready. |
| US307 | Leave Project | High | As a project member, I want to be able to leave a project, so I can pick which projects I'm working on. |
| US308 | View Project Team | High | As a  project member, I want to be able to view who I'm working with. | 
| US309 | View Team Members Profiles | High | As a project member, I want to be able to view the profile of my team members, so I can know more about them. |
| US310 |  Search Tasks | High | As a project member, I want to be able to search tasks,  so I can quickly see the work assigned to me. |
| US311 | Notification When Assigned to Task | High | As a project member, I want to be notified whenever I'm assigned to a new task, so I can be on par with my work. |
| US312 | Notification When Task Completed | High | As a project member, I want to receive notifications when the tasks I'm participating in are completed, so I'm on par with the rest of the team. |
| US313 | Browse the Project Message Forum | Medium | As a project member, I want to be able to browse the project message forum, so I can read messages from other team members about a specific project. | 
| US314 | Post Message to Project Forum | Medium | As a project member, I want to be able to post messages to the project forum, so I can share relevant information with other team members. |
| US315 | Notification for Change in Project Coordinator | Medium | As a project member, I want to be notified when the project coordinator changes, so I can be on par with the team details. |
| US316 | View Project Timeline | Low | As a project member, I want to be able to view the project timeline, so I can know what's already been done and how much is left. |

#### 2.4. Post author

| Identifier | Name                       | Priority | Description                                                                                                                   |
| ---------- | -------------------------- | -------- | ------------------------------------|
| US401 | Edit Post | High | As a post author, I want to be able to edit my posts, so I can add, change information in a way that better represents the issue. | 
| US402 | Delete Post | High | As a post author, I want to be able to delete my posts, so I can remove irrelevant or low quality content from my page. |


#### 2.5. Project Coordinator

| Identifier | Name                       | Priority | Description                                                                                                                   |
| ---------- | -------------------------- | -------- | --------------------------------------------|
| US501 | Add user | High | As a project coordinator, I want add a user into the project so that user can work on it. |
| US502 | Assign new coordinator | High | As a project coordinator, I want to assign a team member as a new project coordinator so that member has new priorities. |
| US503 | Edit project details | High | As a project coordinator, I want to edit project details so I can update projects details. |
| US504 | Assign task to member | High | As a project coordinator, I want to assign a task to a member so that member can see which tasks must do. |
| US505 | Remove project member | High | As a project coordinator, I want to remove a team member so that member can´t participate on the project anymore. |
| US506 | Archive project | High | As a project coordinator, I want to archive a project so I can store old projects that are concluded or desactivated. |
| US507 | Completed Task in Project Managed | High | As a project coordinator, I want to receive notifications whenever taks are completed in one of my projects, so as to know how my teams are working. |
| US508 | Notification When User Accepts Project Invitation | Medium | As a project coordinator, I want to receive notifications whenever a user accepts a project invitation, so I know when users join a project I manage. | 
| US509 | Manage members permissions | Low | As a project coordinator, I want to manage members permissions so I can add or remove permissions for each team member. |




#### 2.6. Administrator

| Identifier | Name                                     | Priority | Description                                                                                                                                                                       |
| ---------- | ---------------------------------------- | -------- | -------------------------------------------|
| US601 | Browse Projects | High | As an administrator, I want to be able to browse projects, so I can quickly find what is being worked on. | 
| US602 | View Project Details | High | As an administrator, I want to be able to view the details of each project, so I can dive deeper into exactly what is being worked on. | 
| US603 | Block User Accounts | High | As an administrator, I want to be able to block users from accessing their account, so I can eliminate malicious users from the platform. | 
| US604 | Send DMs to Users | Ultra Low | As an administrator, I want to be able to send direct messages to users, so I can resolve conflicts and help organize projects. |


### 3. Supplementary Requirements

#### 3.1. Business rules

| Identifier | Name                     | Description                                                                                                                                   |
| ---------- | ------------------------ | ---------------------------------------------------------------------- |
|  BR01 |  Delivery date | The start date must be before the delivery date. |
|  BR02 |  Closing a project | Details of the project must be archived and not deleted. |
|  BR03 |  Admins | Admin accounts are separated from user accounts and cannot create projects or join one. |
|  BR04 |  User account deletion | Whenever someone is banned or a user opts to delete their own account, their data is not deleted. |
|  BR05 | Project Coordinator | When a logged user creates a project, they become the project coordinator for that project. |
| BR06 | Project Coordinator Transfer | When a new project coordinator is appointed (by the old coordinator), the old coordinator becomes a project member. |
| BR07 |  Reopening a task  | When a task was finished, but for some reason there is a need to reopen it, the project coordinator can reopen it.   |
| BR08 | Edit posts |  A user can edit his own forum posts, however it will be possible to see that said user edited their own post. |
| BR09 | Comment on own post | A user has the option to comment on their own post to offer further context if necessary. |



#### 3.2. Technical requirements

| Identifier | Name             | Description                                                                                                                                                                                                                                                                                                       |
| ---------- | ---------------- | -------------------------------------------------------------------------------- |
|  **TR01** |  **Performance** | **The website must be quick to mantain its users' attention.** |
|  TR02 | Availability  | The website must be available 99% of the time each day. |
|  TR03 |  Usability | The website must be simple and easy to use, to ensure it improves its users productivity. |
|  **TR04** | **Accessibility**  | **The system must ensure that everyone can access the pages, regardless of the web browser they use.** |
|  TR05 |  Web application | The system should be implemented as a web application with dynamic pages (HTML5, JavaScript, CSS3 and PHP). It is crucial that a user is able to access the application without having to install specific applications.  |
|  **TR06** | **Security** | **The website should be safe to use by protecting user and project information from potential attackers.**|



#### 3.3. Restrictions

| Identifier | Name     | Description                                                                |
| ---------- | -------- | -------------------------------------------------------------------------- |
| C01  |  Delivery |  The website should be completed and functional by the conclusion of the semester.    |  

---


## A3: Information Architecture

This artifact primarily aims to craft a prototype for the user interface, incorporating essential features through wireframes, while also detailing the connections between the system's core pages.


### 1. Sitemap

A sitemap serves as a graphical depiction illustrating the interconnections among various web pages within a website, offering a comprehensive overview of how the content is structured and interconnected. It encompasses all intended web pages and offers a bird's-eye view of the website's information architecture, revealing the arrangement and hierarchy of information within the site.

![Sitemap](images\sitemap_2363.png)


The **ProjectSync** system is structured into five primary domains, encompassing static pages for system information dissemination (Static Pages), pages facilitating the exploration and organization of the project teams and tasks (Project Pages), individual user-specific pages such as the user profile (Authenticated User Pages), authentication pages for user login and registration (Users Authentication Pages), and sections dedicated to administrative functionalities (Admin Pages).


### 2. Wireframes

Wireframes serve as essential blueprints in the realm of web design and application development. They offer a structured visualization of layout concepts, content placement, and the overarching design at the page level. These wireframes meticulously allocate space, provide a clear hierarchy of content, and illuminate the availability of various features within the design canvas. Furthermore, wireframes are invaluable tools that help streamline the decision-making process and ensure that design ideas are effectively communicated among team members and stakeholders.

For the **ProjectSync** system, the wireframes for the **HomePage (UI01)**, the **Login page (UI06)** and the **Project Workspace (UI07)** are presented below.

#### UI01: HomePage

![Homepage](images\wireframe_1.png)

- `1` Users can click in the website logo in order to go to the home page.
- `2` Users can login in their accounts or create a new by registering.
- `3` This area contains the pages related to the FAQ's or additional information abou the platform.
- `4` The images showcase how the website looks and functions.
- `5` Another way for users to access the login and/or register area.

<br><br><br><br>

#### UI06: Login

![Login](images\wireframe_login.png)

- `1` Users can click in the website logo in order to go to the home page.
- `3` This area contains the pages related to the FAQ's or additional information abou the platform.
- `4` In case the user already have an account, he can fill the form with his personal info and login by clicking the button.
- `5` If the user do not have an account, he can click in the register button to be redirected to the register page.



#### UI07: Project Workspace

![ProjectWorkspace](images\wireframe_2.png)

- `1` Users can click in the website logo in order to go to the home page.
- `2` This area contains the users notifications warning and the option to sign out of the account session.
- `3` By clicking at the star next to the project name, the user selects this projet as a favourite.
- `4` The search bar is dedicated to the finding of project tasks.
- `5` Button that redirects the user to the list of project members.
- `6` Projects boards that showcase the current tasks status and the completion of the project.
- `7` Side bar that allows project switching and returning to the User home page.
- `8` Side bar that allows to create another project and to go to the user profile by clicking in the user icon.


### Extra Wireframes

#### UI07: Project Options in workspace

![ProjectOptions](images\wireframe_project_options.png)


#### UI08: Create Project 

![CreateProject](images\wireframe_create_project.png)


---



## Revision history


***
GROUP2363, 01/10/2023

| Name                  | E-Mail            |
| --------------------- | ----------------- |
| Alberto Serra         | up202103627@up.pt |
| Emanuel Maia          | up202107486@up.pt |
| Miguel Marinho (Editor)| up202108822@up.pt |
| Rúben Fonseca         | up202108830@up.pt |
