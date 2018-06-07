USE master
GO

--DROP DATABASE WEBSHOP
--GO

CREATE DATABASE WEBSHOP
GO
 
USE WEBSHOP
GO

 
/* TABLE CATEGORIE
 * 
 * "Er is een categorie '<categorienaam>'."
 */
CREATE TABLE CATEGORIE (
   CATEGORIENAAM VARCHAR(15) NOT NULL,

   CONSTRAINT PK_CATEGORIE PRIMARY KEY (CATEGORIENAAM),
   CONSTRAINT CK_CATEGORIENAAM CHECK (LEN(CATEGORIENAAM) >=5)
)

/* TABLE GEBRUIKER
 * 
 * "Gebruiker <gebruikersnaam> heeft als wachtwoord '<wachtwoord>'."
 * "Gebruiker <gebruikersnaam> heeft als voornaam <voornaam>."
 * "Gebruiker <gebruikersnaam> heeft als tussenvoegsel <tussenvoegsel>."
 * "Gebruiker <gebruikersnaam> heeft als achternaam <achternaam>."
 * "Gebruiker <gebruikersnaam> is van de sexe <sexe>."
 * "Gebruiker <gebruikersnaam> heeft als emailadres '<emailadres>'."
 * "Gebruiker <gebruikersnaam> woont in de straat '<straatnaam>'."
 * "Het huisnummer van gebruiker <gebruikersnaam> is <huisnummer>."
 * "De postcode van gebruiker <gebruikersnaam> is <postcode>."
 * "De woonplaats van gebruiker <gebruikersnaam> is <plaatsnaam>."
 */
CREATE TABLE GEBRUIKER (
   GEBRUIKERSNAAM VARCHAR(15)  NOT NULL,
   VOORNAAM       VARCHAR(125) NOT NULL,
   TUSSENVOEGSEL  VARCHAR(30)  NULL,
   ACHTERNAAM     VARCHAR(125) NOT NULL,
   STRAATNAAM     VARCHAR(125) NOT NULL,
   HUISNUMMER     INTEGER      NOT NULL,
   POSTCODE       CHAR(6)      NOT NULL,
   WOONPLAATS     VARCHAR(125) NOT NULL,
   EMAIL          VARCHAR(50)  NOT NULL,
   SEXE           CHAR(1)      NOT NULL,
   WACHTWOORD     VARCHAR(15)  NOT NULL,

   CONSTRAINT PK_GEBRUIKER      PRIMARY KEY (GEBRUIKERSNAAM),
   CONSTRAINT CK_GEBRUIKERSNAAM CHECK (LEN(GEBRUIKERSNAAM) >= 5),
   CONSTRAINT CK_VOORNAAM       CHECK (LEN(VOORNAAM) >0),
   CONSTRAINT CK_ACHTERNAAM     CHECK (LEN(ACHTERNAAM) >= 0),
   CONSTRAINT CK_WACHTWOORD     CHECK (LEN(WACHTWOORD) >= 4),
   CONSTRAINT CK_POSTCODE       CHECK (POSTCODE LIKE '[1-9][0-9][0-9][0-9][A-Z][A-Z]'),
   CONSTRAINT CK_SEXE           CHECK (SEXE IN ('M', 'V'))
)


/* TABLE PRODUCT
 * 
 * "Product <productnummer> is '<productnaam>'."
 * "Product <productnummer> valt onder de categorie '<categorienaam>'."
 * "Product <productnummer> heeft thumbnail '<bestandsnaam>'."
 * "Product <productnummer> heeft als afbeelding '<bestandsnaam>'."
 * "Product <productnummer> kost € <bedrag>."
 * "Product <productnummer> heeft omschrijving '<productomschrijving>'."
 * "Van product <productnummer> zijn nog <aantal> exemplaren op voorraad."
 */
CREATE TABLE PRODUCT (
   PRODUCTNUMMER    INTEGER  IDENTITY NOT NULL,
   PRODUCTNAAM      VARCHAR(100)      NOT NULL,
   OMSCHRIJVING     VARCHAR(MAX)      NOT NULL,
   CATEGORIE        VARCHAR(15)       NOT NULL,
   PRIJS            NUMERIC(8,2)      NOT NULL,
   VOORRAAD         INTEGER           NULL,
   AFBEELDING_GROOT VARCHAR(1024)     NOT NULL,
   AFBEELDING_KLEIN VARCHAR(1024)     NOT NULL,

   CONSTRAINT PK_PRODUCT        PRIMARY KEY (PRODUCTNUMMER),
   CONSTRAINT UK_PRODUCTNAAM    UNIQUE (PRODUCTNAAM),
   CONSTRAINT CK_PRODUCTNAAM    CHECK (LEN(PRODUCTNAAM) >= 5),
   CONSTRAINT CK_PRIJS          CHECK (PRIJS > 0),
   CONSTRAINT CK_VOORRAAD       CHECK (VOORRAAD > 0),
   CONSTRAINT CK_OMSCHRIJVING   CHECK(LEN(OMSCHRIJVING) > 50),
   CONSTRAINT FK_PRODUCT_CATEGORIE FOREIGN KEY (CATEGORIE)
				REFERENCES CATEGORIE (CATEGORIENAAM) 
					ON DELETE NO ACTION 
					ON UPDATE NO ACTION
)

/* TABLE PRODUCT_GERELATEERD_PRODUCT
 * 
 * "Product <productnummer> is gerelateerd aan product <productnummer>."
 */
CREATE TABLE PRODUCT_GERELATEERD_PRODUCT (
   PRODUCTNUMMER                     INTEGER NOT NULL,
   PRODUCTNUMMER_GERELATEERD_PRODUCT INTEGER NOT NULL,

   CONSTRAINT PK_PRODUCT_GERELATEERD_PRODUCT PRIMARY KEY (PRODUCTNUMMER, PRODUCTNUMMER_GERELATEERD_PRODUCT),
   CONSTRAINT CK_PRODUCT_GERELATEERD_PRODUCT CHECK (PRODUCTNUMMER <> PRODUCTNUMMER_GERELATEERD_PRODUCT),
   CONSTRAINT FK1_PRODUCT_GERELATEERD_PRODUCT FOREIGN KEY (PRODUCTNUMMER)
				REFERENCES PRODUCT (PRODUCTNUMMER)
					ON DELETE NO ACTION
					ON UPDATE NO ACTION,
   CONSTRAINT FK2_PRODUCT_GERELATEERD_PRODUCT FOREIGN KEY (PRODUCTNUMMER_GERELATEERD_PRODUCT)
				REFERENCES PRODUCT (PRODUCTNUMMER)
					ON DELETE NO ACTION
					ON UPDATE NO ACTION
)

GO


