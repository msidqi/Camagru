FROM mattrayner/lamp:latest-1804


RUN apt-get -y update && apt-get -y install postfix mailutils
RUN sed -i 's/inet_interfaces = all/inet_interfaces = loopback-only/' /etc/postfix/main.cf
RUN sed -i 's/%/localhost/g' /create_mysql_users.sh
RUN service postfix restart
