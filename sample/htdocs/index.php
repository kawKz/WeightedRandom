<?php
require_once (dirname(__FILE__) . '/../../src/Base.php');
require_once (dirname(__FILE__) . '/../../src/Drawer.php');
require_once (dirname(__FILE__) . '/../../src/Util.php');

//$instance = \WeightedRandom\Util::newInstance();
$instance = new WeightedRandom\Drawer();
$lots = array(
    'A'=>'<font size=3 color="#1B0CF5">60%の確率です</font>',
    'B'=>'<font size=5 color="#0CF569">30%の確率です</font>',
    'C'=>'<b><font size=10 color="#F5650C">9%の確率です</font></b>',
    'D'=>'<b><font size=20 color="#F50C0C">1%の確率です</font></b>'
);
$instance->setWeights(array(
    'A'=>60,
    'B'=>30,
    'C'=>9,
    'D'=>1
));
?>
<html>
    <head>
        <title>サンプルコード</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
    </head>
    <body>
        <div class="drawKey" style="text-align: center">
            1. 以下、重み付け抽選のサンプルです（相対確率）。<br />

<?php echo $lots[$instance->drawKey()]; ?>

        </div>
        <br />
        <br />
        <br />
        <div class="drawKey" style="text-align: center">
            2. 以下、的中抽選のサンプルです（絶対確率）。<br />

<?php echo (
    \WeightedRandom\Util::judgeProbability(30)
    ? '<b><font size=20 color="#F50C0C">当たり</font></b>'
    : '<font size=3 color="#1B0CF5">外れ</font>'
); ?>

        </div>
    </body>
</html>