#
#
# Update config files based on .env variables
#
. .env


# ftp
cp ftp/vsftpd.conf.ori ftp/vsftpd.conf
sed -i -e "s/CTFNET_FTP_PASV_ADDRESS/$CTFNET_FTP_PASV_ADDRESS/g" ftp/vsftpd.conf


# smtp
cp smtp/main.cf.ori smtp/main.cf
sed -i -e "s|CTFNET_SMTP_MYNETWORK|$CTFNET_SMTP_MYNETWORK|g" smtp/main.cf

 

