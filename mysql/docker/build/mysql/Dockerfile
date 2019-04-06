# docker/build/mysql/Dockerfile

FROM mysql:5.7

ENV MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD:-root}

# Copy all migrations
RUN mkdir /mysql
RUN mkdir /mysql/migrations
COPY migrations/*.sql /mysql/migrations/

# Concatenate all migrations into one init file
RUN cat /mysql/migrations/create_database.sql >> /mysql/init.sql
RUN cat /mysql/migrations/create_tables.sql >> /mysql/init.sql
RUN cat /mysql/migrations/create_keys_and_constraints.sql >> /mysql/init.sql
RUN cat /mysql/migrations/create_procedures.sql >> /mysql/init.sql
RUN cat /mysql/migrations/create_functions.sql >> /mysql/init.sql
RUN cat /mysql/migrations/create_triggers.sql >> /mysql/init.sql

# Check init file
RUN cat /mysql/init.sql

# Run init.sql
COPY setup.sh /mysql/setup.sh
RUN chmod +x /mysql/setup.sh
RUN /mysql/setup.sh

#ADD migrations/init.sql /docker-entrypoint-initdb.d

EXPOSE 3306

CMD ["service", "mysql", "start"]