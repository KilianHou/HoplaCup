INSERT INTO `wp_clubs` (`Nom`, `Logo`, `Ville`, `Pays`, `Contact`)
VALUES ('Paris Waterpolo', '', 'Paris', 'FRANCE', ''),
       ('CN Marseille', '', 'Marseille', 'FRANCE', ''),
       ('Nice Waterpolo', '', 'Nice', 'FRANCE', ''),
       ('Montpellier Waterpolo', '', 'Montpellier', 'FRANCE', ''),
       ('Strasbourg Waterpolo', '', 'Strasbourg', 'FRANCE', ''),
       ('SG Neukölln Wasserball', '', 'Berlin', 'ALLEMAGNE', ''),
       ('SV Poseidon Hamburg', '', 'Hambourg', 'ALLEMAGNE', ''),
       ('Wasserballverein Bayern München', '', 'Munich', 'ALLEMAGNE', ''),
       ('SV Cannstatt', '', 'Stuttgart', 'ALLEMAGNE', ''),
       ('Waterpolo Club Zurich', '', 'Zurich', 'SUISSE', ''),
       ('Genève Natation 1885', '', 'Genève', 'SUISSE', ''),
       ('Lausanne Natation', '', 'Lausanne', 'SUISSE', '');

INSERT INTO `wp_divisions` (`Division`)
VALUES ('A'),
       ('B'),
       ('Juniors');

INSERT INTO wp_equipes (Nom, Clubs_id, Divisions_id)
VALUES ('Paris A', 1, 1),
       ('Paris B', 1, 2),
       ('Marseille A', 2, 1),
       ('Marseille B', 2, 2),
       ('Marseille Juniors', 2, 3),
       ('Nice A', 3, 1),
       ('Nice B', 3, 2),
       ('Montpellier A', 4, 1),
       ('Montpellier B', 4, 2),
       ('Strasbourg A', 5, 1),
       ('Strasbourg B', 5, 2),
       ('Berlin A', 6, 1),
       ('Berlin B', 6, 2),
       ('Hambourg A', 7, 1),
       ('Hambourg B', 7, 2),
       ('Munich A', 8, 1),
       ('Munich B', 8, 2),
       ('Stuttgart A', 9, 1),
       ('Zurich A', 10, 1),
       ('Zurich B', 10, 2),
       ('Genève A', 11, 1),
       ('Genève Juniors', 11, 3),
       ('Lausanne A', 12, 1),
       ('Lausanne B', 12, 2);