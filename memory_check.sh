#!/bin/bash

"""
Created this script to check the memory usage of a certain PID
when that PID existed. The output was directed to a log file
I identified when running the command. At the time, we were
investigating why the process for the crime site script was
getting killed before it completed.
"""

PID_FILE="/tmp/crime_test_update.pid"

echo begin at `date`

while [[ -e $PID_FILE ]]; do
  # While the PID for crimes exists, check memory
  PID=$(cat ${PID_FILE});
  NOW=$(date +"%Y%m%dT%H%M%S");
  memory_check=`ps -p $PID -o rss | tail -n 1`
  echo -n $memory_check
  echo -n " "
  echo $NOW
  sleep 60
done

echo complete at `date`