<?php

/**
 * This script is used to redirect localhost to a 404 page inside the app BUT
 * is ONLY used when the app is running locally in Docker.
 *
 * If you get redirected to the sentinel path of apache-root-index-sent-you-here
 * then you know there is a problem with one of the app's links or redirects!
 */

http_response_code(301);
header("Location: /~bfgodfr/4620/project/apache-root-index-sent-you-here");
