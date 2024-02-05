FROM mysql:8.0


WORKDIR /var/www/html


COPY ./ /var/www/html

COPY sql.sql /docker-entrypoint-initdb.d/

ENV MYSQL_ROOT_PASSWORD=root
ENV MYSQL_DATABASE=cloud
ENV MYSQL_PASSWORD=root

EXPOSE 3306
