<?php
include "piechart.class.php";
$chart1=new piechart(
					180,
					array('January','February','March','April','May'),
					array(20,30,60,80,100),
					array('yellow','wheat','blue','yellowgreen','fuchsia')
					);
$chart1->draw();
$chart1->out('test1.jpg',0);
$chart1->out('test2.jpg',100);
?>