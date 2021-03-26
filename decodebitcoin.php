<?php
  $url = 'curl -s -H "CB-VERSION: 2018-01-11" "https://api.coinbase.com/v2/prices/BTC-USD/spot"';
  $tmp = shell_exec($url);
  $data = json_decode($tmp, true);
  if ($data && $data['data'] && $data['data']['amount']) {
    $valorbit = (float)$data['data']['amount'];
	echo 'precio BITCOIN-'.$valorbit.'<br>';
  }
  else echo 'BITCOIN no disponible'.'<br>';

    $url = 'curl -s -H "CB-VERSION: 2018-01-11" "https://api.coinbase.com/v2/prices/BCH-USD/spot"';
  $tmp = shell_exec($url);
  $data = json_decode($tmp, true);
  if ($data && $data['data'] && $data['data']['amount']) {
    $valorbit = (float)$data['data']['amount'];
	echo 'precio BITCOIN CASH-'.$valorbit.'<br>';
  }
  else echo 'BITCOIN CASH no disponible'.'<br>';

      $url = 'curl -s -H "CB-VERSION: 2018-01-11" "https://api.coinbase.com/v2/prices/ETH-USD/spot"';
  $tmp = shell_exec($url);
  $data = json_decode($tmp, true);
  if ($data && $data['data'] && $data['data']['amount']) {
    $valorbit = (float)$data['data']['amount'];
	echo 'precio ETHEREUM-'.$valorbit.'<br>';
  }
  else echo 'ETHEREUM no disponible'.'<br>';

      $url = 'curl -s -H "CB-VERSION: 2018-01-11" "https://api.coinbase.com/v2/prices/LTC-USD/spot"';
  $tmp = shell_exec($url);
  $data = json_decode($tmp, true);
  if ($data && $data['data'] && $data['data']['amount']) {
    $valorbit = (float)$data['data']['amount'];
	echo 'precio LITECOIN-'.$valorbit.'<br>';
  }
  else echo 'LITECOIN no disponible'.'<br>';
  echo '<br><br>by GENIUS';
  ?>