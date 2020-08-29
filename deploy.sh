#!/usr/bin/env bash

set -e

ROOT="/home/bfgodfr"
WEB_ROOT="$ROOT/public_html"

APP_NAME="demo"

SECRET_DIR="$ROOT/secrets"
DEPLOY_DIR="$WEB_ROOT/$APP_NAME"

URL="https://webapp.cs.clemson.edu/~bfgodfr/$APP_NAME"

echo; echo "😁 Starting deploy!"; echo

exit_trap() {
    retval=$?

    echo
    if [ $retval -ne 0 ]; then
        echo "⛔️ HALT: received exit code $retval. Deploy aborted."
    else
        echo "🎉 SUCCESS: Deploy finished."
        echo "🌎 View live at $URL."
    fi
    echo

    exit $retval
}
trap exit_trap EXIT

if [ ! -f .env ]; then
    echo "🛑 FATAL: Environment not sane - missing configuration file!"
    echo "ℹ️  Must copy .env.example to .env and enter deployment parameters."
    exit 1
fi

# First fix the disk permissions on the local copies. rsync will copy the
# permission bits from the local machine, so they need to be accurate.
./fix_permissions.sh

# From the rsync manpage:
#     -e, --rsh=COMMAND           specify the remote shell to use
#     -r, --recursive             recurse into directories
#     -v, --verbose               increase verbosity
#     -p, --perms                 preserve permissions
#     -t, --times                 preserve times
#     -d, --dirs                  transfer directories without recursing

echo "📝 Deploying application source..."
# Ensure the deploy directory exists with correct permissions.
ssh webapp mkdir -v -m 711 -p "$DEPLOY_DIR"
# Copy source files to the remote server.
rsync -e ssh -rvptd --delete-before ./src/ webapp:"$DEPLOY_DIR/"

echo "🔑 Deploying secrets..."
# Ensure that the secret directory exists.
ssh webapp mkdir -v -m 711 -p "$SECRET_DIR"
# Copy the secret file to the server.
rsync -e ssh -vpt .env webapp:"$SECRET_DIR/$APP_NAME.env"

# shellcheck disable=SC1091
source .env

rm -f .my.cnf
(umask 177; touch .my.cnf)

cat << EOF >> .my.cnf
# Warning: generated as part of deploy.sh. Will be overwritten!

[client]
host="$DB_ADDR"
user="$DB_USER"
password="$DB_PASS"
EOF

if [[ $* == *--no-db* ]]; then
    echo "ℹ️  Skipping database deployment (--no-db set)."
else
    echo "🧼 Clearing the database..."
    mysql --defaults-file=".my.cnf" -e "DROP DATABASE IF EXISTS $DB_NAME"
    mysql --defaults-file=".my.cnf" -e "CREATE DATABASE $DB_NAME"

    echo "database=\"$DB_NAME\"" >> .my.cnf
    chmod u-w .my.cnf

    echo "📀 Deploying database schema..."
    mysql --defaults-file=".my.cnf" < ./db/schema.sql

    echo "🐍 Deploying sample data..."
    mysql --defaults-file=".my.cnf" < ./db/sample_data.sql
fi
