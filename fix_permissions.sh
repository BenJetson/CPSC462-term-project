#!/usr/bin/env bash

# Make the secret file rw- for the current user but --- (no access) for others.
chmod 600 .env

# Make the current directory rwx for current user but only readable by others.
# Prevents others from getting directory listing at app root.
chmod 744 ./src

# Change the permissions for all files to rw- for current user and r-- for
# the group and world.
chmod -R 644 ./src/*

# Change the permisiions of all subdirectories so that the current user has rwx
# and all others have r--. Allows my user to cd into the folder but prevents
# others from getting directory listing.
find ./src -type d -exec chmod 744 {} \;

# Change the permissions for PHP files, should be rw- by user and ---
# (no access) for others.
find ./src -type f -iname "*.php" -exec chmod 600 {} \;
