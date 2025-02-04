<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Module</title>
    @include('layout.component.head')
    @yield('head')
</head>
<body>

    @include('layout.component.navbar')
    
    <div id="content-separator" style="display: flex;">
        <div id="sidebar" style="flex: 0 0 20%;">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white" style="height:100vh; overflow-y: auto;">
                <div class="position-sticky">
                    <div class="list-group list-group-flush mx-3 mt-4" id="menuSidebar">
                    </div>
                </div>
            </nav>
            <!-- Sidebar --> 
        </div>
    
        <div id="content" style="flex: 1;">
            @yield('content')
        </div>
    </div>

    <footer class="bg-dark" style="text-align: center; padding: 20px; color: white;">
        <p>© 2024 SQL Learning Hub. All rights reserved.</p>
    </footer>
    

    
    @include('layout.component.tail')
    
    <script>
        // set the name
        $(document).ready(function() {
            // $('#profile-name').html("{{$user->name}}")
            var text = "{{$user->name}}";
            var i = 0;
            var isDeleting = false;

            setInterval(function() {
                if (isDeleting) {
                    if (i <= 0) {
                        isDeleting = false;
                    } else {
                        i--;
                    }
                } else {
                    if (i >= text.length) {
                        isDeleting = true;
                    } else {
                        i++;
                    }
                }

                $('#profile-name').html(text.slice(0, i));
            }, 500); // Adjust speed as needed

            addSidebarMenu();
        })

        // add the sidebar menu function
        function addSidebarMenu(){
            $.ajax({
                url: "{{ url('/module/data/get-menu') }}",
                type: "GET",
                success: function(response) {
                    let data = response.data
                    data = $(data)
                    $('#menuSidebar').append(data)
                },
                error: function(jqXHR, textStatus, errorThrown) {

                }
            });
        }


    </script>

    @yield('tail')
</body>
</html>