<?php
$messages = array (
    'start' => array ( 'type' => 'info', 'heading' => 'Yükleniyor...', 'message' => 'Doğrulama işlemi başladı.' ),
    'xhr-error' => array ( 'type' => 'error', 'heading' => 'Hata!', 'message' => 'Veri gönderimi sırasında hata oluştu.' ),
    'form-invalid' => array ( 'type' => '', 'heading' => 'Uyarı!', 'message' => 'Doğrulama için lütfen tüm alanları doldurun ve bilgilerin doğruluğundan emin olun.' ),
    'valid' => array ( 'type' => 'success', 'heading' => 'Başarılı!', 'message' => 'Girmiş olduğunuz TC Kimlik No ve diğer bilgiler doğrulandı.' ),
    'invalid' => array ( 'type' => 'error', 'heading' => 'Başarısız!', 'message' => 'Girmiş olduğunuz TC Kimlik No ve diğer bilgiler doğrulanamadı.' ),
    'svc-error' => array ( 'type' => 'error', 'heading' => 'Başarısız!', 'message' => 'Girmiş olduğunuz bilgilerin doğru olduğundan emin olun.' )
);
function checkTCKN($tckn) {
    $exceptions = array( '11111111110', '22222222220', '33333333330', '44444444440', '55555555550', '66666666660', '77777777770', '88888888880', '99999999990' );
    $tckn = ltrim($tckn, '0');
    if ( !ctype_digit($tckn) || strlen($tckn) != 11 || array_search($tckn, $exceptions) > -1) {
        return false;
    } else {
        $t1 = 0;
        $t2 = 0;
        for ($i = 0; $i < 9; $i = $i + 2) {
          $t1 += intval( $tckn[ $i ] );
        }
        for ($i = 1; $i < 8; $i = $i + 2) {
          $t2 += intval( $tckn[ $i ] );
        }
        $c10 = (10 - ((($t1 * 3) + $t2) % 10)) % 10;
        $c11 = (10 - (((($t2 + $c10) * 3) + $t1) % 10)) % 10;
        return substr( $tckn, 9) == $c10 . $c11;
    }
}

if(!empty($_POST)) {
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    $hasEmptyField = false;
    $originals = array();
    foreach($_POST as $key => $val) {
        if(empty($val) && !$hasEmptyField) {
            $hasEmptyField = true;
        }
        $originals[$key] = $val;
        $_POST[$key] = strtoupper(str_replace( array( 'ı', 'i', 'ö', 'ü', 'ğ', 'ş', 'ç' ), array( 'I', 'İ', 'Ö', 'Ü', 'Ğ', 'Ş', 'Ç' ), $val));
    }
    if( !$hasEmptyField && checkTCKN( $_POST['TCKimlikNo'] ) && ctype_digit( $_POST['DogumYili'] ) ) {
        try {
            $client = new SoapClient('https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?wsdl');
            $response = $client->TCKimlikNoDogrula($_POST);

            if($isAjax) {
                echo $response->TCKimlikNoDogrulaResult ? "valid" : "invalid";
            }
            else {
                $result = $response->TCKimlikNoDogrulaResult ? "valid" : "invalid";
            }
        }
        catch(Exception $ex) {
            if($isAjax) {
                echo 'svc-error';
            }
            else {
                $result = 'svc-error';
            }
            
        }
    }
    else {
        if($isAjax) {
            echo 'form-invalid';
        }
        else {
            $result = 'form-invalid';
        }
    }
    if($isAjax) {
        exit;
    }
}
?>
<!doctype html>
<html>
    <head>
        <title>TC Kimlik No Doğrulama</title>
        <meta charset="utf-8">
        <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="//cdn.jsdelivr.net/select2/3.4.1/select2.css">
        <style type="text/css">
            html, body { background-color: #eee; }
            body { padding-top: 40px;  }
            .container { width: 350px; }
  
            .container > .content {
                background-color: #fff;
                padding: 20px;
                margin: 0 -20px; 
                -webkit-border-radius: 10px 10px 10px 10px;
                   -moz-border-radius: 10px 10px 10px 10px;
                        border-radius: 10px 10px 10px 10px;
                -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                   -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                        box-shadow: 0 1px 2px rgba(0,0,0,.15);
            }
  
            .form {
                margin: 0 auto;
                width: 290px;
            }
          
            legend {
                margin-right: -50px;
                font-weight: bold;
                color: #404040;
            }
            input { width: 100%; }
            .form .pull-left { line-height: 30px; }
            .hidden { display: none; }
            .input-append, .input-prepend { width: 85%; }
            .control-group { margin-bottom: 0; }

            /* select2-bootstrap-css */
            .select2-container{vertical-align:middle}.select2-container .select2-choice{border:1px solid #ccc;height:28px;border-radius:3px;line-height:29px;background:0;filter:none;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075)}.select2-container .select2-choice div{border-left:0;background:0;filter:none}.select2-container-multi .select2-choices{border:1px solid #ccc;border-radius:3px;background:0;filter:none;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075)}.control-group.error .select2-container-multi .select2-choices{border-color:#B94A48}.control-group.error .select2-container .select2-choice{border-color:#B94A48}.select2-container-multi .select2-choices .select2-search-field{height:28px;line-height:27px}.select2-container-active .select2-choice,.select2-container-active .select2-choices,.select2-container-multi.select2-container-active .select2-choices{border-color:rgba(82,168,236,.8);outline:0;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(82,168,236,.6);-moz-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(82,168,236,.6);box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(82,168,236,.6)}.input-append .select2-container,.input-prepend .select2-container{font-size:14px}.input-prepend .select2-container>a.select2-choice,.input-prepend .select2-container-multi .select2-choices{border-top-left-radius:0;border-bottom-left-radius:0}.input-append .select2-container>a,.input-append .select2-container-multi .select2-choices{border-top-right-radius:0;border-bottom-right-radius:0}.select2-dropdown-open .select2-choice,.select2-dropdown-open .select2-choices{border-bottom-left-radius:0;border-bottom-right-radius:0}.select2-dropdown-open.select2-drop-above .select2-choice,.select2-dropdown-open.select2-drop-above .select2-choices{border-top-left-radius:0;border-top-right-radius:0}.input-prepend .select2-container>a.select2-choice>div,.input-append .select2-container>a.select2-choice>div{display:none}.input-append .select2-offscreen,.input-prepend .select2-offscreen{position:absolute}select.select2{height:28px;visibility:hidden}

            .select2-container { width: 262px; }
            .select2-input { width: 262px;}
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="form">
                <h3>TC Kimlik No Doğrulama</h3>
                <?php if( isset( $result ) ) {
                    $msg = $messages[ $result ];
                    echo sprintf('<div class="alert alert-%1$s"><a class="close" data-dismiss="alert" href="#">×</a><h4 class="alert-heading">%2$s</h4><p>%3$s</p></div>', $msg[ 'type' ], $msg[ 'heading' ], $msg[ 'message' ] );
                } ?>
                <form action="" method="post">
                    <fieldset>
                        <div class="control-group">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-barcode"></i></span><input type="text" placeholder="TC Kimlik No" name="TCKimlikNo" id="TCKimlikNo" maxlength="11"<?php echo $originals && $originals['TCKimlikNo'] ? ' value="' . $originals['TCKimlikNo'] . '"' : ''; ?>>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-user"></i></span><input type="text" placeholder="Ad" name="Ad" id="Ad"<?php echo $originals && $originals['Ad'] ? ' value="' . $originals['Ad'] . '"' : ''; ?>>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-user"></i></span><input type="text" placeholder="Soyad" name="Soyad" id="Soyad"<?php echo $originals && $originals['Soyad'] ? ' value="' . $originals['Soyad'] . '"' : ''; ?>>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-calendar"></i></span>
                                <select name="DogumYili" id="DogumYili" class="select2-input">
                                    <option></option>
                                <?php $years = range(date('Y', time()), 1900);
                                foreach($years as $year) {
                                    echo sprintf('<option value="%1$d"%2$s>%1$d</option>', $year, $originals && $originals['DogumYili'] == $year ? ' selected' : '');
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                          <span class="pull-right clear">
                            <button class="btn primary" type="submit"><i class="icon-search"></i> Sorgula</button>
                          </span>
                    </fieldset>
                </form>
                </div>
            </div>
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/select2/3.4.1/select2.min.js"></script>
        <script>
            jQuery(function($) {
                // yerelleştirme
                $.extend($.fn.select2.defaults, {
                    formatNoMatches: function () { return "Sonuç bulunamadı"; },
                    formatInputTooShort: function (input, min) { var n = min - input.length; return "En az " + n + " karakter daha girmelisiniz"; },
                    formatInputTooLong: function (input, max) { var n = input.length - max; return n + " karakter azaltmalısınız"; },
                    formatSelectionTooBig: function (limit) { return "Sadece " + limit + " seçim yapabilirsiniz"; },
                    formatLoadMore: function (pageNumber) { return "Daha fazla..."; },
                    formatSearching: function () { return "Aranıyor..."; }
                });
                // yerelleştirme bitiş
                String.prototype.trimLeft=function(a){return this.replace(RegExp("^["+(a||" ")+"]+"),"")};
                var TCKND = {
                    'msg' : {
                        'template' : '<div class="alert"><a class="close" data-dismiss="alert" href="#">×</a><h4 class="alert-heading"></h4><p></p></div>',
                        'messages' : <?php echo json_encode( $messages ); ?>,
                        'show' : function(type) {
                            var $h3 = $('h3'), $msg = $(this.template), msg = this.messages[type];
                            this.clear();
                            $msg.addClass('alert-' + msg.type)
                            $msg.find('h4').text(msg.heading);
                            $msg.find('p').text(msg.message)
                            $h3.after($msg);
                            return this;
                        },
                        'clear' : function() {
                            $('.alert').remove();
                            return this;
                        }
                    },
                    validate : function() {
                        var $tckn = $('#TCKimlikNo'), $ad = $('#Ad'), $soyad = $('#Soyad'), $dogumyili = $('#DogumYili'), errorCount = 0;
                        $('form .error').removeClass('error');
                        if( !this.checkTCKN($tckn.val()) ) {
                            errorCount++;
                            $tckn.closest('.control-group').addClass('error');
                        }
                        if( !$ad.val() ) {
                            errorCount++;
                            $ad.closest('.control-group').addClass('error');
                        }
                        if( !$soyad.val() ) {
                            errorCount++;
                            $soyad.closest('.control-group').addClass('error');
                        }
                        if( !this.isNumber($dogumyili.val()) ) {
                            errorCount++;
                            $dogumyili.closest('.control-group').addClass('error');
                        }
                        if( errorCount ) {
                            this.msg.show('form-invalid');
                            return false;
                        }
                        else {
                            return true;
                        }
                    },
                    checkTCKN : function(tckn) {
                        var exceptions = [ '11111111110', '22222222220', '33333333330', '44444444440', '55555555550', '66666666660', '77777777770', '88888888880', '99999999990' ],
                            t1 = 0, t2 = 0, c10, c11;;
                        tckn = tckn.toString().trimLeft('0');
                        if ( !this.isNumber(tckn) || tckn.length != 11 || $.inArray( tckn, exceptions ) > -1 ) {
                            return false;
                        } else {
                            for (i = 0; i < 9; i = i + 2) {
                              t1 += parseInt(tckn.charAt(i), 10)
                            }
                            for (i = 1; i < 8; i = i + 2) {
                              t2 += parseInt(tckn.charAt(i), 10)
                            }
                            c10 = (10 - (((t1 * 3) + t2) % 10)) % 10;
                            c11 = (10 - ((((t2 + c10) * 3) + t1) % 10)) % 10;
                            return tckn.substring(9, 11) == [ c10, c11 ].join('');
                        }
                    },
                    isNumber : function(n) {
                        return !isNaN(parseFloat(n)) && isFinite(n);
                    }
                };
                $("select").select2({ placeholder: "Doğum yılı" });
                $('form').on('submit', function(e) {
                    e.preventDefault();
                    TCKND.msg.show('start');
                    if( TCKND.validate() ) {
                        $.post("", $(this).serialize())
                            .done(function(data) { TCKND.msg.show(data); })
                            .fail(function() { TCKND.msg.show('xhr-error'); });
                    }
                });
            });
        </script>
    </body>
</html>
