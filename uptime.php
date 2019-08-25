<?php
date_default_timezone_set('Asia/Seoul');
$id = $_GET['id'];

$type = $_GET['type'];
$url = 'https://api.twitch.tv/kraken/streams/'.$id.'?client_id=';
$json = file_get_contents($url);
$array = json_decode($json);
if ($array->stream) {
  $s = $array->stream->created_at;
  $s = str_replace('T', ' ', $s);
  $s = str_replace('Z', '', $s);
  $date = new DateTime(date("Y-m-d H:i:s", strtotime('+9 hours', date(strtotime($s)))));
  $now = new DateTime(date("Y-m-d H:i:s"));
  $diff = date_diff($date, $now);
  $msg = date("H시 i분 s초에 켜서 ", strtotime('+9 hours', date(strtotime($s))));
  $msg2 = "";
  if ($diff->d > 0) {
    $msg2 .= $diff->d." 일 ";
  }
  if ($diff->h >= 0) {
    $msg2 .= $diff->h." 시간 ";
  }
  if ($diff->i >= 0) {
    $msg2 .= $diff->i." 분 ";
  }
  if ($diff->s >= 0) {
    $msg2 .= $diff->s." 초 ";
  }

  if (empty($type)) {
    echo $msg.$msg2."째 방송 중";
  } else if ($type == "none") {
    echo $msg2."째 방송 중";
  } else {
    echo $msg2;
  }
} else {
  $url = 'https://api.twitch.tv/kraken/channels/'.$id.'/videos?limit=1&broadcast_type=archive&client_id=';
  $json = file_get_contents($url);
  $array = json_decode($json);
  if ($array->videos[0]) {
    $s = $array->videos[0]->created_at;
    $s = str_replace('T', ' ', $s);
    $s = str_replace('Z', '', $s);
    $date = new DateTime(date("Y-m-d H:i:s", strtotime('+9 hours', date(strtotime($s)))));
    $now = new DateTime(date("Y-m-d H:i:s", strtotime('+9 hours +'.$array->videos[0]->length.' seconds', date(strtotime($s)))));
    $diff = date_diff($date, $now);
    $msg = date("현재 방송 중이 아닙니다. 최근 방송은 m월 d일 H시 i분 s초부터 ", strtotime('+9 hours', date(strtotime($s))));
    if ($diff->d > 0) {
      $msg .= $diff->d." 일 ";
    }
    if ($diff->h >= 0) {
      $msg .= $diff->h." 시간 ";
    }
    if ($diff->i >= 0) {
      $msg .= $diff->i." 분 ";
    }
    if ($diff->s >= 0) {
      $msg .= $diff->s." 초 ";
    }
    echo $msg."동안 진행되었습니다.";
  } else {
    echo "현재 방송 중이 아닙니다.";
  }
}
?>
