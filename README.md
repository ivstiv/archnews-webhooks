# About
After looking for an easy integration with Discord the only viable option that I found was IFTTT service.
So, I figured this would be a fun project to play with and make keeping track of Arch News easier with no setup at all. 
Just paste your webhook and that's it! 

# Setup 
If you want to run this on your own here is what you will need:

- PHP 7.2.5 with the following extensions: openssl, PDO, mbstring, tokenizer, bcmath, xml, curl, and fpm if you are planning to use NGINX.
- Git
- Composer
- MySQL 5.7 or MariaDB 10.1.3 or higher
- A webserver (Apache, NGINX, etc.)


1.Clone the repository 

`git clone https://github.com/Ivstiv/archnews-webhooks.git && cd archnews-webhooks`

2.Install the dependencies 

`composer install --optimize-autoloader --no-dev`

3.Configure your .env file

`cp .env.example .env`

4.Edit the following entries (You need to have a database and user already!)

```
    APP_ENV=production
    APP_DEBUG=false
    APP_URL
    DB_HOST
    DB_DATABASE
    DB_USERNAME
    DB_PASSWORD
```

5.Generate a key (run this only if you are installing it for first time)

`php artisan key:generate --force`

6.Migrate the database

`php artisan migrate --seed`

7.Set permissions

```
    chmod -R 755 storage/* bootstrap/cache/
    
    # If using NGINX or Apache (not on CentOS):
    chown -R www-data:www-data * 
    
    # If using NGINX on CentOS:
    chown -R nginx:nginx *
    
    # If using Apache on CentOS
    chown -R apache:apache *
```

8.Cache some of the files to load faster

```
php artisan config:cache
php artisan view:cache
```

9.Crontab configuration (`sudo crontab -e`). Just subtitute <PROJECT_DIR> accordingly.

`* * * * * php <PROJECT_DIR>/artisan schedule:run >> /dev/null 2>&1`

# Contact and contribution
If you have issues, ideas or want to contribute you can [join my discord server](https://discord.gg/VMSDGVD) to have a chat.
The design of the database and the model relationships make it easy to integrate more RSS Feeds so if you want me to add another
one just send me a message. 
