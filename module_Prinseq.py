__author__ = 'Xin Wu'
#!/usr/bin/env python
import os
import re
import argparse
import sys
import glob

# easyPrinseq.py
# User-friendly script to run prinseq with specified parameters.

outPath = sys.argv[1]   #STDIN the output directory for the trimmed files

# Parse command line arguments
parser = argparse.ArgumentParser(description = "Options for easyPrinseq\n")

parser.add_argument("-s", required=True, help="for single-end reads: -s read1")
parser.add_argument("-p", required=False, nargs="+", help="for paired-end reads: -p read1 read2")
parser.add_argument("-l", requred=True, help="specify trim_left number: -1 left_trim")
parser.add_argument("-r", requred=True, help="specify trim_right number: -r right_trim")

args = parser.parse_args()

if args.s:
    read1 = args.s
    reads2 = 0
if args.p:
    read1 = args.p[0]
    read2 = args.p[1]
if args.l:
    leftTrim = args.l
if args.r:
    rightTrim = args.r

base = re.search("^(.*)_R", read1)
basename = base.group(1)

if read2:
    os.system("prinseq-lite.pl "+"-fastq "+read1+" -fastq2 "+read2+" -trim_left "+leftTrim+" -trim_right "+rightTrim+" -out_good "+outPath+basename+"good"+" -out_bad "+outPath+basename+"bad"+" -min_qual_score 28")
else:
    os.system("prinseq-lite.pl "+"-fastq "+read1+" -trim_left "+leftTrim+" -trim_right "+rightTrim+" -out_good "+outPath+basename+"good"+" -out_bad "+outPath+basename+"bad"+" -min_qual_score 28")