-- Equipos
INSERT INTO teams (name, city, coach) VALUES
('FC Barcelona', 'Barcelona', 'Hansi Flick'),
('Real Sociedad', 'San Sebastián', 'Sergio Francisco'),
('Real Madrid', 'Madrid', 'Xabi Alonso'),
('Atlético de Madrid', 'Madrid', 'Diego Simeone');

-- Barcelona
INSERT INTO team_stats (team_id, matches_played, wins, draws, losses, goals_for, goals_against, points)
VALUES
    (1, 4, 3, 1, 0, 13, 4, 10);

-- Atlético de Madrid
INSERT INTO team_stats (team_id, matches_played, wins, draws, losses, goals_for, goals_against, points)
VALUES
    (2, 4, 1, 2, 1, 5, 4, 5);

-- Real Sociedad
INSERT INTO team_stats (team_id, matches_played, wins, draws, losses, goals_for, goals_against, points)
VALUES
    (3, 4, 0, 2, 2, 4, 6, 2);

-- Real Madrid
INSERT INTO team_stats (team_id, matches_played, wins, draws, losses, goals_for, goals_against, points)
VALUES
    (4, 4, 4, 0, 0, 8, 2, 12);

-- Jugadores
INSERT INTO players (name, position, age, team_id) VALUES
('Marc-André ter Stegen','Portero',33,1),
('Wojciech Szczesny','Portero',35,1),
('Joan García','Portero',24,1),
('Alejandro Balde','Defensa',21,1),
('Ronald Araújo','Defensa',26,1),
('Pau Cubarsí','Defensa',18,1),
('Andreas Christensen','Defensa',29,1),
('Jules Koundé','Defensa',26,1),
('Eric García','Defensa',24,1),
('Gerard Martín','Defensa',23,1),
('Marc Casadó','Centrocampista',22,1),
('Pedri','Centrocampista',22,1),
('Fermín López','Centrocampista',22,1),
('Frenkie de Jong','Centrocampista',28,1),
('Gavi','Centrocampista',21,1),
('Dani Olmo','Centrocampista',26,1),
('Lamine Yamal','Delantero',18,1),
('Robert Lewandowski','Delantero',37,1),
('Ferran Torres','Delantero',25,1,),
('Raphinha','Delantero',28,1),
('Marcus Rashford','Delantero',27,1);

INSERT INTO players (name, position, age, team_id) VALUES
('Juan Agustín Musso','Portero',31,2),
('Jan Oblak','Portero',32,2),
('José María Giménez','Defensa',30,2),
('Clément Lenglet','Defensa',30,2),
('Nahuel Molina','Defensa',27,2),
('Dávid Hancko','Defensa',27,2),
('Marc Pubill','Defensa',22,2),
('Robin Le Normand','Defensa',28,2,
('Koke','Centrocampista',33,2),
('Pablo Barrios','Centrocampista',22,2),
('Álex Baena','Centrocampista',24,2),
('Thiago Almada','Centrocampista',24,2),
('Johnny Cardoso','Centrocampista',24,2),
('Giovanni Simeone','Delantero',28,2),  
('Antoine Griezmann','Delantero',34,2),
('Alexander Sørloth','Delantero',29,2);

INSERT INTO players (name, position, age, team_id) VALUES
('Álex Remiro', 'Portero', 30, 3),
('Unai Marrero', 'Portero', 24, 3),
('Aitor Fraga', 'Portero', 22, 3),
('Egoitz Arana', 'Defensa', 23, 3),
('Jon Martín', 'Defensa', 19, 3),
('Álvaro Odriozola', 'Defensa', 29, 3),
('Igor Zubeldia', 'Defensa', 28, 3),
('Jon Gorrotxategi', 'Centrocampista', 23, 3),
('Beñat Turrientes', 'Centrocampista', 23, 3),
('Yangel Herrera', 'Centrocampista', 27, 3),
('Carlos Soler', 'Centrocampista', 28, 3),
('Arsen Zakharyan', 'Centrocampista', 22, 3),
('Brais Méndez', 'Delantero', 28, 3),
('Umar Sadiq', 'Delantero', 28, 3),
('Takefusa Kubo', 'Delantero', 24, 3);

INSERT INTO players (name, position, age, team_id) VALUES
('Thibaut Courtois', 'Portero', 33, 4),
('Andriy Lunin', 'Portero', 26, 4),
('Dean Huijsen', 'Defensa', 20, 4),
('Raúl Asencio', 'Defensa', 22, 4),
('Dani Carvajal', 'Defensa', 33, 4),
('Éder Militão', 'Defensa', 27, 4),
('Antonio Rüdiger', 'Defensa', 31, 4),
('David Alaba', 'Defensa', 33, 4),
('Trent Alexander-Arnold', 'Defensa', 26, 4),
('Aurélien Tchouaméni', 'Centrocampista', 24, 4),
('Jude Bellingham', 'Centrocampista', 22, 4),
('Dani Ceballos', 'Centrocampista', 29, 4),
('Kylian Mbappé', 'Delantero', 26, 4),
('Vinícius Júnior', 'Delantero', 25, 4);

-- Partidos finalizados
INSERT INTO matches (home_team_name, away_team_name, match_date, stadium, competition, home_score, away_score) VALUES
('FC Barcelona', 'Real Sociedad', '2025-08-25', 'Spotify Camp Nou', 'La Liga', 3, 1),
('Sevilla', 'FC Barcelona', '2025-09-01', 'Ramón Sánchez-Pizjuán', 'La Liga', 0, 2),
('FC Barcelona', 'Atlético de Madrid', '2025-09-15', 'Spotify Camp Nou', 'La Liga', 1, 1),
('Real Madrid', 'FC Barcelona', '2025-09-29', 'Santiago Bernabéu', 'La Liga', 2, 2),
('Real Madrid', 'Getafe CF', '2025-08-20', 'Santiago Bernabéu', 'La Liga', 2, 0),
('Real Betis', 'Real Madrid', '2025-08-27', 'Benito Villamarín', 'La Liga', 1, 3),
('Real Madrid', 'Valencia CF', '2025-09-10', 'Santiago Bernabéu', 'La Liga', 4, 1),
('Real Madrid', 'FC Barcelona', '2025-09-29', 'Santiago Bernabéu', 'La Liga', 2, 2),
('Valencia CF', 'Real Sociedad', '2025-09-22', 'Mestalla', 'La Liga', 0, 1),
('Atlético de Madrid', 'Villarreal CF', '2025-09-25', 'Cívitas Metropolitano', 'La Liga', 3, 0);

-- Partidos por Jugar
INSERT INTO matches (home_team_name, away_team_name, match_date, stadium, competition) VALUES
('FC Barcelona', 'Villarreal CF', '2025-10-05', 'Spotify Camp Nou', 'La Liga'),
('Valencia CF', 'FC Barcelona', '2025-10-19', 'Mestalla', 'La Liga'),
('FC Barcelona', 'Athletic Club', '2025-10-26', 'Spotify Camp Nou', 'La Liga'),
('Granada CF', 'FC Barcelona', '2025-11-02', 'Nuevo Los Cármenes', 'La Liga')
('Athletic Club', 'Real Madrid', '2025-10-06', 'San Mamés', 'La Liga'),
('Real Madrid', 'Sevilla', '2025-10-20', 'Santiago Bernabéu', 'La Liga'),
('Atlético de Madrid', 'Real Sociedad', '2025-10-12', 'Cívitas Metropolitano', 'La Liga'),
('Real Sociedad', 'Getafe CF', '2025-10-26', 'Reale Arena', 'La Liga'),
('Athletic Club', 'Atlético de Madrid', '2025-10-26', 'San Mamés', 'La Liga');

-- Admin Prueba
INSERT INTO admin (usuario, contrasena)
VALUES ('admin', SHA2('miContrasena123', 256));