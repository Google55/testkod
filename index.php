<?php


require "dbconn.php";


$admin = 'https://t.me/Khareton';
$token = '1499513012:AAGf0XUV7WY8q28YBv8mnxSKfUxCp2NizZU';
function randuz($length) {

$chars = "abcdefghiijkllmnnoopqrsttuvwxyzABCDEFGHIIJKLLMNNOOPQRSTTUVWXYZ0123456789";

return substr(str_shuffle($chars),0,$length);
}
function fsize($size,$round=2)
{
$sizes=array(' Bytes',' Kb',' Mb',' Gb',' Tb');
$total=count($sizes)-1;
for($i=0;$size>1024 && $i<$total;$i++){
$size/=1024;
}
return round($size,$round).$sizes[$i];
}
function bot($method,$datas=[]){
global $token;
    $url = "https://api.telegram.org/bot".$token."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
function send($id,$msg,$mid,$rmsg=false,$rurl=false){
  bot('sendmessage',[
	'chat_id'=>$id,
	'text'=>$msg,
'reply_to_message_id'=>$mid,
'parse_mode'=>html,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"Kitoblar",'callback_data'=>"kitoblar"],['text'=>"Ro'yxat",'callback_data'=>"royxat"]],[['text'=>$rmsg,'url'=>$rurl]],
],
])
	]);
}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$mid = $message->message_id;
$rmid= $message->reply_to_message->message_id;
$replytx = $message->reply_to_message->text;
$type = $message->chat->type;
$text = $message->text;
$data = $update->callback_query->data;

$inlineqt = $update->inline_query->query;


$ctext = $update->callback_query->message->text; 
if($data){
$cid = $update->callback_query->message->chat->id;
$cmid=$update->callback_query->message->message_id;
}else{
$cid = $message->chat->id;
}
$pdf = $message->document;
$pdfid=$pdf->file_id;
$filename=base64_encode($pdf->file_name);
$filetype=$pdf->mime_type;
$filexajmi=$pdf->file_size;
$xabar=show_users($text);
function sendd($id,$msg){
$mid=$GLOBALS['mid'];
  bot('sendmessage',[
	'chat_id'=>$id,
	'text'=>$msg,
'parse_mode'=>html,
	]);
}

if($pdf and $cid == $admin){
if($type=="private"){
if($filetype=="application/pdf"){
$rand=randuz(5);
$inset = insert($pdfid,$filename,$rand);
if($inset == "ok"){
bot("senddocument", [
"chat_id" =>$cid, 
"document"=>$pdfid,
"caption"=>"Bazaga saqlandi.\nNomi: $filen\nXajmi: ".fsize($filexajmi)."\nTuri: PDF\n\nAdministratortor: @TILON",

]);
}else{
sendd($cid,"joylanmadi: $inset\n Iltimos ushbu xabarni @TILON ga yuboring.");
}
}else{
sendd($cid,"Faqat pdf formatidagi fayllar qabul qilinadi");
}
}else{
if($filetype=="application/pdf"){
}else{
insert($pdfid,$filename,$rand);
}
}
}


$connn=mysqli_connect("localhost","root","uzbek","my_telegramuz");
     
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            die();
        }
if ($text=="/show" or $data=="kitoblar") {
$pageno = 1;
$no_of_records_per_page = 1;
$offset = ($pageno-1) * 1;
$pagen= 0;
        $total_pages_sql = "SELECT COUNT(*) FROM kitob";
        $resultt = mysqli_query($connn,$total_pages_sql);
        $total_rows = mysqli_fetch_array($resultt)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

       $sqll = "SELECT * FROM kitob LIMIT $pagen, 1";
        $res_data = mysqli_query($connn,$sqll);
 
$roww = mysqli_fetch_array($res_data);
$msg = $roww['fileid'];
$ttle=base64_decode($roww['nomi']);
$ttle=str_replace(["_","pdf"],[" ",""],$ttle);
$share=$roww['share'];
if($pageno <= 1){
  $prev = "⚫️"; 
  $pre="m";
}else {
$prev="Oldingi";
$prevc= $pageno - 1;
$pre="prev$prev";
}
if($pageno >= $total_pages){
$next="⚫️";
$nex="m";
} else {
$next="Keyingi";
$nextc = $pageno + 1; 
$nex = "next$nextc";
}
bot("senddocument", [
"chat_id" =>$cid, 
"document"=>trim($msg),
'caption'=>"📒*$ttle*\n\n🤖@Kitobxonbot",
'parse_mode'=>markdown,
 'reply_markup'=>json_encode([
 'inline_keyboard'=>[ 
    [['text'=>"Ulashish",'switch_inline_query'=>"share$share"]],[['text'=>"$prev",'callback_data'=>"$pre"],['text'=>"$next",'callback_data'=>"$nex"]]
       ]
       ]) 
]);


}

if(strstr($data,"next")) {
$dataa=str_replace("next","",$data);

         $pageno =  $dataa;
$no_of_records_per_page = 1;
$offset = ($pageno-1) * 1;
$ofset=$pageno - 1;
        $total_pages_sql = "SELECT COUNT(*) FROM kitob";
        $resultt = mysqli_query($connn,$total_pages_sql);
        $total_rows = mysqli_fetch_array($resultt)[0];
   $total_pages = ceil($total_rows / $no_of_records_per_page);
       $sqll = "SELECT * FROM kitob LIMIT $offset, 1";
        $res_data = mysqli_query($connn,$sqll);
   
$roww = mysqli_fetch_array($res_data);
$nn = utf8_encode($roww['fileid']);
 $tttle=base64_decode($roww['nomi']);
$tttle=str_replace(["_","pdf"],[" ",""],$tttle);
$share=$roww['share'];
if($pageno <= 1){
  $prev = "⚫️"; 
  $pre="m";
}else {
$prev="Oldingi";
$prevc= $pageno - 1;
$pre="prev$prevc";
}
if($pageno >= $total_pages){
$next="⚫️";
$nex="m";
} else {
$next="Keyingi";
$nextc = $pageno + 1; 
$nex="next$nextc";
}
bot("editmessagemedia", [
"chat_id" =>$cid, 
"message_id"=>$cmid,
"media"=>json_encode([
"type"=>"document" ,
"media"=>$nn,
'caption'=>"📒*$tttle*\n\n🤖@Kitobxonbot",
'parse_mode'=>markdown,
 "reply_markup"=>json_encode([
 "inline_keyboard"=>[
  [['text'=>"Ulashish",'switch_inline_query'=>"share$share"]],[['text'=>"$prev",'callback_data'=>"$pre"],['text'=>"$next",'callback_data'=>"$nex"]],
       ] 
       ]) 
       ])
]);

        }elseif(strstr($data,"prev")){
$dataa=str_replace("prev","",$data);
$pageno = $dataa;
$no_of_records_per_page = 1;
$offset = ($pageno-1) * 1;
$odd=$pageno-1;
        $total_pages_sql = "SELECT COUNT(*) FROM kitob";
        $resultt = mysqli_query($connn,$total_pages_sql);
        $total_rows = mysqli_fetch_array($resultt)[0];
   $total_pages = ceil($total_rows / $no_of_records_per_page);
       $sqll = "SELECT * FROM kitob LIMIT $odd, 1";
        $res_data = mysqli_query($connn,$sqll);

  while($roww = mysqli_fetch_array($res_data)){
$msg = $roww['fileid'];
$ttle=base64_decode($roww['nomi']);
$ttle=str_replace(["_","pdf"],[" ",""],$ttle);
$share = $roww['share'];
 }
if($pageno <= 1){
  $prev = "⚫️"; 
  $pre="m";
}else {
$prev="Oldingi";
$prevc= $pageno - 1;
$pre="prev$prevc";

}
if($pageno >= $total_pages){
$next="⚫️";
$nex="m";
} else {
$next="Keyingi";
$nextc = $pageno + 1; 
$nex="next$nextc";
}
bot("editmessagemedia", [
"chat_id" =>$cid, 
"message_id"=>$cmid,

"media"=>json_encode([
'type'=> 'document' ,
'media' =>$msg,
'caption'=>"📒*$ttle*\n\n🤖@Kitobxonbot",
'parse_mode'=>markdown,
 'reply_markup'=>json_encode([
 'inline_keyboard'=>[ 
  [['text'=>"Ulashish",'switch_inline_query'=>"share$share"]],[['text'=>"$prev",'callback_data'=>"$pre"],['text'=>"$next",'callback_data'=>"$nex"]],
       ] 
       ]) 
]),
]);

}
if(strstr($inlineqt,"share")){
$share=str_replace("share","",$inlineqt);
   $sqll = "SELECT * FROM kitob WHERE share='$share'";  
$res_data = mysqli_query($connn,$sqll);
$qator = mysqli_fetch_array($res_data); 
$result = $qator['fileid'];  
$title=base64_decode($qator['nomi']);
$title=str_replace(["_","pdf"],[" ",""],$title);
$share=$qator['share'];
$inl=bot('answerInlineQuery',[
 'inline_query_id'=>$update->inline_query->id,     
        'cache_time'=>'300', 
        'results' =>json_encode([[
'type'=>'document', 
'id'=>base64_encode(rand(5,555666)),
'title'=>$title,
'document_file_id'=>$result,
'caption'=>"📒*$title*\n\n🤖@Kitobxonbot",
'parse_mode'=>markdown,
'reply_markup'=>[
'inline_keyboard'=>[
[['text'=>"Ulashish",'switch_inline_query'=>"share$share"]]
]
]]
]),
]);
}
if ($text=="/sh" or $data=="royxat") { 
$uzpage = 1; 
$zql="SELECT fileid FROM kitob WHERE id=$uzpage"; 
$pirpage = 10; 
$ufset = ($uzpage-1) * 10; 
        $uztotal = "SELECT COUNT(*) FROM kitob"; 
        $risult = mysqli_query($connn,$uztotal); 
        $tutalruws = mysqli_fetch_array($risult)[0];
        $tutalpages = ceil($tutalruws / $pirpage); 
 
       $siql = "SELECT * FROM kitob LIMIT 0, 10"; 
        $risdata = mysqli_query($connn,$siql); 
   $sin=0; 
$msgg="Barcha kitoblar soni ".$tutalruws."ta.\n"; 
$one = [];
  while($roww = mysqli_fetch_array($risdata)){ 

$alll = base64_decode($roww['nomi']); 
$alll=str_replace(["_","pdf"],[" ",""],$alll); 
$fileid=$roww['fileid'];
 $sin++; 
$one[] = ["callback_data"=>"send$sin","text"=>"$sin"];
 $msgg .= $sin.".".$alll."\n"; 
 }
if($uzpage <= 1){ 
  $priv = "⚫️";  
  $pri="i"; 
}else { 
$priv="Oldingi"; 
$privc= $uzpage - 1; 
$pri="priv$priv"; 
} 
if($uzpage >= $tutalpages){ 
$nixt="⚫️"; 
$nix="i"; 
} else { 
$nixt="Keyingi"; 
$nixtc = $uzpage + 1;  
$nix = "nixt$nixtc"; 
} 
bot("sendmessage", [ 
"chat_id" =>$cid,  
"text"=>$msgg, 
 'reply_markup'=>json_encode([ 
 'inline_keyboard'=>array_merge (array_chunk ($one,5),[ [['text'=>"$priv",'callback_data'=>"$pri"],['text'=>"$nixt",'callback_data'=>"$nix"]]
])
       ])  
]); 


}
if(strstr($data,"nixt")) {
$dataa=str_replace("nixt","",$data);

         $uzpage =  $dataa;
$pirpage = 10;
$ufset = ($uzpage-1) * 10;
        $uztotal = "SELECT COUNT(*) FROM kitob";
        $risult = mysqli_query($connn,$uztotal);
        $tutalruws = mysqli_fetch_array($risult)[0];
   $tutalpages = ceil($tutalruws / $pirpage);
       $siql = "SELECT nomi FROM kitob LIMIT $ufset, 10";
        $risdata = mysqli_query($connn,$siql);
   $sin=$ufset;
$msgg="Keyingi kitoblar\n";
$one = [];
  while($rowww = mysqli_fetch_array($risdata)){

$alll = base64_decode($rowww['nomi']);
$alll=str_replace(["_","pdf"],[" ",""],$alll);
 $sin++;
$one[] = ["callback_data"=>"send$sin","text"=>"$sin"];
 $msgg .= $sin.".".$alll."\n";
 }
if($uzpage <= 1){
  $priv = "⚫️"; 
  $pri="o";
}else {
$priv="Oldingi";
$privc= $uzpage - 1;
$pri="priv$privc";
}
if($uzpage >= $tutalpages){
$nixt="⚫️";
$nix="o";
} else {
$nixt="Keyingi";
$nixtc = $uzpage + 1; 
$nix="nixt$nixtc";
}
bot("editmessagetext", [
"chat_id" =>$cid, 
"message_id"=>$cmid,
"text"=>$msgg,
 'reply_markup'=>json_encode([
 'inline_keyboard'=>array_merge (array_chunk ($one,5),[
  [['text'=>"$priv",'callback_data'=>"$pri"],['text'=>"$nixt",'callback_data'=>"$nix"]],
       ])
       ])
]);

        }elseif(strstr($data,"priv")){
$dataa=str_replace("priv","",$data);
$uzpage = $dataa;
$pirpage = 10;
$ufset = ($uzpage-1) * 10;
        $uztotal = "SELECT COUNT(*) FROM kitob";
        $risult = mysqli_query($connn,$uztotal);
        $tutalruws = mysqli_fetch_array($risult)[0];
   $tutalpages = ceil($tutalruws / $pirpage);
       $siql = "SELECT nomi FROM kitob LIMIT $ufset, 10";
        $risdata = mysqli_query($connn,$siql);
   $sin=$ufset;
$msgg="Oldnigi kitoblar\n";
$one = [];
  while($roww = mysqli_fetch_array($risdata)){
$alll = base64_decode($roww['nomi']);
$alll=str_replace(["_","pdf"],[" ",""],$alll);
 $sin++;
 $one[] = ["callback_data"=>"send$sin","text"=>"$sin"];
 $msgg .= $sin.".".$alll."\n";
 }
if($uzpage <= 1){
  $priv = "⚫️"; 
  $pri="mm";
}else {
$priv="Oldnigi";
$privc= $uzpage - 1;
$pri="priv$privc";

}
if($uzpage >= $tutalpages){
$nixt="⚫️";
$nix="mm";
} else {
$nixt="Keyingi";
$nixtc = $uzpage + 1; 
$nix="nixt$nixtc";
}
bot("editmessagetext", [
"chat_id" =>$cid, 
"message_id"=>$cmid,
"text"=>$msgg,
 'reply_markup'=>json_encode([
 'inline_keyboard'=>array_merge (array_chunk ($one,5),[ 
  [['text'=>"$priv",'callback_data'=>"$pri"],['text'=>"$nixt",'callback_data'=>"$nix"]],
       ])
       ]) 
]);

}
$sqll = "SELECT * FROM kitob";  
$res_data = mysqli_query($connn,$sqll); 
$results=[];
while($qator = mysqli_fetch_array($res_data)){
for($a=1;$a<=5;$a++){
$result = $qator['fileid'];
$results [] = [
'text'=>$a,'callback_data'=>"download:$result"];  
}
}
echo "<pre>";
print_r(array_chunk($results,5));
echo "</pre>";
if(strstr($data,"send")){
$dataa=str_replace("send","",$data);
$uzpage = $dataa;
$zql="SELECT * FROM kitob WHERE id=$uzpage";
$res_data = mysqli_query($connn,$zql);
$file = mysqli_fetch_array($res_data);
$fileide=$file['fileid'];
$share=$file['share'];
$ftitle = base64_decode($file['nomi']);
$ftitle = str_replace(["_","pdf"],[" ",""],$ftitle);
bot("senddocument", [
"chat_id" =>$cid, 
"document"=>$fileide,
'caption'=>"📒*$ftitle*\n\n🤖@Kitobxonbot",
'parse_mode'=>markdown,
'reply_markup'=>json_encode(['inline_keyboard'=>[
[['text'=>"Ulashish",'switch_inline_query'=>"share$share"]]
],
]),
]);
}

if ($inlineqt=="") {
   $sqll = "SELECT * FROM kitob";  
$res_data = mysqli_query($connn,$sqll); 
$results=[]; 
while($qator = mysqli_fetch_array($res_data)){  
$result = $qator['fileid'];  
$title=base64_decode($qator['nomi']);
$title=str_replace(["_","pdf"],[" ",""],$title);
$share=$qator['share'];
$results [] =[
'type'=>'document', 
'id'=>base64_encode(rand(5,555666)),
'title'=>$title,
'document_file_id'=>$result,
'caption'=>"📒*$title*\n\n🤖@Kitobxonbot",
'parse_mode'=>markdown,
'reply_markup'=>[
'inline_keyboard'=>[
[['text'=>"Ulashish",'switch_inline_query'=>"share$share"]],
],
]
];
}
bot('answerInlineQuery',[
 'inline_query_id'=>$update->inline_query->id,     
        'cache_time'=>'1', 
        'results' => json_encode(
$results
)
]);
}
switch ($text) {

    case "/start":

send($cid,"Salom bilimdon. )\nUshbu botdan juda ko'plab qiziqarli,foydali kitoblarni topasiz.Agar siz kitobxonlarni jamlab guruh ochgan bo'lsangiz uyerga shu botni qo'shing.Bot guruhga joylanayotgan barcha kitoblarni bazaga yozib qolgan foydalanuvchilar bilan baham ko'radi.",$mid,"Guruhga qo'shish","https://t.me/kitobxonbot?startgroup=new");
break;
case "/start@kitobxonbot":
send($cid,"Salom.
Meni o'zimga o'xshagan kitobxonlar bor guruhlarga qo'shing.Barcha guruhga joylanayotgan kitoblarni bazamga yozib qolgan bilimdonlarga ulashaman.",$mid,"Guruhga qo'shish","https://t.me/kitobxonbot?startgroup=new");
}