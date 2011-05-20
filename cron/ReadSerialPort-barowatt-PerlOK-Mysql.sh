#!/usr/bin/perl -w

# Lecture d'information envoyé par l'appareil "Current Cost" ou "BaroWatt" via cable USB et émulation de port série;
use strict;
use Device::SerialPort qw( :PARAM :STAT 0.07 );
use Date::Calc qw(Add_Delta_YMDHMS Today_and_Now);

my $PORT = "/dev/tty.usbserial";

my $ob = Device::SerialPort->new($PORT);
$ob->baudrate(57600);
$ob->write_settings;

# déclaration et initialisation des valeurs de références pour savoir si on doit traiter les infos remontées et faire un insert en DB
my ($year,$lastHM,$lastHD, $lastHH,$min,$sec) = Add_Delta_YMDHMS(Today_and_Now(), 0,0,0,-3,0,0); # 3 heures auparavant car il faut avoir lancer le script de récup d'historique au préalable si on veut le conserver

open(SERIAL, "+>$PORT");
while (my $line = <SERIAL>) {
	my $sqlins = "";

	# [4,3,2] récupération du mois, jour et heure depuis le tableau retourné par localtime
	my ($refMonth, $refDay, $refHour) = (localtime(time))[4,3,2];
	#print "Val de ref : lastHM = ".$lastHM." - ".$month." lastHD = ".$lastHD." - ".$day." lastHH = ".$lastHH." - ".$hour.".\n";

	if ($line =~ m!<tmpr> *([\-\d.]+)</tmpr>.*<ch1><watts>0*(\d+)</watts></ch1>!) {
		print "Temps réel \n";
		#Information temps réel (toutes les 6 secondes avec mon BaroWatt)
		$sqlins = 'insert into xmldataRT (date, temp, watts) values (now(),'.$1.','.$2.')';
	} elsif ($line =~ m!<units> *(\w+)</units>.*<h004>(\d+)</h004>!) {
		print "H-4 \n";
		#Information historique h-4 (toutes les 2 heures avec mon BaroWatt) je sais pas pourquoi il y a pas de h002 envoyé par mon cc128
		if (!($refHour == $lastHH)) {
			$lastHH = $refHour;
			my ($year,$month,$day, $hour,$min,$sec) = Add_Delta_YMDHMS(Today_and_Now(), 0,0,0,-4,0,0);# on retranche les 4 heures à la date actuelle
			my $strHeureMesure = sprintf("%4d-%02d-%02d %02d:%02d:%02d",$year,$month,$day,$hour,$min,$sec);
			$sqlins = 'insert into xmldataHH (date, unit, val) values ("'.$strHeureMesure.'","'.$1.'",'.$2.')';
		}
	} elsif ($line =~ m!<units> *(\w+)</units>.*<d001>(\d+)</d001>!) {
		print "D-1 \n";
		#Information historique j-1 (toutes les 2 heures avec mon BaroWatt)
		if (!($refDay == $lastHD)) {
			$lastHD = $refDay;
			my ($year,$month,$day, $hour,$min,$sec) = Add_Delta_YMDHMS(Today_and_Now(), 0,0,-1,0,0,0);# on retranche 1 jour à la date actuelle
			my $strHeureMesure = sprintf("%4d-%02d-%02d %02d:%02d:%02d",$year,$month,$day,$hour,$min,$sec);
			$sqlins = 'insert into xmldataHD (date, unit, val) values ("'.$strHeureMesure.'","'.$1.'",'.$2.')';
		}
	} elsif ($line =~ m!<units> *(\w+)</units>.*<m001>(\d+)</m001>!) {
		print "M-1 \n";
		#Information historique m-1 (toutes les 2 heures avec mon BaroWatt)
		if (!($refMonth == $lastHM)) {
			$lastHM = $refMonth;
			my ($year,$month,$day, $hour,$min,$sec) = Add_Delta_YMDHMS(Today_and_Now(), 0,-1,0,0,0,0);# on retranche 1 mois à la date actuelle
			my $strHeureMesure = sprintf("%4d-%02d-%02d %02d:%02d:%02d",$year,$month,$day,$hour,$min,$sec);
			$sqlins = 'insert into xmldataHM (date, unit, val) values ("'.$strHeureMesure.'","'.$1.'",'.$2.')';
		}
	}
	
	if (!($sqlins eq '')) {
		system('echo \''.$sqlins.'\' | mysql -u root --password=asnow06 conso_watt_et_temp');
	}

	print $sqlins."\n".$line."\n";
}

