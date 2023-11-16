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
(1,4,TRUE),
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
