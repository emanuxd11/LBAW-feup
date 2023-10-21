# EBD: Database Specification Component

## A4: Conceptual Data Model

A Conceptual Domain Model contains the identification and description of the entities of the domain and the relationships between them.

A UML class diagram is used to document the model.

The class diagram is developed by starting to include only the classes and their relationships in order not to overload the diagram too early in the process. In the following iterations, additional detail is included, namely: class attributes, attribute domains, multiplicity of associations, and additional restrictions in OCL.

### 1. Class diagram

The diagram in this section represents the main organizational titles, the relationships between them, attributes, multiplicity, and other constraints.

![UML_lbaw](https://git.fe.up.pt/lbaw/lbaw2324/lbaw2363/-/raw/main/Component%20Deliveries/images/~Updated_UML.png?ref_type=heads)

### 2. Additional Business Rules

-A user can only have up to 10 favorite projects.  
-When a user account is deleted, their work in their projects is not deleted.  
-A user can be a member of multiple projects.  

---


## A5: Relational Schema, validation and schema refinement

> Brief presentation of the artifact goals.

### 1. Relational Schema

> The Relational Schema includes the relation schemas, attributes, domains, primary keys, foreign keys and other integrity rules: UNIQUE, DEFAULT, NOT NULL, CHECK.  
> Relation schemas are specified in the compact notation:  

| Relation reference | Relation Compact Notation                        |
| ------------------ | ------------------------------------------------ |
| R01                | Table1(<ins>id</ins>, attribute **NN**)                     |
| R02                | Table2(<ins>id</ins>, attribute → Table1 **NN**)            |
| R03                | Table3(<ins>id</ins>, id2 → Table2, attribute **UK NN**)   |
| R04                | Table4((<ins>id1</ins>, <ins>id2</ins>) → Table3, id3, attribute **CK** attribute > 0) |

### 2. Domains

> The specification of additional domains can also be made in a compact form, using the notation:  

| Domain Name | Domain Specification           |
| ----------- | ------------------------------ |
| Today	      | DATE DEFAULT CURRENT_DATE      |
| Priority    | ENUM ('High', 'Medium', 'Low') |

### 3. Schema validation

> To validate the Relational Schema obtained from the Conceptual Model, all functional dependencies are identified and the normalization of all relation schemas is accomplished. Should it be necessary, in case the scheme is not in the Boyce–Codd Normal Form (BCNF), the relational schema is refined using normalization.  

| **TABLE R01**   | User               |
| --------------  | ---                |
| **Keys**        | { id }, { email }  |
| **Functional Dependencies:** |       |
| FD0101          | id → {email, name} |
| FD0102          | email → {id, name} |
| ...             | ...                |
| **NORMAL FORM** | BCNF               |

> If necessary, description of the changes necessary to convert the schema to BCNF.  
> Justification of the BCNF.  


---


## A6: Indexes, triggers, transactions and database population

> Brief presentation of the artifact goals.

### 1. Database Workload
 
Understanding the **workload** is a crucial step in shaping the application's database design to align with performance objectives. This comprehensive analysis entails estimating the volume of tuples for each relation, as well as predicting their growth over time, which is vital for optimizing the database architecture.

| **Relation reference** | **Relation Name**   | **Order of magnitude**         | **Estimated growth**   |
| ---------------------- | ------------------- | ----------------------------   | ---------------------- |
| R01                    | user                | 10.000 (tens of thousands)     | 10 (tens) / day        |
| R02                    | admin               | 100 (hundreds                  | 1 (one)/ day           |
| R03                    | notification        | 10.000 (tens of thousands)     | 10 (tens)/ day         |
| R04                    | user_notification   | 10.000 (tens of thousands)     | 10 (tens) / day        |
| R05                    | message             | 10.000 (tens of thousands)     | 10 (tens) / day        |
| R06                    | message_sender      | 10.000 (tens of thousands)     | 10 (tens)/ day         |
| R07                    | message_receiver    | 10.000 (tens of thousands)     | 10 (tens) / day        |
| R08                    | project             | 1.000 (thousands)              | 1 (one)/ day           |
| R09                    | project_member      | 10.000 (tens of thousands)     | 10 (tens) / day        |
| R10                    | member_invitation   | 10.000 (tens of thousands)     | 10 (tens) / day        |
| R11                    | coordinator         | 1.000 (thousands)              | 1 (one) / day          |
| R12                    | favorite            | 1.000 (thousands)              | 1 (one) / day          |
| R13                    | task                | 100.000 (hundreds of thousands)| 100 (hundreds) / day   |
| R14                    | memeber_task        | 100.000 (hundreds of thousands)| 100 (hundreds) / day   |
| R15                    | project_task        | 100.000 (hundreds of thousands)| 100 (hundreds) / day   |
| R16                    | changes             | 100.000 (hundreds of thousands)| 100 (hundreds) / day   |
| R17                    | post                | 10.000 (tens of thousands)     | 10 (tens) / day        |
| R18                    | post_comment        | 100.000 (hundreds of thousands)| 100 (hundreds)/ day    |

### 2. Proposed Indices

#### 2.1. Performance Indices
 
> Indices proposed to improve performance of the identified queries.

| **Index**           | IDX01                                  |
| ---                 | ---                                    |
| **Relation**        | Relation where the index is applied    |
| **Attribute**       | Attribute where the index is applied   |
| **Type**            | B-tree, Hash, GiST or GIN              |
| **Cardinality**     | Attribute cardinality: low/medium/high |
| **Clustering**      | Clustering of the index                |
| **Justification**   | Justification for the proposed index   |
| `SQL code`                                                  ||


#### 2.2. Full-text Search Indices 

> The system being developed must provide full-text search features supported by PostgreSQL. Thus, it is necessary to specify the fields where full-text search will be available and the associated setup, namely all necessary configurations, indexes definitions and other relevant details.  

| **Index**           | IDX01                                  |
| ---                 | ---                                    |
| **Relation**        | Relation where the index is applied    |
| **Attribute**       | Attribute where the index is applied   |
| **Type**            | B-tree, Hash, GiST or GIN              |
| **Clustering**      | Clustering of the index                |
| **Justification**   | Justification for the proposed index   |
| `SQL code`                                                  ||


### 3. Triggers
 
Triggers and user defined functions are used to automate tasks depending on changes to the database. Business rules are usually enforced using a combination of triggers and user defined functions. 

| **Trigger**      | TRIGGER01                              |
| ---              | ---                                    |
| **Description**  | When a project is archived, it is removed from all users' favorites |
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

| **Trigger**      | TRIGGER02                              |
| ---              | ---                                    |
| **Description**  | A user cannot have more than 10 favorite projects |
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

| **Trigger**      | TRIGGER03                              |
| ---              | ---                                    |
| **Description**  | A project can only have 1 coordinator at the same time.|
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


### 4. Transactions
 
> Transactions needed to assure the integrity of the data.  

| SQL Reference   | Transaction Name                    |
| --------------- | ----------------------------------- |
| Justification   | Justification for the transaction.  |
| Isolation level | Isolation level of the transaction. |
| `Complete SQL Code`                                   ||


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


DROP TABLE IF EXISTS User CASCADE;
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
DROP TRIGGER IF EXISTS post_search_update;

DROP TRIGGER IF EXISTS remove_favorite_trigger;
DROP TRIGGER IF EXISTS favorite_restriction_trigger;
DROP TRIGGER IF EXISTS one_coordinator_restriction_trigger;

DROP FUNCTION IF EXISTS remove_favorite;
DROP FUNCTION IF EXISTS favorite_restriction;
DROP FUNCTION IF EXISTS one_coordinator_restriction;



CREATE TYPE TaskStatus AS ENUM('To Do','Doing', 'Done');

-- Create User Table
CREATE TABLE User (
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
    id INT PRIMARY KEY REFERENCES User(id)
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
    idUser INT REFERENCES User(id),
    idProject INT REFERENCES Project(id),
    isCoordinator BOOLEAN,
    isFavorite BOOLEAN,
    PRIMARY KEY (idUser, idProject)
);

-- Create ProjectMemberInvitation Table
CREATE TABLE ProjectMemberInvitation (
    idUser INT REFERENCES User(id),
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
    author_id INT REFERENCES User(id),
    project_id INT REFERENCES Project(id)
);

-- Create PostComment Table
CREATE TABLE PostComment (
    id SERIAL PRIMARY KEY,
    comment VARCHAR NOT NULL,
    date DATE NOT NULL,
    author_id INT REFERENCES User(id),
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
    project_id INT REFERENCES Project(id),
    user_assigned_id INT REFERENCES User(id)
);

-- Create Project_Task Relationship Table
CREATE TABLE ProjectMemberTask (
    user_id INT REFERENCES User(id),
    task_id INT REFERENCES Task(id),
    PRIMARY key (user_id,task_id)
);

-- Create Message Table
CREATE TABLE Message (
    id SERIAL PRIMARY KEY,
    text VARCHAR NOT NULL,
    date DATE NOT NULL,
    sender_id INT REFERENCES User(id),
    receiver_id INT REFERENCES User(id)
);

-- Create Changes Table
CREATE TABLE Changes (
    id SERIAL PRIMARY KEY,
    text VARCHAR,
    date DATE,
    project_id INT REFERENCES Project(id),
    user_id INT REFERENCES User(id)
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
    user_id INT REFERENCES User(id),
    notification_id INT REFERENCES Notification(id),
    PRIMARY KEY (user_id,notification_id),
    isChecked BOOLEAN NOT NULL
);


--INDEX 1
CREATE INDEX index_projectmember_iduser ON ProjectMember USING btree(idUser); CLUSTER ProjectMember USING index_projectmember_iduser;

--INDEX 2
CREATE INDEX index_message_sender_receiver ON Message USING btree(sender_id, receiver_id);

--INDEX 3
CREATE INDEX index_task_assigned_member ON Task USING btree(user_assigned_id);

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

> Only a sample of the database population script may be included here, e.g. the first 10 lines. The full script must be available in the repository.

---


## Revision history


***
GROUP2363, xx/10/2023

| Name                  | E-Mail            |
| --------------------- | ----------------- |
| Alberto Serra         | up202103627@up.pt |
| Emanuel Maia          | up202107486@up.pt |
| Miguel Marinho (Editor)| up202108822@up.pt |
| Rúben Fonseca         | up202108830@up.pt |
