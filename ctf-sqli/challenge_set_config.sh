#
#
# Update config files based on .env variables
#
. .env

cp mysql/init_db_mccoy.sql.ori  mysql/init_db_mccoy.sql
sed -i -e "s/READUSER_PASSWD/$READUSER_PASSWD/g" mysql/init_db_mccoy.sql

