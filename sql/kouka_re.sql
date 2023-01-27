-- データベースの削除命令　shopDBが存在した場合削除
DROP DATABASE IF EXISTS kouka2249580re;

-- kouka2249580データベースの作成
CREATE DATABASE kouka2249580re DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

-- ユーザ（DBのユーザ）を作成
-- GRANT all ON kouka2249580re.* TO 'stuff'@'localhost' identified BY '';

-- kouka2249580_reデータベースを選択
USE kouka2249580re;

-- テーブルを作成
CREATE TABLE users (
    id int auto_increment primary key,
    name varchar(50) not null,
    email varchar(256) not null,
    login varchar(200) not null unique, -- uniqueあとづけ
    password varchar(100) not null,
    birthday varchar(20),
    sex varchar(10),
    regdate datetime not null
);

-- CREATE TABLE profile (
-- 	id int auto_increment primary key,
-- 	users_id int not null,
-- 	prof_img varchar(300),
-- 	prof_comment varchar(500),
-- 	foreign key(users_id) references users(id)
-- );

CREATE TABLE contact (
	id int auto_increment primary key,
	users_id int not null,
	class varchar(10) not null,
	contents varchar(800),
	contact_date datetime not null,
	foreign key(users_id) references users(id)
);

CREATE TABLE contact_guest (
	id int auto_increment primary key,
    email varchar(256) not null,
    age varchar(10),
    sex varchar(10),
	class varchar(10) not null,
	contents varchar(800),
	contact_date datetime not null
);

-- データを追加
INSERT INTO users VALUES(null, 'Sample', 'Sample00@XX.XX.jp', 'Sample00', '00Sample', null, null, '2022/07/22/ 15:30:00');
INSERT INTO users VALUES(null, 'Login', 'Login01@XX.XX.jp', 'Login01', '01Login', null, null, '2022/07/22/ 15:30:01');
