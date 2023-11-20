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
DROP TABLE IF EXISTS PostComment CASCADE;
DROP TABLE IF EXISTS Task CASCADE;
DROP TABLE IF EXISTS ProjectMemberTask CASCADE;
DROP TABLE IF EXISTS Message CASCADE;
DROP TABLE IF EXISTS Changes CASCADE;
DROP TABLE IF EXISTS Notification CASCADE;
DROP TABLE IF EXISTS UserNotification CASCADE;

DROP TYPE IF EXISTS TaskStatus;

DROP INDEX IF EXISTS index_projectmember_iduser;
DROP INDEX IF EXISTS index_message_sender_receiver;
DROP INDEX IF EXISTS index_task_assigned_member;

DROP FUNCTION IF EXISTS post_search_update;

DROP FUNCTION IF EXISTS remove_favorite;
DROP FUNCTION IF EXISTS favorite_restriction;
DROP FUNCTION IF EXISTS one_coordinator_restriction;



CREATE TYPE TaskStatus AS ENUM('To Do','Doing', 'Done');

-- Create User Table
CREATE TABLE "User" (
    id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL,
    username VARCHAR NOT NULL UNIQUE,
    email VARCHAR NOT NULL UNIQUE,
    password VARCHAR NOT NULL,
    phoneNumber VARCHAR,
    isDeactivated BOOLEAN
);

-- Create Admin Table (Extending User Table)
CREATE TABLE Admin (
    id INT PRIMARY KEY REFERENCES "User"(id)
    -- Add additional admin-specific fields here
);

-- Create Project Table
CREATE TABLE Project (
    id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL,
    start_date DATE,
    delivery_date DATE,
    archived BOOLEAN, --DEFAULT FALSE
    UNIQUE (name),
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
    idUser INT REFERENCES "User"(id),
    idProject INT REFERENCES Project(id),
    inviteAccepted BOOLEAN,
    PRIMARY KEY (idUser, idProject)
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
    date DATE,
    project_id INT REFERENCES Project(id),
    user_id INT REFERENCES "User"(id)
);

-- Create Notification Table
CREATE TABLE Notification (
    id SERIAL PRIMARY KEY,
    description VARCHAR NOT NULL,
    date DATE NOT NULL,
    origin VARCHAR NOT NULL
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
            RETURN NULL;
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
BEGIN
        IF NEW.isCoordinator IS NOT NULL THEN
            IF EXISTS (SELECT COUNT(*) FROM ProjectMember WHERE NEW.idUser = idUser AND isFavorite = TRUE) = 10 THEN
                RAISE EXCEPTION 'An user cannot have more than 10 favorite projects.';
                RETURN NULL;
            END IF;
        END IF;
        RETURN NEW;
END
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
        IF EXISTS (SELECT COUNT(*) FROM ProjectMember WHERE NEW.idProject = idProject AND isCoordinator = TRUE ) > 0 THEN
           RAISE EXCEPTION 'A project cannot have more than 1 coordinator.';
           RETURN NULL;
        END IF;
        RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER one_coordinator_restriction_trigger
        BEFORE UPDATE ON ProjectMember
        FOR EACH ROW
        EXECUTE PROCEDURE one_coordinator_restriction();


-- insert some stuff to get started
INSERT INTO "User" (id, name, username, email, password, phoneNumber, isDeactivated) VALUES 
    (1,'Rúben Fonseca', 'rubenf11', 'ruben@gmail.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '913111111', FALSE),
    (2,'Miguel Marinho', 'kiriu', 'marinho@gmail.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '912111111', FALSE),
    (3,'Emanuel Maia', 'manu', 'manu@mail.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', '914111111', FALSE);

INSERT INTO Admin (id) VALUES (1);

INSERT INTO Project (name, start_date, delivery_date, archived) VALUES
    ('Project1', '2023-10-20', '2024-10-22', FALSE),
    ('Project2', '2023-10-20', '2024-10-22', FALSE),
    ('Project3', '2023-10-20', '2024-10-22', FALSE),
    ('Project4', '2023-10-20', '2024-10-22', FALSE);

INSERT INTO ProjectMember (idUser,idProject,isCoordinator,isFavorite) VALUES
    (2,1,TRUE,TRUE),
    (2,2,TRUE,FALSE),
    (2,3,FALSE,FALSE),
    (2,4,FALSE,TRUE),
    -- (3,1,FALSE,TRUE),
    (3,2,FALSE,TRUE),
    (3,3,TRUE,TRUE),
    (3,4,TRUE,TRUE);

INSERT INTO Task (name, description, start_date, delivery_date, status, project_id) VALUES
    ('Task 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
    ', '2023-10-20', '2024-1-31', 'Doing', 1),
    ('Christmas 24', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tempus Natalis advenit, cum festivitates et gaudia in omni domo manifestantur. Inter omnia haec, cura nostra sit ut pueros instruamus in viam salutis et bonae valetudinis.
Docemus eos, non solum gaudere donis dulcibus, sed etiam cibum eleganter eligere. Meminerint, bonae consuetudines in dieta et motu corpus valentem efficiunt. Non est nobis propositum condemnare traditionem, sed potius suadere moderationem in omnibus.
Optemus ut Natalis festum sit occasio ad discendum qualis sit via ad vitam sanam et activam. Pulchritudo est in bono statu corporis et animi, et hoc spectaculum valeat ut pueros doceat, non ut excludat aut condemnet.
In huius festi temporibus, tradamus eis sapientiam ut crescant in amore erga suum corpus, ut gaudium Natalis sit non solum de donis, sed etiam de gratia vitae sanitateque.
    ', '2024-12-24', '2024-12-25', 'To Do', 1),
    ('Task 3', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
    ', '2023-11-19', '2024-1-31', 'Done', 1);
