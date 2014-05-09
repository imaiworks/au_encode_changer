いまいまのシステムがUTF-8で出力の場合、auのガラケーで表示するとなぜか文字したりする場合がなぜかあります。  
とか言って衰退するガラケーのためにシステムを書き換えるのはコスト的に無駄って判断をされる場合があります。  
  
やりたいことは  
[サーバ utf-8]--[UFT-8 SHIFT-JIS変換]--[ガラケーshift-jis]  
なだけなので、大それた携帯変換アプライアンスをいれなくても  
proxy的なPGで処理すればいいだけ。  
  
ってことで、  
mod_rewriteでフックさせてコンテンツをSHIFT-JISに変換するだけのphpです。  
（逆に、POSTで渡ってくる値もSHIFT-JISからUTF-8に変換もします）  
  
  
使用例  
１．changer.phpを置く
２．.htaccess等に以下のように書く
  （このままだとすべてshift-jis変換するのでUA判定するrewrite_condを入れたほうがいいでしょう）
  
<IfModule mod_rewrite.c>  
RewriteEngine On  
RewriteBase /  
  
RewriteCond %{REQUEST_FILENAME} !(.(jpg|gif|png|js|css|ico))$  
RewriteCond %{QUERY_STRING}     !changetrue=(.*)$  
RewriteRule ^(.*)$ changer.php [QSA,L]  
  
</IfModule>  
  
以上です

大したコードではないのですが、一応BSD licenceとしておきます
ご自由にお使いください

(c)2014 imaiworks

