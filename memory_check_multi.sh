#!/bin/bash

# Created this script to check the memory usage of the top five
# processes running while our script is running. The output was directed
# to a log file I identified when running the command.
# At the time, we were investigating why the process for the crime
# site script was getting killed before it completed.

PID_FILE="/tmp/example.pid"

echo begin at `date`

while [[ -e $PID_FILE ]];
do
  # Output the five most memory intensive processes and sort (by size). Format nicely using awk.
  memory_check=`ps ax -o rss,command | sort -nr | head -n 5 | awk '{printf "%i|%s\n",$1,$2$3}'`
  # Format our date nicely.
  NOW=$(date +"%Y-%m-%d %H:%M:%S");
  echo " "
  echo $NOW
  echo $memory_check
  sleep 60
done

echo complete at `date`