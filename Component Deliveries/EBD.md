# EBD: Database Specification Component

## A4: Conceptual Data Model

> A Conceptual Data Model plays a fundamental role in database design, helping to define the core elements of a domain and how they relate to each other. The primary tool for representing this model is a UML (Unified Modeling Language) class diagram.

The UML class diagram serves as a visual blueprint for the Conceptual Data Model. It starts as a high-level representation and progressively incorporates more detail in subsequent iterations.

- **Initial Stage**: In the initial stages, the focus is on identifying and depicting the core entities and their relationships. This foundational step aims to maintain simplicity and clarity in the model.

- **Subsequent Iterations**: As the design process evolves, additional details are integrated into the class diagram. This includes class attributes, attribute domains (defining data types and value constraints), the multiplicity of associations (clarifying how entities are linked), and the introduction of constraints and rules through OCL (Object Constraint Language).

This iterative approach ensures that the Conceptual Data Model provides a comprehensive and accurate representation of the domain, paving the way for successful database design and development. It forms the basis upon which more detailed models and physical databases are constructed in later stages.

### 1\. Class diagram

The diagram in this section represents the main organizational titles, the relationships between them, attributes, multiplicity, and other constraints.

![UML_lbaw](https://git.fe.up.pt/lbaw/lbaw2324/lbaw2363/-/raw/main/Component%20Deliveries/images/Updated_UML.png?ref_type=heads)

### 2\. Additional Business Rules

\-A user can only have up to 10 favorite projects.\
-When a user account is deleted, their work in their projects is not deleted.\
-A user can be a member of multiple projects.

---

## A5: Relational Schema, validation and schema refinement

> This artifact is dedicated to the pivotal aspects of relational schema validation and refinement. These practices are indispensable in the database design process, as they contribute to data integrity and the efficient operation of query performance. Ensuring a well-structured database schema is of paramount importance, as it serves as the cornerstone for dependable data-driven applications and systems. A robust schema paves the way for seamless and optimized interaction with databases, exemplified by applications like ProjectSync.

### 1\. Relational Schema

> The Relational Schema includes the relation schemas, attributes, domains, primary keys, foreign keys and other integrity rules: UNIQUE, DEFAULT, NOT NULL, CHECK.\
> Relation schemas are specified in the compact notation:

| Relation reference | Relation Compact Notation |
|--------------------|---------------------------|
| R01 | User(<ins>id</ins> **PK**, name **NN**, username **NN** **UQ**, email **NN** **UQ**, password **NN**, phone_number, is_deactivated) |
| R02 | Admin(<ins>user_id→User</ins> **PK**) |
| R03 | Project(<ins>id</ins> **PK**, name **NN** **UQ**, start_date, delivery_date **CK** (delivery_date >= start_date), archived) |
| R04 | ProjectMember(<ins>user_id→User</ins> **PK**, <ins>project_id→Project</ins> **PK**, is_coordinator, is_favorite) |
| R05 | Post(<ins>id</ins> **PK**, title **NN**, description, upvotes, date **NN**, is_edited, project_id→Project, author_id→User ) |
| R06 | PostComment(<ins>id</ins> **PK**, comment **NN**, date **NN**, is_edited, parent_post_id→Post, parent_comment_id→PostComment, author_id→User) |
| R07 | Task(<ins>id</ins> **PK**, name **NN**, description, start_date, delivery_date **CK** (delivery_date >= start_date), status **DE** 'To Do') |
| R08 | ProjectMemberTask(<ins>user_id→User</ins> **PK**, <ins>task_id→Task</ins> **PK**) |
| R09 | Message(<ins>id</ins> **PK**, text **NN**, date **NN**, sender_id→User, receiver_id→User) |
| R10 | Changes(<ins>id</ins> **PK**, text, date, user_id→User, project_id→Project) |
| R11 | Notification(<ins>id</ins> **PK**, description **NN**, date **NN**, origin **NN**) |
| R12 | UserNotification(<ins>user_id→User</ins> **PK**, <ins>notification_id→Notification</ins> **PK**, isChecked **NN**) |

**Abbreviations used:**

**PK** = PRIMARY KEY

**UQ** = UNIQUE

**NN** = NOT NULL

**DE** = DEFAULT

**CK** = CHECK
### 2\. Domains

> The following additional domains were specified:

| Domain Name | Domain Specification |
|-------------|----------------------|
| TaskStatus | ENUM('To Do','Doing', 'Done') |

### 3\. Schema validation

> To validate the Relational Schema obtained from the Conceptual Model, all functional dependencies are identified and the normalization of all relation schemas is accomplished.

| **TABLE R01** | User |
|---------------|------|
| **Keys** | { id }, { username }, { email } |
| **Functional Dependencies:** |  |
| FD0101 | id → { name, username, email, password, phone_number, is_deactivated } |
| FD0102 | name → { id, username, email, password, phone_number, is_deactivated } |
| FD0103 | username → { id, name, email, password, phone_number, is_deactivated } |
| FD0104 | email → { id, name, username, password, phone_number, is_deactivated } |
| **NORMAL FORM** | BCNF |

| **TABLE R02** | Admin |
|---------------|------|
| **Keys** | { id } |
| **Functional Dependencies:** |  |
| FD0101 | *none* |
| **NORMAL FORM** | BCNF |

| **TABLE R03** | Project |
|---------------|------|
| **Keys** | { id }, { name } |
| **Functional Dependencies:** |  |
| FD0101 | id → { name, start_date, delivery_date, archived } |
| FD0102 | name → { id, start_date, delivery_date, archived } |
| **NORMAL FORM** | BCNF |

| **TABLE R04** | ProjectMember |
|---------------|------|
| **Keys** | { user_id, project_id } |
| **Functional Dependencies:** |  |
| FD0101 |  { user_id, project_id } → { is_coordinator, is_favorite } |
| **NORMAL FORM** | BCNF |

| **TABLE R05** | Post |
|---------------|------|
| **Keys** | { id } |
| **Functional Dependencies:** |  |
| FD0101 | id → { title, description, upvotes, date, is_edited, project_id, author_id } |
| **NORMAL FORM** | BCNF |

| **TABLE R06** | PostComment |
|---------------|------|
| **Keys** | { id } |
| **Functional Dependencies:** |  |
| FD0101 | id → { comment, date, is_edited, parent_post_id, parent_comment_id, author_id } |
| **NORMAL FORM** | BCNF |

| **TABLE R07** | Task |
|---------------|------|
| **Keys** | { id } |
| **Functional Dependencies:** |  |
| FD0101 | id → { name, description, start_date, delivery_date, status } |
| **NORMAL FORM** | BCNF |

| **TABLE R08** | ProjectMemmberTask |
|---------------|------|
| **Keys** | { user_id, task_id } |
| **Functional Dependencies:** |  |
| FD0101 | *none* |
| **NORMAL FORM** | BCNF |

| **TABLE R09** | Message |
|---------------|------|
| **Keys** | { id } |
| **Functional Dependencies:** |  |
| FD0101 | id → { text, date, sender_id, receiver_id } |
| **NORMAL FORM** | BCNF |

| **TABLE R10** | Changes |
|---------------|------|
| **Keys** | { id } |
| **Functional Dependencies:** |  |
| FD0101 | id → { text, date, user_id, project_id } |
| **NORMAL FORM** | BCNF |

| **TABLE R11** | Notification |
|---------------|------|
| **Keys** | { id } |
| **Functional Dependencies:** |  |
| FD0101 | id → { description, date, origin } |
| **NORMAL FORM** | BCNF |

| **TABLE R12** | UserNotification |
|---------------|------|
| **Keys** | { user_id, notification_id } |
| **Functional Dependencies:** |  |
| FD0101 | { user_id, notification_id } → { is_checked } |
| **NORMAL FORM** | BCNF |

> No further normalization was needed for this schema, as each of the relations presented already conform with BCNF, considering they are all in 3NF and have no overlapping candidate keys.

---

## A6: Indexes, triggers, transactions and database population

> This section is dedicated to an array of essential components in database management, each serving distinct yet interrelated roles:

- **Indexes**: Indexes enhance query performance by providing swift access to specific data within a database. This part explores the creation and optimization of indexes to expedite data retrieval.

- **Triggers**: Triggers are event-driven actions that automatically respond to database events. The focus here is on understanding, configuring, and managing triggers to maintain data consistency and enforce business rules.

- **Transactions**: Transactions ensure data integrity by grouping one or more SQL operations into a single, atomic unit. This segment delves into transaction management, including the use of ACID properties (Atomicity, Consistency, Isolation, Durability) for data reliability.

- **Database Population**: This aspect involves the systematic injection of data into the database. The discussion includes techniques for initial data population, periodic updates, and strategies for generating meaningful test data.

Together, these components play a vital role in shaping a well-structured and high-performance database, a cornerstone for robust data-driven applications and systems.

### 1\. Database Workload

Understanding the **workload** is a crucial step in shaping the application's database design to align with performance objectives. This comprehensive analysis entails estimating the volume of tuples for each relation, as well as predicting their growth over time, which is vital for optimizing the database architecture.

| **Relation reference** | **Relation Name** | **Order of magnitude** | **Estimated growth** |
|------------------------|-------------------|------------------------|----------------------|
| R01 | user | 10.000 (tens of thousands) | 10 (tens)/day |
| R02 | admin | 100 (hundreds | 1 (one)/day |
| R03 | notification | 10.000 (tens of thousands) | 10 (tens)/day |
| R04 | user_notification | 10.000 (tens of thousands) | 10 (tens)/day |
| R05 | message | 10.000 (tens of thousands) | 10 (tens)/day |
| R06 | message_sender | 10.000 (tens of thousands) | 10 (tens)/day |
| R07 | message_receiver | 10.000 (tens of thousands) | 10 (tens)/day |
| R08 | project | 1.000 (thousands) | 1 (one)/ day |
| R09 | project_member | 10.000 (tens of thousands) | 10 (tens)/day |
| R10 | member_invitation | 10.000 (tens of thousands) | 10 (tens)/day |
| R11 | coordinator | 1.000 (thousands) | 1 (one)/day |
| R12 | favorite | 1.000 (thousands) | 1 (one)/day |
| R13 | task | 100.000 (hundreds of thousands) | 100 (hundreds)/day |
| R14 | memeber_task | 100.000 (hundreds of thousands) | 100 (hundreds)/day |
| R15 | project_task | 100.000 (hundreds of thousands) | 100 (hundreds)/day |
| R16 | changes | 100.000 (hundreds of thousands) | 100 (hundreds)/day |
| R17 | post | 10.000 (tens of thousands) | 10 (tens)/day |
| R18 | post_comment | 100.000 (hundreds of thousands) | 100 (hundreds)/day |

### 2\. Proposed Indices

#### 2.1. Performance Indices

> Proposed indexes are intended to enhance the performance of specific queries that have been identified. These indexes will be strategically designed to optimize query execution and accelerate data retrieval in the system."

| **Index** | IDX01 |
|-----------|-------|
| **Relation** | ProjectMember |
| **Attribute** | idUser |
| **Type** | B-tree |
| **Cardinality** | Medium |
| **Clustering** | Yes |
| **Justification** | ProjectMember table will be large and it will be very common to access this table for searching some project members, so it seems useful an index for this. The cardinality is medium and update frequency is not high, so it's an oportunity to include clustering. Since clustering will be used, the index type is B-tree. |

```sql
CREATE INDEX index_projectmember_iduser ON ProjectMember USING btree(idUser); CLUSTER ProjectMember USING index_projectmember_iduser;
```

| **Index** | IDX02 |
|-----------|-------|
| **Relation** | Message |
| **Attribute** | sender_id, receiver_id |
| **Type** | B-tree |
| **Cardinality** | High |
| **Clustering** | No |
| **Justification** | Table Message will be large because it will save each message sent by an user. Each user can send different messages(different id) for a specific user(same receiver_id), so this table will have the same combination with different id's, so it will have an high cardinality. This action of sending messages will be frequent, so it is an index for searching messages will be useful. However this index is not a good candidate for clustering because it won't be efficient in this case. |

```sql
CREATE INDEX index_message_sender_receiver ON Message USING btree(sender_id, receiver_id);
```

| **Index** | IDX03 |
|-----------|-------|
| **Relation** | Task |
| **Attribute** | user_assigned_id |
| **Type** | B-tree |
| **Cardinality** | Medium |
| **Clustering** | No |
| **Justification** | Table Task will be large because it will save each task defined for a project and a very common query to filter every task assigned for an project member will be necessary, so an index is useful. It has medium cardinality due to multiple tuples having the same user_assigned_id and medium update frequency, so clustering isn't a good option, because it won't be efficient. |

```sql
CREATE INDEX index_task_assigned_member ON ProjectMemberTask USING btree(user_id,task_id);
```

#### 2.2. Full-text Search Indices

> The system under development must incorporate comprehensive full-text search capabilities, utilizing PostgreSQL's robust features. This requirement necessitates the specification of the fields within the database where full-text search functionality will be made available. Additionally, the associated setup should encompass all essential configurations, indexing definitions, and any other pertinent details. This comprehensive approach ensures that the system's full-text search functionality is not only enabled but also optimized for efficient and effective information retrieval.

| **Index** | IDX04 |
|-----------|-------|
| **Relation** | Post |
| **Attribute** | Attribute where the index is applied |
| **Type** | GIN |
| **Clustering** | No |
| **Justification** | Improve the performance of full-text search for searching a speficic term on the table Post. In this table, the attributes "title" and "description" won't be updated frequently, so GIN type seems a good option. |

```sql
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
```

### 3\. Triggers

> Triggers and user-defined functions play a crucial role in automating tasks in response to changes in the database. They are often employed to enforce business rules, which ensure data consistency and adherence to specific guidelines. This combination of triggers and user-defined functions forms a powerful mechanism for maintaining data integrity within the database.

| **Trigger** | TRIGGER01 |
|-------------|-----------|
| **Description** | When a project is archived, it is removed from all users' favorites |

```sql
CREATE FUNCTION remove_favorite() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF NEW.archived = TRUE THEN
            UPDATE "ProjectMember" SET isFavorite = FALSE WHERE idProject = NEW.id;
            RETURN NULL;
            END IF;
        RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER remove_favorite_trigger
        BEFORE UPDATE ON "Project"
        FOR EACH ROW
        EXECUTE PROCEDURE remove_favorite();
```

| **Trigger** | TRIGGER02 |
|-------------|-----------|
| **Description** | A user cannot have more than 10 favorite projects |

```sql
CREATE FUNCTION favorite_restriction() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF NEW.isCoordinator IS NOT NULL THEN
            IF EXISTS (SELECT COUNT(*) FROM "ProjectMember" WHERE NEW.idUser = idUser AND isFavorite = TRUE) = 10 THEN
                RAISE EXCEPTION 'An user cannot have more than 10 favorite projects.';
                RETURN NULL;
            END IF;
        END IF;
        RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER favorite_restriction_trigger
        BEFORE UPDATE ON "ProjectMember"
        FOR EACH ROW
        EXECUTE PROCEDURE favorite_restriction();
```

| **Trigger** | TRIGGER03 |
|-------------|-----------|
| **Description** | A project can only have 1 coordinator at the same time. |

```sql
CREATE FUNCTION one_coordinator_restriction() RETURNS TRIGGER AS
$BODY$
BEGIN
        IF EXISTS (SELECT COUNT(*) FROM "ProjectMember" WHERE NEW.idProject = idProject AND isCoordinator = TRUE ) > 0 THEN
           RAISE EXCEPTION 'A project cannot have more than 1 coordinator.';
           RETURN NULL;
        END IF;
        RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER one_coordinator_restriction_trigger
        BEFORE UPDATE ON "ProjectMember"
        FOR EACH ROW
        EXECUTE PROCEDURE one_coordinator_restriction();
```

### 4\. Transactions

> Transactions are a fundamental component required to ensure the integrity of the data within the system. These transactions, governed by the principles of ACID (Atomicity, Consistency, Isolation, Durability), serve to maintain data accuracy, reliability, and consistency throughout the database operations.

| Transaction | TRAN01 |
|-------------|--------|
| Description | Get tasks in the 'To Do' status |
| Justification | In the middle of the transaction, the insertion of new rows in the task table can occur, which implies that the information retrieved in both selects is different, consequently resulting in a Phantom Read. It's READ ONLY because it only uses Selects. |
| Isolation level | SERIALIZABLE READ ONLY |

```sql
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE READ ONLY;

-- Get number of 'TO DO' tasks
SELECT COUNT(*)
FROM Task
WHERE status = 'To Do';

-- Get 'TO DO' tasks and information about the users assigned
SELECT user.username, Task.start_date, Task.delivery_date, Task.description
FROM Task
INNER JOIN ProjectMemberTask ON Task.id = ProjectMemberTask.task_id
INNER JOIN ProjectMember ON ProjectMember.id = ProjectMemberTask.user_id
INNER JOIN user ON user.id = ProjectMember.idUser
WHERE Task.status = 'TO DO'

END TRANSACTION;
```

| Transaction | TRAN02 |
|-------------|--------|
| Description | Get tasks in the 'To Do' status |
| Justification | The isolation level is Repeatable Read, because, otherwise, an update of task_id could happen, due to an insert in the table Task committed by a concurrent transaction, and as a result, inconsistent data would be stored. |
| Isolation level | REPEATABLE READ |

```sql
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL REPEATABLE READ

-- Insert task
INSERT INTO Task (name, description, start_date, delivery_date, status, project_id)
 VALUES ($name, $description, $start_date, $delivery_date, $status, $project_id);

-- Assign user to task
INSERT INTO ProjectMemberTask (user_id, task_id)
 VALUES ($user_id,currval('task_id_seq'));

END TRANSACTION;
```

## Annex A. SQL Code

- **SQL Schemas**: SQL schemas are useful for organizing database objects into logical groups, providing a way to separate and manage database structures. They help maintain database clarity, security, and access control by categorizing tables, views, and other objects under distinct schemas. By using schemas, you can better structure and control the database, making it easier to manage, maintain, and secure.
- **Database Scripts**: The EBD component includes essential database scripts in this annex.
- **Separate Elements**: It's crucial to present the database creation script (for building and rebuilding the database) and the population script (containing test data with plausible values) as separate elements.
- **Git Repository**: To enhance collaboration, it's recommended to include this code in the group's Git repository and provide accessible links.

### A.1. Database schema

```sql
-- Drop existing tables if they exist
DROP DATABASE IF EXISTS test;
CREATE DATABASE test;
\c test

DROP SCHEMA IF EXISTS test;
CREATE SCHEMA test;

SET search_path TO test;

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
    archived BOOLEAN,
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
```

### A.2. Database population

```sql
INSERT INTO "User" (id,name,username,email,password,phoneNumber,isDeactivated) VALUES (1,'Rúben Fonseca', 'rubenf11', 'ruben@gmail.com', 'rfvf', '913111111', FALSE),
(2,'Miguel Marinho', 'kiriu', 'marinho@gmail.com', 'mnm', '912111111', FALSE),
(3,'Emanuel Maia', 'marco_pantani', 'manu@gmail.com', 'mp', '911111111', FALSE),
(4,'Alberto Serra', 'alberto_serra', 'alberto@gmail.com', 'as', '914111111', FALSE),
(5,'João Ferreira', 'jferreira', 'jferreira@gmail.com', 'jf', '915111111', FALSE),
(6,'Maria Santos', 'msantos', 'msantos@gmail.com', 'password', '916111111', FALSE),
(7,'Cristiano Silva', 'csilva', 'csilva@gmail.com', 'password', '917111111', FALSE),
(8,'Ricardo Loureiro', 'rloureiro', 'rloureiro@gmail.com', 'password', '918111111', FALSE),
(9,'Daniel Castro', 'dcastro', 'dcastro@gmail.com', 'password', '919111111', FALSE),
(10,'Gonçalo Ferreira', 'gferreira', 'gferreira@gmail.com', 'password', '910111111', FALSE);

INSERT INTO Admin (id) VALUES (1),(2),(3),(4);


INSERT INTO Project (id,name,start_date,delivery_date,archived) VALUES (1,'Project1', '2023-10-22', '2024-10-22', FALSE),
(2,'Project2', '2023-10-20', '2024-10-22', FALSE),
(3,'Project3', '2023-10-19', '2024-10-22', FALSE),
(4,'Project4', '2023-10-18', '2024-10-22', FALSE),
(5,'Project5', '2023-10-17', '2024-10-22', FALSE),
(6,'Project6', '2023-10-16', '2024-10-22', FALSE),
(7,'Project7', '2023-10-15', '2024-10-22', FALSE),
(8,'Project8', '2023-10-14', '2024-10-22', FALSE),
(9,'Project9', '2023-10-12', '2024-10-22', FALSE),
(10,'Project10', '2022-10-22', '2023-01-22', TRUE);

INSERT INTO ProjectMember (idUser,idProject,isCoordinator,isFavorite) VALUES (1,1,TRUE,TRUE),
(2,2,TRUE,TRUE),
(1,3,FALSE,TRUE),
(3,6,TRUE,TRUE),
(4,3,TRUE,TRUE),
(10,2,FALSE,TRUE),
(2,1,FALSE,TRUE),
(3,7,TRUE,TRUE),
(3,10,TRUE,TRUE),
(2,10,FALSE,TRUE);

INSERT INTO ProjectMemberInvitation (idUser,idProject,inviteAccepted) VALUES (1,3,TRUE),
(1,3,TRUE),
(3,6,TRUE),
(4,3,TRUE),
(10,2,TRUE),
(2,1,TRUE),
(1,7,FALSE),
(2,8,FALSE),
(7,1,FALSE),
(4,10,FALSE);

INSERT INTO Post (id,title,description,upvotes,date,isEdited,author_id,project_id) VALUES (1,'title1', 'description1', 1,'2023-10-22',FALSE,1,1),
(2,'title2', 'description2', 1,'2023-10-22',FALSE,2,2),
(3,'title3', 'description3', 1,'2023-10-22',FALSE,1,3),
(4,'title4', 'description4', 1,'2023-10-22',FALSE,1,1),
(5,'title5', 'description5', 1,'2023-10-22',FALSE,3,6),
(6,'title6', 'description6', 1,'2023-10-22',FALSE,10,2),
(7,'title7', 'description7', 1,'2023-10-22',FALSE,10,2),
(8,'title8', 'description8', 1,'2023-10-22',FALSE,2,1),
(9,'title9', 'description9', 1,'2023-10-22',FALSE,3,10),
(10,'title10', 'description10', 1,'2023-10-22',FALSE,2,10);

INSERT INTO PostComment (id,comment,date,author_id,post_id,parent_comment_id,isEdited) VALUES (1,'comment1','2023-10-22',1,1,NULL,FALSE),
(2,'comment2','2023-10-22',2,2,NULL,FALSE),
(3,'comment3','2023-10-22',1,3,NULL,FALSE),
(4,'comment4','2023-10-22',4,3,NULL,FALSE),
(5,'comment5','2023-10-22',1,7,NULL,FALSE),
(6,'comment6','2023-10-22',2,1,NULL,FALSE),
(7,'comment7','2023-10-22',2,8,NULL,FALSE),
(8,'comment8','2023-10-22',1,1,NULL,FALSE),
(9,'comment9','2023-10-22',7,1,NULL,FALSE),
(10,'comment10','2023-10-22',4,10,NULL,FALSE);

INSERT INTO Task (id,name,description,start_date,delivery_date,status,project_id) VALUES (1, 'Task1','description1','2023-10-22', '2024-10-22',DEFAULT,1),
(2, 'Task2','description2','2023-10-22', '2024-10-22',DEFAULT,2),
(3, 'Task3','description3','2023-10-22', '2024-10-22',DEFAULT,3),
(4, 'Task4','description4','2023-10-22', '2024-10-22',DEFAULT,6),
(5, 'Task5','description5','2023-10-22', '2024-10-22',DEFAULT,1),
(6, 'Task6','description6','2023-10-22', '2024-10-22',DEFAULT,10),
(7, 'Task7','description7','2023-10-22', '2024-10-22',DEFAULT,10),
(8, 'Task8','description8','2023-10-22', '2024-10-22',DEFAULT,2),
(9, 'Task9','description9','2023-10-22', '2024-10-22',DEFAULT,10),
(10, 'Task10','description10','2023-10-22', '2024-10-22',DEFAULT,10);

INSERT INTO ProjectMemberTask (user_id,task_id) VALUES (1,1),
(2,2),
(1,3),
(3,4),
(2,5),
(2,6),
(3,7),
(10,8),
(2,9),
(3,10);

INSERT INTO Message (id,text,date,sender_id,receiver_id) VALUES (1,'text1','2023-10-22',1,2),
(2,'text2','2023-10-22',1,3),
(3,'text3','2023-10-22',1,4),
(4,'text4','2023-10-22',3,2),
(5,'text5','2023-10-22',4,1),
(6,'text6','2023-10-22',3,2),
(7,'text7','2023-10-22',1,5),
(8,'text8','2023-10-22',3,4),
(9,'text9','2023-10-22',4,2),
(10,'text10','2023-10-22',3,6);

INSERT INTO Changes (id,text,date,project_id,user_id) VALUES (1,'text','2023-10-23',1,1),
(2,'text','2023-10-23',2,2),
(3,'text','2023-10-23',1,3),
(4,'text','2023-10-23',3,6),
(5,'text','2023-10-23',4,3),
(6,'text','2023-10-23',10,2),
(7,'text','2023-10-23',2,1),
(8,'text','2023-10-23',3,7),
(9,'text','2023-10-23',3,10),
(10,'text','2023-10-23',2,10);

INSERT INTO Notification (id,description,date,origin) VALUES (1,'description','2023-10-22', 'o'),
(2,'description','2023-10-22', 'o'),
(3,'description','2023-10-22', 'o'),
(4,'description','2023-10-22', 'o'),
(5,'description','2023-10-22', 'o'),
(6,'description','2023-10-22', 'o'),
(7,'description','2023-10-22', 'o'),
(8,'description','2023-10-22', 'o'),
(9,'description','2023-10-22', 'o'),
(10,'description','2023-10-22', 'o');

INSERT INTO UserNotification (user_id,notification_id,isChecked) VALUES (1,1,FALSE),
(1,2,FALSE),
(2,3,FALSE),
(3,4,FALSE),
(2,5,FALSE),
(3,6,FALSE),
(4,7,FALSE),
(1,8,FALSE),
(2,9,FALSE),
(4,10,FALSE);
```

---

## Revision history

---

GROUP2363, 22/10/2023

| Name | E-Mail |
|------|--------|
| Alberto Serra (Editor) | up202103627@up.pt |
| Emanuel Maia | up202107486@up.pt |
| Miguel Marinho | up202108822@up.pt |
| Rúben Fonseca | up202108830@up.pt |

