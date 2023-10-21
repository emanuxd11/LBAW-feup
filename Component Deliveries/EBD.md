# EBD: Database Specification Component

## A4: Conceptual Data Model

A Conceptual Domain Model contains the identification and description of the entities of the domain and the relationships between them.

A UML class diagram is used to document the model.

The class diagram is developed by starting to include only the classes and their relationships in order not to overload the diagram too early in the process. In the following iterations, additional detail is included, namely: class attributes, attribute domains, multiplicity of associations, and additional restrictions in OCL.

### 1. Class diagram

The diagram in this section represents the main organizational titles, the relationships between them, attributes, multiplicity, and other constraints.

![UML_lbaw](https://git.fe.up.pt/lbaw/lbaw2324/lbaw2363/-/raw/main/Component%20Deliveries/images/UML_UPDATED.png?ref_type=heads)

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

> The database scripts are included in this annex to the EBD component.
> 
> The database creation script and the population script should be presented as separate elements.
> The creation script includes the code necessary to build (and rebuild) the database.
> The population script includes an amount of tuples suitable for testing and with plausible values for the fields of the database.
>
> The complete code of each script must be included in the group's git repository and links added here.

### A.1. Database schema

> The complete database creation must be included here and also as a script in the repository.

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
