#!/usr/bin/perl
use Getopt::Long;

$separator='';
GetOptions(
    "aurora|a" => sub { $separator="\%" },
    "verbose|v" => \$verbose,
);

if(@ARGV<1) {
  print("Usage: $0 <4-byte command>\n");
  exit(0);
}
sub bin2dec {
  return unpack("N", pack("B32", substr("0" x 32 . shift, -32)));
}

$prefix="0822";
$cmd=join( '', @ARGV);
$cmd=$prefix . $cmd;
@bytes = grep(/\S/, split(/(..)/, $cmd, -1) );

# Calculate checksum
$sum=0;
foreach $byte (@bytes) {
    next if($byte eq '');
    $dec_byte=sprintf("%d", hex($byte) );
    $sum+=$dec_byte;
}

$bin_comp=substr(sprintf("%08b", ~$sum),-8) ;
$chksum=bin2dec($bin_comp) + 1;
push (@bytes, sprintf("%02x", $chksum) );


printf ("Checksum: %02x\n", $chksum) if ($verbose);
printf ("Command: %s%02x\n", $cmd, $chksum);
printf ("%s%s\n", $separator, join($separator, @bytes) );
