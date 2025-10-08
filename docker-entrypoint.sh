#!/bin/bash
set -e

# Defaults (can be overridden with env vars)
: ${DB_ROOT_PASSWORD:=root}
: ${DB_NAME:=servicedesk}
: ${DB_USER:=root}
: ${DB_PASSWORD:=$DB_ROOT_PASSWORD}

# Initialize MariaDB data directory if empty
if [ ! -d "/var/lib/mysql/mysql" ]; then
  echo "Initializing MariaDB data directory..."
  mysqld --initialize-insecure --user=mysql --datadir=/var/lib/mysql || true
  service mysql start || true

  # Secure the installation: set root password and create database/user
  mysql --protocol=socket -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '${DB_ROOT_PASSWORD}'; FLUSH PRIVILEGES;"
  mysql --protocol=socket -e "CREATE DATABASE IF NOT EXISTS \\`${DB_NAME}\\` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
  if [ "${DB_USER}" != "root" ]; then
    mysql --protocol=socket -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'%' IDENTIFIED BY '${DB_PASSWORD}'; GRANT ALL PRIVILEGES ON \\`${DB_NAME}\\`.* TO '${DB_USER}'@'%'; FLUSH PRIVILEGES;"
  else
    mysql --protocol=socket -e "GRANT ALL PRIVILEGES ON \\`${DB_NAME}\\`.* TO 'root'@'%' IDENTIFIED BY '${DB_ROOT_PASSWORD}'; FLUSH PRIVILEGES;"
  fi

  service mysql stop || true
  echo "MariaDB initialized."
fi

exec "$@"
