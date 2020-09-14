#!/bin/bash

# Script to ensure that the Apache server reliably starts.
# Copied from the official PHP image in the Docker library.
#
# Source:
# https://github.com/docker-library/php/commit/a51c16e5f91be6243452471d1454dca5b168e3d4

set -e

# Apache gets grumpy about PID files pre-existing
rm -f /var/run/apache2/apache2.pid

exec apachectl -D FOREGROUND
