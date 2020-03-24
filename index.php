<html>
<head>
    <meta charset="UTF-8">
    <meta lang="tr">
    <meta about="SGK veri çekme">
    <meta name="Abdulkadir GÜNGÖR">
    <meta mail="a.kadirgngr@gmail.com">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>Burası Sgk dosya çekme</title>
</head>

    <body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">SGK Veri Çekme</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">

        </div>
    </nav>

    <div class="mt-5"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 offset-sm-3">
                <form action="curl.php" method="post">
                    <div class="form-group">
                        <label for="username">Kullanıcı Adı</label>
                        <input class="form-control" id="username" type="text" name="username" placeholder="İşyeri Sahibinin Tc'sini yazınız">
                    </div>
                    <div class="form-group">
                        <label for="isyeri_kod">İşyeri Kodu</label>
                        <input class="form-control" id="isyeri_kod" type="text" name="isyeri_kod" placeholder="İşyeri kodunu yazınız">
                    </div>
                    <div class="form-group">
                        <label for="password">Sistem Şifresi</label>
                        <input class="form-control" id="password" type="text" name="password" placeholder="Sistem Şifresini yazınız">
                    </div>
                    <div class="form-group">
                        <label for="isyeri_sifre">İşyeri Şifresi</label>
                        <input class="form-control" id="isyeri_sifre" type="text" name="isyeri_sifre" placeholder="İşyeri Şifresini yazınız">
                    </div>

                    <button class="btn btn-success" type="submit">Gönder</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        Önceden indirilen dosyalar
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php
                            $pdf_flies = glob('*.zip');

                            foreach ($pdf_flies as $files){
                                echo "<li class=\"list-group-item\"><a class=\"card-link\" href='".$files."'>".$files."</a></li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    </body>
</html>