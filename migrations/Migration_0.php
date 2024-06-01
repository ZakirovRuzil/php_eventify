<?php

declare(strict_types=1);

namespace app\migrations;

use app\core\Migration;

class Migration_0 extends Migration
{
    function getVersion(): int
    {
        return 0;
    }

    function up(): void
    {
        $this->database->getPdo()->query("CREATE TABLE if not exists users (
            id serial primary key,
            first_name varchar(50),
            second_name varchar(50),
            age int,
            email varchar(100) UNIQUE,
            phone VARCHAR(50),
            password VARCHAR(255)
        )");

        $this->database->getPdo()->query("CREATE TABLE if not exists events (
            id serial primary key,
            name varchar(50),
            short_description varchar(100),
            long_description text,
            place varchar(50),
            date DATE,
            time TIME,
            image VARCHAR(255),
            user_id INT,
            FOREIGN KEY (user_id) REFERENCES users (id)
        )");

        $this->database->getPdo()->query("CREATE TABLE if not exists feedbacks (
            id serial primary key,
            comment TEXT,
            rate int,
            user_id int,
            event_id int,
            FOREIGN KEY (user_id) REFERENCES users (id),
            FOREIGN KEY (event_id) REFERENCES events (id)
        )");

        $this->database->getPdo()->query("CREATE TABLE if not exists members (
            id serial primary key,
            user_id int,
            event_id int,
            FOREIGN KEY (user_id) REFERENCES users (id),
            FOREIGN KEY (event_id) REFERENCES events (id)
        )");

        parent::up();
    }

    function down(): void
    {
        $this->database->getPdo()->query("DROP TABLE IF EXISTS users; DROP TABLE IF EXISTS events; DROP TABLE IF EXISTS feedbacks; DROP TABLE IF EXISTS members");
    }
}
