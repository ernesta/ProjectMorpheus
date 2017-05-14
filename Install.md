0) chmod -R 777
1) composer install
2) create "cache" folder in bootstrap
3) run php artisan clear-compiled
4) run php artisan key:generate
5) create a new MySQL database
6) create .env file from the env.example file, and add
    - correct MySQL database name and login credentials
    - correct website/STEAM user name and login credentials
    - set STEAM_API_KEY ~ get it from: https://steamcommunity.com/dev
    - set MASHAPE_API_KEY ~ get it from: http://docs.mashape.com/api-keys
7) Run php artisan migrate:refresh --seed
8) Login with your username & password
9) Go to user profile (public/profile)
 - Run "update gaming data" -> this will collect data about the user
10) Run public/get/store/100 a couple of times -> this will collect game meta data


