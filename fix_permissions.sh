#!/usr/bin/env bash

echo "✅ Fixing disk permissions..."

# Make the secret file rw- for the current user but --- (no access) for others.
chmod 600 .env

# Make the current directory rwx for current user but --x for others.
# Gives me full permissions for the directory but prevents others from listing
# the contents or modifying the directory listing (can still access files,
# respecting file permissions).
chmod 711 ./src

# Change the permissions for all files to rw- for current user and r-- for
# the group and world.
( shopt -s dotglob; chmod -R 644 ./src/* )

# Change the permisiions of all subdirectories so that the current user has rwx
# and all others have --x. Gives me full permissions for the directory but
# prevents others from listing the contents or modifying the directory listing
# (can still access files, respecting file permissions).folder.
find ./src -type d -exec chmod 711 {} \;

# Change the permissions for PHP files, should be rw- by user and ---
# (no access) for others.
find ./src -type f -iname "*.php" -exec chmod 600 {} \;
