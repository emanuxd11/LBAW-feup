-- Drop existing tables if they exist
DROP SCHEMA IF EXISTS lbaw2363 CASCADE;
CREATE SCHEMA lbaw2363;
SET search_path TO lbaw2363;

DROP TABLE IF EXISTS "User" CASCADE;
DROP TABLE IF EXISTS Admin CASCADE;
DROP TABLE IF EXISTS Project CASCADE;
DROP TABLE IF EXISTS ProjectMember CASCADE;
DROP TABLE IF EXISTS ProjectMemberInvitation CASCADE;
DROP TABLE IF EXISTS Post CASCADE;
DROP TABLE IF EXISTS PostUpvote CASCADE;
DROP TABLE IF EXISTS PostComment CASCADE;
DROP TABLE IF EXISTS Task CASCADE;
DROP TABLE IF EXISTS ProjectMemberTask CASCADE;
DROP TABLE IF EXISTS TaskComents CASCADE;
DROP TABLE IF EXISTS Message CASCADE;
DROP TABLE IF EXISTS Changes CASCADE;
DROP TABLE IF EXISTS Notification CASCADE;
DROP TABLE IF EXISTS UserNotification CASCADE;

DROP TYPE IF EXISTS TaskStatus;
DROP TYPE IF EXISTS UpvoteType;

DROP INDEX IF EXISTS index_projectmember_iduser;
DROP INDEX IF EXISTS index_message_sender_receiver;
DROP INDEX IF EXISTS index_task_assigned_member;

DROP FUNCTION IF EXISTS post_search_update;

DROP FUNCTION IF EXISTS remove_favorite;
DROP FUNCTION IF EXISTS favorite_restriction;
DROP FUNCTION IF EXISTS one_coordinator_restriction;



CREATE TYPE TaskStatus AS ENUM('To Do','Doing', 'Done');
CREATE TYPE UpvoteType AS ENUM('up','down');

-- Create password_resets table
-- (for password reset tokens,
-- added during development)
CREATE TABLE password_reset_tokens (
    email TEXT NOT NULL,
    token TEXT NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE,
    PRIMARY KEY(email,token)
);

-- Create User Table
CREATE TABLE "User" (
    id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL,
    username VARCHAR NOT NULL UNIQUE,
    email VARCHAR NOT NULL UNIQUE,
    password VARCHAR NOT NULL,
    phoneNumber VARCHAR,
    remember_token VARCHAR,
    bio VARCHAR,
    profile_pic VARCHAR,
    isDeactivated BOOLEAN
);

-- Create Admin Table (Extending User Table)
CREATE TABLE Admin (
    id INT PRIMARY KEY REFERENCES "User"(id)
);

-- Create Project Table
CREATE TABLE Project (
    id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL,
    start_date DATE,
    delivery_date DATE,
    archived BOOLEAN,
    UNIQUE (name),
    description VARCHAR,
    icon_pic VARCHAR,
    CHECK (delivery_date >= start_date)
);

-- Create ProjectMember Table
CREATE TABLE ProjectMember (
    idUser INT REFERENCES "User"(id),
    idProject INT REFERENCES Project(id),
    isCoordinator BOOLEAN,
    isFavorite BOOLEAN,
    PRIMARY KEY (idUser, idProject)
);

-- Create ProjectMemberInvitation Table
CREATE TABLE ProjectMemberInvitation (
    id SERIAL PRIMARY KEY,
    invitation_token VARCHAR NOT NULL,
    idUser INT REFERENCES "User"(id),
    idProject INT REFERENCES Project(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Post Table
CREATE TABLE Post (
    id SERIAL PRIMARY KEY,
    title VARCHAR NOT NULL,
    description VARCHAR,
    upvotes INT,
    date DATE NOT NULL,
    isEdited BOOLEAN,
    author_id INT REFERENCES "User"(id),
    project_id INT REFERENCES Project(id)
);

CREATE TABLE PostUpvote (
    user_id INT REFERENCES "User"(id),
    post_id INT REFERENCES Post(id),
    upvote_type UpvoteType NOT NULL,
    PRIMARY key (user_id,post_id)
);

-- Create PostComment Table
CREATE TABLE PostComment (
    id SERIAL PRIMARY KEY,
    comment VARCHAR NOT NULL,
    date DATE NOT NULL,
    author_id INT REFERENCES "User"(id),
    post_id INT REFERENCES Post(id),
    parent_comment_id INT REFERENCES PostComment(id),
    isEdited BOOLEAN
);

-- Create Task Table
CREATE TABLE Task (
    id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL,
    description VARCHAR,
    start_date DATE,
    delivery_date DATE,
    status TaskStatus DEFAULT 'To Do',
    CHECK (delivery_date >= start_date),
    project_id INT REFERENCES Project(id)
);

-- Create Project_Task Relationship Table
CREATE TABLE ProjectMemberTask (
    user_id INT REFERENCES "User"(id),
    task_id INT REFERENCES Task(id),
    PRIMARY key (user_id,task_id)
);

-- Create Table TaskComent
CREATE TABLE TaskComments(
    id SERIAL PRIMARY KEY,
    comment VARCHAR NOT NULL,
    created_at DATE NOT NULL,
    isEdited BOOLEAN NOT NULL,
    task_id INT REFERENCES Task(id),
    user_id INT REFERENCES "User"(id)
);

-- Create Message Table
CREATE TABLE Message (
    id SERIAL PRIMARY KEY,
    text VARCHAR NOT NULL,
    date DATE NOT NULL,
    sender_id INT REFERENCES "User"(id),
    receiver_id INT REFERENCES "User"(id)
);

-- Create Changes Table
CREATE TABLE Changes (
    id SERIAL PRIMARY KEY,
    text VARCHAR,
    date TIMESTAMP,
    project_id INT REFERENCES Project(id),
    user_id INT REFERENCES "User"(id)
);

-- Create Notification Table
CREATE TABLE Notification (
    id SERIAL PRIMARY KEY,
    description VARCHAR NOT NULL,
    date DATE NOT NULL
);

-- Create UserNotification Table
CREATE TABLE UserNotification(
    user_id INT REFERENCES "User"(id),
    notification_id INT REFERENCES Notification(id),
    PRIMARY KEY (user_id,notification_id),
    isChecked BOOLEAN NOT NULL
);


--INDEX 1
CREATE INDEX index_projectmember_iduser ON ProjectMember USING btree(idUser); CLUSTER ProjectMember USING index_projectmember_iduser;

--INDEX 2
CREATE INDEX index_message_sender_receiver ON Message USING btree(sender_id, receiver_id);

--INDEX 3
CREATE INDEX index_task_assigned_member ON ProjectMemberTask USING btree(user_id,task_id);

--FULLTEXT SEARCH
ALTER TABLE Post
ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION post_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.title), 'A') ||
            setweight(to_tsvector('english', NEW.description), 'B')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.title <> OLD.title OR NEW.description <> OLD.description) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.title), 'A') ||
                setweight(to_tsvector('english', NEW.description), 'B') 
            );
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE 'plpgsql';


CREATE TRIGGER post_search_update
BEFORE INSERT OR UPDATE ON Post
FOR EACH ROW
EXECUTE PROCEDURE post_search_update();

--TRIGGER 1
CREATE FUNCTION remove_favorite() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF NEW.archived = TRUE THEN
            UPDATE ProjectMember SET isFavorite = FALSE WHERE idProject = NEW.id;
            RETURN NEW;
        END IF;
        
        RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER remove_favorite_trigger
        BEFORE UPDATE ON Project
        FOR EACH ROW
        EXECUTE PROCEDURE remove_favorite();

--TRIGGER 2
CREATE FUNCTION favorite_restriction() RETURNS TRIGGER AS
$BODY$
DECLARE
    favorite_count INTEGER;
BEGIN
    -- Count the number of favorite projects for the current user
    SELECT COUNT(*)
    INTO favorite_count
    FROM ProjectMember
    WHERE idUser = NEW.idUser AND isFavorite = TRUE;

    -- Check if the count is greater than 10
    IF favorite_count > 10 THEN
        RAISE EXCEPTION 'A project member cannot have more than 10 favorite projects.';
    END IF;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER favorite_restriction_trigger
        BEFORE UPDATE ON ProjectMember
        FOR EACH ROW
        EXECUTE PROCEDURE favorite_restriction();

--TRIGGER 3
CREATE FUNCTION one_coordinator_restriction() RETURNS TRIGGER AS
$BODY$
BEGIN
    -- Check if there is more than one coordinator for the project
    IF EXISTS (
        SELECT 1
        FROM ProjectMember
        WHERE idProject = NEW.idProject AND isCoordinator = TRUE
        HAVING COUNT(*) > 1
    ) THEN
        RAISE EXCEPTION 'A project cannot have more than one coordinator.';
    END IF;

    RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER one_coordinator_restriction_trigger
        BEFORE UPDATE ON ProjectMember
        FOR EACH ROW
        EXECUTE PROCEDURE one_coordinator_restriction();


-- Insert some stuff to get started

INSERT INTO "User" (name, username, email, password, phoneNumber, isDeactivated, bio, profile_pic) VALUES 
    ('Rúben Fonseca', 'rubenf11', 'up202108830@up.pt', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '913111111', FALSE, 'A passionate and results-oriented project manager with over 5 years of experience in bringing complex initiatives to life. Expertise in managing cross-functional teams, setting clear objectives, and delivering projects on time and within budget. Adept at utilizing project management software to streamline processes, track progress, and maintain transparency.' ,NULL),
    ('Miguel Marinho', 'kiryu', 'up202108822@up.pt', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '912111111', FALSE, 'Project coordinator with a knack for organizing and prioritizing tasks. Proven ability to navigate complex projects, manage multiple deadlines, and ensure seamless cross-team communication. Expertise in utilizing project management software to create detailed project plans, track progress, and identify potential risks.', '/images/avatars/based_kiryu.jpg'),
    ('Emanuel Maia', 'manu', 'up202107486@up.pt', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '914111111', FALSE, 'Project member with a passion for driving innovation and achieving ambitious goals. Expertise in developing comprehensive project roadmaps, identifying market opportunities, and aligning projects with business objectives.', '/images/avatars/manu.jpg'),
    ('Alberto Serra', 'i_love_naruto', 'up202103627@up.pt', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '915111111', FALSE, 'A creative and resourceful project analyst with a sharp eye for detail and a knack for problem-solving. Expertise in analyzing project data, identifying trends, and providing actionable insights. Proven ability to translate complex information into clear and concise reports.', NULL),
    ('John Doe', 'john_doe', 'john.doe@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '916111111', FALSE, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', NULL),
    ('Jane Smith', 'jane_smith', 'jane.smith@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '917111111', FALSE, 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', NULL),
    ('Alice Johnson', 'alice_j', 'alice.johnson@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '918111111', FALSE, 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', NULL),
    ('Bob Anderson', 'bob_a', 'bob.anderson@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '919111111', FALSE, 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', NULL),
    ('Eva Martinez', 'eva_m', 'eva.martinez@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '920111111', FALSE, 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', NULL),
    ('John Smith', 'john_s', 'john.smith@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '921111111', FALSE, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', NULL),
    ('Emily Davis', 'emily_d', 'emily.davis@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '922111111', FALSE, 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', NULL),
    ('Michael Brown', 'michael_b', 'michael.brown@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '923111111', FALSE, 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.', NULL),
    ('Sophia Wilson', 'sophia_w', 'sophia.wilson@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '924111111', FALSE, 'Duis aute irure dolor in reprehenderit in voluptate velit esse.', NULL),
    ('Daniel Miller', 'daniel_m', 'daniel.miller@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '925111111', FALSE, 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt.', NULL),
    ('Olivia Johnson', 'olivia_j', 'olivia.johnson@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '926111111', FALSE, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', NULL),
    ('William Davis', 'william_d', 'william.davis@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '927111111', FALSE, 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', NULL),
    ('Ava Brown', 'ava_b', 'ava.brown@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '928111111', FALSE, 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.', NULL),
    ('Liam Wilson', 'liam_w', 'liam.wilson@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '929111111', FALSE, 'Duis aute irure dolor in reprehenderit in voluptate velit esse.', NULL),
    ('Emma Miller', 'emma_m', 'emma.miller@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '930111111', FALSE, 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt.', NULL),
    ('Ethan Wilson', 'ethan_w', 'ethan.wilson@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '931111111', FALSE, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', NULL),
    ('Avery Davis', 'avery_d', 'avery.davis@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '932111111', FALSE, 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', NULL),
    ('Logan Miller', 'logan_m', 'logan.miller@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '933111111', FALSE, 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.', NULL),
    ('Mia Brown', 'mia_b', 'mia.brown@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '934111111', FALSE, 'Duis aute irure dolor in reprehenderit in voluptate velit esse.', NULL),
    ('Owen Johnson', 'owen_j', 'owen.johnson@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '935111111', FALSE, 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt.', NULL),
    ('Isabella Wilson', 'isabella_w', 'isabella.wilson@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '936111111', FALSE, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', NULL),
    ('James Davis', 'james_d', 'james.davis@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '937111111', FALSE, 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', NULL),
    ('Grace Miller', 'grace_m', 'grace.miller@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '938111111', FALSE, 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.', NULL),
    ('Jackson Brown', 'jackson_b', 'jackson.brown@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '939111111', FALSE, 'Duis aute irure dolor in reprehenderit in voluptate velit esse.', NULL),
    ('Sophie Johnson', 'sophie_j', 'sophie.johnson@example.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '940111111', FALSE, 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt.', NULL);


INSERT INTO Admin (id) VALUES (1);

INSERT INTO Project (name, start_date, delivery_date, description,archived) VALUES
    ('LBAW', '2023-10-20', '2024-10-22', 'In this transformative initiative, our team is embarking on the development of a Next-Gen Educational Platform that seeks to redefine the landscape of online learning. The project is multifaceted, incorporating innovative features to enhance user engagement, foster collaborative learning, and provide personalized educational experiences.
    Central to the project is the implementation of a dynamic content delivery system. Traditional one-size-fits-all approaches to online learning will be replaced with adaptive content that adjusts to individual learning styles. Leveraging machine learning algorithms, the platform will analyze user interactions and performance data to tailor content delivery, ensuring that each learner receives a customized educational journey. This adaptive approach aims to cater to diverse learning preferences and optimize knowledge retention.
    In conclusion, the Advanced Healthcare Management System project is poised to bring about a transformative shift in how healthcare services are delivered and managed. By leveraging state-of-the-art technologies such as EHR, telemedicine, and predictive analytics, we aim to create a healthcare ecosystem that is not only responsive to individual patient needs but also scalable and adaptable to the evolving landscape of the healthcare industry. Our commitment is to empower healthcare professionals, engage patients in their care, and contribute to a more efficient and patient-centric healthcare experience.' ,FALSE),
    ('FSI', '2023-10-20', '2024-10-22', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.' ,FALSE),
    ('RCOM', '2023-10-20', '2024-10-22', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.' ,FALSE),
    ('PFL', '2023-10-20', '2024-10-22', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.' ,FALSE);

INSERT INTO ProjectMember (idUser,idProject,isCoordinator,isFavorite) VALUES
    (2,1,TRUE,TRUE),
    (2,2,TRUE,FALSE),
    (2,3,FALSE,FALSE),
    (2,4,FALSE,TRUE),
    -- (3,1,FALSE,TRUE),
    -- (3,2,FALSE,TRUE),
    (3,3,TRUE,TRUE),
    (3,4,TRUE,TRUE),
    (4, 1, FALSE, FALSE),
    (5, 1, FALSE, FALSE),
    (10, 1, FALSE, FALSE),
    (11, 1, FALSE, FALSE),
    (12, 1, FALSE, FALSE),
    -- (6, 1, FALSE, FALSE),
    -- (7, 1, FALSE, FALSE),
    (8, 1, FALSE, FALSE);

INSERT INTO Task (name, description, start_date, delivery_date, status, project_id) VALUES
    ('Member Context Menu', 'To enhance the system predictive capabilities, a Machine Learning Forecasting Module will be integrated. This module will analyze historical data, market trends, and other relevant factors to predict future demand for specific products. The goal is to optimize inventory levels, reduce excess stock, and improve overall inventory turnover.', '2023-10-20', '2024-1-31', 'To Do', 1),
    ('Improve CSS', 'One of the key features of AIMS will be its ability to provide real-time updates on inventory levels. This task involves implementing sensors and IoT devices to continuously monitor stock levels. The system will automatically trigger alerts when inventory falls below predefined thresholds, enabling proactive decision-making and preventing stockouts.', '2023-10-20', '2024-1-31', 'To Do', 1),
    ('Fix Modal Overlay', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
    ', '2023-10-20', '2024-1-31', 'To Do', 1),
    ('Christmas 24', 'Tempus Natalis advenit, cum festivitates et gaudia in omni domo manifestantur. Inter omnia haec, cura nostra sit ut pueros instruamus in viam salutis et bonae valetudinis.
    Docemus eos, non solum gaudere donis dulcibus, sed etiam cibum eleganter eligere. Meminerint, bonae consuetudines in dieta et motu corpus valentem efficiunt. Non est nobis propositum condemnare traditionem, sed potius suadere moderationem in omnibus.
    Optemus ut Natalis festum sit occasio ad discendum qualis sit via ad vitam sanam et activam. Pulchritudo est in bono statu corporis et animi, et hoc spectaculum valeat ut pueros doceat, non ut excludat aut condemnet.
    In huius festi temporibus, tradamus eis sapientiam ut crescant in amore erga suum corpus, ut gaudium Natalis sit non solum de donis, sed etiam de gratia vitae sanitateque.
    ', '2024-12-24', '2024-12-25', 'Doing', 1),
    ('Project Page Layout', 'Creating an intuitive and user-friendly interface is crucial for the success of AIMS. Our design team will focus on developing a sleek and responsive user interface that caters to both novice and experienced users. The UI will provide easy access to critical information, such as current inventory levels, order history, and customizable reporting options.', '2023-10-20', '2024-1-31', 'Doing', 1),
    ('Cool Side Bar', 'To enhance the systems predictive capabilities, a Machine Learning Forecasting Module will be integrated. This module will analyze historical data, market trends, and other relevant factors to predict future demand for specific products. The goal is to optimize inventory levels, reduce excess stock, and improve overall inventory turnover.', '2023-11-19', '2024-1-31', 'Done', 1);

INSERT INTO Post (title, description, upvotes, date, author_id, project_id) VALUES
    ('Seeking Input for Our Web Development Stack! 💻⚙️', 'Hey fellow devs! Were gearing up for an exciting web development project and would love your insights on the tech stack. Our goal is to create a dynamic and scalable platform. Currently considering React for the frontend, Node.js for the backend, and MongoDB for the database. What are your thoughts on this stack? Any alternative suggestions or considerations we might be missing? Lets brainstorm in the comments! #WebDevForum #TechStackTalk', '7', '2024-1-31', 2, 1),
    ('LBAW Project: Laravel Gurus Needed! 🚀🔍', 'Hello fellow LBAW developers! Our project is diving deep into Laravel, and I could use some guidance. What are your preferred Laravel packages or features that have proven invaluable in your LBAW projects? Any tips for seamless integration of Laravel into our current workflow? Share your Laravel expertise in the comments and lets elevate our LBAW game! 🌐💡 #LaravelInLBAW #TechTips', '25', '2023-12-23', 2, 1),
    ('Choosing the Ultimate Web Dev Stack - Let the Debates Begin! 🤔💬', 'Hey coding maestros! Were in the midst of selecting the ultimate tech stack for our web dev project. React or Vue? Express or Django? MongoDB or PostgreSQL? Let the debates commence! Share your experiences, preferences, and heated opinions in the comments below. Lets make this stack the stuff of legends! 🔥🚀 #WebDevShowdown #TechDebates', '-51', '2024-1-31', 4, 1),
    ('LBAW Database Woes in Laravel - Seeking Advice! 📊🛠️', 'Hey LBAW developers! Currently tackling some database challenges in our Laravel-based LBAW project. How do you efficiently structure and manage databases using Laravel migrations? Any pitfalls to avoid or optimization tips? Your experiences could save me hours of trial and error. Lets discuss in the comments and conquer these database hurdles together! 🤓💻 #LaravelDBStruggles #LBAWDatabaseTips', '1', '2023-12-31', 12, 1);

INSERT INTO PostComment (comment, date, author_id, post_id, parent_comment_id, isEdited) VALUES 
    ('Why limit ourselves? Lets use every framework and library out there! Embrace the chaos! Imagine the innovation! 😂 Who needs simplicity when you can have an overwhelming abundance of choices? 🤷‍♂️ #AllFrameworksAllTheTime', '2024-1-31', 2, 3, NULL, FALSE),
    ('Youre all missing the point - raw performance is everything! Forget React and Vue, handcraft your own framework in assembly language for unparalleled speed. Who cares if its impractical? Performance over practicality, always! 💻⚡️ #PerformanceMatters', '2024-1-31', 10, 3, NULL, FALSE),
    ('Why bother with all these fancy frameworks and databases? HTML is the alpha and omega! Real devs hand-code everything in HTML using Notepad. Anything else is just bloat and laziness. Keep it real, folks! 💻🚫 #HTMLPurist', '2024-1-31', 11, 3, NULL, FALSE),
    ('MongoDB is a solid choice for flexibility, but consider the nature of your data and whether a relational database might be a better fit. PostgreSQL, for instance, offers ACID compliance. It depends on the project requirements. Good luck! 🛠️🗃️', '2024-1-31', 10, 1, NULL, FALSE),
    ('Node.js is a powerhouse for the backend, especially for real-time applications. Considering MongoDB is a NoSQL database, ensure it aligns with your data structure needs. Also, have you explored the benefits of serverless architecture for certain components? It might be worth a look. 🔍💡', '2024-1-31', 12, 1, NULL, FALSE),
    ('Solid choices! React provides a great user experience, and pairing it with Node.js for a full JavaScript stack is a smart move. Have you thought about using TypeScript to enhance code quality and maintainability on the frontend? 🚀📝', '2024-1-31', 2, 1, NULL, FALSE);