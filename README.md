# TC Kimlik No Doğrulama #

Nüfus ve Vatandaşlık İşleri Genel Müdürlüğü Web servisi üzerinden TC Kimlik Numarası doğrulaması yapan kod öbeği. Twitter Bootstrap ile güzelleştirilmiş ve sevgiyle yapılmıştır. ;)

Phpfiddle üzerinden kodu çalıştırabilirsiniz. [Buraya](http://phpfiddle.org/main/code/m36-60x) tıkladıktan sonra açılan sayfada F9 ya da *Run* bağlantısını kullanarak test edebilirsiniz.

Kullanılan kütüphaneler;  

* [jQuery](http://jquery.com)
* [Bootstrap](http://twitter.github.io/bootstrap/)
* [select2](http://ivaynberg.github.io/select2/) 
* [select2-bootstrap-css](https://github.com/t0m/select2-bootstrap-css)
* [String Extensions](https://github.com/karalamalar/StringExtensions) (Sadece trimLeft fonksiyonu)

## Geçmiş ##

### v1.1 ###

* JavaScript olmadığında kodun çalışabilmesi için gerekli PHP kodları eklendi.
* DOM'u kirletmemek için JavaScript değişkenleri tek bir global değişkende toplandı.
* PHP tarafına TC Kimlik Numarasının algoritmik kontrolü eklendi.
* JavaScript ile TC Kimlik numarası kontrolünü sağlayan yordam geliştirildi.
* Beyaz boşluklar düzenlendi.

### v1.0 ###

* Kütüphaneler mümkünse CDN'den, değilse kodun içinden kullanılacak şekilde ayarlandı.
* JavaScript form kontrolü eklendi.
* TC Kimlik numarasının algoritmik kontrolü için JavaScript yordamı eklendi.
* Formun gönderilmesi sırasında araya girilerek isteğin AJAX ile yapılması sağlandı.
* Sonuç mesajlarının gösterimi için `msg` nesnesi geliştirildi.
* Bootstrap ile şık bir form hazırlandı.
