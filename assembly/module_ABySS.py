__author__ = 'Xin Wu'
#!/usr/bin/env python
import os
import sys
import re

# Generates hash of trimmed reads and invokes either SE or PE ABySS de novo assembly.
# Outputs contig file(s) in FASTA format in user-specified directory.
# Usage: module_ABySS.py </path/to/inputfiles/> </path/to/output directory/>

def matchPE(prinseqPaths):              #use case hash generator for ABySS suffix requirement for PE reads.
    for file in prinseqPaths:
        newfile = ""
        if "good_1" in file:
            newfile = file.replace("good_1", "R*")
        elif "good_2" in file:
            newfile = file.replace("good_2", "R*")
        if not newfile in fileHash:
            fileHash[newfile] = [file]
        else:
            fileHash[newfile].append(file)
    return

def moduleAbyssSE(inputfileOne):
    base = re.match("^(.*)_", inputfileOne)
    filename = base.group(1)
    os.system("ABYSS "+"-k 21 "+inputDirectory+inputfileOne+" -o "+outputDirectory+filename+".fa")

def moduleAbyssPE(inputfileOne, inputfileTwo):
    base = re.match("^(.*)_", inputfileTwo)
    filename = base.group(1)
    os.system("cd "+outputDirectory)
    os.system("abyss-pe "+" K=21 "+"name="+outputDirectory+filename+".fa "+"in="+outputDirectory+inputfileOne+" "+outputDirectory+inputfileTwo)


########################################################################################################################
###                                                   MAIN PROGRAM                                                   ###
########################################################################################################################

inputDirectory = sys.argv[1]        #point to directory of trimmed (prinseq) files.
outputDirectory = sys.argv[2]       #point to output directory where ABySS assemblies will be stored.

fileHash = {}

prinseqPaths = [os.path.join(inputDirectory, fn) for fn in next(os.walk(inputDirectory))[2]]

matchPE(prinseqPaths)       #sorts the file-path(s)-values in hash

for key in fileHash:

    currentValue = fileHash.get(key)
    sortedValue = sorted(currentValue)

    if len(sortedValue) == 1:               #if key in hash has one value, invoke SPAdes for single-end read
        inputfileOne = sortedValue[0]
        moduleAbyssSE(inputfileOne)

    if len(sortedValue) == 2:               #if key in hash has two values, invoke SPAdes for paired-end reads
        inputfileOne = sortedValue[0]
        inputfileTwo = sortedValue[1]
        moduleAbyssPE(inputfileOne, inputfileTwo)