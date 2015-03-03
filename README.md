# CompGenomics2015 -- Assembly Group
Jill Walker, Xin Wu, Diana Williams, Anuj Gupta, Ke Qi, Nicholas Kovacs, Sung Im, Taylor Griswold, Yuanbo Wang

## Requisite software

---

Prinseq (raw read trimming) -- [PRINSEQ](www.prinseq.sourceforge.net)

ABySS (de novo assembly) -- [BC Cancer Agency](www.bcgsc.ca/platform/bioinfo/software/abyss)

Fastqc (raw read metrics) -- [Babraham Bioinformatics](www.bioinformatics.babraham.ac.uk/projects/fastqc/)

CISA (contig integration) -- [Contig Integrator for Sequence Assembly](sb.nhri.org.tw/CISA/en/CISA)

Edena (de novo assembly) -- [Genomic Research Laboratory - University of Geneva Hospitals](www.genomic.ch/edena.php)

SPAdes (de novo assembly) -- [St. Petersburg Academic Univerity of the Russian Academy of Sciences](bioinf.spbau.ru/spades)

QUAST (assembly metrics) -- [St. Petersburg Academic Univerity of the Russian Academy of Sciences](bioinf.spbau.ru/quast)

SMALT (reference assembly) -- [Wellcome Trust Sanger Institute](https://www.sanger.ac.uk/resources/software/smalt)

## Scripts

---

### module_wranglePairedEnds.py
_Returns a dictionary of 'key : value' pairs._

**Usage:** module_wranglePairedEnds.py </path/to/input files/>

### module_ABySS.py
_Generates hash of trimmed reads and invokes either SE or PE ABySS de novo assembly._
_Outputs contig file(s) in FASTA format in user-specified directory._

**Usage:** module_ABySS.py </path/to/inputfiles/> </path/to/output directory/>

### module_Edena.py 
_Generates hash of trimmed reads and invokes either SE or PE Edena de novo assembly._
_Output is a directory containing the contig and scaffold files in FASTA format._

**Usage:** module_Edena.py </path/to/inputfiles/> </path/to/output directory/>

### module_SPAdes.py
_Generates hash of trimmed reads and invokes either SE or PE SPAdes de novo assembly._
_Output is a directory containing the contig and scaffold files in FASTA format._

**Usage:** module_SPAdes.py </path/to/inputfiles/> </path/to/output directory/>

### module_Prinseq.py
_User-friendly script to run prinseq with specified parameters._
_variable outPath (the output directory) is currently hard coded._

**Usage for unpaired read:** module_Prinseq.py -s </path/to/read> -l <left-trim no.> -r <right-trim no.>

**Usage for paired-end read:** module_Prinseq.py -p </path/to/read1> </path/to/read2> -l <left-trim no.> -r <right-trim no.>

### module_fastQC.py
_Invokes fastqc on raw read file(s)_

**Usage:** module_fastQC.py </path/to/input files/> </path/to/output directory/>

### module_Quast.py
_Locates contig/scaffold FASTA files and invokes Quast to generate assembly metrics._
_Quast metrics files are generated in the respective assembly directories._

**Usage:** See script for usage example

### module_Compare.py
_Takes in a report.tsv file and returns a dictionary containing all attributes as keys and their respective values._

**Usage:** See script for usage example