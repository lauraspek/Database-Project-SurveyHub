
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database: `surveyhub`

DROP DATABASE IF EXISTS surveyhub;

CREATE DATABASE IF NOT EXISTS surveyhub;

USE surveyhub;

-- Table structure for table `admin`

CREATE TABLE IF NOT EXISTS `admin` (
  `Email_User` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `closed_answer`

CREATE TABLE IF NOT EXISTS `closed_answer` (
  `Email_User` varchar(50) NOT NULL,
  `Id_Options` int(11) NOT NULL,
  `Id_OptionClosedQuestion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `closed_question`

CREATE TABLE IF NOT EXISTS `closed_question` (
  `Id_Question` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `company`

CREATE TABLE IF NOT EXISTS `company` (
  `Email` varchar(50) NOT NULL,
  `Cf` varchar(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Venue` varchar(30) NOT NULL,
  `Password` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `composition`

CREATE TABLE IF NOT EXISTS `composition` (
  `Id_Survey` int(11) NOT NULL,
  `Id_Question` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `domain`

CREATE TABLE IF NOT EXISTS `domain` (
  `Keyword` varchar(30) NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `interest`

CREATE TABLE IF NOT EXISTS `interest` (
  `Keyword_Domain` varchar(30) NOT NULL,
  `Email_User` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `invitation`

CREATE TABLE IF NOT EXISTS `invitation` (
  `Id` int(11) AUTO_INCREMENT,
  `Outcome` enum('accepted','refused', 'pending') DEFAULT 'pending',
  `Email_User` varchar(50) NOT NULL,
  `Email_PremiumUser` varchar(50) DEFAULT NULL,
  `Email_Company` varchar(50) DEFAULT NULL,
  `Id_Survey` int(11) NOT NULL,
  PRIMARY KEY(Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `open_answer`

CREATE TABLE IF NOT EXISTS `open_answer` (
  `Text` text NOT NULL,
  `Email_User` varchar(50) NOT NULL,
  `Id_OpenQuestion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `open_question`

CREATE TABLE IF NOT EXISTS `open_question` (
  `Id_Question` int(11) NOT NULL,
  `MaxChar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `options`

CREATE TABLE IF NOT EXISTS `options` (
  `Id_ClosedQuestion` int(11) NOT NULL,
  `Id` int(11) NOT NULL,
  `Text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `premium`

CREATE TABLE IF NOT EXISTS `premium` (
  `Costs` float NOT NULL DEFAULT 50,
  `NSurveys` int(11) NOT NULL DEFAULT 0,
  `SubscriptionDate` date NOT NULL,
  `ExpiringDate` date NOT NULL,
  `Email_User` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `prize`

CREATE TABLE IF NOT EXISTS `prize` (
  `Name` varchar(30) NOT NULL,
  `Description` text NOT NULL,
  `Photo` blob NOT NULL,
  `Points` int(11) NOT NULL,
  `Email_AdminUser` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `question`

CREATE TABLE IF NOT EXISTS `question` (
  `Id` int(11) NOT NULL,
  `Text` text NOT NULL,
  `Photo` blob DEFAULT NULL,
  `Score` float DEFAULT NULL,
  `Type` enum('Open', 'Closed'),
  `Email_Company` varchar(50) DEFAULT NULL,
  `Email_PremiumUser` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `receive`

CREATE TABLE IF NOT EXISTS `receive` (
  `Email_User` varchar(50) NOT NULL,
  `Name_Prize` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `survey`

CREATE TABLE IF NOT EXISTS `survey` (
  `Id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `Title` varchar(30) NOT NULL,
  `State` enum('open','closed') NOT NULL,
  `CreationDate` date NOT NULL,
  `ClosingDate` date NOT NULL,
  `Keyword_Domain` varchar(30) NOT NULL,
  `Email_Company` varchar(50) DEFAULT NULL,
  `MaxUser` int(11) NOT NULL,
  `Email_PremiumUser` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table structure for table `user`

CREATE TABLE IF NOT EXISTS `user` (
  `Email` varchar(50) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Surname` varchar(30) NOT NULL,
  `BirthYear` int(11) NOT NULL,
  `BirthPlace` varchar(30) NOT NULL,
  `BonusTot` float NOT NULL,
  `Password` varchar(30) NOT NULL, 
  `UserType` int(11)			# 1 for admin user, 2 for premium user, 3 for generic user
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


#filing the database

INSERT INTO `closed_answer` (`Email_User`, `Id_Options`, `Id_OptionClosedQuestion`) VALUES
('giulia.piva@gmail.com', 001, 0000000017);

INSERT INTO `closed_question` (`Id_Question`) VALUES
(0000000019),
(0000000020),
(0000000018),
(0000000017);

INSERT INTO `company` (`Email`, `Cf`, `Name`, `Venue`,`Password` ) VALUES
('alge@gmail.com' ,'ALG01GRE01', 'Alge snc', 'Tokyo', 'alge1'),
('trilogy@gmail.com' ,'TRG02BDE02', 'Trilogy spa', 'Budapest', 'trilogy1');

INSERT INTO `composition` (`Id_Survey`, `Id_Question`) VALUES
(0000000002, 0000000016),
(0000000002, 0000000019),
(0000000005, 0000000017),
(0000000005, 0000000020),
(0000000004, 0000000018);

INSERT INTO `domain` (`Keyword`, `Description`) VALUES
('Athletics', 'It contains surveys about users` preferences and habits about athletics topics'),
('Politics', 'It contains surveys about users` preferences and habits about politic topics'),
('Technology',  'It contains surveys about users` preferences and habits about technology topics'),
('Cooking',  'It contains surveys about users` preferences and habits about cooking topics');

INSERT INTO `interest` (`Email_User`, `Keyword_Domain`) VALUES
('chiara.caso@gmail.com', 'Cooking'),
('silvia.rossi@gmail.com', 'Cooking'),
('silvia.rossi@gmail.com', 'Politics'),
('laura.specchiulli@gmail.com', 'Politics'),
('fra.bondi@gmail.com', 'Technology'),
('fra.bondi@gmail.com', 'Politics'),
('giulia.piva@gmail.com', 'Technology'),
('giulia.piva@gmail.com', 'Athletics'),
('greta.eva@gmail.com', 'Technology'),
('chiara.caso@gmail.com', 'Athletics'),
('fra.bondi@gmail.com', 'Athletics'),
('laura.specchiulli@gmail.com', 'Cooking');

INSERT INTO `invitation` (`Id`, `Outcome`, `Email_User`, `Email_PremiumUser`, `Email_Company`, `Id_Survey`) VALUES
(0000000001, 'pending', 'laura.specchiulli@gmail.com' ,  'greta.eva@gmail.com', NULL, 0000000005),
(0000000002, 'pending', 'chiara.caso@gmail.com', 'greta.eva@gmail.com', NULL,  0000000005),
(0000000003, 'pending','fra.bondi@gmail.com', 'giulia.piva@gmail.com', NULL, 0000000004),
(0000000004, 'accepted', 'giulia.piva@gmail.com' , NULL, 'alge@gmail.com', 0000000001),
(0000000006, 'pending','giulia.piva@gmail.com', NULL, 'alge@gmail.com', 0000000002),
(0000000005, 'accepted', 'giulia.piva@gmail.com' , NULL, 'trilogy@gmail.com', 0000000003),
(0000000007, 'accepted','fra.bondi@gmail.com', NULL, 'alge@gmail.com', 0000000002),
(0000000008, 'refused','fra.bondi@gmail.com', NULL, 'alge@gmail.com', 0000000001),
(0000000009, 'accepted', 'chiara.caso@gmail.com' , NULL, 'alge@gmail.com', 0000000002);


INSERT INTO `open_answer` (`Text`, `Email_User`, `Id_OpenQuestion`) VALUES
('I root for the italian runner Marcell Jacobs because he already won the Tokyo Olympics in the 100m', 'fra.bondi@gmail.com', 0000000016),
('I root for the italian runner Marcell Jacobs because he already won the Tokyo Olympics in the 100m', 'giulia.piva@gmail.com', 0000000016);

INSERT INTO `open_question` (`Id_Question`, `MaxChar`) VALUES
(0000000020, 200),
(0000000021, 200),
(0000000016, 200);

INSERT INTO `options` (`Id_ClosedQuestion`, `Id`, `Text`) VALUES
(0000000018, 001, 'Yes'),
(0000000018, 002, 'No'),
(0000000018, 003, 'Maybe'),
(0000000020, 001, 'Yes'),
(0000000020, 002, 'No'),
(0000000020, 003, 'Proudly'),
(0000000019, 001, 'My family'),
(0000000019, 002, 'My friends'),
(0000000019, 003, 'No one'),
(0000000019, 004, 'The only one who loves me: my dog'),
(0000000017, 001, 'Sushi'),
(0000000017, 002, 'Pizza margherita'),
(0000000017, 003, 'Steak and fries');

INSERT INTO `premium` (`Costs`, `NSurveys`, `SubscriptionDate`, `ExpiringDate`, `Email_User`) VALUES
(50,  1, '2023-01-01', '2024-01-01', 'giulia.piva@gmail.com'),
(50,  1, '2022-10-15', '2023-10-15', 'greta.eva@gmail.com');

INSERT INTO `prize` (`Name`, `Description`, `Photo`, `Points`, `Email_AdminUser`) VALUES
('Top5', 'Congrats! You have entered the top 5 users of SurveyHub', '', 50, 'laura.specchiulli@gmail.com'),
('Top10', 'Congrats! You have entered the top 10 users of SurveyHub', '', 40, 'laura.specchiulli@gmail.com'),
('Top15', 'Congrats! You have entered the top 15 users of SurveyHub', '', 30, 'silvia.rossi@gmail.com'),
('Top20', 'Congrats! You have entered the top 20 users of SurveyHub', '', 20, 'silvia.rossi@gmail.com'),
('Top30', 'Congrats! You have entered the top 30 users of SurveyHub', '', 10, 'silvia.rossi@gmail.com');

INSERT INTO `question` (`Id`, `Text`, `Photo`, `Score`, `Type`, `Email_Company`, `Email_PremiumUser`) VALUES
(0000000016, 'Who are you rooting for the win in the 100m Athletics in the Olympics?', '', 5,'Open', 'alge@gmail.com', NULL),
(0000000019, 'Who are you coming with to the olympic games?', '', 2,'Closed', 'alge@gmail.com', NULL),
(0000000017, 'What is your favourite dish to cook?', '', 2,'Closed', NULL, 'greta.eva@gmail.com'),
(0000000020, 'What is the worst dish you ever eaten?', '', 2,'Closed', NULL, 'greta.eva@gmail.com'),
(0000000018, 'Are you a leftist?', '', 2,'Closed', NULL, 'giulia.piva@gmail.com'),
(0000000021, 'How many times have you used your voting card?', '', 5,'Open', NULL, 'giulia.piva@gmail.com');

INSERT INTO `survey` (`Id`, `Title`, `State`, `CreationDate`, `ClosingDate`, `Keyword_Domain`, `Email_Company`, `MaxUser`, `Email_PremiumUser`) VALUES
(0000000001, 'FAVOURITE DISCIPLINE', 'open', '2022-12-16', '2023-10-12', 'Athletics','alge@gmail.com' , 10, DEFAULT),
(0000000002,'FORECAST 2024 OLYMPIC GAMES', 'open', '2022-12-17', '2023-10-17', 'Athletics', 'alge@gmail.com', 10, DEFAULT),
(0000000003, 'FAVOURITE OPERATING SYSTEM', 'closed', '2022-12-16', '2023-08-12', 'Technology', 'trilogy@gmail.com', 10, DEFAULT),
(0000000004, 'POLITICAL PREFERENCES', 'open', '2022-12-16', '2023-10-12', 'Politics', DEFAULT, 10, 'giulia.piva@gmail.com'),
(0000000005, 'FAVOURITE MEAL', 'open', '2022-12-16', '2023-12-12', 'Cooking', DEFAULT, 10, 'greta.eva@gmail.com');

INSERT INTO `user` (`Email`, `Name`, `Surname`, `BirthYear`, `BirthPlace`, `BonusTot`, `Password`,  `UserType`) VALUES
('chiara.caso@gmail.com', 'Chiara', 'Caso', 2001, 'Bologna', 19.5, 'Lokki', 3),
('giulia.piva@gmail.com', 'Giulia', 'Piva', 2004, 'Firenze', 9.5, 'Pivuz', 2),
('silvia.rossi@gmail.com', 'Silvia', 'Rossi', 1996, 'Napoli', 32, 'Sissi', 1),
('laura.specchiulli@gmail.com', 'Laura', 'Specchiulli', 2002, 'Porretta', 12.5, 'Spek', 1),
('fra.bondi@gmail.com', 'Francesco', 'Bondi', 2000, 'Salerno', 49.5, 'Frafra', 3),
('greta.eva@gmail.com', 'Greta', 'Eva', 1980, 'Reggio Emilia', 15, 'Eva1', 2);

INSERT INTO `admin` (`Email_User`) VALUES
('silvia.rossi@gmail.com'),
('laura.specchiulli@gmail.com');

INSERT INTO `receive` (`Email_User`, `Name_Prize`) VALUES
('silvia.rossi@gmail.com', 'top30'),
('silvia.rossi@gmail.com', 'top20'),
('silvia.rossi@gmail.com', 'top15'),
('laura.specchiulli@gmail.com', 'top30'),
('chiara.caso@gmail.com', 'top30'),
('fra.bondi@gmail.com', 'top30'),
('fra.bondi@gmail.com', 'top20'),
('fra.bondi@gmail.com', 'top15'),
('fra.bondi@gmail.com', 'top10'),
('greta.eva@gmail.com', 'top30');


-- Constraints

ALTER TABLE `admin`
  ADD PRIMARY KEY (`Email_User`);


ALTER TABLE `closed_answer`
  ADD PRIMARY KEY (`Email_User`,`Id_Options`,`Id_OptionClosedQuestion`),
  ADD KEY `Id_OptionClosedQuestion` (`Id_OptionClosedQuestion`);


ALTER TABLE `closed_question`
  ADD PRIMARY KEY (`Id_Question`);


ALTER TABLE `company`
  ADD PRIMARY KEY (`Email`);


ALTER TABLE `composition`
  ADD PRIMARY KEY (`Id_Survey`,`Id_Question`),
  ADD KEY `Id_Question` (`Id_Question`);


ALTER TABLE `domain`
  ADD PRIMARY KEY (`Keyword`);


ALTER TABLE `interest`
  ADD PRIMARY KEY (`Keyword_Domain`,`Email_User`),
  ADD KEY `Email_User` (`Email_User`);


ALTER TABLE `invitation`
  ADD KEY `Email_Company` (`Email_Company`),
  ADD KEY `Email_PremiumUser` (`Email_PremiumUser`),
  ADD KEY `Email_User` (`Email_User`),
  ADD KEY `Id_Survey` (`Id_Survey`);


ALTER TABLE `open_answer`
  ADD PRIMARY KEY (`Email_User`,`Id_OpenQuestion`),
  ADD KEY `Id_OpenQuestion` (`Id_OpenQuestion`);


ALTER TABLE `open_question`
  ADD PRIMARY KEY (`Id_Question`);


ALTER TABLE `options`
  ADD PRIMARY KEY (`Id_ClosedQuestion`,`Id`);


ALTER TABLE `premium`
  ADD PRIMARY KEY (`Email_User`);


ALTER TABLE `prize`
  ADD PRIMARY KEY (`Name`),
  ADD KEY `Email_AdminUser` (`Email_AdminUser`);


ALTER TABLE `question`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Email_Company` (`Email_Company`),
  ADD KEY `Email_PremiumUser` (`Email_PremiumUser`);


ALTER TABLE `receive`
  ADD PRIMARY KEY (`Email_User`,`Name_Prize`),
  ADD KEY `Name_Prize` (`Name_Prize`);


ALTER TABLE `survey`
  ADD KEY `Email_Company` (`Email_Company`),
  ADD KEY `Email_PremiumUser` (`Email_PremiumUser`),
  ADD KEY `Keyword_Domain` (`Keyword_Domain`);


ALTER TABLE `user`
  ADD PRIMARY KEY (`Email`);


ALTER TABLE `question`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`Email_User`) REFERENCES `user` (`Email`) ON UPDATE cascade ON DELETE cascade;


ALTER TABLE `closed_answer`
  ADD CONSTRAINT `closed_answer_ibfk_1` FOREIGN KEY (`Email_User`) REFERENCES `user` (`Email`) ON UPDATE cascade ON DELETE cascade,
  ADD CONSTRAINT `closed_answer_ibfk_2` FOREIGN KEY (`Id_OptionClosedQuestion`) REFERENCES `options` (`Id_ClosedQuestion`) ON UPDATE cascade ON DELETE cascade;


ALTER TABLE `closed_question`
  ADD CONSTRAINT `closed_question_ibfk_1` FOREIGN KEY (`Id_Question`) REFERENCES `question` (`Id`) ON UPDATE cascade ON DELETE cascade;


ALTER TABLE `composition`
  ADD CONSTRAINT `composition_ibfk_1` FOREIGN KEY (`Id_Survey`) REFERENCES `survey` (`Id`) ON UPDATE cascade ON DELETE cascade,
  ADD CONSTRAINT `composition_ibfk_2` FOREIGN KEY (`Id_Question`) REFERENCES `question` (`Id`) ON UPDATE cascade ON DELETE cascade;


ALTER TABLE `interest`
  ADD CONSTRAINT `interest_ibfk_1` FOREIGN KEY (`Email_User`) REFERENCES `user` (`Email`) ON UPDATE cascade ON DELETE cascade,
  ADD CONSTRAINT `interest_ibfk_2` FOREIGN KEY (`Keyword_Domain`) REFERENCES `domain` (`Keyword`) ON UPDATE cascade ON DELETE cascade;


ALTER TABLE `invitation`
  ADD CONSTRAINT `invitation_ibfk_1` FOREIGN KEY (`Email_Company`) REFERENCES `company` (`Email`) ON UPDATE cascade ON DELETE cascade,
  ADD CONSTRAINT `invitation_ibfk_2` FOREIGN KEY (`Email_PremiumUser`) REFERENCES `premium` (`Email_User`) ON UPDATE cascade ON DELETE cascade,
  ADD CONSTRAINT `invitation_ibfk_3` FOREIGN KEY (`Email_User`) REFERENCES `user` (`Email`) ON UPDATE cascade ON DELETE cascade,
  ADD CONSTRAINT `invitation_ibfk_4` FOREIGN KEY (`Id_Survey`) REFERENCES `survey` (`Id`) ON UPDATE cascade ON DELETE cascade;


ALTER TABLE `open_answer`
  ADD CONSTRAINT `open_answer_ibfk_1` FOREIGN KEY (`Email_User`) REFERENCES `user` (`Email`) ON UPDATE cascade ON DELETE cascade,
  ADD CONSTRAINT `open_answer_ibfk_2` FOREIGN KEY (`Id_OpenQuestion`) REFERENCES `open_question` (`Id_Question`) ON UPDATE cascade ON DELETE cascade;


ALTER TABLE `open_question`
  ADD CONSTRAINT `open_question_ibfk_1` FOREIGN KEY (`Id_Question`) REFERENCES `question` (`Id`) ON UPDATE cascade ON DELETE cascade;


ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`Id_ClosedQuestion`) REFERENCES `closed_question` (`Id_Question`) ON UPDATE cascade ON DELETE cascade;


ALTER TABLE `premium`
  ADD CONSTRAINT `premium_ibfk_1` FOREIGN KEY (`Email_User`) REFERENCES `user` (`Email`) ON UPDATE cascade ON DELETE cascade;


ALTER TABLE `prize`
  ADD CONSTRAINT `prize_ibfk_1` FOREIGN KEY (`Email_AdminUser`) REFERENCES `admin` (`Email_User`) ON UPDATE cascade ON DELETE cascade;


ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`Email_Company`) REFERENCES `company` (`Email`) ON UPDATE cascade ON DELETE cascade,
  ADD CONSTRAINT `question_ibfk_2` FOREIGN KEY (`Email_PremiumUser`) REFERENCES `premium` (`Email_User`) ON UPDATE cascade ON DELETE cascade;


ALTER TABLE `receive`
  ADD CONSTRAINT `receive_ibfk_1` FOREIGN KEY (`Email_User`) REFERENCES `user` (`Email`) ON UPDATE cascade ON DELETE cascade,
  ADD CONSTRAINT `receive_ibfk_2` FOREIGN KEY (`Name_Prize`) REFERENCES `prize` (`Name`) ON UPDATE cascade ON DELETE cascade;


ALTER TABLE `survey`
  ADD CONSTRAINT `survey_ibfk_1` FOREIGN KEY (`Email_Company`) REFERENCES `company` (`Email`) ON UPDATE cascade ON DELETE cascade,
  ADD CONSTRAINT `survey_ibfk_2` FOREIGN KEY (`Email_PremiumUser`) REFERENCES `premium` (`Email_User`) ON UPDATE cascade ON DELETE cascade,
  ADD CONSTRAINT `survey_ibfk_3` FOREIGN KEY (`Keyword_Domain`) REFERENCES `domain` (`Keyword`) ON UPDATE cascade ON DELETE cascade;
  
  
#PROCEDURES
  
/*procedure for inserting a company into the database */
  
DELIMITER $$
CREATE PROCEDURE RegistrationCompany(IN EmailCompany VARCHAR(50), NameCompany VARCHAR(50), VenueCompany VARCHAR(30), CfCompany VARCHAR(11), PasswordCompany VARCHAR(50))
BEGIN
  INSERT INTO company(Email, Cf, Name, Venue, Password) VALUES (EmailCompany, CfCompany, NameCompany, VenueCompany, PasswordCompany);
END $$
DELIMITER ;

/*procedure for inserting an user into the database, if the user's an admin or a premium it will
  automatically be inserted also in the premium or admin sections as well */

DELIMITER $$
CREATE PROCEDURE RegistrationUser(IN EmailUser varchar(50), NameUser varchar(30), SurnameUser varchar(30), BirthYearUser int, BirthPlaceUser varchar(30), PasswordUser varchar(50), UserType integer)
BEGIN
  INSERT INTO user VALUES (EmailUser, NameUser, SurnameUser, BirthYearUser, BirthPlaceUser, 0, PasswordUser, UserType );#settiamo di default totBonus=0
  IF(UserType = 1) THEN
		INSERT INTO admin VALUES(EmailUser);
  ELSEIF (UserType = 2) THEN
		INSERT INTO premium(Email_User, SubscriptionDate, ExpiringDate) VALUES(EmailUser, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 12 MONTH));
  END IF;
END $$
DELIMITER ;

/*procedure for viewing the open surveys to which a selected user is participating */

DELIMITER $$
CREATE PROCEDURE ViewActiveSurveys(IN EmailUser VARCHAR(50))
BEGIN
SELECT * 
FROM invitation AS i, survey AS s
WHERE i.Email_User=EmailUser AND i.Id_Survey=s.Id AND i.Outcome='accepted' AND s.State='open';
END $$
DELIMITER ;

/*procedure for viewing the expired survey to which a selected user has participated to */

DELIMITER $$
CREATE PROCEDURE ViewExpiredSurveys(IN EmailUser VARCHAR(50))
BEGIN
SELECT * 
FROM invitation AS i, survey AS s
WHERE i.Email_User=EmailUser AND i.Id_Survey=s.Id AND i.Outcome='accepted' AND s.State='closed';
END $$
DELIMITER ;

/*procedure for viewing the prizes received by a selected user */

DELIMITER $$
CREATE PROCEDURE ViewReceivedPrizes(IN EmailUser varchar(50))
BEGIN
	SELECT *
    FROM receive JOIN prize ON receive.Name_Prize = prize.Name
    WHERE (receive.Name_Prize = Prize.Name) and (Receive.Email_User = EmailUser);
END$$
DELIMITER ;

/*procedure for viewing the surveys crrated by a selected premium user */

DELIMITER $$
CREATE PROCEDURE ViewPremiumCreatedSurveys(IN EmailPremium VARCHAR(50))
BEGIN
	SELECT * 
	FROM survey 
	WHERE Email_PremiumUser = EmailPremium;
END $$
DELIMITER ;

/*procedure for viewing the surveys created by a selected company */

DELIMITER $$
CREATE PROCEDURE ViewCompanyCreatedSurveys(IN EmailCompany VARCHAR(50))
BEGIN
	SELECT * 
	FROM survey 
	WHERE Email_Company = EmailCompany;
END $$
DELIMITER ;

/*procedure for viewing the users' ranking in terms of BonusTot  */


DELIMITER $$
CREATE PROCEDURE OrderBonusTot()
BEGIN 
	SELECT *
    FROM user
    ORDER BY BonusTot DESC;
END $$
DELIMITER ;

/*procedure for viewing all the available prizes in the database */


DELIMITER $$
CREATE PROCEDURE ViewPrizes()
BEGIN 
	SELECT *
    FROM prize
    ORDER BY Points DESC;
END $$
DELIMITER ;

/*procedure for inserting a prize in the database by an admin user */


DELIMITER $$
CREATE PROCEDURE InsertPrize(IN Name varchar(30), Description text, Photo blob, Points int, Email_AdminUser varchar(50))
BEGIN
	INSERT into prize values (Name, Description, Photo, Points, Email_AdminUser);
END $$
DElIMITER ;

/*procedure for viewing the invitation he received and to which he hasn't already given a response*/


DELIMITER $$
CREATE PROCEDURE ViewPendingInvitation(IN EmailUser VARCHAR(50))
BEGIN
	SELECT * 
    FROM survey as s, invitation as i 
    WHERE i.Id_Survey=s.id 
    AND Email_User = EmailUser
    AND Outcome = 'pending' 
    AND State = 'open';
END $$
DELIMITER ;

/*procedure for inserting a domain  */


DELIMITER $$
CREATE PROCEDURE InsertDomain(IN KeywordDomain VARCHAR(30), DescriptionDomain TEXT)
BEGIN
	INSERT into domain values(KeywordDomain, DescriptionDomain);
END $$
DELIMITER ;

/*procedure for creating a survey by a premium user */


DELIMITER $$
CREATE PROCEDURE CreatePremiumSurvey(IN  TitleSurvey VARCHAR(50), StateSurvey ENUM('open','closed'), MaxUserSurvey INT, CreationDateSurvey DATE, ClosingDateSurvey DATE, KeywordDomain VARCHAR(30), EmailPremium VARCHAR(30))
BEGIN
	INSERT INTO survey(Title, State, CreationDate, ClosingDate, Keyword_Domain, MaxUser, Email_PremiumUser)
    VALUES (TitleSurvey, StateSurvey, CreationDateSurvey, ClosingDateSurvey, KeywordDomain, MaxUserSurvey, EmailPremium);
END $$
DELIMITER ;

/*procedure for creating a survey by a company */


DELIMITER $$
CREATE PROCEDURE CreateCompanySurvey(IN  TitleSurvey VARCHAR(50), StateSurvey ENUM('open','closed'), MaxUserSurvey INT, CreationDateSurvey DATE, ClosingDateSurvey DATE, KeywordDomain VARCHAR(30), EmailCompany VARCHAR(30))
BEGIN
	INSERT INTO survey(Title, State, CreationDate, ClosingDate, Keyword_Domain, MaxUser, Email_Company)
    VALUES (TitleSurvey, StateSurvey, CreationDateSurvey, ClosingDateSurvey, KeywordDomain, MaxUserSurvey, EmailCompany);
END $$
DELIMITER ;

/*procedure for inserting an open question in the database by a company */


DELIMITER $$
CREATE PROCEDURE InsertCompanyOpenQuestion(IN TextQ TEXT, EmailCompany VARCHAR(50), PhotoQ BLOB, PointsQ FLOAT, MaxCharQuestion INTEGER)
BEGIN
INSERT INTO question(Text, Photo, Score, Type, Email_Company) values (TextQ, PhotoQ, PointsQ, 'open', EmailCompany);
INSERT INTO open_question(Id_Question, MaxChar) values (LAST_INSERT_ID(), MaxCharQuestion);
END $$
DELIMITER ;

/*procedure for inserting an open question in the database by a premium user */


DELIMITER $$
CREATE PROCEDURE InsertPremiumOpenQuestion(IN TextQ TEXT, EmailPremium VARCHAR(50), PhotoQ BLOB, PointsQ FLOAT, MaxCharQuestion INTEGER)
BEGIN
INSERT INTO question(Text, Photo, Score, Type, Email_PremiumUser) values (TextQ, PhotoQ, PointsQ, 'open', EmailPremium);
INSERT INTO open_question(Id_Question, MaxChar) values (LAST_INSERT_ID(), MaxCharQuestion);
END $$
DELIMITER ;

/*procedure for inserting a closed question in the database by a premium user*/


DELIMITER $$
CREATE PROCEDURE InsertPremiumClosedQuestion(IN TextQ TEXT, EmailPremium VARCHAR(50), PhotoQ BLOB, PointsQ FLOAT)
BEGIN
INSERT INTO question(Text, Photo, Score, Type, Email_PremiumUser) values (TextQ, PhotoQ, PointsQ, 'closed', EmailPremium);
INSERT INTO closed_question(Id_Question) values (LAST_INSERT_ID());
END $$
DELIMITER ;

/*procedure for inserting a closed question in the database by a company*/

DELIMITER $$
CREATE PROCEDURE InsertCompanyClosedQuestion(IN TextQ TEXT, EmailCompany VARCHAR(50), PhotoQ BLOB, PointsQ FLOAT)
BEGIN
INSERT INTO question(Text, Photo, Score, Type, Email_Company) values (TextQ, PhotoQ, PointsQ, 'closed', EmailCompany);
INSERT INTO closed_question(Id_Question) values (LAST_INSERT_ID());
END $$
DELIMITER ;

/*procedure for inserting an option in the database */

DELIMITER $$ 
CREATE PROCEDURE InsertOption(IN IdClosedQuestion INTEGER, IdOption INTEGER, TextOption TEXT)
BEGIN
	INSERT into options values (IdClosedQuestion, IdOption, TextOption);
END $$
DELIMITER ;

/*procedure for selecting the users a company can invite by selecting the ones that
  are interested in the domain to which the survey belongs and that haven't already received an invite */

DELIMITER $$
CREATE PROCEDURE CompanyInvitation(EmailCompany VARCHAR(50), IdSurvey INTEGER)
BEGIN
	SELECT * 
    FROM user
    WHERE Email IN (SELECT Email
		            FROM user JOIN interest ON user.Email = interest.Email_User
					WHERE Keyword_Domain = (SELECT Keyword_Domain 
											FROM survey
											WHERE Id = IdSurvey))
				AND Email NOT IN (SELECT Email 
								  FROM user JOIN invitation ON user.Email = invitation.Email_User
								  WHERE invitation.Id_survey = IdSurvey)
								  ORDER BY RAND();
END $$
DELIMITER ;



#TRIGGERS

/*trigger for changing the state of a survey to 'closed' once it's reached maximum amount of user participating */

DELIMITER $$
CREATE TRIGGER MaxUsersSurveyStateChange AFTER UPDATE ON invitation
FOR EACH ROW
BEGIN
	IF ( NEW.Outcome <> OLD.Outcome AND NEW.Outcome = "accepted" ) THEN 
		UPDATE survey SET State = 'closed' WHERE MaxUser = (
			SELECT count(*)
            FROM invitation
            WHERE (invitation.Id_Survey = survey.Id) AND (invitation.Outcome = 'accepted'));
	END IF;
END $$
DELIMITER ;


/*trigger for changing the state of a survey to 'closed' once it's the closing day according to the attribute 'ClosingDate'*/

DELIMITER $$
CREATE TRIGGER ClosingDateSurveyStateChange BEFORE UPDATE ON survey
FOR EACH ROW
BEGIN
	IF ( NEW.ClosingDate <= curdate()) THEN
		SET NEW.State = 'closed';
	END IF;
END $$
DELIMITER ;
    
/*trigger for incrementing the premium user's surveys number every time he creates one*/

DELIMITER $$
CREATE TRIGGER NSurveysIncrement AFTER INSERT ON survey
FOR EACH ROW
BEGIN
	UPDATE premium
    SET NSurveys = NSurveys + 1
    WHERE premium.Email_User = NEW.Email_PremiumUser;
END $$
DELIMITER ;

/*trigger for incrementing the total bonuses of an user every time he accepts an invitation to a survey */

DELIMITER $$
CREATE TRIGGER BonusTotIncrement AFTER UPDATE ON invitation
FOR EACH ROW
BEGIN
	IF ( NEW.Outcome = 'accepted' AND OLD.Outcome = 'refused') THEN
		UPDATE user
        SET BonusTot = BonusTot + 0.5
        WHERE Email = NEW.Email_User;
	END IF;
END $$
DELIMITER ;

/*trigger for assigning a prize to an user once he reached the minimum amoount of points necessary
  according to the attribute BonusTot */

DELIMITER $$
CREATE TRIGGER PrizeAssignment AFTER UPDATE ON User
FOR EACH ROW
BEGIN
	DECLARE done BOOLEAN DEFAULT FALSE;
	DECLARE PrizeNameLocal varchar(30);
	DECLARE PrizeCursor CURSOR FOR SELECT Name 
							   FROM Prize 
                               WHERE (Points<= NEW.BonusTot); /* declaring a cursor containing the names of the prizes that the user 
															     has received or should receive according to the amount of points he has*/
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE; /* exception handler which assigns the value true to the variable 'done' once 
														    the cursor can't find new lines to elaborate */
	OPEN PrizeCursor;
	WHILE NOT done DO
		FETCH PrizeCursor INTO PrizeNameLocal; /* for each */
		IF (PrizeNameLocal IS NOT NULL AND NOT done) THEN  /* if there is a value in PrizeNameLocal and the list of prizes is not finished... */
			IF NOT EXISTS (SELECT * 
						   FROM Receive
						   WHERE Name_Prize = PrizeNameLocal AND Email_User = NEW.Email) THEN /* checks if the user has already received the prize */
				INSERT INTO Receive(Email_User, Name_Prize) VALUES (NEW.Email, PrizeNameLocal);
			END IF;
		END IF;
	END WHILE;
	CLOSE PrizeCursor;
END $$
DELIMITER ;

/*trigger for assigning the points to an user once he answers an open question */

DELIMITER $$
CREATE TRIGGER OpenAnswerPointsAssignment AFTER INSERT ON open_answer
FOR EACH ROW
BEGIN
	UPDATE user
    SET BonusTot = BonusTot + (SELECT Score
							   FROM question JOIN open_answer ON question.Id = open_answer.Id_OpenQuestion
                               WHERE Email_User = NEW.Email_User AND Id_OpenQuestion = NEW.Id_OpenQuestion)
	WHERE user.Email = NEW.Email_User;
END $$
DELIMITER ;

/*trigger for assigning the points to an user once he answers a closed question */

DELIMITER $$
CREATE TRIGGER ClosedAnswerPointsAssignment AFTER INSERT ON closed_answer
FOR EACH ROW
BEGIN
	UPDATE user
	SET BonusTot = BonusTot + (SELECT Score
							   FROM question JOIN closed_answer ON question.Id = closed_answer.Id_OptionClosedQuestion
							   WHERE Email_User = NEW.Email_User AND Id_OptionClosedQuestion = NEW.Id_OptionClosedQuestion)
	WHERE user.Email = NEW.Email_User;
END $$
DELIMITER ;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
