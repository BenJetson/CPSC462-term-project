# CPSC 462 Term Project

This project is property of Ben Godfrey (bfgodfr).

## Project Topic

The goal of this project is to produce an IT Helpdesk application on the LAMP
stack with two key features:

- A **knowledge base** so that users can get answers immediately on their own.
  This gets users the support they need faster and reduces the ticket volume
  for the help desk staff.
- A **help ticket** system so that customers can request individual assistance
  when the knowledge base does not have the answer they need. This cuts down on
  email volume for the help desk staff and assists them with keeping track of
  which issues are unresolved.

## Codebase Structure

- `/db/` - directory containing files relating to the database itself.

  - `/db/migrations/` - directory containing all database migration files,
    named in the order that they are to be run.

- `/docker/` - directory containing files that are necessary to run the app on
  your local development machine in Docker. NOT USED IN PRODUCTION.

- `/scripts/` - helpful shell scripts that are used to automate various
  deployment and other tasks.

- `/src/` - directory containing the application source code. This is what gets
  the public HTML folder on the webserver.

  - `/src/assets/` - directory containing static resource files.

  - `/src/includes/` - directory containing PHP code that will be included
    within other scripts. NOT ACCESSIBLE via the internet.

    - `/src/includes/components/` - directory containing PHP code that can
      render various views. Most of the HTML markup lives here.

    - `/src/includes/db/` - directory containing PHP code with database driver
      functions. All direct interfacing with the database happens in these
      files only. Other scripts may call these functions to get DB data.

    - `/src/includes/forms/` - directory containing PHP code that will handle
      all form POST processing. Will do access/validity checks and make
      appropriate database driver calls.

    - `/src/includes/pages/` - directory containing PHP code that renders a
      full HTML5 compliant page using view the components passed in.

    - `/src/includes/types/` - directory containing PHP classes that model the
      data structures used throughout the application.

  - `/src/vendor/` - directory containing source code that this application
    depends on, but is not part of this repository. The contents of this
    directory are controlled by [PHP Composer](getcomposer.org). Vendored code
    is not checked into the repository and must be fetched by running the
    composer recipe provided in the Makefile.

  - `/src/*.php` - these PHP scripts are the driver scripts, the only ones that
    are accessible to the public via the internet. **ALL EXECUTION STARTS HERE**

- `/.env*` - these are environment files, which are used to store secrets and
  other application configuration variables. Each tier has its own file.
- `/composer*` - these files describe the PHP libraries that this application
  depends on and their respective dependency versions.
- `/docker-compose.yml` - this file describes the cluster of Docker containers
  that can be spun up for local development purposes. NOT USED IN PRODUCTION.
- `/Dockerfile*` - these files describe how to build the containers that are
  referenced in the Docker Compose file. NOT USED IN PRODUCTION.
- `/LICENSE` - this file describes the terms under which the files contained
  within this repository may be used.
- `/Makefile` - this file contains recipes that can be invoked via make to
  build and deploy this application.

## Deployment Notes

This project includes deployment scripts. The necessary files will be copied
to the server automatically.

1. Fill in the appropriate `.env` file for the desired target tier. Use
   `.env.prod` for production and `.env.dev` for development.
1. Configure your SSH client for the SoC web application server. We must set two
   options for the script to work.

   ```ssh_config
   Host webapp
       HostName webapp.computing.clemson.edu
       User bfgodfr
       ForwardAgent yes
       BatchMode yes
       ConnectTimeout 2
   ```

1. Then you may run one of the deploy commands, depending on the desired target
   tier. Run `make deploy` for production and `make dev` for development.
