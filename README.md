Introduction:
---
In this project I create my own framework base on PHP 8.0 (use no PHP framework and only use symfony component in my `composer.json`). I use docker to simplify set up, and  I imagine it is a sunny day :-).

System Design:
---
The system uses eventual consistency to populate api data in a separate storage(as it is for reporting purpose, immediate consistency is not an issue), then when user ask to query data, system use the storage in order to fetch a proper result. 

How to use:
---

I personally prefer docker, but the choice is yours, in order to use, you need to follow:

- run `./build.sh` 
- check if your docker containers are running (docker ps)
- run `docker-compose exec worker composer install`
- run query (which is at the end of this document) on db (migration and seeding are missing according to lack of time).
- if you use docker, mysql is running on `0.0.0.0:3308` 
- run `docker-compose exec worker php console supermetrics:sync:api` to populate data.
- goto `http://0.0.0.0:8080/posts` to see proper results.

> note: you should add the command to cron job to automatically update posts data

Final Consideration:
---
this is just a test and of course needs a thousand improvement and fixes, but it designed as simple as possible. Anymore complicated design with details we can talk about it: feel free to send Email: alirezarahmani@live.com

SQL
---
```sql
create database supermetrics;
create table supermetrics.posts
(
    id           varchar(250) not null,
    from_name    varchar(60)  null,
    from_id      varchar(60)  null,
    message      text         null,
    type         varchar(60)  null,
    created_time datetime     null,
    constraint posts_id_uindex
        unique (id)
);

alter table supermetrics.posts
    add primary key (id);
```
What are missing:
---
Of course without test is not completed but as they do not ask in the task description and lack of time I did not write any test.

Not Working?
---
feel free to send email, maybe something is missing alirezarahmani@live.com
