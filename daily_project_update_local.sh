#!/bin/bash

PID_FILE="/tmp/daily_project_update_local.pid"

# Check if PID file exists at location
# If PID file exists, then we exit the script
# and log that we exited.
if [[ -e $PID_FILE ]]; then
  PID=$(cat ${PID_FILE});
  echo "Project update is already running! PID = $PID" 1>&2;
  exit 1
fi

# Create the PID file.
echo $$ > $PID_FILE

echo begin at `date`
PROJECT=projectname
export DJANGO_SETTINGS_MODULE=path.to.settings
ROOT=/vagrant

source /home/vagrant/.virtualenvs/projectname/bin/activate
if [[ ! -e $PGPASSFILE ]]; then
  export PGPASSFILE="/vagrant/projectname/configs/${DEPLOYMENT_TARGET}/pgpass"
fi

$ROOT/manage.py run_first_management_command &&\
$ROOT/manage.py run_second_management_command

echo complete at `date`

# Remove the PID file.
rm $PID_FILE