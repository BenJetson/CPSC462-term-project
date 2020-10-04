#!/bin/bash

# Script to ensure that the Apache server reliably starts.
# Adapted from the official PHP image in the Docker library.
#
# Source:
# https://github.com/docker-library/php/commit/a51c16e5f91be6243452471d1454dca5b168e3d4

set -e

# Apache gets grumpy about PID files pre-existing
rm -f /var/run/apache2/apache2.pid

# Must source Apache environment variables before starting the daemon.
# See also: https://askubuntu.com/a/147065
# shellcheck disable=SC1091
source /etc/apache2/envvars

exec apache2 -DFOREGROUND
