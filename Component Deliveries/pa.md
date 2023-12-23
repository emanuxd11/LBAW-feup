# PA: Product and Presentation

> ProjectSync aims to create an intuitive and streamlined project management platform, simplifying collaboration for organizations. Our vision is to optimize productivity through efficient project oversight, a user-friendly interface, and a built-in forum. The platform's roles cater to varied responsibilities, ensuring a seamless user experience. With a focus on simplicity and enhanced communication through a robust notification system, ProjectSync is committed to delivering a cohesive and efficient project management solution.

## A9: Product

> We developed a web application that allows users to create projects and manage them. Inside a project you can invite other people, create and edit tasks and post on foruns, as well as comment on forum posts and tasks.  

### 1. Installation

> Link to the release with the final version of the source code in the group's Git repository.  
```
docker run -it -p 8000:80 --name=lbaw2363 -e DB_DATABASE="lbaw2363" -e DB_SCHEMA="lbaw2363" -e DB_USERNAME="lbaw2363" -e \
DB_PASSWORD="PASSWORD" git.fe.up.pt:5050/lbaw/lbaw2324/lbaw2363 
```  

### 2. Usage

> URL to the product: http://lbaw2363.lbaw.fe.up.pt  

#### 2.1. Administration Credentials

> Administration URL: URL  

| Email | Password |
| -------- | -------- |
| up202108830@up.pt    | 1234 |

#### 2.2. User Credentials

| Type          | Username  | Password |
| ------------- | --------- | -------- |
| User/Project Coordinator | up202108822@up.pt    | 1234 |
| User/Project Coordinator   | up202107486@up.pt    | 1234 |

### 3. Application Help

> Some examples of the help we implemented were nametags in every input field, so the user knows what they need to input, and placeholders giving examples of what to input.  

### 4. Input Validation

> Input data is always validated on the server-side, and when necessary, also on the client-side.
One example of a server side validation is when we try to put a delivery date, that is before the creation date. One example of client side validation, is when we try to remove someone from a project as a coordinator. A pop up will appear asking if we really want to remove an user from the project.

### 5. Check Accessibility and Usability

> Results of accessibility and usability tests using the following checklists:

> Accessibility:[Link](https://git.fe.up.pt/lbaw/lbaw2324/lbaw2363/-/blob/main/filesExtra/sapo_acessiblidade.pdf)   
> Usability:[Link](https://git.fe.up.pt/lbaw/lbaw2324/lbaw2363/-/blob/main/filesExtra/sapo_Usabilidade.pdf)   

### 6. HTML & CSS Validation

> The results of the validation of the HTML and CSS code:

> HTML:[Link](https://git.fe.up.pt/lbaw/lbaw2324/lbaw2363/-/blob/main/Component%20Deliveries/images/HTML_Valid?ref_type=heads)   
> CSS:[Link](https://git.fe.up.pt/lbaw/lbaw2324/lbaw2363/-/blob/main/Component%20Deliveries/images/CSS_Valid?ref_type=heads)  

### 7. Revisions to the Project
Most of the changes were done in the database.  
- Added TaskComents table;
- Added PostUpvote table;
- Added type UpvoteType;
- Added password_reset_tokens table;
- Added image path, bio and remember_token fields to User table;
- Removed inviteAccepted column from ProjectMemberInvitation table;
- Removed composite primary key from ProjectMemberInvitation and added an id;


### 8. Implementation Details

#### 8.1. Libraries Used

In this project we used bootstrap[https://getbootstrap.com/docs/4.1/getting-started/introduction/], FontAwesome[https://fontawesome.com/docs] and pusher[https://pusher.com/docs/].

#### 8.2 User Stories

We managed to implement all of the required user stories. 


| US Identifier | Name    | Module | Priority                       | Team Members               | State  |
| ------------- | ------- | ------ | ------------------------------ | -------------------------- | ------ |
|  US101          | LogIn | Module 2 | High | **Miguel Marinho**, **Alberto Serra**  |  100%  |
|  US102          | Sign-up | Module 2 | High | **Miguel Marinho**, **Alberto Serra**               |   100%  | 
|  US103          | Home | Module 1 | High | **Alberto Serra**                 |   100%  | 
|  US201          | Create Project | Module 3 | High | **Emanuel Maia**      |  100%  | 
|US205 |Logout| Module 2|High| **Alberto Serra** |100%|
|US104 |Static Pages| Module 2|High| **Alberto Serra** |100%|
|US202 |View Projects| Module 3|High| **Emanuel Maia**, **Alberto Serra**|100%|
|US206 |Notification When Assigned to Project| Module 3|High| **Miguel Marinho**, **Emanuel Maia**|100%|
|US204 |Mark project as favorite| Module 3|High| **Emanuel Maia**|100%|
|US301| Create task|Module 3| High |  **Miguel Marinho**, **Alberto Serra** | 100%|
|US302| Manage tasks|Module 3| High |  **Miguel Marinho**,**Alberto Serra** | 100%|
|US303| Assign Users to Tasks|Module 3| High |  **Miguel Marinho** | 100%|
|US304| View Task Details|Module 3| High |  **Miguel Marinho**, **Alberto Serra**, **Emanuel Maia** | 100%|
|US305| Comment on task|Module 3| High |  **Miguel Marinho** | 100%|
|US306| Complete an assigned task|Module 3| High |  **Miguel Marinho** | 100%|
|US601| Browse Projects|Module 4| High |  **Rúben Fonseca**| 100%|
|US603| Block User Accounts|Module 4| High |  **Miguel Marinho** | 100%|
|US602| View Project Details|Module 4| High |  **Miguel Marinho**, **Rúben Fonseca** | 100%|
|US604| Delete User Accounts|Module 4 and 2| High |  **Miguel Marinho** | 100%|
|US307| Leave Project|Module 3| High |  **Emanuel Maia** | 100%|
|US309| View team member profiles|Module 3| High |  **Rúben Fonseca**,**Emanuel Maia** | 100%|
|US310| Search Tasks|Module 3| High |  **Miguel Marinho**, **Emanuel Maia** | 100%|
|US313| Browse the Project Message Forum|Module 3| High |  **Miguel Marinho** | 100%|
|US314| Post Message to Project Forum|Module 3| High |  **Miguel Marinho** | 100%|
|US401| Edit Post|Module 3| High |  **Miguel Marinho** | 100%|
|US501| Add user|Module 3| High |  **Emanuel Maia** | 100%|
|US502| Assign new coordinator|Module 3| High |  **Emanuel Maia**, **Miguel Marinho** | 100%|
|US503| Edit project details|Module 3| High |  **Rúben Fonseca**,**Emanuel Maia** | 100%|
|US504| Assign task to member|Module 3| High |  **Miguel Marinho** | 100%|
|US505| Remove project member|Module 3| High |  **Emanuel Maia** | 100%|
|US506| Archive project|Module 3| High |  **Emanuel Maia** | 100%|
|US507| Completed Task in Project Managed|Module 3| High |  **Miguel Marinho**,**Emanuel Maia**| 100%|
|US507| Invite user to project via email|Module 3| High | **Emanuel Maia**| 100%|
|US311| Notification When Assigned to Task|Module 3| High |  **Miguel Marinho** | 100%|
|US312| Notification When Task Completed|Module 3| High |  **Miguel Marinho** | 90%|
|US315| Notification for Change in Project Coordinator|Module 3| Medium |  **Miguel Marinho** | 100%|
|US508| Notification When User Accepts Project Invitation | Module 3 | Medium | **Miguel Marinho** | 100%|

---


## A10: Presentation
 
> This artifact corresponds to the presentation of the product.

### 1. Product presentation

> We developed a web application that allows users to create projects and manage them. Inside a project you can invite other people, create and edit tasks and post on foruns, as well as comment on forum posts and tasks.  

URL to the product: http://lbaw2363.lbaw.fe.up.pt  



### 2. Video presentation

![PIC](https://git.fe.up.pt/lbaw/lbaw2324/lbaw2363/-/raw/main/filesExtra/screenshotVideo.png)

Link to video: [https://www.youtube.com/watch?v=_xkL9mtCYWg]


---


## Revision history

No changes have yet been made to this document.

---

GROUP2363, 21/12/2023

| Name | E-Mail |
|------|--------|
| Alberto Serra | up202103627@up.pt |
| Emanuel Maia (Editor)| up202107486@up.pt |
| Miguel Marinho | up202108822@up.pt |
| Rúben Fonseca | up202108830@up.pt |
