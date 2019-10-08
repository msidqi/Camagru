FROM mattrayner/lamp:latest-1804


RUN apt-get -y update && apt-get -y install postfix mailutils
RUN sed -i 's/inet_interfaces = all/inet_interfaces = loopback-only/' /etc/postfix/main.cf
RUN echo 'service postfix restart' >> ~/.bashrc
# RUN sed -i 's/%/localhost/g' /create_mysql_users.sh
# COPY user.sql /.
# EXPOSE 80
# EXPOSE 443
# CMD mysql < /app/user.sql
# CMD service postfix restart