FROM postgres

ENV POSTGRES_USER=postgres
ENV POSTGRES_PASSWORD=postgres
ENV POSTGRES_DB=tollManager
ENV PGDATA=/data/postgres

COPY baseScript.sql /docker-entrypoint-initdb.d/

