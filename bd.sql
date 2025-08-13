create table usuario(
id int auto_increment primary key,
nombre varchar(100) not null,
celular varchar(20)
);

create table producto(
id int auto_increment primary key,
nombre varchar(100) not null,
precio decimal(10,2) not NULL,
descripcion TEXT
);

create table pedido(
id int auto_increment primary key,
id_usuario int not null,
metodo_pago varchar(50),
estado_pago enum('pendiente','pagado','cancelado')default 'pendiente',
costo_envio decimal(10,2) default 0.00,
total decimal(10,2) not null,
fecha_envio date,
lugar varchar(200) not null,
descripcion text,
recibe varchar(100) not null,
foreign key (id_usuario) references usuario(id)
);

create table detalle(
id int auto_increment primary key,
id_pedido int not null,
id_producto int null,
cantidad int default 1,
foreign key(id_pedido) references pedido(id),
foreign key(id_producto) references producto(id)
);

create table repartidor(
id int auto_increment primary key,
id_pedido int not null,
nombre varchar(100) not null,
celular varchar(20),
fecha date,
foreign key (id_pedido) references pedido(id)
);

insert into usuario(nombre, celular) values ('valeria ibarra','6181801434'), ('jatziri','6182037721');
INSERT INTO producto (nombre, precio, descripcion) VALUES
('Pastel de Chocolate', 250.00, 'Delicioso pastel de chocolate con betún'),
('Caja de Galletas', 100.00, 'Galletas variadas en caja decorativa'),
('Refresco 2L', 50.00, 'Refresco de cola 2 litros');

INSERT INTO pedido (id_usuario, metodo_pago, estado_pago, costo_envio, total, fecha_envio, lugar, descripcion, recibe)
VALUES (1, 'tarjeta', 'pagado', 50.00, 550.00, '2025-07-05', 'Calle Ficticia 123', 'Pedido de cumpleaños', 'Ana Martínez');

INSERT INTO repartidor (id_pedido, nombre, celular, fecha)
VALUES (1, 'Carlos López', '5551122334', '2025-07-05');

select * from usuarios;