<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>👾</text></svg>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <title>Реєстрація в розіграші</title>
</head>
<body>
<main>
    <section>
        <hr>
        <div class="container-sm">
            <h2>Реєстрація в розіграші</h2>
            <form id="save">
                @csrf
                <div id="data" class="mb-3">
                    <label for="contactInformation" class="form-label">
                        Контактна інформація (посилання на твіттер)
                    </label>
                    <textarea class="form-control" id="contactInformation" rows="3"></textarea>
                    <div id="contactInformationDisclaimer" class="form-text">
                        Ми ніколи нікому не передамо вашу контактну інформацію.
                    </div>
                </div>
                <button id="submit" type="submit" class="btn btn-primary">Відправити</button>
                <div class="mt-2">
                    <div class="alert alert-success" role="alert" id="save_success" style="display: none">
                        Дякуємо! Ваш номер <span id="number"></span>. У випадку виграшу ми з Вами зв'яжемось
                    </div>
                    <div class="alert alert-danger" role="alert" id="save_error" style="display: none">
                        Щось пішло не так. Напишіть <a href="https://t.me/setnemo" target="_blank">https://t.me/setnemo</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</main>
<script>
    $(() => {
        $('#save').on('submit', function (e) {
            e.preventDefault();
            let token = $(`#save [name="_token"]`).val();
            let contact = $('#contactInformation').val();
            $.ajax({
                url: "/save",
                type: "POST",
                data: {
                    _token: token,
                    contact: contact,
                },
                success: function (response) {
                    $('#save_error').hide();
                    $('#data').hide();
                    $('#submit').hide();
                    $('#number').html(response.number);
                    $('#save_success').show();
                },
                error: function () {
                    $('#save_success').hide();
                    $('#save_error').show();
                },
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
</body>
</html>
