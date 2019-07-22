#
#
# Update config files based on .env variables
#
. .env

cp mysql/init_db_ctf.sql.ori  mysql/init_db_ctf.sql
sed -i -e "s/MYSQL_USER_PASSWORD/$MYSQL_USER_PASSWORD/g" mysql/init_db_ctf.sql
sed -i -e "s/CTF_ADMIN_ACCOUNT/$CTF_ADMIN_ACCOUNT/g" mysql/init_db_ctf.sql
sed -i -e "s/CTF_ADMIN_UID/$CTF_ADMIN_UID/g" mysql/init_db_ctf.sql
sed -i -e "s/CTF_ADMIN_PASSWORD/$CTF_ADMIN_PASSWORD/g" mysql/init_db_ctf.sql
