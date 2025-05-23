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
    comprado SMALLINT DEFAULT 0,
    situacion CHAR(1),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    FOREIGN KEY (prioridad_id) REFERENCES prioridades(id)
);

