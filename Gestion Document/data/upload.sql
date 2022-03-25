SET default_storage_engine=InnoDb;

drop database if exists upload;

create database upload character set utf8mb4 collate utf8mb4_unicode_ci;

use upload;

-- La gestion des documents

CREATE TABLE document
(
    id           int          auto_increment primary key,
    dateEmission date         not null ,
    titre        varchar(100) NOT NULL unique
);


-- vérification de l'unicité du titre avant celle réalisée par l'index unique afin de renvoyé un message clair

delimiter $$
create trigger avantAjoutDocument before insert on document
    for each row
begin
    if exists(select 1 from document where titre = new.titre) then
        SIGNAL sqlstate '45000' set message_text = '#Ce titre est déjà attribué à un autre document';
    end if;
    if new.dateEmission is null then
        set new.dateEmission = curdate();
    end if;
end
$$

create trigger avantMajDocument before update on document
    for each row
    if exists(select 1 from document where titre = new.titre and id != new.id) then
        SIGNAL sqlstate '45000' set message_text = '#Ce titre est déjà attribué à un autre document';
    end if;
$$

delimiter ;

INSERT INTO document (dateEmission, titre) VALUES ('2021-09-20', 'Calendrier prévisionnel');

INSERT INTO document (titre) VALUES ('Record de la somme');

-- la gestion des étudiants

CREATE TABLE etudiant
(
    id     int(11)     NOT NULL AUTO_INCREMENT,
    nom    varchar(20) NOT NULL,
    prenom varchar(20) NOT NULL,
    PRIMARY KEY (id),
    unique key (nom, prenom)
);

delimiter $$
create trigger avantAjoutEtudiant
    before insert
    on etudiant
    for each row
begin
    if exists(select 1 from etudiant where nom = new.nom and prenom = new.prenom) then
        SIGNAL sqlstate '45000' set message_text = '#Cet étudiant existe déjà';
    end if;
end
$$
delimiter ;

delimiter $$
create trigger avantModificationEtudiant
    before update
    on etudiant
    for each row
begin
    if exists(select 1 from etudiant where nom = new.nom and prenom = new.prenom and id != new.id) then
        SIGNAL sqlstate '45000' set message_text = '#Un étudiant de même nom et même prénom existe déjà';
    end if;
end
$$
delimiter ;


INSERT INTO etudiant (nom, prenom, id)
VALUES ('COLLINAS', 'Mathéos', 1),
       ('CREUZOT', 'Jules', 2),
       ('DAWAGNE', 'Jérémy', 3),
       ('DELAPORTE', 'Pierre', 4),
       ('DELAPORTE', 'Théo', 5),
       ('DESSOUT', 'Jérémy', 6),
       ('DURIEZ', 'Dimitri', 7),
       ('HUSIAUX', 'Valentin', 8),
       ('KABA', 'Théo', 9),
       ('KREMER', 'Anton', 10),
       ('LASORNE', 'Lucas', 11),
       ('LEGUAY', 'Théo', 12),
       ('LEMAIRE', 'Gauthier', 13),
       ('MAILLARD', 'Grégoire', 14),
       ('MAILLET', 'Arnaud', 15),
       ('MARTIN', 'Aurelien', 16),
       ('MEZGHACHE', 'Yanis', 17),
       ('NANCELLE', 'Alexis', 18),
       ('NEVES', 'Dylan', 19),
       ('OUTREBON', 'Pierre', 20),
       ('POSSON', 'Corentin', 21);


-- la gestion des étudiants version 2

CREATE TABLE etudiant2
(
    id int AUTO_INCREMENT PRIMARY KEY,
    nom varchar(20) NOT NULL,
    prenom varchar(20) NOT NULL,
    photo varchar(40) null,
    unique key (nom, prenom)
);

delimiter $$
create trigger avantAjoutEtudiant2
    before insert
    on etudiant2
    for each row
begin
    if exists(select 1 from etudiant2 where nom = new.nom and prenom = new.prenom) then
        SIGNAL sqlstate '45000' set message_text = '#Cet étudiant existe déjà';
    end if;
end
$$

create trigger avantModificationEtudiant2
    before update
    on etudiant2
    for each row
begin
    if exists(select 1 from etudiant2 where nom = new.nom and prenom = new.prenom and id != new.id) then
        SIGNAL sqlstate '45000' set message_text = '#Un étudiant de même nom et même prénom existe déjà';
    end if;
end
$$

delimiter ;

INSERT INTO etudiant2 (nom, prenom, photo)
VALUES ('COLLINAS', 'Mathéos', '1.png'),
       ('CREUZOT', 'Jules', '2.png'),
       ('DAWAGNE', 'Jérémy', '3.png'),
       ('DELAPORTE', 'Pierre', '4.png'),
       ('DELAPORTE', 'Théo', '5.png'),
       ('DESSOUT', 'Jérémy', '6.png'),
       ('DURIEZ', 'Dimitri', '7.png'),
       ('HUSIAUX', 'Valentin', '8.png'),
       ('KABA', 'Théo', '9.png'),
       ('KREMER', 'Anton', '10.png'),
       ('LASORNE', 'Lucas', '11.png'),
       ('LEGUAY', 'Théo', '12.png'),
       ('LEMAIRE', 'Gauthier', '13.png'),
       ('MAILLARD', 'Grégoire', '14.png'),
       ('MAILLET', 'Arnaud', '15.png'),
       ('MARTIN', 'Aurelien', '16.png'),
       ('MEZGHACHE', 'Yanis', '17.png'),
       ('NANCELLE', 'Alexis', '18.png'),
       ('NEVES', 'Dylan', '19.png'),
       ('OUTREBON', 'Pierre', '20.png'),
       ('POSSON', 'Corentin', '21.png');
