. .env; docker-compose exec webserver_mysql mysql -u root -p$MYSQL_ROOT_PASSWORD -e "USE dbctf; delete from flags;"
