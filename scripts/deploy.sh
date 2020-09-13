#!/usr/bin/env bash

set -e

fatal_error() {
    echo "🛑 FATAL: $1"
    exit 1
}

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

# Check to make sure that TIER is set.
if [[ -z "${TIER}" ]]; then
    fatal_error "must set TIER."
elif [[ "${TIER}" == "local" ]]; then
    fatal_error "must use docker to deploy to local tier."
elif [[ ":dev:prod:" != *:$TIER:* ]]; then
    fatal_error "tier must be either dev or prod."
fi

# Ensure the script always starts at the repository root.
# Source: https://code-maven.com/bash-shell-relative-path
cd "$(dirname "$(dirname "$(realpath "$0")")")"

ROOT="/home/bfgodfr"
WEB_ROOT="$ROOT/public_html"

APP_NAME="project"
if [[ "$TIER" != "prod" ]]; then
    APP_NAME="$APP_NAME-$TIER"
fi

APP_PATH="4620/$APP_NAME"

DEPLOY_DIR="$WEB_ROOT/$APP_PATH"
SECRET_DIR="$ROOT/secrets/$APP_PATH"

URL="https://webapp.cs.clemson.edu/~bfgodfr/$APP_PATH"

echo; echo "😁 Starting deploy!"; echo

if [ ! -f .env ]; then
    echo "ℹ️  Should copy .env.example to .env.$TIER and enter parameters."
    fatal_error "Environment not sane - missing configuration file!"
fi

# First fix the disk permissions on the local copies. rsync will copy the
# permission bits from the local machine, so they need to be accurate.
./scripts/fix_permissions.sh

# From the rsync manpage:
#     -e, --rsh=COMMAND           specify the remote shell to use
#     -r, --recursive             recurse into directories
#     -v, --verbose               increase verbosity
#     -p, --perms                 preserve permissions
#     -t, --times                 preserve times
#     -d, --dirs                  transfer directories without recursing

echo "🔑 Deploying secrets..."
SECRET_FILE=".env.$TIER"

# shellcheck disable=SC1090
if [[ -f "$SECRET_FILE" ]]; then
    source "$SECRET_FILE"
    chmod 400 "$SECRET_FILE"
else
    fatal_error "secret file $SECRET_FILE does not exist."
fi

# Ensure that the secret directory exists.
ssh webapp mkdir -v -m 711 -p "$SECRET_DIR"
# Copy the secret file to the server.
rsync -e ssh -vpt "$SECRET_FILE" webapp:"$SECRET_DIR/.env"

(
umask 333; rm -f ./src/.htaccess; cat << EOF >> ./src/.htaccess
# Warning: generated as part of deploy.sh. Will be overwritten!

SetEnv SECRET_DIR "$SECRET_DIR"
EOF
)

echo "📝 Deploying application source..."
# Ensure the deploy directory exists with correct permissions.
ssh webapp mkdir -v -m 711 -p "$DEPLOY_DIR"
# Copy source files to the remote server.
rsync -e ssh -rvptd --delete-before ./src/ webapp:"$DEPLOY_DIR/"

rm -f ./src/.htaccess
rm -f .my.cnf
(umask 177; touch .my.cnf)

cat << EOF >> .my.cnf
# Warning: generated as part of deploy.sh. Will be overwritten!

[client]
host="$MYSQL_ADDR"
user="$MYSQL_USER"
password="$MYSQL_PASSWORD"
EOF

if [[ $* == *--no-db* ]]; then
    echo "ℹ️  Skipping database deployment (--no-db set)."
else
    echo "🧼 Clearing the database..."
    mysql --defaults-file=".my.cnf" -e \
        "DROP DATABASE IF EXISTS \`$MYSQL_DATABASE\`"
    mysql --defaults-file=".my.cnf" -e \
        "CREATE DATABASE \`$MYSQL_DATABASE\`"

    echo "database=\"$MYSQL_DATABASE\"" >> .my.cnf
    chmod u-w .my.cnf

    echo "📀 Deploying database schema..."
    mysql --defaults-file=".my.cnf" < ./db/schema.sql

    echo "🐍 Deploying sample data..."
    mysql --defaults-file=".my.cnf" < ./db/sample_data.sql
fi
