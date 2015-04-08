#!/usr/bin/perl -w
#
# Author: Anuj Gupta
# Date  : 21rd Feb 2015
# Integrate de novo assemblies and reference assemblies into a consensus assembly.

use strict;
use Getopt::Long;
use Cwd	'abs_path';
use File::Find;
print "Current directory is ", abs_path($0),"\n";

my $usage = "$0 [-out=output ANI matrix. Default:distance.txt] [-ext=extension of the fasta files. Default:fasta]";

my $ext="fasta";          # Input Queries Folder
my $out = "AssemblyScore.txt"; # Output File

# Get the arguments
my $args  = GetOptions ("ext=s"     => \$ext,
                        "out=s"     => \$out);
system("mkdir CISA");
open LOG, ">CISA/log.txt" or die "Cannot open log.txt\n";
my $edenaDir = "/home/sim8/assemblyMagicResults/edena";
my $abyssDir = "/home/sim8/assemblyMagicResults/abyss";
my $spadesDir = "/home/sim8/assemblyMagicResults/spades";
my $refDir = "/home/sim8/assemblyMagicResults/reference";

opendir( my $DIR, $spadesDir );
while ( my $entry = readdir $DIR ) {
    	next unless -d $spadesDir . '/' . $entry;
	next if $entry eq '.' or $entry eq '..';
 	my ($key) = $entry =~ /M\d+/g;
	print "Key $key\n";
#	my $key = substr($entry, 0, 6);
	system("mkdir CISA/$key");
	my $spadesAssembly = "$spadesDir/$entry";
	my $abyssAssembly = "empty";
	my $edenaAssembly = "empty";
	my $refAssembly = "empty";
	my $DIR1;
	my $entry1;
	opendir( $DIR1, $abyssDir );
	while ($entry1 = readdir $DIR1) {
		if (index($entry1, $key) != -1) {
			$abyssAssembly = "$abyssDir/$entry1";
		}
	}
	closedir $DIR1;
	opendir( $DIR1, $edenaDir );
        while ($entry1 = readdir $DIR1) {
                if (index($entry1, $key) != -1) {
                        $edenaAssembly = "$edenaDir/$entry1";
                }
        }
        closedir $DIR1;
	opendir( $DIR1, $refDir );
        while ($entry1 = readdir $DIR1) {
                if (index($entry1, $key) != -1) {
                        $refAssembly = "$refDir/$entry1";
                }
        }
        closedir $DIR1;


	if (-d "$spadesAssembly") {
 		print LOG "$spadesAssembly exists\n";
	}
	else {
		print LOG "****Spades Assembly does not exist for $key\n";
#		exit 1;
	}
	if (-d "$edenaAssembly") {
                print LOG "$edenaAssembly exists\n";
        }
        else {
                print LOG "****Edena Assembly does not exist for $key\n";
#		exit 1;
        }
	if (-d "$abyssAssembly") {
                print LOG "$abyssAssembly exists\n";
        }
        else {
                print LOG "****Abyss Assembly does not exist for $key\n";
#		exit 1;
        }
	if (-d "$refAssembly") {
                print LOG "$refAssembly exists\n";
        }
        else {
                print LOG "****Reference Assembly does not exist for $key\n";
#               exit 1;
        }
	my $count = 0;
	my $spC = 0;
	my $abC = 0;
	my $edC = 0;
	my $reC = 0;
	my $spadesAssemblyFasta = "$spadesAssembly/contigs.fasta";
	my $edenaAssemblyFasta = "$edenaAssembly/_contigs.fasta";
	my $abyssAssemblyFasta = "$abyssAssembly/$key-contigs.fa";
	my $refAssemblyFasta = "empty";
	if (-d "$refAssembly") {
		opendir($DIR1, $refAssembly) or die $!;
		while (my $file = readdir($DIR1)){
			next unless ($file =~ m/M\d+/);
			$refAssemblyFasta = "$refAssembly/$file";
		}
		closedir $DIR1;
	}
	if (-e "$spadesAssemblyFasta") {
		print LOG "$spadesAssemblyFasta exists\n";
		$spC = 1;
	}
	else {
		print LOG "********Spades Fasta does not exist for $key\n";
#                exit 1;
	}
	if (-e "$abyssAssemblyFasta") {
                print LOG "$abyssAssemblyFasta exists\n";
		$abC = 1;
        }
        else {
                print LOG "********Abyss Fasta does not exist for $key\n";
# 		exit 1;
	}
	if (-e "$edenaAssemblyFasta") {
                print LOG "$edenaAssemblyFasta exists\n";
		$edC = 1;
        }
        else {
                print LOG "********Edena Fasta does not exist for $key\n";
#                exit 1;
        }
	if (-e "$refAssemblyFasta") {
                print LOG "$refAssemblyFasta exists\n";
		$reC = 1;
        }
        else {
                print LOG "********Reference Fasta does not exist for $key\n";
#               exit 1;
        }
	print LOG "$count\n";
        $count=$spC + $abC + $edC + $reC;
	open CONF, ">CISA/$key/Merge.config" or die "Cannot open Merge.config\n";
	print CONF "count=$count\n";
	if ($spC == 1 ){
		print CONF "data=$spadesAssemblyFasta,title=$key-spades\n";
	}
	if ($edC == 1){
		print CONF "data=$edenaAssemblyFasta,title=$key-edena\n";
	}
	if ($abC == 1){
		print CONF "data=$abyssAssemblyFasta,title=$key-abyss\n";
	}
	if ($reC == 1){
		print CONF "data=$refAssemblyFasta,title=$key-reference\n";
	}
	print CONF "Master_file=CISA/$key/$key-master.fa\n";
	print CONF "min_length=100\n";
	print CONF "Gap=11\n";
	close CONF;

	system("python ~/installation/CISA1.3/Merge.py CISA/$key/Merge.config > CISA/$key/MergeOut");

	my $maxLen = 0;
	open MO, "<CISA/$key/MergeOut" or die "Cannot open MergeOut for $key\n";
	while (<MO>){
		if ($_ =~ m/^Length of the longest contig:/){
                        chomp $_;
                        my $len = (split(/\s+/))[5];
                        if ($len > $maxLen){
				$maxLen = $len;
			}
                }
	}
	close MO;

	open CONF, ">CISA/$key/CISA.config" or die "Cannot open CISA.config\n";
        print CONF "genome=$maxLen\n";
        print CONF "infile=CISA/$key/$key-master.fa\n";
        print CONF "outfile=CISA/$key/cisa-contig.fa\n";
        print CONF "nucmer=/home/bgandham3/installations/MUMmer3.23/nucmer\n";
	print CONF "R2_Gap=0.95\n";
        print CONF "CISA=/home/bgandham3/installations/CISA1.3\n";
        print CONF "makeblastdb=/home/bgandham3/installations/ncbi-blast-2.2.30+/bin/makeblastdb\n";
        print CONF "blastn=/home/bgandham3/installations/ncbi-blast-2.2.30+/bin/blastn\n";
        close CONF;

	system("python /home/bgandham3/installations/CISA1.3/CISA.py CISA/$key/CISA.config");

	system("mv ./CISA1* ./CISA/$key/");
	system("mv ./CISA2* ./CISA/$key/");
	system("mv ./CISA3* ./CISA/$key/");
	system("mv ./CISA4* ./CISA/$key/");
	system("mv ./*info* ./CISA/$key/");
	system("mv ./*.fa ./CISA/$key/");

}
closedir $DIR;
close LOG;