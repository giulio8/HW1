USE HW1;

DROP table if exists Tratte;
DROP table if exists Prenotazioni;
DROP table if exists Destinazioni;
DROP table if exists Utenti;
CREATE table Utenti (
	username varchar(255) primary key,
    firstname varchar(255),
    lastname varchar(255),
    birthdate date,
    email varchar(255),
    password char(72)
);

CREATE table Destinazioni (
	titolo varchar(255),
    utente varchar(255),
    descrizione varchar(1023),
    immagine varchar(255),
    primary key (titolo, utente),
    index (utente),
    foreign key (utente) references Utenti(username) on delete cascade
);

CREATE table Prenotazioni (
	id varchar(255) primary key,
    prezzo float,
    valuta varchar(15),
    origine char(3),
    destinazione char(3),
    codice_compagnia varchar(255),
    data_partenza date,
    data_arrivo date,
    titolo_destinazione varchar(255),
    utente varchar(255),
    bagaglio int,
    data_prenotazione date,
    index (titolo_destinazione, utente),
    foreign key (titolo_destinazione, utente) references Destinazioni(titolo, utente) on delete cascade
);

CREATE table Tratte (
	id varchar(255) primary key,
    origine char(3),
    destinazione char(3),
    volo varchar(255),
    data_partenza date,
    data_arrivo date,
    index (volo),
    foreign key (volo) references Prenotazioni(id) on delete cascade
);








