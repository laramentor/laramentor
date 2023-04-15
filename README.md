## Laravel Mentorship Program

### Software Requirements

-   Docker

### Project Installation

1. Clone the repository to your local machine.

2. Navigate to the project directory

3. Create a .env file based off .env.example

4. Start docker engine

5. Build containers

    - docker-compose build

6. Bring up containers in detached mode

    - docker-compose up -d

7. SSH into the laramentor app container

    - docker exec -it -u ubuntu laramentor /bin/bash

8. Run the following commands

    - composer install
    - php artisan key:generate
    - php artisan migrate
    - php artisan db:seed
    - npm install
    - npm run dev

### Alternatively,

-   Setup "make" on your system
-   Clone the repository to your local machine.
-   Navigate to the project directory
-   Run "make" to setup project.

Visit the following urls to ensure everything is correctly setup:

    - **[Laramentor](http://localhost:9050)**

## Database management

To manage the database, use Adminer, which can be accessed at **[DB Management Interface](http://localhost:9053)**. Login with the following credentials:

-   System: MySQL
-   Server: db
-   Username: laramentor_user
-   Password: root
-   Database: laramentor_db
