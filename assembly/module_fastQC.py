__author__ = 'Xin Wu'
#!/usr/bin/env python
import os
import sys
import subprocess

#fastqc.py
#Invokes fastqc on raw read file(s)
#Usage: module_fastQC.py /path/to/raw/reads /path/to/output/directory

inputDirectory = sys.argv[1]    #point to directory where raw reads are stored
outputDirectory = sys.argv[2]   #point to directory where html files will be stored

paths = [os.path.join(inputDirectory,fn) for fn in next(os.walk(inputDirectory))[2]] #store file path(s) into list

def moduleFastQC(file):
    subprocess.call(["fastqc', "-o"", ODfastqc, file,])

moduleFastQC(file, outputDirectory)