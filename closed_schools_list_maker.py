"""
Just needed to make a simple list from a column in a CSV file.
"""

import sys
from csvkit import CSVKitReader

input_file = sys.argv[1]
r = CSVKitReader(open(input_file,"U"),encoding="MacRoman")

closed_schools_list = []

for row in r:
    closed_schools_list.extend(row)
    
print closed_schools_list
    