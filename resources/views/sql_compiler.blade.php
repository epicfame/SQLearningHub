<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SQL Practice</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        {{-- <link href="" rel="stylesheet" /> --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css"></link>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
        <style>
            .feature {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                height: 4rem;
                width: 4rem;
                font-size: 2rem;
            }
        </style>
    </head>
    <body>
        <!-- Responsive navbar-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-5">
                <a class="navbar-brand" href="#!">SQL Learning Hub</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="/home">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/module">Module</a></li>
                        <li class="nav-item"><a class="nav-link active" href="/practice">Practice</a></li>
                        <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    <div id="flash-message" style="display: none;"></div>
    <section id="content" style="width: 100%">
        <div class="d-inline-flex flex-row">
            <div class="left" style="width: 50%; float: left;">
                <h1>Soal</h1>
                <div id="introduction">
                    Introduction
                    <br>
                    Terdapat sebuah tabel bernama test dan memiliki beberapa kolom yaitu nama, no_phone, temp
                </div>
                <br>
                <div id="table">
                    <h3>Table</h3>
                    <h4>test</h4>
                    <ul>
                        <li>id : integer</li>
                        <li>name : string</li>
                        <li>no_phone : string</li>
                        <li>temp : string</li>
                    </ul>
                </div>
                <div id="example">
                    <h3>Perintah</h3>
                    <span>Ambil name, no_phone, dan temp dari tabel test. Dengan nama kolom name,no_phone,</span>
                </div>
            </div>
            <div class="right" style="width: 50%; float: right;">
                <h1>Jawaban</h1>
                <form method="POST" id="submit-form" class="form" role="form">
                    @CSRF
                    <div id="code"></div>
                    <button type="submit" id="submit">Submit</button>
                </form>
            </div>
        </div>
        
    </section>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/javascript/javascript.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/sql/sql.min.js"></script>
    
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <script>

        var myCodeMirror = CodeMirror(document.querySelector('#code'), {
            lineNumbers: true,
            tabSize: 2,
            value: 'SELECT * FROM TEST',
            mode: 'text/x-mysql'
        });

        $('#submit-form').on('submit', function(e) {
            var code = myCodeMirror.getValue();
            e.preventDefault();
            $.ajax({
                url: "/practice/sql-compiler/compile/1",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{!! csrf_token() !!}'
                },
                data: {
                    query : code
                },
                success: function(result){
                    console.log(result)
                    if(result.success){
                        showMessage('success', result.message)
                    }
                    else{
                        showMessage('error', result.message)
                    }
                    // $("#message").html(result.message);
                },
                error: function(result){
                    console.log(result)
                    showMessage('error', result.message)
                    // $("#message").html(result.message);

                }
            });
        });

        // FUNCTION FOR SUCCESS/ERROR MESSAGE
        function showMessage(type, message, time) {
            let alertClass;

            switch(type) {
                case 'success':
                    alertClass = 'alert-success';
                    break;
                case 'error':
                    alertClass = 'alert-danger';
                    break;
                case 'info':
                    alertClass = 'alert-info';
                    break;
                default:
                    alertClass = 'alert-secondary';
            }

            $('#flash-message').html('<div class="alert ' + alertClass + '">' + message + '</div>').show();
            if(time != null && time != ''){
                setTimeout(function() {
                    $('#flash-message').fadeOut('slow');
                }, time * 1000); // time in seconds
            }
        }
    </script>
</body>
</html>