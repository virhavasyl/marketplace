# Marketplace

### installation steps:
1. Clone the repository
2. composer install
3. composer dump-autoload
4. chmod 777 -R storage
5. chmod 777 -R bootstrap
6. chmod 777 -R vendor
7. php artisan cache:clear
8. php artisan config:clear
9. php artisan route:clear
10. cp .env.example .env
11. Set correct database settings in the .env file (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
12. Set base domain setting in the .env file (APP_BASE_DOMAIN).
13. php artisan key:generate
14. php artisan migrate
15. php artisan db:seed (Only one time running command)
16. npm install
17. npm run dev (to compile scss/js/fonts).

