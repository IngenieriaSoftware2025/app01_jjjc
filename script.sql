CREATE TABLE categorias (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE prioridades (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE productos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cantidad INT NOT NULL,
    categoria_id INT NOT NULL,
    prioridad_id INT NOT NULL,
    comprado SMALLINT DEFAULT 1,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (prioridad_id) REFERENCES prioridades(id)
);

INSERT INTO categorias (nombre) VALUES ('Alimentos');
INSERT INTO categorias (nombre) VALUES ('Higiene');
INSERT INTO categorias (nombre) VALUES ('Hogar');


INSERT INTO prioridades (nombre) VALUES ('Alta');
INSERT INTO prioridades (nombre) VALUES ('Media');
INSERT INTO prioridades (nombre) VALUES ('Baja');

SELECT * FROM productos