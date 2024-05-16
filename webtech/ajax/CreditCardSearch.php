
<?php
// class definition
class CreditCard
{
  // Properties
  public $type;
  public $bank;
  public $limit;

  // constructor
  function __construct($typ, $bnk, $lmt)
  {
    $this->type = $typ;
    $this->bank = $bnk;
    $this->limit = $lmt;
  }
}

class Result
{
  public $found;
  public $card;

  // constructor
  function __construct($f, $c)
  {
    $this->found = $f;
    $this->card = $c;
  }
}

// create the array of CreditCard
$cc1 = new CreditCard('Visa', 'Maybank', 15000);
$cc2 = new CreditCard('Master', 'CIMB', 10000);
$cc3 = new CreditCard('Visa', 'RHB', 5000);

$ccardList = array($cc1, $cc2, $cc3);

// http://localhost/AD05/CreditCardSearch.php

$jsonStr = $_POST['jsonStr'];

$searchKey = json_decode($jsonStr);

$idx_found = -1;
for ($i = 0; $i < sizeof($ccardList); $i++) {

  if (
    $ccardList[$i]->type == $searchKey->type &&
    $ccardList[$i]->bank == $searchKey->bank
  ) {
    $idx_found = $i;
    break; // quit the loop after found it
  }
}

if ($idx_found != -1) {
  $result = new Result(1, $ccardList[$idx_found]);
} else {
  $result = new Result(0, null);
}

header("Content-type:text/plain");
header("Access-Control-Allow-Origin: *");
echo (json_encode($result));
?>
