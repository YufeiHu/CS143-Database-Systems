DROP TABLE IF EXISTS MovieActor;
DROP TABLE IF EXISTS Actor;
DROP TABLE IF EXISTS Movie;
DROP TABLE IF EXISTS MovieGenre;
DROP TABLE IF EXISTS Director;
DROP TABLE IF EXISTS Review;
DROP TABLE IF EXISTS MovieDirector;
DROP TABLE IF EXISTS MaxPersonID;
DROP TABLE IF EXISTS MaxMovieID;


CREATE TABLE Actor(
    id INT NOT NULL,
    last VARCHAR(20),
    first VARCHAR(20),
    sex VARCHAR(6),
    dob DATE NOT NULL,
    dod DATE,
    -- Primary key constraints: every actor has a unique id
    PRIMARY KEY (id),
    -- Check constraints: id in Actor table must be non-negative
    CHECK(id >= 0)
) ENGINE=INNODB;


CREATE TABLE Movie(
    id INT NOT NULL,
    title VARCHAR(100),
    year INT,
    rating VARCHAR(10),
    company VARCHAR(50),
    -- Primary key constraints: every movie has a unique id
    PRIMARY KEY (id),
    -- Check constraints: id in Movie table must be non-negative
    CHECK(id >= 0)
) ENGINE=INNODB;


CREATE TABLE MovieActor(
    mid INT,
    aid INT,
    role VARCHAR(50),
    PRIMARY KEY (mid, aid, role),
    -- Referential integrity constraints: mid in the MovieActor
    -- table must be associated with id in the Movie table
    FOREIGN KEY (mid) references Movie(id),
    -- Referential integrity constraints: aid in the MovieActor
    -- table must be associated with id in the Actor table
    FOREIGN KEY (aid) references Actor(id)
) ENGINE=INNODB;


CREATE TABLE MovieGenre(
    mid INT NOT NULL,
    genre VARCHAR(20),
    PRIMARY KEY (mid, genre),
    -- Referential integrity constraints: mid in the MovieGenre
    -- table must be associated with id in the Movie table
    FOREIGN KEY (mid) references Movie(id)
) ENGINE=INNODB;


CREATE TABLE Director(
    id INT NOT NULL,
    last VARCHAR(20),
    first VARCHAR(20),
    dob DATE NOT NULL,
    dod DATE,
    -- Primary key constraints: every director has a unique id
    PRIMARY KEY (id)
) ENGINE=INNODB;


CREATE TABLE Review(
    name VARCHAR(20) NOT NULL,
    time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    mid INT NOT NULL,
    rating INT NOT NULL,
    comment VARCHAR(500),
    -- Primary key constraints: every review has a unique
    -- set of name, time and movie id
    PRIMARY KEY (name, time, mid),
    -- Referential integrity constraints: mid in the Review
    -- table must be associated with id in the Movie table
    FOREIGN KEY (mid) references Movie(id),
    -- Check constraints: rating in Review table must stay
    -- between 0 and 5
    CHECK(rating >= 0 AND rating <= 5)
) ENGINE=INNODB;


CREATE TABLE MovieDirector(
    mid INT NOT NULL,
    did INT NOT NULL,
    PRIMARY KEY (mid, did),
    -- Referential integrity constraints: mid in the MovieDirector
    -- table must be associated with id in the Movie table
    FOREIGN KEY (mid) references Movie(id),
    -- Referential integrity constraints: did in the MovieDirector
    -- table must be associated with id in the Director table
    FOREIGN KEY (did) references Director(id)
) ENGINE=INNODB;


CREATE TABLE MaxPersonID(
    id INT NOT NULL,
    PRIMARY KEY (id)
) ENGINE=INNODB;


CREATE TABLE MaxMovieID(
    id INT NOT NULL,
    PRIMARY KEY (id)
) ENGINE=INNODB;
