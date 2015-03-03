__author__ = 'Yuanbo Wang, Sung Im'
#!/usr/bin/env python
import os
import subprocess

# Generates hash of trimmed reads and invokes either SE or PE Edena de novo assembly.
# Output is a directory containing the contig and scaffold files in FASTA format.

def wranglePairedEnds(paths):
    for file in paths:
        newfile = ""
        if "R1" in file:
            newfile = file.replace("R1", "R*")
        elif "R2" in file:
            newfile = file.replace("R2", "R*")
        if not newfile in fileHash:
            fileHash[newfile] = [file]
        else:
            fileHash[newfile].append(file)
    return

def moduleedenaSE(inputfileOne):
    base = os.path.basename(inputfileOne)
    filename = os.path.splitext(base)[0]
    fileOutputDirectory = outputDirectory+str(filename)
    subprocess.call(["mkdir", fileOutputDirectory])
    subprocess.call(["edena", "-r", inputfileOne, "-p", fileOutputDirectory+"/out"])    #runs file thru overlap mode
    subprocess.call(["edena", "-e", fileOutputDirectory+"/out.ovl", "-p", fileOutputDirectory+"/"])     #takes .ovl file and generates contigs

def moduleedenaPE(inputfileOne, inputfileTwo):
    base = os.path.basename(inputfileOne)
    filename = os.path.splitext(base)[0]
    fileOutputDirectory = outputDirectory+str(filename)
    subprocess.call(["mkdir", fileOutputDirectory])
    subprocess.call(["edena", "-DRpairs", inputfileOne, inputfileTwo, "-p", fileOutputDirectory+"/out"])
    subprocess.call(["edena", "-e", fileOutputDirectory+"/out.ovl", "-p", fileOutputDirectory+"/"])



########################################################################################################################
###                                                   MAIN PROGRAM                                                   ###
########################################################################################################################


inputDirectory = sys.argv[1]    #point to directory of trimmed (prinseq) files.
outputDirectory = sys.argv[2]   #point to output directory where Edena .ovl and contig files will be stored

fileHash = {}
paths = [os.path.join(inputDirectory,fn) for fn in next(os.walk(inputDirectory))[2]]

wranglePairedEnds(paths)    #generates hash of paired-end files

inputfileOne = ""
inputfileTwo = ""

for key in fileHash:

    hashValues = fileHash.get(key)
    sortedValues = sorted(hashValues)   #sorts the file-path(s)-values in hash

    if len(sortedValues) == 1:
        inputfileOne = sortedValues[0]

        moduleedenaSE(inputfileOne)     #if key in hash has one value, invoke Edena for single-end read

    if len(sortedValues) == 2:
        inputfileOne = sortedValues[0]
        inputfileTwo = sortedValues[1]

        moduleedenaPE(inputfileOne, inputfileTwo)   #if key in hash has two values, invoke SPAdes for paired-end reads
