# Breathe (Stress Management Application)

![alt text](http://breathe.geekycs.com/img/BreatheGithub.png)

**💻 DEMO**  
http://breathe.geekycs.com
  
**🕺🏻 Admin Credentials**  
Email: admin@breathe.com  
Password: 123456


## Installation

1. Install Laravel by following the instructions
https://laravel.com/docs/5.7/installation#installation

2. Clone the project and install vendor files
```
git clone https://github.com/choonkhiing/breathe_app.git

Enter project folder directory 
cd /projects/breathe_app

Install Laravel vendor packages using Composer
composer install
```

3. Create .env file with contents from .env.example 

4. Update database configurations in .env file (database username, database password, database table)

5. Generate application key and run Laravel on your localhost 
```
php artisan key:generate
```

6. Migrate database and seed the database
```
php artisan migrate
php artisan seed:db
```

7. Run project on your localhost
```
php artisan serve
```

