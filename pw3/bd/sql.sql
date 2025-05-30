
drop database db_agenda;
create database db_agenda;
use db_Agenda;

create table contato(
 idprod int(4) not null primary key auto_increment,
 nome char(40) not null,
 endereco char(40) not null,
 telefone char(40) not null,
 email char(80) not null) Engine = InnoDB;
 
 
 insert into contato (nome, endereco, telefone, email ) values ("jose","rua2", "8888","jose@gmail" );
 insert into contato (nome, endereco, telefone, email ) values ("joao","rua 4", "555555","joao@gmail" );
 insert into contato (nome, endereco, telefone, email ) values ("maria","av 9", "222222","maria@gmail" );
 
 select * from contato;