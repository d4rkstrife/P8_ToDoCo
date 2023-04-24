[![Codacy Badge](https://app.codacy.com/project/badge/Grade/f2a9c9fc8e8249eba96de3126e8ea311)](https://app.codacy.com/gh/d4rkstrife/P8_ToDoCo/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

P8_ToDoCo
Project installation Prerequisite :

Php : 8.1
Symfony : 6.2
Composer
Yarn
GIT installation :

GIT (https://git-scm.com/downloads) When GIT is installed, go in the folder of your choice and then execute this command:
- git clone https://github.com/d4rkstrife/P8_ToDoCo.git
  The project will be automatically copied in the folder.

In .env file, modifiate

- DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
  with your own database informations.


Then you have to install all dependencies the app needed to run: Run in this order:

- composer install
- yarn install
- yarn build
  To create the database and tables, run:

- symfony console doctrine:database:create
- symfony console doctrine:migrations:migrate
  
- You can generate fake datas with fixtures Run:

- symfony console doctrine:fixtures:load
  Then to launch the application, run

- yarn watch
- symfony serve

Some fakes users and fakes tasks are created by the fixtures. You can use them or create a new user.