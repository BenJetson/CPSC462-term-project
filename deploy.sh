#!/usr/bin/env bash

set -e

ROOT="/home/bfgodfr"
WEB_ROOT="$ROOT/public_html"

APP_NAME="demo"

SECRET_DIR="$ROOT/secrets"
DEPLOY_DIR="$WEB_ROOT/$APP_NAME"

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

# Ensure the deploy directory exists.
ssh webapp mkdir -p "$DEPLOY_DIR"
# Copy source files to the remote server.
rsync -e ssh -rvptd --delete-before ./src/ webapp:"$DEPLOY_DIR/"

# Ensure that the secret directory exists.
ssh webapp mkdir -p "$SECRET_DIR"
# Copy the secret file to the server.
rsync -e ssh -vpt .env webapp:"$SECRET_DIR/$APP_NAME.env"

# TODO: Clear the database.
# TODO: Apply the schema to the database.
# TODO: Load sample data to the database.
