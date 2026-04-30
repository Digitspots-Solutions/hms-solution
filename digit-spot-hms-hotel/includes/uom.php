<?php

//unit of measurement

$uoms = array(
	1=>"Gram(s)",
	2=>"Litre(s)",
	3=>"Millimeter(s)",
	4=>"Count(s)",
	5=>"Centimeter(s)",
	6=>"Milli Litres",
	7=>"Bag(s)",
	8=>"Block(s)",
	9=>"Bottle(s)",
	10=>"Bucket(s)",
	11=>"Bundle(s)",
	12=>"Can(s)",
	13=>"Cup(s)",
	14=>"Dozen",
	15=>"Jar",
	16=>"Kg(s)",
	17=>"Pieces",
	18=>"Packet(s)",
	19=>"Ptn",
	20=>"Ream(s)",
	21=>"Sat",
	22=>"Tab",
	23=>"Tin(s)",
	24=>"Yard(s)",
	25=>"Gallon(s)",
	26=>"Pairs",
	27=>"Create(s)",
	28=>"Length",
	29=>"Pound(s)",
	30=>"Roll(s)",
	31=>"Inch(es)",
	32=>"Set(s)",
	33=>"Tot(s)",
	34=>"Trip(s)",
	35=>"Jug(s)",
	36=>"Glass(es)",
	37=>"Slim Jim",
	38=>"Square Metre(s)",
	39=>"Group",
	40=>"Each",
	41=>"Carton",
	42=>"Portion",
	43=>"Booklet(s)",
	44=>"Drum(s)",
	45=>"Sachet(s)",
	46=>"Wrap(s)",
	47=>"Sheet(s)",
	48=>"Meter(s)"
);

asort($uoms);
//sort associative array (value) in descending order while krsort do for (key)
//asort does for (value) in ascending order while ksort does for (key) ascending

$list_uoms = '';

foreach ($uoms as $uom_key => $uom_value) {
	$list_uoms .= '<option value="'.$uom_key.'">'.$uom_value.'</option>';
}

#-----------------------------------------------------------------------------------

function get_uom($uom) {
	
	global $uoms;

	$th_uom = '';

	foreach ($uoms as $uom_key => $uom_value) {
		if($uom == $uom_key) {
			$th_uom = $uom_value;
			break;
		}
	}

	return $th_uom;
}

?>
