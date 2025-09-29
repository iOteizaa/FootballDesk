CREATE TABLE teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    city VARCHAR(100),
    coach VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE team_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_id INT NOT NULL,
    matches_played INT DEFAULT 0,
    wins INT DEFAULT 0,
    draws INT DEFAULT 0,
    losses INT DEFAULT 0,
    goals_for INT DEFAULT 0,
    goals_against INT DEFAULT 0,
    points INT DEFAULT 0,
    last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE
);

CREATE TABLE players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    position VARCHAR(50),
    age INT,
    team_id INT,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE
);

CREATE TABLE matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    home_team INT NOT NULL,
    away_team INT NOT NULL,
    match_date DATE NOT NULL,
    result VARCHAR(20),
    FOREIGN KEY (home_team) REFERENCES teams(id),
    FOREIGN KEY (away_team) REFERENCES teams(id)
);

CREATE TABLE admin (
  id INT PRIMARY KEY AUTO_INCREMENT,
  usuario VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL
);