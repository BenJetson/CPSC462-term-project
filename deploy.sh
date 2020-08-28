#!/usr/bin/env bash

set -e

ROOT="/home/bfgodfr"
WEB_ROOT="$ROOT/public_html"

APP_NAME="demo"

SECRET_DIR="$ROOT/secrets"
DEPLOY_DIR="$WEB_ROOT/$APP_NAME"

URL="https://webapp.cs.clemson.edu/~bfgodfr/$APP_NAME"

echo "😁 Starting deploy!"

exit_trap() {
    retval=$?

    echo
    if [ $retval -ne 0 ]; then
        echo "⛔️ HALT: received exit code $retval. Deploy aborted."
    else
        echo "🎉 SUCCESS: Deploy finished."
        echo "🌎 View live at $URL."
    fi

    exit $retval
}
trap exit_trap EXIT

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

echo "🧼 Clearing the database...     🙅‍ not yet implemented."
# TODO: Clear the database.
echo "📀 Deploying database schema... 🙅‍ not yet implemented."
# TODO: Apply the schema to the database.
echo "🐍 Deploying sample data...     🙅‍ not yet implemented."
# TODO: Load sample data to the database.
