__author__ = 'Yuanbo Wang'
#!/usr/bin/python3
#calculates scores for lists of quast evaluation results and outputs into tabular format
import csv
from math import log
from collections import defaultdict
import os

inputDirectory_edena = "/home/sim8/assemblyMagicResults/edena/"
inputDirectory_abyss = "/home/sim8/assemblyMagicResults/abyss/"
inputDirectory_spades = "/home/sim8/assemblyMagicResults/spades/"
inputDirectory_reference = "/home/sim8/assemblyMagicResults/reference/"
inputDirectory_cisa = "/home/sim8/assemblyMagicResults/CISA/"
folders_edena = [os.path.join(inputDirectory_edena,fn) for fn in next(os.walk(inputDirectory_edena))[1]]
folders_abyss = [os.path.join(inputDirectory_abyss,fn) for fn in next(os.walk(inputDirectory_abyss))[1]]
folders_spades = [os.path.join(inputDirectory_spades,fn) for fn in next(os.walk(inputDirectory_spades))[1]]
folders_reference = [os.path.join(inputDirectory_reference,fn) for fn in next(os.walk(inputDirectory_reference))[1]]
folders_cisa = [os.path.join(inputDirectory_cisa,fn) for fn in next(os.walk(inputDirectory_cisa))[1]]

# takes in a report.tsv file and returns a dictionary containing all attributes as keys
# and their respective values
def file2Dict(resultFile):
    with open(resultFile, 'rb') as f:
        reader = csv.reader(f, delimiter='\t')
        dataList = list(reader)
    return {item[0]:item[1] for item in dataList}

# takes in a dictionary and outputs the score calculated
def calcScore(resultDict):
    numContigs = float(resultDict['# contigs'])
    n50 = float(resultDict['N50'])
    genlen = float(resultDict['Total length'])
    score = log((n50 * genlen) / numContigs)
    return score

# takes in a list of directory (for a certain assembler) and assembler name, calculates score
# for each report file under each directory, and store results in a 2-d dictionary: 
# {M10699:{edenascore: ###, spadesscore: ###, abyssscore: ###, cisa score: ###}, M10562:...}
scoreDict = defaultdict(dict)
def makeScoreDict(directory, assembler):
    for f in next(os.walk(directory))[1]:
        try:
            filePath = os.path.join(directory,f) + "/report.tsv"
            fileKey = f[:6]
            score = calcScore(file2Dict(filePath))
            scoreDict[fileKey][assembler] = score
        except:
            pass
    return scoreDict

# Takes the score dictionary and outputs to tabular format
def scoreDict2File(scoreDict):
    outFile = open("scores.csv", 'w')
    outFile.write("Read\tabyss\tspades\tedena\treference\tCISA\n")
    assemblers = ['abyss', 'spades', 'edena', 'reference', 'cisa']
    for assembly in scoreDict:
        outFile.write(assembly+"\t")
        for assembler in assemblers:
            try:
                outFile.write(str(scoreDict[assembly][assembler])+"\t")
            except:
                outFile.write("Missing"+"\t")
        outFile.write("\n")

# =======Example Usage========= #

'''
scoreDict = makeScoreDict(inputDirectory_abyss, 'abyss')
scoreDict = makeScoreDict(inputDirectory_spades, 'spades')
scoreDict = makeScoreDict(inputDirectory_edena, 'edena')
scoreDict = makeScoreDict(inputDirectory_reference, 'reference')
scoreDict = makeScoreDict(inputDirectory_cisa, 'cisa')

print len(scoreDict.keys())
for f in scoreDict:
    print "file: " + f
    print len(scoreDict[f].keys()) == 4
    for assembler in scoreDict[f]:
        print "assembler: " + assembler + "; score: " + str(scoreDict[f][assembler])

scoreDict2File(scoreDict)
'''
# ============================= #
